<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Super Admin Role
        $role = Role::updateOrCreate(
            ['id' => 1],
            ['name' => 'Super Admin']
        );

        // 2. Create Super Admin User (in users table for standard login)
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Main Admin',
                'password' => Hash::make('password'),
                'role_id' => $role->id,
            ]
        );

        // 3. Create record in admins table too (for consistency)
        Admin::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Main Admin',
                'password' => Hash::make('password'),
                'role_id' => $role->id,
                'is_blocked' => false,
            ]
        );

        // 4. Create additional test admins
        $supervisorRole = Role::firstOrCreate(['name' => 'Supervisor']);
        Admin::updateOrCreate(
            ['email' => 'supervisor@example.com'],
            [
                'name' => 'John Supervisor',
                'password' => Hash::make('password'),
                'role_id' => $supervisorRole->id,
                'is_blocked' => false,
            ]
        );

        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        Admin::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Sarah Manager',
                'password' => Hash::make('password'),
                'role_id' => $managerRole->id,
                'is_blocked' => false,
            ]
        );

        // Ensure users table matches
        foreach (Admin::all() as $adm) {
            \App\Models\User::updateOrCreate(
                ['email' => $adm->email],
                [
                    'name' => $adm->name,
                    'password' => $adm->password,
                    'role_id' => $adm->role_id,
                ]
            );
        }
    }
}
