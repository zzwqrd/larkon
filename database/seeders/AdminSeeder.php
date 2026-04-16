<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Super Admin Role
        $role = Role::updateOrCreate(
            ['name' => 'Super Admin'],
            []
        );

        // 2. Create Super Admin User (in users table for standard auth linkage)
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role_id' => $role->id,
            ]
        );

        // 3. Create record in admins table too
        Admin::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role_id' => $role->id,
                'is_blocked' => false,
            ]
        );

        // We run the command to seed all permissions to Super Admin role
        // This will capture every sub_route from routes/admin.php perfectly.
        try {
            DB::table('permissions')->where('role_id', $role->id)->delete();
            Artisan::call('permissions:update');
        } catch (\Exception $e) {
             // Silently continue if the command is not fully loaded during seeder
        }
    }
}
