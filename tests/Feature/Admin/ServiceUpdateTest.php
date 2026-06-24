<?php

namespace Tests\Feature\Admin;

use App\Models\Invitation;
use App\Models\Order;
use App\Models\Package;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use App\Models\UserSubscription;
use App\Services\InvitationService;
use App\Services\QuotaService;
use App\Services\SubscriptionService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ServiceUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected Role $userRole;
    protected Role $adminRole;
    protected Package $package;
    protected Theme $theme;

    protected SubscriptionService $subscriptionService;
    protected QuotaService $quotaService;
    protected InvitationService $invitationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->userRole = Role::where('name', 'User')->first();
        $this->adminRole = Role::where('name', 'Admin')->first();

        $this->package = Package::create([
            'id' => 1,
            'name' => 'Premium Pack',
            'price' => 150000,
            'invitation_quota' => 2,
            'duration_days' => 30,
            'status' => 'active',
        ]);

        $this->theme = Theme::create([
            'name' => 'Modern Minimalist',
            'slug' => 'modern-minimalist',
            'folder' => 'modern',
            'status' => 'active',
        ]);

        $this->subscriptionService = app(SubscriptionService::class);
        $this->quotaService = app(QuotaService::class);
        $this->invitationService = app(InvitationService::class);
    }

    /**
     * Uji SubscriptionService.
     */
    public function test_subscription_service_methods(): void
    {
        $user = User::factory()->create();
        
        $order = Order::create([
            'customer_name' => $user->name,
            'phone' => '08123456789',
            'email' => $user->email,
            'package_id' => $this->package->id,
            'quota' => 2,
            'price' => 150000,
            'status' => 'pending',
            'start_date' => Carbon::today()->toDateString(),
            'end_date' => Carbon::today()->addDays(30)->toDateString(),
        ]);

        // 1. Uji createSubscription
        $subscription = $this->subscriptionService->createSubscription(
            $user,
            $order,
            $this->package,
            Carbon::today()->toDateString(),
            Carbon::today()->addDays(30)->toDateString()
        );

        $this->assertInstanceOf(UserSubscription::class, $subscription);
        $this->assertDatabaseHas('user_subscriptions', [
            'id' => $subscription->id,
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        // 2. Uji checkActive
        $this->assertTrue($this->subscriptionService->checkActive($user));

        // 3. Uji extendSubscription
        $extendedDate = Carbon::today()->addDays(60)->toDateString();
        $extendedSub = $this->subscriptionService->extendSubscription($subscription, $extendedDate);
        $this->assertEquals($extendedDate, $extendedSub->end_date->toDateString());

        // 4. Uji expireSubscription
        $expiredSub = $this->subscriptionService->expireSubscription($subscription);
        $this->assertEquals('expired', $expiredSub->status);
        $this->assertFalse($this->subscriptionService->checkActive($user));
    }

    /**
     * Uji QuotaService.
     */
    public function test_quota_service_methods(): void
    {
        $user = User::factory()->create();
        $user->roles()->sync([$this->userRole->id]);

        $order = Order::create([
            'customer_name' => $user->name,
            'phone' => '08123456789',
            'email' => $user->email,
            'package_id' => $this->package->id,
            'quota' => 2, // Quota is 2
            'price' => 150000,
            'status' => 'active',
            'start_date' => Carbon::today()->toDateString(),
            'end_date' => Carbon::today()->addDays(30)->toDateString(),
        ]);

        // User memiliki subscription aktif
        $this->assertTrue($this->quotaService->checkQuota($user));

        // Buat 1 undangan (kuota terpakai = 1, tersisa = 1)
        Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-satu',
            'title' => 'Undangan Satu',
            'groom_name' => 'Groom',
            'bride_name' => 'Bride',
            'venue' => 'Venue A',
            'address' => 'Address A',
            'status' => 'draft',
        ]);

        $this->assertTrue($this->quotaService->checkQuota($user));

        // Buat undangan kedua (kuota terpakai = 2, tersisa = 0)
        Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-dua',
            'title' => 'Undangan Dua',
            'groom_name' => 'Groom 2',
            'bride_name' => 'Bride 2',
            'venue' => 'Venue B',
            'address' => 'Address B',
            'status' => 'draft',
        ]);

        // Kuota sekarang habis
        $this->assertFalse($this->quotaService->checkQuota($user));
        $this->assertFalse($this->quotaService->consumeQuota($user));

        // Pengecekan admin / superadmin membypass kuota
        $admin = User::factory()->create(['email' => 'admin@teman-seakad.com']);
        $admin->roles()->sync([$this->adminRole->id]);

        $this->assertTrue($this->quotaService->checkQuota($admin));
    }

    /**
     * Uji InvitationService.
     */
    public function test_invitation_service_methods(): void
    {
        $user = User::factory()->create();

        $invitation = Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-test',
            'title' => 'Undangan Test',
            'groom_name' => 'Groom',
            'bride_name' => 'Bride',
            'venue' => 'Venue',
            'address' => 'Address',
            'status' => 'draft',
        ]);

        $this->assertEquals('draft', $invitation->status);

        // 1. Uji publishInvitation
        $publishedInvitation = $this->invitationService->publishInvitation($invitation);
        $this->assertEquals('published', $publishedInvitation->status);

        // 2. Uji setExpiredDate
        $expiredDate = Carbon::today()->addDays(15)->toDateString();
        $updatedInvitation = $this->invitationService->setExpiredDate($publishedInvitation, $expiredDate);
        
        $this->assertEquals(Carbon::parse($expiredDate)->toDateString(), Carbon::parse($updatedInvitation->expired_at)->toDateString());
    }
}
