<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\Package;
use App\Models\Role;
use App\Models\User;
use App\Models\UserSubscription;
use App\Notifications\SubscriptionExpiringNotification;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class NotificationSystemTest extends TestCase
{
    use RefreshDatabase;

    protected Role $userRole;

    protected Package $package;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->userRole = Role::where('name', 'User')->first();

        // Buat paket default
        $this->package = Package::create([
            'id' => 1,
            'name' => 'Basic',
            'price' => 100000,
            'invitation_quota' => 5,
            'duration_days' => 30,
            'status' => 'active',
        ]);
    }

    /**
     * Helper untuk membuat user dengan subscription.
     */
    private function createUserWithSubscription(string $email, int $daysFromNow, string $status = 'active'): User
    {
        $user = User::factory()->create(['email' => $email]);
        $user->roles()->sync([$this->userRole->id]);

        $startDate = Carbon::today()->subDays(5)->toDateString();
        $endDate = Carbon::today()->addDays($daysFromNow)->toDateString();

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

        return $user;
    }

    /**
     * Uji Artisan command mengirim notifikasi pengingat untuk H-30, H-7, dan H-1.
     */
    public function test_command_sends_notifications_for_h30_h7_h1(): void
    {
        $user30 = $this->createUserWithSubscription('user30@example.com', 30);
        $user7 = $this->createUserWithSubscription('user7@example.com', 7);
        $user1 = $this->createUserWithSubscription('user1@example.com', 1);

        // Subscriptions yang diabaikan (H-15)
        $user15 = $this->createUserWithSubscription('user15@example.com', 15);

        // Jalankan artisan command
        $this->artisan('subscriptions:send-reminders')
            ->expectsOutputToContain('Berhasil mengirim 3 notifikasi pengingat langganan.')
            ->assertExitCode(0);

        // Pastikan tabel notifications berisi 3 notifikasi
        $this->assertDatabaseCount('notifications', 3);

        // Verifikasi isi notifikasi untuk masing-masing user
        $this->assertEquals(1, $user30->unreadNotifications()->count());
        $this->assertEquals(1, $user7->unreadNotifications()->count());
        $this->assertEquals(1, $user1->unreadNotifications()->count());
        $this->assertEquals(0, $user15->unreadNotifications()->count());

        $notification30 = $user30->unreadNotifications()->first();
        $this->assertEquals('Reminder: Masa Aktif Langganan', $notification30->data['title']);
        $this->assertEquals(30, $notification30->data['days_remaining']);
    }

    /**
     * Uji command tidak mengirimkan notifikasi duplikat.
     */
    public function test_command_does_not_send_duplicate_notifications(): void
    {
        $user30 = $this->createUserWithSubscription('user30@example.com', 30);

        // Jalankan command pertama kali
        $this->artisan('subscriptions:send-reminders')
            ->expectsOutputToContain('Berhasil mengirim 1 notifikasi pengingat langganan.')
            ->assertExitCode(0);

        $this->assertDatabaseCount('notifications', 1);

        // Jalankan command kedua kali, harusnya 0 yang dikirim karena sudah ada di database
        $this->artisan('subscriptions:send-reminders')
            ->expectsOutputToContain('Berhasil mengirim 0 notifikasi pengingat langganan.')
            ->assertExitCode(0);

        $this->assertDatabaseCount('notifications', 1);
    }

    /**
     * Uji command mengabaikan subscription yang sudah expired atau cancelled.
     */
    public function test_command_ignores_expired_and_cancelled_subscriptions(): void
    {
        $userExpired = $this->createUserWithSubscription('expired@example.com', 7, 'expired');
        $userCancelled = $this->createUserWithSubscription('cancelled@example.com', 7, 'cancelled');

        // Jalankan command
        $this->artisan('subscriptions:send-reminders')
            ->expectsOutputToContain('Berhasil mengirim 0 notifikasi pengingat langganan.')
            ->assertExitCode(0);

        $this->assertDatabaseCount('notifications', 0);
    }

    /**
     * Uji user dapat mengakses halaman daftar notifikasi.
     */
    public function test_user_can_access_notifications_page(): void
    {
        $user = $this->createUserWithSubscription('client@example.com', 20);

        $response = $this->actingAs($user)->get(route('admin.notifications.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.notifications.index');
        $response->assertViewHas('notifications');
    }

    /**
     * Uji user dapat menandai notifikasi sebagai dibaca.
     */
    public function test_user_can_mark_notification_as_read(): void
    {
        $user = $this->createUserWithSubscription('client@example.com', 20);
        $subscription = $user->subscriptions()->first();

        // Kirim notifikasi manual
        $user->notify(new SubscriptionExpiringNotification($subscription, 30));

        $notification = $user->unreadNotifications()->first();
        $this->assertNotNull($notification);

        // Tandai sudah dibaca via PATCH route
        $response = $this->actingAs($user)->patch(route('admin.notifications.read', $notification->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Notifikasi berhasil ditandai sebagai dibaca.');

        // Refresh model & assert read
        $this->assertEquals(0, $user->unreadNotifications()->count());
        $this->assertNotNull($notification->fresh()->read_at);
    }

    /**
     * Uji user dapat menandai semua notifikasi sebagai dibaca.
     */
    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = $this->createUserWithSubscription('client@example.com', 20);
        $subscription = $user->subscriptions()->first();

        // Kirim 2 notifikasi manual
        $user->notify(new SubscriptionExpiringNotification($subscription, 30));
        $user->notify(new SubscriptionExpiringNotification($subscription, 7));

        $this->assertEquals(2, $user->unreadNotifications()->count());

        // Tandai semua dibaca via POST route
        $response = $this->actingAs($user)->post(route('admin.notifications.mark-all-read'));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Semua notifikasi berhasil ditandai sebagai dibaca.');

        $this->assertEquals(0, $user->unreadNotifications()->count());
    }
}
