<?php

namespace Tests\Feature\Admin;

use App\Models\Invitation;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    protected Theme $theme;

    protected Role $userRole;

    protected Role $adminRole;

    protected Role $superadminRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->seed(RolePermissionSeeder::class);

        // Fetch seeded roles
        $this->userRole = Role::where('name', 'User')->first();
        $this->adminRole = Role::where('name', 'Admin')->first();
        $this->superadminRole = Role::where('name', 'Superadmin')->first();

        // Create an active theme for testing
        $this->theme = Theme::create([
            'name' => 'Rustic',
            'slug' => 'rustic',
            'folder' => 'rustic-forest',
            'status' => 'active',
        ]);
    }

    /**
     * Uji tamu (guest) yang belum login dialihkan ke halaman login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.invitations.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Uji user biasa hanya melihat undangan milik sendiri.
     */
    public function test_user_only_views_own_invitations(): void
    {
        $user1 = User::factory()->create();
        $user1->roles()->sync([$this->userRole->id]);

        $user2 = User::factory()->create();
        $user2->roles()->sync([$this->userRole->id]);

        // Undangan milik user1
        $invitation1 = Invitation::create([
            'user_id' => $user1->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-user-1',
            'title' => 'Pernikahan User 1',
            'groom_name' => 'Groom 1',
            'bride_name' => 'Bride 1',
            'venue' => 'Gedung 1',
            'address' => 'Alamat 1',
            'status' => 'draft',
        ]);

        // Undangan milik user2
        $invitation2 = Invitation::create([
            'user_id' => $user2->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-user-2',
            'title' => 'Pernikahan User 2',
            'groom_name' => 'Groom 2',
            'bride_name' => 'Bride 2',
            'venue' => 'Gedung 2',
            'address' => 'Alamat 2',
            'status' => 'draft',
        ]);

        // Akses sebagai user1
        $response = $this->actingAs($user1)->get(route('admin.invitations.index'));
        $response->assertStatus(200);
        $response->assertSee('Pernikahan User 1');
        $response->assertDontSee('Pernikahan User 2');
    }

    /**
     * Uji Superadmin dan Admin dapat melihat semua undangan.
     */
    public function test_superadmin_and_admin_can_view_all_invitations(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->roles()->sync([$this->superadminRole->id]);

        $admin = User::factory()->create();
        $admin->roles()->sync([$this->adminRole->id]);

        $user = User::factory()->create();
        $user->roles()->sync([$this->userRole->id]);

        // Buat undangan milik user
        Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-user',
            'title' => 'Undangan User Biasa',
            'groom_name' => 'Groom User',
            'bride_name' => 'Bride User',
            'venue' => 'Gedung User',
            'address' => 'Alamat User',
            'status' => 'draft',
        ]);

        // Akses sebagai Superadmin
        $responseSuper = $this->actingAs($superadmin)->get(route('admin.invitations.index'));
        $responseSuper->assertStatus(200);
        $responseSuper->assertSee('Undangan User Biasa');

        // Akses sebagai Admin
        $responseAdmin = $this->actingAs($admin)->get(route('admin.invitations.index'));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Undangan User Biasa');
    }

    /**
     * Uji pembuatan undangan oleh user biasa.
     */
    public function test_user_can_create_invitation(): void
    {
        $user = User::factory()->create();
        $user->roles()->sync([$this->userRole->id]);

        $payload = [
            'theme_id' => $this->theme->id,
            'title' => 'Pernikahan Kita',
            'slug' => 'pernikahan-kita',
            'groom_name' => 'Budi',
            'bride_name' => 'Wati',
            'venue' => 'Masjid Raya',
            'address' => 'Jalan Kebangsaan No. 12',
            'maps_url' => 'https://maps.google.com/?q=jakarta',
            'description' => 'Mohon kehadirannya',
        ];

        $response = $this->actingAs($user)->post(route('admin.invitations.store'), $payload);
        $response->assertRedirect(route('admin.invitations.index'));
        $response->assertSessionHas('success', 'Undangan baru berhasil dibuat.');

        $this->assertDatabaseHas('invitations', [
            'user_id' => $user->id,
            'slug' => 'pernikahan-kita',
            'groom_name' => 'Budi',
            'bride_name' => 'Wati',
            'status' => 'draft',
        ]);
    }

    /**
     * Uji user dapat memperbarui undangan miliknya sendiri.
     */
    public function test_user_can_update_own_invitation(): void
    {
        $user = User::factory()->create();
        $user->roles()->sync([$this->userRole->id]);

        $invitation = Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-lama',
            'title' => 'Undangan Lama',
            'groom_name' => 'Lama Pria',
            'bride_name' => 'Lama Wanita',
            'venue' => 'Gedung Lama',
            'address' => 'Alamat Lama',
            'status' => 'draft',
        ]);

        $payload = [
            'theme_id' => $this->theme->id,
            'title' => 'Undangan Baru',
            'slug' => 'undangan-baru',
            'groom_name' => 'Baru Pria',
            'bride_name' => 'Baru Wanita',
            'venue' => 'Gedung Baru',
            'address' => 'Alamat Baru',
        ];

        $response = $this->actingAs($user)->put(route('admin.invitations.update', $invitation->id), $payload);
        $response->assertRedirect(route('admin.invitations.index'));
        $response->assertSessionHas('success', 'Data undangan berhasil diperbarui.');

        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'title' => 'Undangan Baru',
            'slug' => 'undangan-baru',
            'groom_name' => 'Baru Pria',
        ]);
    }

    /**
     * Uji user tidak dapat memperbarui undangan milik orang lain.
     */
    public function test_user_cannot_update_others_invitation(): void
    {
        $user1 = User::factory()->create();
        $user1->roles()->sync([$this->userRole->id]);

        $user2 = User::factory()->create();
        $user2->roles()->sync([$this->userRole->id]);

        $invitationOfUser2 = Invitation::create([
            'user_id' => $user2->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-user-2',
            'title' => 'Judul User 2',
            'groom_name' => 'Pria 2',
            'bride_name' => 'Wanita 2',
            'venue' => 'Gedung 2',
            'address' => 'Alamat 2',
            'status' => 'draft',
        ]);

        $payload = [
            'theme_id' => $this->theme->id,
            'title' => 'Ubah Judul User 2',
            'slug' => 'ubah-slug-user-2',
            'groom_name' => 'Pria Ubah',
            'bride_name' => 'Wanita Ubah',
            'venue' => 'Gedung Ubah',
            'address' => 'Alamat Ubah',
        ];

        $response = $this->actingAs($user1)->put(route('admin.invitations.update', $invitationOfUser2->id), $payload);
        $response->assertStatus(403);
    }

    /**
     * Uji toggle status publish & disable undangan milik sendiri.
     */
    public function test_user_can_toggle_publish_and_disable_own_invitation(): void
    {
        $user = User::factory()->create();
        $user->roles()->sync([$this->userRole->id]);

        $invitation = Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-budi',
            'title' => 'Pernikahan Budi',
            'groom_name' => 'Budi',
            'bride_name' => 'Ani',
            'venue' => 'Rumah',
            'address' => 'Kampung Halaman',
            'status' => 'draft',
        ]);

        // 1. Publish (draft -> published)
        $response = $this->actingAs($user)->put(route('admin.invitations.toggle-status', $invitation->id));
        $response->assertRedirect(route('admin.invitations.index'));
        $response->assertSessionHas('success', 'Undangan berhasil diterbitkan (aktif selama 30 hari).');

        $invitation->refresh();
        $this->assertEquals('published', $invitation->status);
        $this->assertNotNull($invitation->expired_at);
        $this->assertTrue($invitation->expired_at->isAfter(now()));

        // 2. Disable (published -> draft)
        $response2 = $this->actingAs($user)->put(route('admin.invitations.toggle-status', $invitation->id));
        $response2->assertRedirect(route('admin.invitations.index'));
        $response2->assertSessionHas('success', 'Undangan berhasil dinonaktifkan (kembali ke draft).');

        $invitation->refresh();
        $this->assertEquals('draft', $invitation->status);
        $this->assertNull($invitation->expired_at);
    }

    /**
     * Uji user tidak dapat men-toggle status undangan milik orang lain.
     */
    public function test_user_cannot_toggle_others_invitation_status(): void
    {
        $user1 = User::factory()->create();
        $user1->roles()->sync([$this->userRole->id]);

        $user2 = User::factory()->create();
        $user2->roles()->sync([$this->userRole->id]);

        $invitationOfUser2 = Invitation::create([
            'user_id' => $user2->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-user-2',
            'title' => 'Judul User 2',
            'groom_name' => 'Pria 2',
            'bride_name' => 'Wanita 2',
            'venue' => 'Gedung 2',
            'address' => 'Alamat 2',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($user1)->put(route('admin.invitations.toggle-status', $invitationOfUser2->id));
        $response->assertStatus(403);
    }

    /**
     * Uji user dapat menghapus undangan miliknya sendiri.
     */
    public function test_user_can_delete_own_invitation(): void
    {
        $user = User::factory()->create();
        $user->roles()->sync([$this->userRole->id]);

        $invitation = Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-budi',
            'title' => 'Pernikahan Budi',
            'groom_name' => 'Budi',
            'bride_name' => 'Ani',
            'venue' => 'Rumah',
            'address' => 'Kampung Halaman',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($user)->delete(route('admin.invitations.destroy', $invitation->id));
        $response->assertRedirect(route('admin.invitations.index'));
        $response->assertSessionHas('success', 'Undangan berhasil dihapus.');

        $this->assertDatabaseMissing('invitations', [
            'id' => $invitation->id,
        ]);
    }

    /**
     * Uji user tidak dapat menghapus undangan milik orang lain.
     */
    public function test_user_cannot_delete_others_invitation(): void
    {
        $user1 = User::factory()->create();
        $user1->roles()->sync([$this->userRole->id]);

        $user2 = User::factory()->create();
        $user2->roles()->sync([$this->userRole->id]);

        $invitationOfUser2 = Invitation::create([
            'user_id' => $user2->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-user-2',
            'title' => 'Judul User 2',
            'groom_name' => 'Pria 2',
            'bride_name' => 'Wanita 2',
            'venue' => 'Gedung 2',
            'address' => 'Alamat 2',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($user1)->delete(route('admin.invitations.destroy', $invitationOfUser2->id));
        $response->assertStatus(403);
    }
}
