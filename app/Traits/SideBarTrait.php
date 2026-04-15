<?php

namespace App\Traits;

use App\Models\Permission;
use Illuminate\Support\Facades\Route;

trait SideBarTrait
{
    public static function sidebarButtons()
    {
        $buttonsHtml = '';
        $currentRoute = request()->route();
        $currentName = $currentRoute ? $currentRoute->getName() : '';

        $user = auth()->guard('admin')->user();
        if (!$user)
            return '';

        // Super Admin Bypass
        $isSuperAdmin = ($user->id == 1);

        // Fetch User Permissions from Database
        $myPermissions = Permission::where('role_id', $user->role_id)->pluck('permission')->toArray();

        // Prevent permission index crash for dashboard
        $myPermissions[] = 'admin.dashboard.index';

        $renderedTitles = [];

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();
            $actions = $route->getAction();

            // Handle Menu Titles (General, Users, etc.)
            if (isset($actions['menu_title'])) {
                $mTitle = __($actions['menu_title']);
                if (!in_array($mTitle, $renderedTitles)) {
                    $buttonsHtml .= '<li class="menu-title mt-2">' . $mTitle . '</li>';
                    $renderedTitles[] = $mTitle;
                }
            }

            // Must have a real name, an icon, and must NOT be marked as a sub_route
            if ($name !== null && isset($actions['icon']) && (!isset($actions['sub_route']) || !$actions['sub_route'])) {

                // Show if SuperAdmin OR user has permission for the main route
                if ($isSuperAdmin || in_array($name, $myPermissions)) {
                    $titleArr = $actions['title'] ?? [];
                    $titleText = isset($titleArr[0]) ? __($titleArr[0]) : ucwords(str_replace(['.', '-'], ' ', $name));
                    $icon = $actions['icon'];

                    // Parent Route (Dropdown) Logic
                    if (isset($actions['type']) && $actions['type'] === 'parent') {
                        $boolExpanded = 'false';
                        $showState = '';
                        $activeParent = '';
                        $listButtons = '';

                        $collapseId = 'sidebar_' . str_replace(['.'], '_', $name);

                        // Find Sub Routes
                        foreach (Route::getRoutes() as $sub_route) {
                            $subName = $sub_route->getName();
                            $subActions = $sub_route->getAction();

                            // Ensure it's marked as a sub_route AND exists in the parent's 'children' array
                            if ($subName && isset($subActions['sub_route']) && $subActions['sub_route'] === true && isset($actions['children']) && in_array($subName, $actions['children'])) {

                                // Show if SuperAdmin OR user has permission for the sub route
                                if ($isSuperAdmin || in_array($subName, $myPermissions)) {
                                    $subActive = ($currentName == $subName) ? 'active' : '';

                                    if ($subActive === 'active') {
                                        $activeParent = 'active'; // In Laravel BS5 templates, the parent A link sometimes gets color if active.
                                        $showState = 'show';
                                        $boolExpanded = 'true';
                                    }

                                    $subTitleArr = $subActions['title'] ?? [];
                                    $subTitleText = isset($subTitleArr[0]) ? __($subTitleArr[0]) : ucwords(str_replace(['.', '-'], ' ', $subName));

                                    // Larkon specific structure for sub menu item
                                    $listButtons .= '
                                        <li class="sub-nav-item">
                                            <a class="sub-nav-link ' . $subActive . '" href="' . route($subName) . '">' . $subTitleText . '</a>
                                        </li>';
                                }
                            }
                        }

                        // Only render parent if it has children that passed the permission check
                        if (!empty($listButtons)) {
                            $buttonsHtml .= '
                            <li class="nav-item">
                                <a class="nav-link menu-arrow ' . $activeParent . '" href="#' . $collapseId . '" data-bs-toggle="collapse" role="button" aria-expanded="' . $boolExpanded . '" aria-controls="' . $collapseId . '">
                                    <span class="nav-icon">
                                        <iconify-icon icon="' . $icon . '"></iconify-icon>
                                    </span>
                                    <span class="nav-text"> ' . $titleText . ' </span>
                                </a>
                                <div class="collapse ' . $showState . '" id="' . $collapseId . '">
                                    <ul class="nav sub-navbar-nav">
                                        ' . $listButtons . '
                                    </ul>
                                </div>
                            </li>';
                        }

                    } else {
                        // Single Route Logic
                        $activeSingle = ($currentName == $name) ? 'active' : '';

                        // Check if the route requires parameters (e.g. {any})
                        try {
                            $routeUrl = route($name);
                        } catch (\Exception $e) {
                            $routeUrl = '#'; // Fallback if route fails to generate due to missing parameters
                        }

                        // Larkon specific structure for single menu item
                        $buttonsHtml .= '
                            <li class="nav-item">
                                <a class="nav-link ' . $activeSingle . '" href="' . $routeUrl . '">
                                    <span class="nav-icon">
                                        <iconify-icon icon="' . $icon . '"></iconify-icon>
                                    </span>
                                    <span class="nav-text"> ' . $titleText . ' </span>
                                </a>
                            </li>';
                    }
                }
            }
        }
        return $buttonsHtml;
    }
}
