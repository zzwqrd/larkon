<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index(Request $request)
    {
        $query = Role::with('admins')->withCount('admins');

        if ($request->has('q') && !empty($request->q)) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        $roles = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.roles._table', compact('roles'))->render()
            ]);
        }

        return view('admin.roles.list', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = $this->getPermissionGroups();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
                'permissions' => 'required|array'
            ]);

            $role = Role::create(['name' => $request->name]);

            foreach ($request->permissions as $perm) {
                $role->permissions()->create(['permission' => $perm]);
            }

            return response()->json(['success' => true, 'redirect' => route('admin.roles.index')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = $this->getPermissionGroups();
        $rolePermissions = $role->permissions->pluck('permission')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);

            // Protection for Super Admin (ID 1)
            $isSuperAdmin = ($role->id == 1);

            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $id,
                'permissions' => $isSuperAdmin ? 'nullable|array' : 'required|array'
            ]);

            $role->update(['name' => $request->name]);

            // Sync permissions
            $role->permissions()->delete();

            if ($isSuperAdmin) {
                // Super Admin always gets all system permissions
                $allSystemPerms = array_column(array_merge(...array_values($this->getPermissionGroups())), 'name');
                foreach ($allSystemPerms as $perm) {
                    $role->permissions()->create(['permission' => $perm]);
                }
            } else {
                foreach ($request->permissions as $perm) {
                    $role->permissions()->create(['permission' => $perm]);
                }
            }

            return response()->json(['success' => true, 'redirect' => route('admin.roles.index')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the comprehensive permissions list.
     */
    public function permissionsIndex(Request $request)
    {
        $permissions = [];
        $routesArr = Route::getRoutes();

        // Eager load roles with their permissions
        $allRoles = Role::with('permissions')->get();

        foreach ($routesArr as $route) {
            $name = $route->getName();
            // Filter system routes and only keep meaningful ones
            if ($name && !str_starts_with($name, '_') && !str_starts_with($name, 'ignition') && !str_starts_with($name, 'sanctum')) {

                $assignedRoles = $allRoles->filter(function ($role) use ($name) {
                    return $role->permissions->contains('permission', $name);
                })->pluck('name')->toArray();

                $titleArr = $route->getAction('title');
                $title = isset($titleArr[0]) ? __($titleArr[0]) : ucwords(str_replace(['.', '-', '_'], ' ', $name));

                // Manual Search Filtering
                if ($request->has('q') && !empty($request->q)) {
                    $search = strtolower($request->q);
                    if (strpos(strtolower($name), $search) === false && strpos(strtolower($title), $search) === false) {
                        continue;
                    }
                }

                $permissions[] = [
                    'name' => $name,
                    'title' => $title,
                    'assigned_to' => $assignedRoles,
                    'category' => explode('.', $name)[0] ?? 'General'
                ];
            }
        }

        // Manual Pagination
        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $itemsForCurrentPage = array_slice($permissions, $offset, $perPage);

        $paginatedPermissions = new \Illuminate\Pagination\LengthAwarePaginator(
            $itemsForCurrentPage,
            count($permissions),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.permissions._table', ['permissions' => $paginatedPermissions])->render()
            ]);
        }

        return view('admin.permissions.index', [
            'permissions' => $paginatedPermissions,
            'allRoles' => $allRoles
        ]);
    }

    /**
     * Helper to group routes for the UI.
     */
    private function getPermissionGroups()
    {
        $routesArr = Route::getRoutes();
        $groups = [];

        // Routes to completely skip (system/internal)
        $skipRoutes = [
            'admin.root', 'admin.logout', 'admin.changeLang',
            'admin.show.login', 'admin.login',
            'third', 'second', 'any',
        ];

        // Routes that should be grouped under "Authentication"
        $authRoutes = [
            'login', 'register', 'password.request', 'password.email',
            'password.reset', 'password.update',
        ];

        // Action labels map for generating fallback titles
        $actionLabels = [
            'store'      => 'messages.create',
            'edit'       => 'messages.edit',
            'update'     => 'messages.edit',
            'destroy'    => 'messages.delete',
            'toggle'     => 'messages.toggle',
            'bulkDelete' => 'messages.bulk_delete',
            'index'      => 'messages.list',
            'create'     => 'messages.create',
            'show'       => 'messages.details',
            'approve'    => 'messages.active',
            'reject'     => 'messages.cancel',
            'updateStatus' => 'messages.status',
        ];

        foreach ($routesArr as $route) {
            $name = $route->getName();
            if (!$name) continue;

            // Filter framework routes
            if (str_starts_with($name, '_') || str_starts_with($name, 'ignition')
                || str_starts_with($name, 'sanctum') || str_starts_with($name, 'debugbar')) {
                continue;
            }

            // Skip system/internal routes
            if (in_array($name, $skipRoutes)) continue;

            // Skip menu-only parent routes (they have type=parent and no real controller action)
            $actions = $route->getAction();
            if (isset($actions['type']) && $actions['type'] === 'parent') continue;

            // --- Determine Group Name ---
            $groupKey = 'general';

            if (in_array($name, $authRoutes)) {
                $groupKey = 'authentication';
            } else {
                $segments = explode('.', $name);

                // Strip 'admin.' prefix
                if ($segments[0] === 'admin' && count($segments) > 1) {
                    $routePrefix = $segments[1];
                } elseif (count($segments) > 1) {
                    $routePrefix = $segments[0];
                } else {
                    $routePrefix = $segments[0];
                }

                // Map sub-modules to major parent groups
                $parentGroupsMap = [
                    // Products
                    'category'       => 'products',
                    'brands'         => 'products',
                    'sizes'          => 'products',
                    'notes'          => 'products',
                    'products'       => 'products',
                    // Manufacturing
                    'formulas'       => 'manufacturing',
                    'essential_oils' => 'manufacturing',
                    'oil_categories' => 'manufacturing',
                    // Logistics
                    'shipping'       => 'logistics',
                    'returns'        => 'logistics',
                    // Others
                    'customers'      => 'users',
                    'sellers'        => 'users',
                    'admins'         => 'admins',
                    'roles'          => 'roles',
                    'orders'         => 'orders',
                    'inventory'      => 'inventory',
                    'coupons'        => 'coupons',
                    'reviews'        => 'reviews',
                ];

                // Normalize prefix
                $routePrefix = str_replace('-', '_', strtolower($routePrefix));
                
                // Determine the major group
                $groupKey = $parentGroupsMap[$routePrefix] ?? $routePrefix;
            }

            // Normalize final group key
            $groupKey = str_replace('-', '_', strtolower($groupKey));

            // Translate the display group using the mapped group key
            $displayGroup = __('messages.' . $groupKey);
            if ($displayGroup === 'messages.' . $groupKey) {
                $displayGroup = ucfirst(str_replace('_', ' ', $groupKey));
            }

            // Translate the specific sub-module name (e.g. Brands, Sizes) for appending to label
            $subModuleTranslation = __('messages.' . $routePrefix);
            if ($subModuleTranslation === 'messages.' . $routePrefix) {
                $subModuleTranslation = ucfirst(str_replace('_', ' ', $routePrefix));
            }

            // --- Determine Permission Title ---
            $titleArr = $route->getAction('title');
            if (isset($titleArr[0])) {
                $baseTitle = __($titleArr[0]);
            } else {
                // Smart fallback: extract action from route name and translate it
                $segments = explode('.', $name);
                $actionKey = end($segments);

                if (isset($actionLabels[$actionKey])) {
                    $baseTitle = __($actionLabels[$actionKey]);
                } else {
                    $baseTitle = ucwords(str_replace(['.', '-', '_'], ' ', $actionKey));
                }
            }

            // If we mapped multiple sub-modules into one group, we should append the sub-module name
            // so the checkboxes are distinguishable (e.g. "Create" -> "Create Sizes")
            // Exception: When sub-module is exactly the major group (e.g. products -> products)
            $title = $baseTitle;
            if ($routePrefix !== $groupKey) {
                $title = $baseTitle . ' - ' . $subModuleTranslation;
            }

            $groups[$displayGroup][] = [
                'name' => $name,
                'title' => $title,
                'is_master' => $route->getAction('master') ?? false
            ];

        }

        ksort($groups);
        return $groups;
    }

    /**
     * Delete a role.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if ($role->id == 1) {
            return response()->json(['success' => false, 'msg' => 'Super Admin role is protected and cannot be deleted.'], 403);
        }
        $role->permissions()->delete(); // Clear related permissions first
        $role->delete();
        return response()->json(['success' => true, 'id' => $id]);
    }

    /**
     * Bulk Delete roles via AJAX.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            // Strictly protect ID 1
            $roles = Role::whereIn('id', $ids)->where('id', '!=', 1)->get();
            foreach ($roles as $role) {
                $role->permissions()->delete();
                $role->delete();
            }
        }

        return response()->json(['success' => true]);
    }
    /**
     * Update roles for a specific permission (route name).
     */
    public function updatePermissionRoles(Request $request)
    {
        $request->validate([
            'permission' => 'required|string',
            'roles' => 'array'
        ]);

        $permissionName = $request->permission;
        $roleIds = $request->input('roles', []);

        // Remove existing assignments for this specific permission
        Permission::where('permission', $permissionName)->delete();

        // Add new assignments
        foreach ($roleIds as $roleId) {
            Permission::create([
                'role_id' => $roleId,
                'permission' => $permissionName
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Roles updated successfully for permission: ' . $permissionName
        ]);
    }
}
