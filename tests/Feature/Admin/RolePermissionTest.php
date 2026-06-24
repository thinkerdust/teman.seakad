<?php

namespace Tests\Feature\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Uji user tanpa permission dashboard.view ditolak akses ke dashboard.
     */
    public function test_user_without_dashboard_permission_cannot_access_dashboard(): void
    {
        $this->seed(RolePermissionSeeder::class);

        // Buat user biasa (role User hanya memiliki dashboard.view, theme.view, invitation.create)
        $user = User::factory()->create();
        $userRole = Role::where('name', 'User')->first();
        $user->roles()->sync([$userRole->id]);

        // Coba akses user management (butuh user.view)
        $response = $this->actingAs($user)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    /**
     * Uji user dengan permission user.view dapat mengakses halaman user list.
     */
    public function test_user_with_user_view_permission_can_access_users_page(): void
    {
        $this->seed(RolePermissionSeeder::class);

        // Buat user dan beri role Admin (memiliki user.view)
        $user = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $user->roles()->sync([$adminRole->id]);

        $response = $this->actingAs($user)->get(route('admin.users.index'));
        $response->assertStatus(200);
    }

    /**
     * Uji default admin (admin@teman-seakad.com) otomatis memiliki akses penuh (bypass).
     */
    public function test_superadmin_bypass_permission_check(): void
    {
        $this->seed(RolePermissionSeeder::class);

        // Buat superadmin default
        $superadmin = User::factory()->create([
            'email' => 'admin@teman-seakad.com',
        ]);
        // Jangan beri role apa-apa secara sengaja untuk menguji bypass email

        $response = $this->actingAs($superadmin)->get(route('admin.users.index'));
        $response->assertStatus(200);

        // Buat user lain dengan role Superadmin untuk menguji bypass role
        $user = User::factory()->create();
        $superadminRole = Role::where('name', 'Superadmin')->first();
        $user->roles()->sync([$superadminRole->id]);

        $response = $this->actingAs($user)->get(route('admin.users.index'));
        $response->assertStatus(200);
    }
}
