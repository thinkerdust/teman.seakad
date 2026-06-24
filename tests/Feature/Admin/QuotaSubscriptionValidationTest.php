<?php

namespace Tests\Feature\Admin;

use App\Models\Invitation;
use App\Models\Order;
use App\Models\Package;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuotaSubscriptionValidationTest extends TestCase
{
    use RefreshDatabase;

    protected Theme $theme;

    protected Role $userRole;

    protected Role $adminRole;

    protected Role $superadminRole;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->userRole = Role::where('name', 'User')->first();
        $this->adminRole = Role::where('name', 'Admin')->first();
        $this->superadminRole = Role::where('name', 'Superadmin')->first();

        // Create active theme
        $this->theme = Theme::create([
            'name' => 'Rustic',
            'slug' => 'rustic',
            'folder' => 'rustic-forest',
            'status' => 'active',
        ]);

        // Create default package
        Package::create([
            'id' => 1,
            'name' => 'Basic',
            'price' => 100000,
            'invitation_quota' => 1,
            'duration_days' => 30,
            'status' => 'active',
        ]);
    }

    /**
     * Helper to set up a user with an active order & subscription.
     */
    private function setupUserWithQuota(string $email, int $quota): User
    {
        Order::create([
            'customer_name' => 'Jane Client',
            'phone' => '08987654321',
            'email' => $email,
            'package_id' => 1,
            'quota' => $quota,
            'price' => 100000,
            'status' => 'active',
            'start_date' => Carbon::today()->toDateString(),
            'end_date' => Carbon::today()->addDays(30)->toDateString(),
        ]);

        $user = User::where('email', $email)->first();
        $user->roles()->sync([$this->userRole->id]);

        return $user;
    }

    /**
     * Uji regular user dengan kuota habis tidak bisa membuat undangan baru.
     */
    public function test_user_with_exhausted_quota_cannot_create_invitation(): void
    {
        // Setup user dengan kuota = 1
        $user = $this->setupUserWithQuota('client@example.com', 1);

        // Buat 1 undangan pertama (kuota terpakai = 1, tersisa = 0)
        Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-pertama',
            'title' => 'Pernikahan Pertama',
            'groom_name' => 'Groom',
            'bride_name' => 'Bride',
            'venue' => 'Gedung A',
            'address' => 'Alamat A',
            'status' => 'draft',
        ]);

        // Kirim post request untuk membuat undangan kedua
        $response = $this->actingAs($user)->post(route('admin.invitations.store'), [
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-kedua',
            'title' => 'Pernikahan Kedua',
            'groom_name' => 'Groom 2',
            'bride_name' => 'Bride 2',
            'venue' => 'Gedung B',
            'address' => 'Alamat B',
        ]);

        $response->assertRedirect(route('admin.invitations.index'));
        $response->assertSessionHas('error', 'Kuota pembuatan undangan Anda sudah habis. Silakan lakukan perpanjangan atau hubungi admin.');

        // Pastikan undangan kedua tidak masuk database
        $this->assertDatabaseMissing('invitations', [
            'slug' => 'undangan-kedua',
        ]);
    }

    /**
     * Uji regular user dengan kuota tersedia bisa membuat undangan baru.
     */
    public function test_user_with_available_quota_can_create_invitation(): void
    {
        // Setup user dengan kuota = 2
        $user = $this->setupUserWithQuota('client2@example.com', 2);

        // Buat 1 undangan pertama
        Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-satu',
            'title' => 'Pernikahan Satu',
            'groom_name' => 'Groom',
            'bride_name' => 'Bride',
            'venue' => 'Gedung A',
            'address' => 'Alamat A',
            'status' => 'draft',
        ]);

        // Kirim post request untuk membuat undangan kedua
        $response = $this->actingAs($user)->post(route('admin.invitations.store'), [
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-dua',
            'title' => 'Pernikahan Dua',
            'groom_name' => 'Groom 2',
            'bride_name' => 'Bride 2',
            'venue' => 'Gedung B',
            'address' => 'Alamat B',
        ]);

        $response->assertRedirect(route('admin.invitations.index'));
        $response->assertSessionHas('success', 'Undangan baru berhasil dibuat.');

        // Pastikan undangan kedua berhasil masuk database
        $this->assertDatabaseHas('invitations', [
            'slug' => 'undangan-dua',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Uji administrator (Admin/Superadmin) tidak dibatasi kuota atau langganan saat membuat undangan.
     */
    public function test_admin_bypasses_quota_and_subscription_validation(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->roles()->sync([$this->superadminRole->id]);

        // Kirim post request tanpa memiliki order/subscription aktif
        $response = $this->actingAs($superadmin)->post(route('admin.invitations.store'), [
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-admin',
            'title' => 'Pernikahan Admin',
            'groom_name' => 'Groom Admin',
            'bride_name' => 'Bride Admin',
            'venue' => 'Gedung Admin',
            'address' => 'Alamat Admin',
        ]);

        $response->assertRedirect(route('admin.invitations.index'));
        $response->assertSessionHas('success', 'Undangan baru berhasil dibuat.');

        $this->assertDatabaseHas('invitations', [
            'slug' => 'undangan-admin',
            'user_id' => $superadmin->id,
        ]);
    }
}
