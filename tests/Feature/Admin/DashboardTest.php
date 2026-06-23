<?php

namespace Tests\Feature\Admin;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles & permissions
        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * Uji tamu (guest) yang belum login dialihkan ke halaman login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Uji Superadmin dapat melihat dashboard beserta metrik global keseluruhan.
     */
    public function test_superadmin_can_view_global_statistics(): void
    {
        // 1. Buat data uji
        $superadmin = User::factory()->create();
        $superadminRole = Role::where('name', 'Superadmin')->first();
        $superadmin->roles()->sync([$superadminRole->id]);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $theme = Theme::create([
            'name' => 'Floral Test',
            'slug' => 'floral-test',
            'folder' => 'floral-test',
            'status' => 'active'
        ]);

        // Buat undangan untuk user1 & user2
        $invitation1 = Invitation::create([
            'user_id' => $user1->id,
            'theme_id' => $theme->id,
            'slug' => 'undangan-1',
            'title' => 'Undangan 1',
            'groom_name' => 'Groom 1',
            'bride_name' => 'Bride 1',
            'venue' => 'Venue 1',
            'address' => 'Address 1',
            'status' => 'published',
            'created_at' => now(),
        ]);

        $invitation2 = Invitation::create([
            'user_id' => $user2->id,
            'theme_id' => $theme->id,
            'slug' => 'undangan-2',
            'title' => 'Undangan 2',
            'groom_name' => 'Groom 2',
            'bride_name' => 'Bride 2',
            'venue' => 'Venue 2',
            'address' => 'Address 2',
            'status' => 'draft',
            'created_at' => now(),
        ]);

        // Buat tamu untuk undangan
        Guest::create([
            'invitation_id' => $invitation1->id,
            'name' => 'Tamu 1',
            'attendance' => 'hadir',
        ]);

        Guest::create([
            'invitation_id' => $invitation2->id,
            'name' => 'Tamu 2',
            'attendance' => 'tidak_hadir',
        ]);

        // 2. Akses dashboard sebagai Superadmin
        $response = $this->actingAs($superadmin)->get(route('admin.dashboard'));
        
        $response->assertStatus(200);
        $response->assertViewHas('stats', function ($stats) {
            // Superadmin melihat total seluruh user (Superadmin + user1 + user2 = 3)
            return $stats['total_users'] === 3 &&
                   $stats['total_invitations'] === 2 &&
                   $stats['total_themes'] === 1 &&
                   $stats['total_guests'] === 2;
        });

        // Pastikan widget "Total Pengguna" muncul di view
        $response->assertSee('Total Pengguna');
    }

    /**
     * Uji Regular User hanya melihat statistik miliknya sendiri.
     */
    public function test_regular_user_only_views_own_statistics(): void
    {
        // 1. Buat data uji
        $userRole = Role::where('name', 'User')->first();
        
        $user1 = User::factory()->create();
        $user1->roles()->sync([$userRole->id]);

        $user2 = User::factory()->create();
        $user2->roles()->sync([$userRole->id]);

        $theme = Theme::create([
            'name' => 'Floral Test',
            'slug' => 'floral-test',
            'folder' => 'floral-test',
            'status' => 'active'
        ]);

        // Undangan milik user1
        $invitation1 = Invitation::create([
            'user_id' => $user1->id,
            'theme_id' => $theme->id,
            'slug' => 'undangan-1',
            'title' => 'Undangan 1',
            'groom_name' => 'Groom 1',
            'bride_name' => 'Bride 1',
            'venue' => 'Venue 1',
            'address' => 'Address 1',
            'status' => 'published',
            'created_at' => now(),
        ]);

        // Undangan milik user2
        $invitation2 = Invitation::create([
            'user_id' => $user2->id,
            'theme_id' => $theme->id,
            'slug' => 'undangan-2',
            'title' => 'Undangan 2',
            'groom_name' => 'Groom 2',
            'bride_name' => 'Bride 2',
            'venue' => 'Venue 2',
            'address' => 'Address 2',
            'status' => 'draft',
            'created_at' => now(),
        ]);

        // Tamu untuk undangan user1
        Guest::create([
            'invitation_id' => $invitation1->id,
            'name' => 'Tamu User 1',
            'attendance' => 'hadir',
        ]);

        // Tamu untuk undangan user2
        Guest::create([
            'invitation_id' => $invitation2->id,
            'name' => 'Tamu User 2',
            'attendance' => 'hadir',
        ]);

        // 2. Akses sebagai user1
        $response = $this->actingAs($user1)->get(route('admin.dashboard'));
        
        $response->assertStatus(200);
        $response->assertViewHas('stats', function ($stats) {
            // User1 hanya melihat data miliknya sendiri (total_users disembunyikan/0, invitation = 1, guests = 1)
            return $stats['total_invitations'] === 1 &&
                   $stats['total_themes'] === 1 &&
                   $stats['total_guests'] === 1;
        });

        // Widget "Total Pengguna" disembunyikan untuk regular user
        $response->assertDontSee('Total Pengguna');
    }
}
