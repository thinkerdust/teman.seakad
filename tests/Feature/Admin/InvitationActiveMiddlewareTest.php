<?php

namespace Tests\Feature\Admin;

use App\Models\Invitation;
use App\Models\Order;
use App\Models\Package;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class InvitationActiveMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected Theme $theme;
    protected Role $userRole;
    protected Package $package;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->userRole = Role::where('name', 'User')->first();

        // Create active theme
        $this->theme = Theme::create([
            'name' => 'Rustic',
            'slug' => 'rustic',
            'folder' => 'rustic-forest',
            'status' => 'active',
        ]);

        // Create default package
        $this->package = Package::create([
            'id' => 1,
            'name' => 'Basic',
            'price' => 100000,
            'invitation_quota' => 1,
            'duration_days' => 30,
            'status' => 'active',
        ]);
    }

    /**
     * Helper to set up a user with an active subscription.
     */
    private function setupUserWithActiveSubscription(string $email = 'user@example.com'): User
    {
        Order::create([
            'customer_name' => 'Jane Client',
            'phone' => '08987654321',
            'email' => $email,
            'package_id' => 1,
            'quota' => 1,
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
     * Uji middleware mengizinkan akses ke undangan aktif & published.
     */
    public function test_middleware_allows_active_published_invitation(): void
    {
        $user = $this->setupUserWithActiveSubscription('client1@example.com');

        $invitation = Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-aktif',
            'title' => 'Jane Wedding Active',
            'groom_name' => 'Groom',
            'bride_name' => 'Bride',
            'venue' => 'Gedung A',
            'address' => 'Alamat A',
            'status' => 'published',
            'published_at' => Carbon::now()->toDateString(),
            'expired_at' => Carbon::today()->addDays(20)->toDateString(),
        ]);

        // Uji GET rute publik
        $response = $this->get(route('public.invitation', $invitation->slug));
        $response->assertStatus(200);

        // Uji POST RSVP
        $responseRsvp = $this->postJson(route('public.invitation.rsvp', $invitation->slug), [
            'name' => 'Guest A',
            'attendance' => 'hadir',
            'message' => 'Selamat!',
        ]);
        $responseRsvp->assertStatus(200);
        $responseRsvp->assertJsonPath('success', true);
    }

    /**
     * Uji middleware memblokir undangan draft dengan HTTP 403.
     */
    public function test_middleware_blocks_draft_invitation(): void
    {
        $user = $this->setupUserWithActiveSubscription('client2@example.com');

        $invitation = Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-draft',
            'title' => 'Jane Wedding Draft',
            'groom_name' => 'Groom',
            'bride_name' => 'Bride',
            'venue' => 'Gedung A',
            'address' => 'Alamat A',
            'status' => 'draft',
        ]);

        // Uji GET rute publik (HTML 403)
        $response = $this->get(route('public.invitation', $invitation->slug));
        $response->assertStatus(403);

        // Uji POST RSVP (JSON 403)
        $responseRsvp = $this->postJson(route('public.invitation.rsvp', $invitation->slug), [
            'name' => 'Guest A',
            'attendance' => 'hadir',
        ]);
        $responseRsvp->assertStatus(403);
        $responseRsvp->assertJsonPath('message', 'Undangan ini masih dalam status draft dan belum diterbitkan oleh pemilik.');
    }

    /**
     * Uji middleware memblokir undangan kedaluwarsa dengan HTTP 410.
     */
    public function test_middleware_blocks_expired_invitation(): void
    {
        $user = $this->setupUserWithActiveSubscription('client3@example.com');

        $invitation = Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-expired',
            'title' => 'Jane Wedding Expired',
            'groom_name' => 'Groom',
            'bride_name' => 'Bride',
            'venue' => 'Gedung A',
            'address' => 'Alamat A',
            'status' => 'published',
            'published_at' => Carbon::now()->subDays(10)->toDateString(),
            'expired_at' => Carbon::yesterday()->toDateString(),
        ]);

        // Uji GET rute publik (HTML 410)
        $response = $this->get(route('public.invitation', $invitation->slug));
        $response->assertStatus(410);
        $response->assertSee('Undangan sudah tidak tersedia');

        // Uji POST RSVP (JSON 410)
        $responseRsvp = $this->postJson(route('public.invitation.rsvp', $invitation->slug), [
            'name' => 'Guest A',
            'attendance' => 'hadir',
        ]);
        $responseRsvp->assertStatus(410);
        $responseRsvp->assertJsonPath('message', 'Undangan sudah tidak tersedia.');
    }

    /**
     * Uji middleware mengembalikan 404 jika undangan tidak ditemukan.
     */
    public function test_middleware_returns_404_if_invitation_not_found(): void
    {
        $response = $this->get(route('public.invitation', 'slug-tidak-ada'));
        $response->assertStatus(404);

        $responseRsvp = $this->postJson(route('public.invitation.rsvp', 'slug-tidak-ada'), [
            'name' => 'Guest A',
            'attendance' => 'hadir',
        ]);
        $responseRsvp->assertStatus(404);
        $responseRsvp->assertJsonPath('message', 'Undangan tidak ditemukan.');
    }
}
