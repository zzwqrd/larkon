<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;

/**
 * SidebarTrait: builds sidebar HTML based on route metadata & user permissions.
 *
 * Ported from perfume_admin project. Not wired-in yet; call SidebarTrait::sidebarButtons().
 */
trait SidebarTrait
{
    protected static function getNameWithoutAdmin(string $name): string
    {
        $currentNameArray = explode('.', $name);
        return implode('.', array_splice($currentNameArray, 1));
    }

    public static function sidebarButtons(): string
    {
        $routes = [];
        $buttonsHtml = '';
        $currentName = request()->route()?->getName();

        $roleId = Auth::guard('admin')->user()->role_id ?? null;
        $myPermissions = $roleId
            ? Permission::where('role_id', $roleId)->pluck('permission')->toArray()
            : [];

        foreach (Route::getRoutes() as $route) {
            if ($route->getName() === null) {
                continue;
            }

            $nameParts = explode('.', $route->getName());
            $isAdminArea = ($nameParts[0] ?? '') === 'admin';
            $canAccess = in_array($route->getName(), $myPermissions, true);
            $actions = $route->getAction();
            $hasIcon = isset($actions['icon']);
            $isSubRoute = $actions['sub_route'] ?? false;

            if (!($isAdminArea && $canAccess && $hasIcon && !$isSubRoute)) {
                continue;
            }

            $routes[] = $route;
            $active = ($currentName === $route->getName() || (isset($actions['children']) && in_array(self::getNameWithoutAdmin($currentName), $actions['children'], true))) ? 'open active' : '';

            if (($actions['type'] ?? null) === 'parent') {
                $boolExpanded = 'false';
                $show = '';
                $listButtons = '';

                foreach (Route::getRoutes() as $subRoute) {
                    $subActions = $subRoute->getAction();
                    $subActive = '';

                    if (($subActions['sub_route'] ?? false) === true && in_array(self::getNameWithoutAdmin($subRoute->getName()), $actions['children'] ?? [], true)) {
                        $subActive = ($currentName === $subRoute->getName() || (isset($subActions['children']) && in_array(self::getNameWithoutAdmin($currentName), $subActions['children'], true))) ? 'open active' : '';
                        $subIcon = $subActions['icon'] ?? '';
                        $listButtons .= '<li class="menu-item ' . $subActive . '"><a class="menu-link ' . $subActive . '" href="' . route($subRoute->getName()) . '">' .
                            $subIcon . '<div data-i18n="' . self::getTitle($subActions['title']) . '"> ' . self::getTitle($subActions['title']) . ' </div></a></li>';
                    }

                    if ($subActive === 'active') {
                        $active = $subActive;
                        $boolExpanded = $subActive ? 'true' : 'false';
                        $show = $subActive ? 'show' : '';
                    }
                }

                $buttonsHtml .= '<li class="menu-item ' . $active . '"><a class="menu-link menu-toggle ' . $show . '" href="javascript:void(0);">' .
                    $actions['icon'] . '<div data-i18n="' . self::getTitle($actions['title']) . '">' . self::getTitle($actions['title']) . ' </div></a><ul class="menu-sub">' . $listButtons . '</ul></li>';
            } else {
                $buttonsHtml .= '<li class="menu-item ' . $active . '"><a class="menu-link" href="' . route($route->getName()) . '">' .
                    $actions['icon'] . '<div data-i18n="' . self::getTitle($actions['title']) . '">' . self::getTitle($actions['title']) . ' </div></a></li>';
            }
        }

        return $buttonsHtml;
    }

    protected static function getTitle(array $arr): string
    {
        return implode(' ', array_map(fn ($val) => __($val), $arr));
    }
}
