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

            return response()->json(['success' => true, 'redirect' => route('roles.index')]);
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

            return response()->json(['success' => true, 'redirect' => route('roles.index')]);
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

        return view('admin.permissions.index', ['permissions' => $paginatedPermissions]);
    }

    /**
     * Helper to group routes for the UI.
     */
    private function getPermissionGroups()
    {
        $routesArr = Route::getRoutes();
        $groups = [];

        foreach ($routesArr as $route) {
            $name = $route->getName();
            
            // Filter system routes
            if ($name && !str_starts_with($name, '_') && !str_starts_with($name, 'ignition') && !str_starts_with($name, 'sanctum') && !str_starts_with($name, 'debugbar')) {

                // Determine Group Name
                $groupName = 'General';
                $segments = explode('.', $name);
                
                // If it's a module route (e.g. admins.list or roles.index)
                if (count($segments) > 1) {
                    $groupName = $segments[0];
                }

                // If it's a core auth route (login, register), group as Authentication
                if (in_array($name, ['login', 'register', 'logout', 'password.request', 'password.reset'])) {
                    $groupName = 'Authentication';
                }

                $displayGroup = ucfirst($groupName);

                $titleArr = $route->getAction('title');
                $title = isset($titleArr[0]) ? __($titleArr[0]) : ucwords(str_replace(['.', '-', '_'], ' ', $name));

                // We want to list all permissions but categorize them nicely
                $groups[$displayGroup][] = [
                    'name' => $name,
                    'title' => $title,
                    'is_master' => $route->getAction('master') ?? false
                ];
            }
        }

        // Sort groups to make it look professional
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
            Role::whereIn('id', $ids)->where('id', '!=', 1)->delete();
        }

        return response()->json(['success' => true]);
    }
}
