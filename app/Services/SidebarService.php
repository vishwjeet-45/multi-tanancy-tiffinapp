<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SidebarService
{

    public static function get(): array
    {
        $user = Auth::user();


        return [
            [
                'id' => 'dashboard',
                'title' => 'Dashboard',
                'icon' => '<i class="ri-dashboard-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('dashboard'),
                'permission' => 'view_dashboard',
            ],
            [
                'id' => 'users',
                'title' => 'Users',
                'icon' => '<i class="ri-group-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('users.*'),
                'permission' => 'view_users',
            ],
             [
                'id' => 'dashboard',
                'title' => 'Dashboard',
                'icon' => '<i class="ri-dashboard-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('dashboard'),
                'permission' => 'view_dashboard',
            ],
            [
                'id' => 'users',
                'title' => 'Users',
                'icon' => '<i class="ri-group-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('users.*'),
                'permission' => 'view_users',
            ],
             [
                'id' => 'dashboard',
                'title' => 'Dashboard',
                'icon' => '<i class="ri-dashboard-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('dashboard'),
                'permission' => 'view_dashboard',
            ],
            [
                'id' => 'users',
                'title' => 'Users',
                'icon' => '<i class="ri-group-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('users.*'),
                'permission' => 'view_users',
            ],
             [
                'id' => 'dashboard',
                'title' => 'Dashboard',
                'icon' => '<i class="ri-dashboard-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('dashboard'),
                'permission' => 'view_dashboard',
            ],
            [
                'id' => 'users',
                'title' => 'Users',
                'icon' => '<i class="ri-group-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('users.*'),
                'permission' => 'view_users',
            ],
             [
                'id' => 'dashboard',
                'title' => 'Dashboard',
                'icon' => '<i class="ri-dashboard-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('dashboard'),
                'permission' => 'view_dashboard',
            ],
            [
                'id' => 'users',
                'title' => 'Users',
                'icon' => '<i class="ri-group-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('users.*'),
                'permission' => 'view_users',
            ],
             [
                'id' => 'dashboard',
                'title' => 'Dashboard',
                'icon' => '<i class="ri-dashboard-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('dashboard'),
                'permission' => 'view_dashboard',
            ],
            [
                'id' => 'users',
                'title' => 'Users',
                'icon' => '<i class="ri-group-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('users.*'),
                'permission' => 'view_users',
            ],
             [
                'id' => 'dashboard',
                'title' => 'Dashboard',
                'icon' => '<i class="ri-dashboard-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('dashboard'),
                'permission' => 'view_dashboard',
            ],
            [
                'id' => 'users',
                'title' => 'Users',
                'icon' => '<i class="ri-group-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('users.*'),
                'permission' => 'view_users',
            ],
             [
                'id' => 'dashboard',
                'title' => 'Dashboard',
                'icon' => '<i class="ri-dashboard-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('dashboard'),
                'permission' => 'view_dashboard',
            ],
            [
                'id' => 'users',
                'title' => 'Users',
                'icon' => '<i class="ri-group-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('users.*'),
                'permission' => 'view_users',
            ],
             [
                'id' => 'dashboard',
                'title' => 'Dashboard',
                'icon' => '<i class="ri-dashboard-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('dashboard'),
                'permission' => 'view_dashboard',
            ],
            [
                'id' => 'users',
                'title' => 'Users',
                'icon' => '<i class="ri-group-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('users.*'),
                'permission' => 'view_users',
            ],
             [
                'id' => 'dashboard',
                'title' => 'Dashboard',
                'icon' => '<i class="ri-dashboard-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('dashboard'),
                'permission' => 'view_dashboard',
            ],
            [
                'id' => 'users',
                'title' => 'Users',
                'icon' => '<i class="ri-group-line"></i>',
                'link' => '#',
                'is_active' => request()->routeIs('users.*'),
                'permission' => 'view_users',
            ],

        ];
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        // Cache per user role to improve performance
        $cacheKey = 'sidebar_menu_' . $user->id . '_' . md5(json_encode($user->roles->pluck('id')));

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($user) {
            $menuItems = self::getMenuStructure();

            // Filter by permissions
            return collect($menuItems)
                ->filter(fn($item) => self::hasPermission($user, $item))
                ->map(function ($item) use ($user) {
                    // Filter submenu items by permission
                    if (isset($item['submenu']) && is_array($item['submenu'])) {
                        $item['submenu'] = collect($item['submenu'])
                            ->filter(fn($sub) => self::hasPermission($user, $sub))
                            ->values()
                            ->toArray();
                    }

                    // Mark active states
                    $item['is_active'] = self::isActive($item['link']);

                    if (isset($item['submenu'])) {
                        foreach ($item['submenu'] as &$sub) {
                            $sub['is_active'] = self::isActive($sub['link']);
                        }
                    }

                    return $item;
                })
                ->values()
                ->toArray();
        });
    }

    /**
     * Define the complete menu structure
     */
    private static function getMenuStructure(): array
    {
        return [
            [
                'id' => 'dashboard',
                'title' => 'Dashboard',
                'icon' => '<i class="ri-dashboard-line"></i>',
                'link' => route('dashboard'),
                'permission' => 'view_dashboard',
            ],
            [
                'id' => 'users',
                'title' => 'Manage Users',
                'icon' => '<i class="ri-group-line"></i>',
                'link' => route('users.index'),
                'permission' => 'manage_users',
                'submenu' => self::getUserRoleSubmenu(),
            ],
            [
                'id' => 'settings',
                'title' => 'Settings',
                'icon' => '<i class="ri-settings-3-line"></i>',
                'link' => route('settings.index'),
                'permission' => 'manage_settings',
            ],
            // Add more menu items here...
        ];
    }

    /**
     * Generate submenu for user roles dynamically
     */
    private static function getUserRoleSubmenu(): array
    {
        $roles = collect([
            (object)['id' => 1, 'name' => 'admin'],
            (object)['id' => 2, 'name' => 'manager'],
        ]);

        return $roles->map(function ($role) {
            return [
                'id' => 'role_' . $role->id,
                'title' => ucwords(str_replace('_', ' ', $role->name)),
                'link' => route('admin.user_list', Str::slug($role->name)),
                'permission' => 'view_users',
            ];
        })->toArray();
    }

    /**
     * Check if user has permission for menu item
     */
    private static function hasPermission($user, array $item): bool
    {
        // No permission required
        if (!isset($item['permission'])) {
            return true;
        }
        return true;
    }

    /**
     * Check if the current route matches the menu item
     */
    private static function isActive(string $link): bool
    {
        $currentUrl = url()->current();
        $menuUrl = $link;

        // Exact match
        if ($currentUrl === $menuUrl) {
            return true;
        }

        // Check if current URL starts with menu URL (for nested routes)
        if (Str::startsWith($currentUrl, $menuUrl)) {
            return true;
        }

        // Check route name matching (more reliable)
        $currentRouteName = Route::currentRouteName();
        $menuRouteName = self::extractRouteName($link);

        if ($menuRouteName && Str::startsWith($currentRouteName, $menuRouteName)) {
            return true;
        }

        return false;
    }

    /**
     * Extract route name from a generated URL
     */
    private static function extractRouteName(string $url): ?string
    {
        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            if ($route->uri() && url($route->uri()) === $url) {
                return $route->getName();
            }
        }

        return null;
    }

    /**
     * Clear sidebar cache for a user
     */
    public static function clearCache(?int $userId = null): void
    {
        if ($userId) {
            Cache::forget('sidebar_menu_' . $userId . '_*');
        } else {
            Cache::flush();
        }
    }
}
