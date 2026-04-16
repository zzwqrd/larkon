<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the admins.
     */
    public function index(Request $request)
    {
        $query = Admin::with('role');

        // Server-side Searching
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Server-side Pagination
        $admins = $query->paginate(10);

        // Handle AJAX Request for Table Updates
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.admins._table', compact('admins'))->render()
            ]);
        }

        return view('admin.admins.list', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.admins.create', compact('roles'));
    }

    /**
     * Store a newly created admin.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:admins,email',
                'password' => 'required|confirmed|min:8',
                'role_id' => 'required|exists:roles,id',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->only(['name', 'email', 'role_id']);
            $data['password'] = Hash::make($request->password);
            $data['is_blocked'] = $request->has('active') ? 0 : 1;

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/admins'), $filename);
                $data['avatar'] = 'uploads/admins/' . $filename;
            }

            Admin::create($data);

            return response()->json(['success' => true, 'redirect' => route('admin.admins.list')]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'msg' => collect($e->errors())->flatten()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        $roles = Role::all();
        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    /**
     * Update the specified admin.
     */
    public function update(Request $request, $id)
    {
        try {
            $admin = Admin::findOrFail($id);

            // 1. Protection for Super Admin (ID 1 or email admin@admin.com)
            $isSuperAdmin = ($admin->id == 1 || $admin->email == 'admin@admin.com');

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:admins,email,' . $id,
                'role_id' => $isSuperAdmin ? 'nullable' : 'required|exists:roles,id',
                'password' => 'nullable|confirmed|min:8',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->only(['name', 'email']);
            
            // Only update role if NOT super admin
            if (!$isSuperAdmin) {
                $data['role_id'] = $request->role_id;
                $data['is_blocked'] = $request->has('active') ? 0 : 1;
            } else {
                $data['is_blocked'] = 0; // Super Admin is always active
            }

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($admin->avatar && file_exists(public_path($admin->avatar))) {
                    @unlink(public_path($admin->avatar));
                }
                
                $file = $request->file('avatar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/admins'), $filename);
                $data['avatar'] = 'uploads/admins/' . $filename;
            }

            $admin->update($data);

            return response()->json(['success' => true, 'redirect' => route('admin.admins.list')]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'msg' => collect($e->errors())->flatten()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }
    /**
     * Delete an admin via AJAX.
     */
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        if ($admin->id == 1 || $admin->email == 'admin@admin.com') { // Protect super admin
            return response()->json(['success' => false, 'msg' => 'Cannot delete Super Admin'], 403);
        }
        $admin->delete();

        return response()->json(['success' => true, 'id' => $id]);
    }

    /**
     * Bulk Delete admins via AJAX.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            // Protect ID 1
            Admin::whereIn('id', $ids)->where('id', '!=', 1)->where('email', '!=', 'admin@admin.com')->delete();
        }

        return response()->json(['success' => true]);
    }
}
