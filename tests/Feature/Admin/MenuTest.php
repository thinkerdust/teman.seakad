<?php

namespace Tests\Feature\Admin;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\MenuSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Uji user tanpa permission menu.view ditolak akses ke manajemen menu.
     */
    public function test_user_without_menu_permission_cannot_access_menus_page(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(MenuSeeder::class);

        // Buat user biasa (role User hanya memiliki dashboard, theme, dan invitation)
        $user = User::factory()->create();
        $userRole = Role::where('name', 'User')->first();
        $user->roles()->sync([$userRole->id]);

        $response = $this->actingAs($user)->get(route('admin.menus.index'));
        $response->assertStatus(403);
    }

    /**
     * Uji user dengan permission menu.view dapat mengakses halaman manajemen menu.
     */
    public function test_user_with_menu_permission_can_access_menus_page(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(MenuSeeder::class);

        // Berikan user role Superadmin (memiliki akses penuh termasuk menu.view)
        $user = User::factory()->create();
        $superadminRole = Role::where('name', 'Superadmin')->first();
        $user->roles()->sync([$superadminRole->id]);

        $response = $this->actingAs($user)->get(route('admin.menus.index'));
        $response->assertStatus(200);
    }

    /**
     * Uji crud menu lengkap oleh superadmin.
     */
    public function test_superadmin_can_perform_menu_crud(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(MenuSeeder::class);

        $superadmin = User::factory()->create([
            'email' => 'admin@teman-seakad.com',
        ]);

        // 1. Create Menu (POST)
        $response = $this->actingAs($superadmin)->post(route('admin.menus.store'), [
            'parent_id' => null,
            'title' => 'Menu Uji Coba',
            'icon' => '<svg></svg>',
            'route' => 'admin.dashboard',
            'permission' => 'dashboard.view',
            'order' => 10,
            'status' => 'active',
        ]);
        $response->assertRedirect(route('admin.menus.index'));
        $this->assertDatabaseHas('menus', ['title' => 'Menu Uji Coba']);

        // 2. Update Menu (PUT)
        $menu = Menu::where('title', 'Menu Uji Coba')->firstOrFail();
        $response = $this->actingAs($superadmin)->put(route('admin.menus.update', $menu), [
            'parent_id' => null,
            'title' => 'Menu Uji Coba Diubah',
            'icon' => '<svg></svg>',
            'route' => 'admin.dashboard',
            'permission' => 'dashboard.view',
            'order' => 15,
            'status' => 'inactive',
        ]);
        $response->assertRedirect(route('admin.menus.index'));
        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'title' => 'Menu Uji Coba Diubah',
            'status' => 'inactive',
        ]);

        // 3. Delete Menu (DELETE)
        $response = $this->actingAs($superadmin)->delete(route('admin.menus.destroy', $menu));
        $response->assertRedirect(route('admin.menus.index'));
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }
}
