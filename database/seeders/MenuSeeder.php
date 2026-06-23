<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Group Header: Menu Utama
        $menuUtama = Menu::updateOrCreate(
            ['title' => 'Menu Utama', 'parent_id' => null],
            [
                'icon' => null,
                'route' => null,
                'permission' => null,
                'order' => 1,
                'status' => 'active',
            ]
        );

        // Dashboard under Menu Utama
        Menu::updateOrCreate(
            ['title' => 'Dashboard', 'parent_id' => $menuUtama->id],
            [
                'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>',
                'route' => 'admin.dashboard',
                'permission' => 'dashboard.view',
                'order' => 1,
                'status' => 'active',
            ]
        );

        // User Management under Menu Utama
        Menu::updateOrCreate(
            ['title' => 'User Management', 'parent_id' => $menuUtama->id],
            [
                'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
                'route' => 'admin.users.index',
                'permission' => 'user.view',
                'order' => 2,
                'status' => 'active',
            ]
        );

        // Menu Management under Menu Utama
        Menu::updateOrCreate(
            ['title' => 'Menu Management', 'parent_id' => $menuUtama->id],
            [
                'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>',
                'route' => 'admin.menus.index',
                'permission' => 'menu.view',
                'order' => 3,
                'status' => 'active',
            ]
        );

        // 2. Group Header: Fitur Undangan
        $fiturUndangan = Menu::updateOrCreate(
            ['title' => 'Fitur Undangan', 'parent_id' => null],
            [
                'icon' => null,
                'route' => null,
                'permission' => null,
                'order' => 2,
                'status' => 'active',
            ]
        );

        // Tema Undangan under Fitur Undangan
        Menu::updateOrCreate(
            ['title' => 'Tema Undangan', 'parent_id' => $fiturUndangan->id],
            [
                'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
                'route' => 'admin.themes.index',
                'permission' => 'theme.view',
                'order' => 1,
                'status' => 'active',
            ]
        );

        // Daftar Undangan under Fitur Undangan
        Menu::updateOrCreate(
            ['title' => 'Daftar Undangan', 'parent_id' => $fiturUndangan->id],
            [
                'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5M12 14v4h-.01" /></svg>',
                'route' => 'admin.invitations.index',
                'permission' => 'invitation.view',
                'order' => 2,
                'status' => 'active',
            ]
        );
    }
}
