<?php

namespace App\Services;

use App\Models\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class MenuService
{
    /**
     * Generate the professional sidebar menu items based on route metadata,
     * maintaining the exact order of registration in the routes file.
     */
    public static function getMenuItems()
    {
        $user = Auth::guard('admin')->user();
        if (!$user)
            return [];

        $isSuperAdmin = ($user->id == 1);
        $myPermissions = Permission::where('role_id', $user->role_id)->pluck('permission')->toArray();
        $myPermissions[] = 'admin.dashboard.index';

        $routes = Route::getRoutes();
        $finalMenu = [];
        $parentsMap = [];

        /**
         * PASS 1: Sequential Processing
         * We iterate through the routes in their exact registration order.
         */
        foreach ($routes as $route) {
            $name = $route->getName();
            $actions = $route->getAction();

            if (!$name)
                continue;

            // Authorization Check
            if (!$isSuperAdmin && !in_array($name, $myPermissions))
                continue;

            $isParent = isset($actions['type']) && $actions['type'] === 'parent';
            $isSubRoute = isset($actions['sub_route']) && $actions['sub_route'] === true;

            // --- CASE A: PARENT DROPDOWN ---
            if ($isParent) {
                $item = [
                    'name' => $name,
                    'title' => isset($actions['title'][0]) ? __($actions['title'][0]) : ucwords(str_replace(['admin.', '.', '-'], ' ', $name)),
                    'icon' => $actions['icon'] ?? 'solar:folder-bold-duotone',
                    'menu_title' => $actions['menu_title'] ?? null,
                    'type' => 'parent',
                    'children_names' => $actions['children'] ?? [],
                    'children' => []
                ];

                // Add to final menu and keep a reference for children
                $finalMenu[] = &$item;
                $parentsMap[$name] = &$item;
                unset($item); // Clear reference for next loop
            }
            // --- CASE B: SINGLE NAV ITEM ---
            elseif (!$isSubRoute && isset($actions['icon'])) {
                try {
                    $url = route($name);
                    $finalMenu[] = [
                        'name' => $name,
                        'title' => isset($actions['title'][0]) ? __($actions['title'][0]) : ucwords(str_replace(['admin.', '.', '-'], ' ', $name)),
                        'icon' => $actions['icon'],
                        'menu_title' => $actions['menu_title'] ?? null,
                        'type' => 'single',
                        'children' => []
                    ];
                } catch (\Exception $e) {
                    continue; // Skip routes with parameters
                }
            }
            // --- CASE C: SUB-ROUTE ---
            elseif ($isSubRoute) {
                // Find which parent this child belongs to
                foreach ($parentsMap as $parentName => &$parent) {
                    if (in_array($name, $parent['children_names']) || in_array(str_replace('admin.', '', $name), $parent['children_names'])) {
                        try {
                            $url = route($name);
                            $parent['children'][] = [
                                'name' => $name,
                                'title' => isset($actions['title'][0]) ? __($actions['title'][0]) : ucwords(str_replace(['admin.', '.', '-'], ' ', $name)),
                                'url' => $url
                            ];
                        } catch (\Exception $e) {
                            // Skip
                        }
                        break; // Child found its home
                    }
                }
            }
        }

        return $finalMenu;
    }
}
