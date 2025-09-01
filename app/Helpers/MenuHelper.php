<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    /**
     * Get the dynamic menu based on user permissions.
     *
     * @param  array  $menu  The full menu array from the JSON file.
     * @return array The filtered menu array.
     */
    public static function getDynamicMenu(array $menu): array
    {
        $user = Auth::user();

        // If no user is logged in, show only public items.
        if (! $user) {
            return array_filter($menu, fn ($item) => ! isset($item['permission']));
        }

        $finalMenu = [];
        $lastHeader = null;

        foreach ($menu as $item) {
            // Check if the current item is a menu header.
            if (isset($item['menuHeader'])) {
                // We hold onto the header for now. We'll add it later if we find a visible item.
                $lastHeader = $item;

                continue;
            }

            // Assume the item has a permission requirement.
            $hasPermission = ! isset($item['permission']) || $user->can(config('permit.'.$item['permission'].'.name')); // If no permission key, it's public.

            if (isset($item['name']) && $item['name'] == 'My Detachment' && $user->detachment_id == null) {
                $hasPermission = false;
            }
            // NEW: Special handling for "Form Library"
            if (isset($item['name']) && $item['name'] == 'Form Library') {
                // 1. Get all form types from the config file
                $formTypes = config('forms.types', []);

                // 2. Extract just the 'view_permission' values
                $formPermissions = Arr::pluck($formTypes, 'name');

                // 3. Prepend the config prefix to each permission name
                $fullPermissions = array_map(function ($permission) {
                    $permit = 'fill '.strtolower($permission);

                    return config('permit.'.$permit.'.name');
                }, $formPermissions);

                // 4. Check if the user has ANY of these permissions
                $hasPermission = $user->canany($fullPermissions);
            }
            // Handle items with submenus.
            if (isset($item['submenu'])) {
                $visibleSubmenuItems = array_filter($item['submenu'], function ($subItem) use ($user) {
                    return ! isset($subItem['permission']) || $user->can(config('permit.'.$subItem['permission'].'.name'));
                });

                // The main menu item is only visible if it has at least one visible sub-item.
                $item['submenu'] = $visibleSubmenuItems;
                $hasPermission = ! empty($item['submenu']);
            }

            // If the item is visible, we add the held header (if any) and the item itself.
            if ($hasPermission) {
                if ($lastHeader) {
                    $finalMenu[] = $lastHeader;
                    $lastHeader = null; // Clear the temporary header.
                }
                $finalMenu[] = $item;
            }
        }

        return $finalMenu;
    }
}
