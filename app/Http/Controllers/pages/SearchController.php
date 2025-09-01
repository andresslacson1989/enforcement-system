<?php

namespace App\Http\Controllers\pages;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SearchController extends Controller
{
    public function searchRoutes()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['navigation' => []]);
        }

        // 1. Get searchable forms based on user permissions.
        $searchable_items = $this->getSearchableForms($user);

        // 2. Get searchable menu items from the JSON file.
        $menuPath = resource_path('menu/verticalMenu.json');
        if (File::exists($menuPath)) {
            $menuData = json_decode(File::get($menuPath), true);
            // 3. Recursively extract menu items the user can see.
            $menu_items = $this->extractMenuSearchableItems($menuData['menu'], $user);
            // 4. Merge the two lists together.
            $searchable_items = array_merge($searchable_items, $menu_items);
        }

        return response()->json(['navigation' => ['routes' => $searchable_items]]);
    }

    /**
     * Extracts searchable form items based on user permissions.
     * This is now a separate, non-recursive method.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    private function getSearchableForms($user)
    {
        $searchable = [];
        $forms = config('forms.types');

        foreach ($forms as $slug => $form) {
            // Construct the permission name directly, e.g., "fill requirement transmittal form"
            $permissionName = 'fill '.strtolower($form['name']);

            if ($user->can(config('permit.'.$permissionName.'.name'))) {
                $searchable[] = [
                    'name' => $form['name'],
                    'url' => 'form/create/'.$slug,
                    'icon' => 'ti tabler-forms',
                ];
            }
        }

        return $searchable;
    }

    /**
     * Recursively extracts searchable menu items based on user permissions.
     * This method no longer processes forms, which fixes the duplication bug.
     *
     * @param  array  $menuItems
     * @param  \App\Models\User  $user
     * @return array
     */
    private function extractMenuSearchableItems($menuItems, $user)
    {
        $searchable = [];

        foreach ($menuItems as $item) {
            // Skip menu headers
            if (isset($item['menuHeader'])) {
                continue;
            }

            // Check for permission and add the item if authorized
            // The permission key in the JSON is the permission name.
            if (isset($item['permission']) && $user->can(config('permit.'.$item['permission'].'.name'))) {
                if (isset($item['url'])) {
                    $searchable[] = [
                        'name' => $item['name'],
                        'url' => $item['url'],
                        'icon' => $item['icon'] ?? 'ti tabler-route',
                    ];
                }
            }

            // If there's a submenu, recursively process it
            if (isset($item['submenu'])) {
                $searchable = array_merge($searchable, $this->extractMenuSearchableItems($item['submenu'], $user));
            }
        }

        return $searchable;
    }
}
