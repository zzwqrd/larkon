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
        $query = Role::query();

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
     * Show front for creating a new role with permission matrix.
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
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array'
        ]);

        $role = Role::create(['name' => $request->name]);
        
        foreach ($request->permissions as $perm) {
            $role->permissions()->create(['permission' => $perm]);
        }

        return response()->json(['success' => true, 'redirect' => route('roles.index')]);
    }

    /**
     * Helper to group routes for the UI.
     * This follows the 'Idea' from your old project but in a cleaner way.
     */
    private function getPermissionGroups()
    {
        $routes = Route::getRoutes();
        $groups = [];

        foreach ($routes as $route) {
            $name = $route->getName();
            if ($name && !str_starts_with($name, 'ignition') && !str_starts_with($name, 'sanctum')) {
                // Group by the first part of the route name (e.g., 'products', 'admins')
                $segments = explode('.', $name);
                $groupName = $segments[0];
                
                $groups[$groupName][] = [
                    'name' => $name,
                    'title' => ucwords(str_replace(['.', '-', '_'], ' ', $name))
                ];
            }
        }

        return $groups;
    }

    /**
     * Delete a role.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if ($role->id == 1) return response()->json(['success' => false, 'msg' => 'Protected'], 403);
        $role->delete();
        return response()->json(['success' => true, 'id' => $id]);
    }
}
