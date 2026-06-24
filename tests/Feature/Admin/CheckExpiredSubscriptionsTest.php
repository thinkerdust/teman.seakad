<?php

namespace Tests\Feature\Admin;

use App\Models\Invitation;
use App\Models\Order;
use App\Models\Package;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use App\Models\UserSubscription;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CheckExpiredSubscriptionsTest extends TestCase
{
    use RefreshDatabase;

    protected Role $userRole;

    protected Package $package;

    protected Theme $theme;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->userRole = Role::where('name', 'User')->first();

        // Buat paket default
        $this->package = Package::create([
            'id' => 1,
            'name' => 'Basic Pack',
            'price' => 100000,
            'invitation_quota' => 5,
            'duration_days' => 30,
            'status' => 'active',
        ]);

        // Buat tema default
        $this->theme = Theme::create([
            'name' => 'Rustic Forest',
            'slug' => 'rustic-forest',
            'folder' => 'rustic',
            'status' => 'active',
        ]);
    }

    /**
     * Helper untuk membuat user dengan subscription.
     */
    private function createUserWithSubscription(string $email, string $startDate, string $endDate, string $status = 'active'): array
    {
        $user = User::factory()->create(['email' => $email]);
        $user->roles()->sync([$this->userRole->id]);

        $order = Order::create([
            'customer_name' => $user->name,
            'phone' => '08987654321',
            'email' => $user->email,
            'package_id' => $this->package->id,
            'quota' => 5,
            'price' => 100000,
            'status' => 'active',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $subscription = UserSubscription::where('order_id', $order->id)->first();
        if ($subscription && $status !== 'active') {
            $subscription->update(['status' => $status]);
        }

        return [$user, $subscription];
    }

    /**
     * Helper untuk membuat undangan untuk user.
     */
    private function createInvitationForUser(User $user, string $slug, string $status = 'published', ?string $expiredAt = null): Invitation
    {
        return Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => $slug,
            'title' => 'Wedding of '.$user->name,
            'groom_name' => 'Groom',
            'bride_name' => 'Bride',
            'venue' => 'Grand Hall',
            'address' => '123 Main Street',
            'status' => $status,
            'expired_at' => $expiredAt,
        ]);
    }

    /**
     * Uji command mengubah subscription yang kedaluwarsa menjadi expired dan menonaktifkan undangan terkait.
     */
    public function test_command_expires_past_subscriptions_and_their_invitations(): void
    {
        // 1. Subscription yang sudah melewati masa aktif (end_date kemarin)
        [$userExpired, $subExpired] = $this->createUserWithSubscription(
            'expired@example.com',
            Carbon::today()->subDays(31)->toDateString(),
            Carbon::yesterday()->toDateString()
        );

        $invitationExpiredUser = $this->createInvitationForUser($userExpired, 'undangan-expired-user', 'published', Carbon::yesterday()->toDateString());

        // 2. Subscription yang masih aktif (end_date besok)
        [$userActive, $subActive] = $this->createUserWithSubscription(
            'active@example.com',
            Carbon::today()->subDays(15)->toDateString(),
            Carbon::tomorrow()->toDateString()
        );

        $invitationActiveUser = $this->createInvitationForUser($userActive, 'undangan-active-user', 'published', Carbon::tomorrow()->toDateString());

        // Jalankan Artisan Command
        $this->artisan('subscriptions:check-expired')
            ->expectsOutputToContain('Berhasil memperbarui 1 subscription menjadi expired.')
            ->expectsOutputToContain('Berhasil memperbarui 1 invitation menjadi expired.')
            ->assertExitCode(0);

        // Assert Subscription yang expired berubah statusnya
        $subExpired->refresh();
        $this->assertEquals('expired', $subExpired->status);

        // Assert Undangan user yang expired berubah statusnya
        $invitationExpiredUser->refresh();
        $this->assertEquals('expired', $invitationExpiredUser->status);

        // Assert Subscription yang aktif tetap aktif
        $subActive->refresh();
        $this->assertEquals('active', $subActive->status);

        // Assert Undangan user yang aktif tetap published
        $invitationActiveUser->refresh();
        $this->assertEquals('published', $invitationActiveUser->status);
    }

    /**
     * Uji command mengabaikan subscription yang sudah ditandai expired atau cancelled.
     */
    public function test_command_ignores_already_expired_or_cancelled_subscriptions(): void
    {
        [$userAlreadyExpired, $subAlreadyExpired] = $this->createUserWithSubscription(
            'already-expired@example.com',
            Carbon::today()->subDays(31)->toDateString(),
            Carbon::yesterday()->toDateString(),
            'expired'
        );

        [$userCancelled, $subCancelled] = $this->createUserWithSubscription(
            'cancelled@example.com',
            Carbon::today()->subDays(31)->toDateString(),
            Carbon::yesterday()->toDateString(),
            'cancelled'
        );

        // Jalankan Artisan Command
        $this->artisan('subscriptions:check-expired')
            ->expectsOutputToContain('Berhasil memperbarui 0 subscription menjadi expired.')
            ->expectsOutputToContain('Berhasil memperbarui 0 invitation menjadi expired.')
            ->assertExitCode(0);
    }

    /**
     * Uji command meng-expire-kan undangan secara mandiri jika expired_at kurang dari hari ini,
     * bahkan jika subscription-nya masih berstatus aktif.
     */
    public function test_command_expires_invitations_with_past_expired_at_independently(): void
    {
        [$user, $sub] = $this->createUserWithSubscription(
            'user@example.com',
            Carbon::today()->subDays(10)->toDateString(),
            Carbon::tomorrow()->toDateString()
        );

        // Undangan yang expired_at nya di masa lalu (misal karena setting manual)
        $invitationExpired = $this->createInvitationForUser($user, 'undangan-expired-manual', 'published', Carbon::yesterday()->toDateString());

        // Undangan yang expired_at nya besok
        $invitationActive = $this->createInvitationForUser($user, 'undangan-active-manual', 'published', Carbon::tomorrow()->toDateString());

        // Jalankan Artisan Command
        $this->artisan('subscriptions:check-expired')
            ->expectsOutputToContain('Berhasil memperbarui 0 subscription menjadi expired.')
            ->expectsOutputToContain('Berhasil memperbarui 1 invitation menjadi expired.')
            ->assertExitCode(0);

        // Assert undangan dengan expired_at kemarin berubah menjadi expired
        $invitationExpired->refresh();
        $this->assertEquals('expired', $invitationExpired->status);

        // Assert undangan dengan expired_at besok tetap published
        $invitationActive->refresh();
        $this->assertEquals('published', $invitationActive->status);
    }
}
