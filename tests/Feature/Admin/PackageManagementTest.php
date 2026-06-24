<?php

namespace Tests\Feature\Admin;

use App\Models\Package;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * User tanpa hak akses package.view ditolak akses ke halaman paket.
     */
    public function test_user_without_package_permission_cannot_access_packages_page(): void
    {
        $user = User::factory()->create();
        $userRole = Role::where('name', 'User')->first();
        $user->roles()->sync([$userRole->id]);

        $response = $this->actingAs($user)->get(route('admin.packages.index'));
        $response->assertStatus(403);
    }

    /**
     * User dengan hak akses package.view dapat mengakses halaman paket.
     */
    public function test_user_with_package_view_permission_can_access_packages_page(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $response = $this->actingAs($admin)->get(route('admin.packages.index'));
        $response->assertStatus(200);
    }

    /**
     * Admin dapat membuat paket baru.
     */
    public function test_admin_can_create_package(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $packageData = [
            'name' => 'Gold Package',
            'description' => 'Paket emas dengan fasilitas lengkap',
            'price' => 500000,
            'invitation_quota' => 10,
            'duration_days' => 180,
            'status' => 'active',
        ];

        $response = $this->actingAs($admin)->post(route('admin.packages.store'), $packageData);

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseHas('packages', [
            'name' => 'Gold Package',
            'price' => 500000.00,
            'invitation_quota' => 10,
            'duration_days' => 180,
            'status' => 'active',
        ]);
    }

    /**
     * Admin dapat memperbarui data paket.
     */
    public function test_admin_can_update_package(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $pkg = Package::create([
            'name' => 'Silver Package',
            'description' => 'Paket perak standard',
            'price' => 200000,
            'invitation_quota' => 3,
            'duration_days' => 90,
            'status' => 'active',
        ]);

        $updatedData = [
            'name' => 'Silver Package Premium',
            'description' => 'Paket perak dengan diskon khusus',
            'price' => 180000,
            'invitation_quota' => 4,
            'duration_days' => 120,
            'status' => 'inactive',
        ];

        $response = $this->actingAs($admin)->put(route('admin.packages.update', $pkg), $updatedData);

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseHas('packages', [
            'id' => $pkg->id,
            'name' => 'Silver Package Premium',
            'price' => 180000.00,
            'invitation_quota' => 4,
            'duration_days' => 120,
            'status' => 'inactive',
        ]);
    }

    /**
     * Admin dapat menghapus paket.
     */
    public function test_admin_can_delete_package(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $pkg = Package::create([
            'name' => 'Temp Package',
            'price' => 50000,
            'invitation_quota' => 1,
            'duration_days' => 7,
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.packages.destroy', $pkg));

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseMissing('packages', ['id' => $pkg->id]);
    }
}
