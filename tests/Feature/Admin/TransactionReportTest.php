<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\Package;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class TransactionReportTest extends TestCase
{
    use RefreshDatabase;

    protected Package $packageGold;
    protected Package $packageSilver;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->seed(RolePermissionSeeder::class);

        // Set up test packages
        $this->packageGold = Package::create([
            'name' => 'Gold Premium',
            'price' => 300000,
            'invitation_quota' => 20,
            'duration_days' => 365,
            'status' => 'active',
        ]);

        $this->packageSilver = Package::create([
            'name' => 'Silver Standard',
            'price' => 150000,
            'invitation_quota' => 10,
            'duration_days' => 180,
            'status' => 'active',
        ]);
    }

    /**
     * Guest redirected to login.
     */
    public function test_guest_cannot_access_transaction_report_page(): void
    {
        $response = $this->get(route('admin.reports.transactions'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Regular user without active subscription redirected to subscription expired page.
     */
    public function test_regular_user_without_subscription_cannot_access(): void
    {
        $user = User::factory()->create();
        $userRole = Role::where('name', 'User')->first();
        $user->roles()->sync([$userRole->id]);

        $response = $this->actingAs($user)->get(route('admin.reports.transactions'));
        $response->assertStatus(302);
        $response->assertRedirect(route('subscription.expired'));
    }

    /**
     * Regular user with active subscription but without order.view permission gets 403.
     */
    public function test_regular_user_with_subscription_but_without_permission_cannot_access(): void
    {
        $user = User::factory()->create();
        $userRole = Role::where('name', 'User')->first();
        $user->roles()->sync([$userRole->id]);

        // Create active subscription via order boot/event
        Order::create([
            'order_number' => 'ORD-USER-ACTIVE',
            'customer_name' => $user->name,
            'phone' => '08123456789',
            'email' => $user->email,
            'price' => 150000,
            'quota' => 10,
            'status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('admin.reports.transactions'));
        $response->assertStatus(403);
    }

    /**
     * Admin can access transaction report page.
     */
    public function test_admin_can_access_transaction_report_page(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $response = $this->actingAs($admin)->get(route('admin.reports.transactions'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.reports.transactions');
        $response->assertSee('Laporan Transaksi');
    }

    /**
     * Test filter by search.
     */
    public function test_transaction_report_filter_by_search(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        Order::create([
            'order_number' => 'ORD-999-SEARCH-ONE',
            'customer_name' => 'Arief Budiman',
            'phone' => '08123456789',
            'email' => 'arief@example.com',
            'price' => 150000,
            'quota' => 10,
            'status' => 'pending',
        ]);

        Order::create([
            'order_number' => 'ORD-999-SEARCH-TWO',
            'customer_name' => 'Budi Santoso',
            'phone' => '08123456780',
            'email' => 'budi@example.com',
            'price' => 150000,
            'quota' => 10,
            'status' => 'pending',
        ]);

        // Search for 'Arief'
        $response = $this->actingAs($admin)->get(route('admin.reports.transactions', ['search' => 'Arief']));
        $response->assertStatus(200);
        $response->assertSee('ORD-999-SEARCH-ONE');
        $response->assertDontSee('ORD-999-SEARCH-TWO');

        // Search for 'SEARCH-TWO'
        $response = $this->actingAs($admin)->get(route('admin.reports.transactions', ['search' => 'SEARCH-TWO']));
        $response->assertStatus(200);
        $response->assertSee('ORD-999-SEARCH-TWO');
        $response->assertDontSee('ORD-999-SEARCH-ONE');
    }

    /**
     * Test filter by package.
     */
    public function test_transaction_report_filter_by_package(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        Order::create([
            'order_number' => 'ORD-GOLD',
            'customer_name' => 'Arief',
            'phone' => '08123456789',
            'email' => 'arief@example.com',
            'price' => 300000,
            'quota' => 20,
            'status' => 'pending',
            'package_id' => $this->packageGold->id,
        ]);

        Order::create([
            'order_number' => 'ORD-SILVER',
            'customer_name' => 'Budi',
            'phone' => '08123456780',
            'email' => 'budi@example.com',
            'price' => 150000,
            'quota' => 10,
            'status' => 'pending',
            'package_id' => $this->packageSilver->id,
        ]);

        // Filter by Gold Package
        $response = $this->actingAs($admin)->get(route('admin.reports.transactions', ['package_id' => $this->packageGold->id]));
        $response->assertStatus(200);
        $response->assertSee('ORD-GOLD');
        $response->assertDontSee('ORD-SILVER');
    }

    /**
     * Test filter by status.
     */
    public function test_transaction_report_filter_by_status(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        Order::create([
            'order_number' => 'ORD-PENDING',
            'customer_name' => 'Arief',
            'phone' => '08123456789',
            'email' => 'arief@example.com',
            'price' => 150000,
            'quota' => 10,
            'status' => 'pending',
        ]);

        Order::create([
            'order_number' => 'ORD-ACTIVE',
            'customer_name' => 'Budi',
            'phone' => '08123456780',
            'email' => 'budi@example.com',
            'price' => 150000,
            'quota' => 10,
            'status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
        ]);

        // Filter by status 'active'
        $response = $this->actingAs($admin)->get(route('admin.reports.transactions', ['status' => 'active']));
        $response->assertStatus(200);
        $response->assertSee('ORD-ACTIVE');
        $response->assertDontSee('ORD-PENDING');
    }

    /**
     * Test filter by date range.
     */
    public function test_transaction_report_filter_by_date_range(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        Order::create([
            'order_number' => 'ORD-DATE-1',
            'customer_name' => 'Arief',
            'phone' => '08123456789',
            'email' => 'arief@example.com',
            'price' => 150000,
            'quota' => 10,
            'status' => 'active',
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-30',
        ]);

        Order::create([
            'order_number' => 'ORD-DATE-2',
            'customer_name' => 'Budi',
            'phone' => '08123456780',
            'email' => 'budi@example.com',
            'price' => 150000,
            'quota' => 10,
            'status' => 'active',
            'start_date' => '2026-06-15',
            'end_date' => '2026-07-15',
        ]);

        // Filter: date_from = 2026-06-10
        $response = $this->actingAs($admin)->get(route('admin.reports.transactions', [
            'date_from' => '2026-06-10',
        ]));
        $response->assertStatus(200);
        $response->assertSee('ORD-DATE-2');
        $response->assertDontSee('ORD-DATE-1');

        // Filter: date_to = 2026-06-10
        $response = $this->actingAs($admin)->get(route('admin.reports.transactions', [
            'date_to' => '2026-06-10',
        ]));
        $response->assertStatus(200);
        $response->assertSee('ORD-DATE-1');
        $response->assertDontSee('ORD-DATE-2');
    }

    /**
     * Test filter by active / expired period.
     */
    public function test_transaction_report_filter_by_active_expired_period(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $today = Carbon::parse('2026-06-24');
        Carbon::setTestNow($today);

        // Active period
        Order::create([
            'order_number' => 'ORD-PERIOD-ACTIVE',
            'customer_name' => 'Arief',
            'phone' => '08123456789',
            'email' => 'arief@example.com',
            'price' => 150000,
            'quota' => 10,
            'status' => 'active',
            'start_date' => '2026-06-20',
            'end_date' => '2026-07-20',
        ]);

        // Expired period (end_date in the past relative to 2026-06-24)
        Order::create([
            'order_number' => 'ORD-PERIOD-EXPIRED',
            'customer_name' => 'Budi',
            'phone' => '08123456780',
            'email' => 'budi@example.com',
            'price' => 150000,
            'quota' => 10,
            'status' => 'active',
            'start_date' => '2026-05-01',
            'end_date' => '2026-06-15',
        ]);

        // Filter period = active
        $response = $this->actingAs($admin)->get(route('admin.reports.transactions', ['period' => 'active']));
        $response->assertStatus(200);
        $response->assertSee('ORD-PERIOD-ACTIVE');
        $response->assertDontSee('ORD-PERIOD-EXPIRED');

        // Filter period = expired
        $response = $this->actingAs($admin)->get(route('admin.reports.transactions', ['period' => 'expired']));
        $response->assertStatus(200);
        $response->assertSee('ORD-PERIOD-EXPIRED');
        $response->assertDontSee('ORD-PERIOD-ACTIVE');

        Carbon::setTestNow(); // Clean up test time
    }
}
