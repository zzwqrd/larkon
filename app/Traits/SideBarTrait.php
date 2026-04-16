<?php

namespace App\Traits;

use App\Services\MenuService;

trait SideBarTrait
{
    /**
     * Generate HTML buttons for the professional sidebar.
     * Clean implementation that renders localized items from MenuService.
     */
    public static function sidebarButtons()
    {
        $menuItems = MenuService::getMenuItems();
        $html = '';

        foreach ($menuItems as $item) {
            $hasChildren = !empty($item['children']);
            $isActive = request()->routeIs($item['name']) || ($hasChildren && collect($item['children'])->contains(fn($child) => request()->routeIs($child['name'])));
            
            // Add Section Menu Title (e.g., General, Orders) if provided
            if ($item['menu_title']) {
                $html .= '<li class="menu-title mt-2">' . (strpos($item['menu_title'], 'messages.') !== false ? __($item['menu_title']) : $item['menu_title']) . '</li>';
            }

            if ($item['type'] === 'single' || !$hasChildren) {
                // Render single nav item
                $html .= '<li class="nav-item">
                    <a class="nav-link ' . ($isActive ? 'active' : '') . '" href="' . route($item['name']) . '">
                        <span class="nav-icon">
                            <iconify-icon icon="' . $item['icon'] . '"></iconify-icon>
                        </span>
                        <span class="nav-text"> ' . $item['title'] . ' </span>
                    </a>
                </li>';
            } else {
                // Render parent dropdown item
                $targetId = 'sidebar' . preg_replace('/[^a-zA-Z0-9]/', '', $item['name']);
                $html .= '<li class="nav-item">
                    <a class="nav-link menu-arrow ' . ($isActive ? '' : 'collapsed') . '" href="#' . $targetId . '" data-bs-toggle="collapse" role="button" aria-expanded="' . ($isActive ? 'true' : 'false') . '" aria-controls="' . $targetId . '">
                        <span class="nav-icon">
                            <iconify-icon icon="' . $item['icon'] . '"></iconify-icon>
                        </span>
                        <span class="nav-text"> ' . $item['title'] . ' </span>
                    </a>
                    <div class="collapse ' . ($isActive ? 'show' : '') . '" id="' . $targetId . '" data-bs-parent="#navbar-nav">
                        <ul class="nav sub-navbar-nav">';
                
                foreach ($item['children'] as $child) {
                    $isChildActive = request()->routeIs($child['name']);
                    $html .= '<li class="sub-nav-item">
                        <a class="sub-nav-link ' . ($isChildActive ? 'active' : '') . '" href="' . $child['url'] . '">' . $child['title'] . '</a>
                    </li>';
                }

                $html .= '</ul>
                    </div>
                </li>';
            }
        }

        return $html;
    }
}
