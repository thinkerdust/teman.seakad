<?php

namespace Tests\Feature\Admin;

use App\Models\Invitation;
use App\Models\Order;
use App\Models\Package;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationActivePeriodTest extends TestCase
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
     * Helper to set up a user with an active order and subscription.
     */
    private function setupUserWithActiveSubscription(string $email = 'user@example.com', int $subDays = 30): array
    {
        $order = Order::create([
            'customer_name' => 'Jane Client',
            'phone' => '08987654321',
            'email' => $email,
            'package_id' => 1,
            'quota' => 1,
            'price' => 100000,
            'status' => 'active',
            'start_date' => Carbon::today()->toDateString(),
            'end_date' => Carbon::today()->addDays($subDays)->toDateString(),
        ]);

        $user = User::where('email', $email)->first();
        $user->roles()->sync([$this->userRole->id]);

        return [$user, $order];
    }

    /**
     * Uji published_at dan expired_at otomatis terisi saat publish invitation.
     */
    public function test_published_at_and_expired_at_are_populated_on_publish(): void
    {
        [$user, $order] = $this->setupUserWithActiveSubscription('client@example.com', 45);

        // Create draft invitation
        $invitation = Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'peta-pernikahan-jane',
            'title' => 'Jane Wedding',
            'groom_name' => 'Jane Groom',
            'bride_name' => 'Jane Bride',
            'venue' => 'Jane Venue',
            'address' => 'Jane Address',
            'status' => 'draft',
        ]);

        $this->assertNull($invitation->published_at);
        $this->assertNull($invitation->expired_at);

        // Publish invitation
        $response = $this->actingAs($user)->put(route('admin.invitations.toggle-status', $invitation));

        $response->assertRedirect(route('admin.invitations.index'));

        $invitation->refresh();
        $this->assertEquals('published', $invitation->status);
        $this->assertNotNull($invitation->published_at);

        // expired_at should match subscription end_date
        $subscription = UserSubscription::where('user_id', $user->id)->first();
        $this->assertNotNull($subscription);
        $this->assertEquals($subscription->end_date->toDateString(), $invitation->expired_at->toDateString());
    }

    /**
     * Uji tamu dapat mengakses undangan jika aktif dan published.
     */
    public function test_guest_can_access_published_and_active_invitation(): void
    {
        [$user, $order] = $this->setupUserWithActiveSubscription('client2@example.com', 30);

        // Create published active invitation
        $invitation = Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'peta-pernikahan-jane-2',
            'title' => 'Jane Wedding 2',
            'groom_name' => 'Jane Groom 2',
            'bride_name' => 'Jane Bride 2',
            'venue' => 'Jane Venue 2',
            'address' => 'Jane Address 2',
            'status' => 'published',
            'published_at' => Carbon::now()->toDateString(),
            'expired_at' => Carbon::today()->addDays(20)->toDateString(),
        ]);

        $response = $this->get(route('public.invitation', $invitation->slug));
        $response->assertStatus(200);
        $response->assertSee('Jane Wedding 2');
    }

    /**
     * Uji tamu melihat pesan expired jika undangan telah kedaluwarsa.
     */
    public function test_guest_sees_expired_message_when_invitation_is_expired(): void
    {
        [$user, $order] = $this->setupUserWithActiveSubscription('client3@example.com', 30);

        // Create published expired invitation (expired_at yesterday)
        $invitation = Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'peta-pernikahan-jane-3',
            'title' => 'Jane Wedding 3',
            'groom_name' => 'Jane Groom 3',
            'bride_name' => 'Jane Bride 3',
            'venue' => 'Jane Venue 3',
            'address' => 'Jane Address 3',
            'status' => 'published',
            'published_at' => Carbon::now()->subDays(10)->toDateString(),
            'expired_at' => Carbon::yesterday()->toDateString(),
        ]);

        $response = $this->get(route('public.invitation', $invitation->slug));
        $response->assertStatus(410);
        $response->assertSee('Undangan sudah tidak tersedia');
    }

    /**
     * Uji tamu dilarang mengakses undangan jika statusnya draft.
     */
    public function test_guest_cannot_access_draft_invitation(): void
    {
        [$user, $order] = $this->setupUserWithActiveSubscription('client4@example.com', 30);

        $invitation = Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'peta-pernikahan-jane-4',
            'title' => 'Jane Wedding 4',
            'groom_name' => 'Jane Groom 4',
            'bride_name' => 'Jane Bride 4',
            'venue' => 'Jane Venue 4',
            'address' => 'Jane Address 4',
            'status' => 'draft',
        ]);

        $response = $this->get(route('public.invitation', $invitation->slug));
        $response->assertStatus(403);
    }
}
