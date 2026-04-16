<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Models\Permission;
use App\Models\Role;

class UpdatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically scan admin routes and update permissions table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scanning admin routes...');

        $routes = Route::getRoutes();
        $adminPermissions = [];

        foreach ($routes as $route) {
            $name = $route->getName();
            $actions = $route->getAction();
            
            // Capture routes that are clearly part of the admin panel
            // (either via name prefix or via administrative metadata)
            if ($name) {
                if (str_starts_with($name, 'admin.') || 
                    isset($actions['title']) || 
                    isset($actions['icon']) || 
                    isset($actions['menu_title']) || 
                    isset($actions['sub_route'])) {
                    $adminPermissions[] = $name;
                }
            }
        }

        $adminPermissions = array_unique($adminPermissions);
        $count = count($adminPermissions);

        $this->info("Found {$count} admin permissions.");

        // For this professional implementation, we ensure Role ID 1 (Super Admin) 
        // has all these permissions by default.
        $superAdmin = Role::find(1);
        if (!$superAdmin) {
            $this->error('Super Admin role (ID: 1) not found. Please seed your roles first.');
            return Command::FAILURE;
        }

        $this->info("Syncing permissions to Super Admin (Role: {$superAdmin->name})...");

        foreach ($adminPermissions as $permName) {
            Permission::updateOrCreate([
                'role_id'    => $superAdmin->id,
                'permission' => $permName,
            ]);
        }

        $this->info("Successfully updated {$count} permissions for Super Admin.");
        return Command::SUCCESS;
    }
}
