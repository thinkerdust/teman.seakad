<?php

namespace App\Http\View\Composers;

use App\Models\Menu;
use Illuminate\View\View;

class SidebarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $user = auth()->user();

        // 1. Ambil menu-menu root (parent_id: null) yang aktif
        $rootMenus = Menu::whereNull('parent_id')
            ->where('status', 'active')
            ->orderBy('order')
            ->with(['children' => function ($query) {
                $query->where('status', 'active')->orderBy('order');
            }])
            ->get();

        // 2. Filter menu berdasarkan hak akses (permission)
        $filteredMenus = $rootMenus->filter(function ($menu) use ($user) {
            // Check root menu permission
            if ($menu->permission && (! $user || ! $user->hasPermission($menu->permission))) {
                return false;
            }

            // Filter children
            if ($menu->children->isNotEmpty()) {
                $menu->setRelation('children', $menu->children->filter(function ($child) use ($user) {
                    if ($child->permission && (! $user || ! $user->hasPermission($child->permission))) {
                        return false;
                    }

                    return true;
                }));
            }

            return true;
        });

        $view->with('sidebarMenus', $filteredMenus);
    }
}
