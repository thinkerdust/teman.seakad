<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\Package;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        // Seed default packages so foreign key package_id references in tests exist
        Package::create([
            'id' => 1,
            'name' => 'Basic',
            'price' => 100000,
            'invitation_quota' => 1,
            'duration_days' => 30,
            'status' => 'active',
        ]);
        Package::create([
            'id' => 2,
            'name' => 'Premium',
            'price' => 300000,
            'invitation_quota' => 5,
            'duration_days' => 365,
            'status' => 'active',
        ]);
    }

    /**
     * User tanpa hak akses order.view ditolak akses ke halaman order.
     */
    public function test_user_without_order_permission_cannot_access_orders_page(): void
    {
        $user = User::factory()->create();
        $userRole = Role::where('name', 'User')->first();
        $user->roles()->sync([$userRole->id]);

        $response = $this->actingAs($user)->get(route('admin.orders.index'));
        $response->assertStatus(403);
    }

    /**
     * User dengan hak akses order.view dapat mengakses halaman order.
     */
    public function test_user_with_order_view_permission_can_access_orders_page(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index'));
        $response->assertStatus(200);
    }

    /**
     * Admin dapat membuat order baru.
     */
    public function test_admin_can_create_order(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $orderData = [
            'customer_name' => 'John Doe',
            'phone' => '08123456789',
            'email' => 'johndoe@example.com',
            'package_id' => 1,
            'quota' => 5,
            'price' => 150000,
            'status' => 'pending',
            'notes' => 'Catatan pesanan baru',
        ];

        $response = $this->actingAs($admin)->post(route('admin.orders.store'), $orderData);

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'quota' => 5,
            'price' => 150000.00,
            'status' => 'pending',
        ]);

        $order = Order::first();
        $this->assertNotNull($order->order_number);
        $this->assertStringStartsWith('ORD-', $order->order_number);
    }

    /**
     * Admin dapat memperbarui data order.
     */
    public function test_admin_can_update_order(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $order = Order::create([
            'order_number' => 'ORD-12345',
            'customer_name' => 'John Doe',
            'phone' => '08123456789',
            'email' => 'johndoe@example.com',
            'quota' => 5,
            'price' => 150000,
            'status' => 'pending',
        ]);

        $updatedData = [
            'customer_name' => 'John Doe Updated',
            'phone' => '08123456789',
            'email' => 'johndoe.updated@example.com',
            'package_id' => 2,
            'quota' => 10,
            'price' => 300000,
            'status' => 'confirmed',
            'start_date' => '2026-06-01',
            'end_date' => '2026-07-01',
            'notes' => 'Catatan diperbarui',
        ];

        $response = $this->actingAs($admin)->put(route('admin.orders.update', $order), $updatedData);

        $response->assertRedirect(route('admin.orders.index'));

        $order->refresh();
        $this->assertEquals('John Doe Updated', $order->customer_name);
        $this->assertEquals('johndoe.updated@example.com', $order->email);
        $this->assertEquals(10, $order->quota);
        $this->assertEquals(300000.00, $order->price);
        $this->assertEquals('confirmed', $order->status);
        $this->assertEquals('2026-06-01', $order->start_date->format('Y-m-d'));
        $this->assertEquals('2026-07-01', $order->end_date->format('Y-m-d'));
    }

    /**
     * Admin dapat menghapus order.
     */
    public function test_admin_can_delete_order(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $order = Order::create([
            'customer_name' => 'John Doe',
            'phone' => '08123456789',
            'email' => 'johndoe@example.com',
            'quota' => 5,
            'price' => 150000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.orders.destroy', $order));

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    /**
     * Admin dapat mengubah status order secara langsung.
     */
    public function test_admin_can_update_order_status(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $order = Order::create([
            'customer_name' => 'John Doe',
            'phone' => '08123456789',
            'email' => 'johndoe@example.com',
            'quota' => 5,
            'price' => 150000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'confirmed',
        ]);
    }

    /**
     * Admin dapat mengaktifkan order (mengisi start_date & end_date).
     */
    public function test_admin_can_activate_order(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $order = Order::create([
            'customer_name' => 'John Doe',
            'phone' => '08123456789',
            'email' => 'johndoe@example.com',
            'quota' => 5,
            'price' => 150000,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.orders.activate', $order), [
            'start_date' => '2026-06-24',
            'end_date' => '2026-07-24',
        ]);

        $response->assertRedirect(route('admin.orders.index'));

        $order->refresh();
        $this->assertEquals('active', $order->status);
        $this->assertEquals('2026-06-24', $order->start_date->format('Y-m-d'));
        $this->assertEquals('2026-07-24', $order->end_date->format('Y-m-d'));
    }

    /**
     * Admin melakukan follow up order (status pending berubah ke follow_up, lalu redirect ke wa.me).
     */
    public function test_admin_can_follow_up_order(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $order = Order::create([
            'customer_name' => 'John Doe',
            'phone' => '08123456789',
            'email' => 'johndoe@example.com',
            'quota' => 5,
            'price' => 150000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.follow-up', $order));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'follow_up',
        ]);

        $response->assertStatus(302);
        $this->assertStringContainsString('api.whatsapp.com/send', $response->headers->get('Location'));
    }

    /**
     * Admin dapat membuat akun user baru otomatis dari data order.
     */
    public function test_admin_can_create_user_from_order(): void
    {
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first();
        $admin->roles()->sync([$adminRole->id]);

        $order = Order::create([
            'customer_name' => 'John Customer',
            'phone' => '08999999999',
            'email' => 'customer@example.com',
            'quota' => 5,
            'price' => 150000,
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.orders.create-user', $order));

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('user_credentials');

        // Cek database user terbuat
        $this->assertDatabaseHas('users', [
            'name' => 'John Customer',
            'email' => 'customer@example.com',
            'phone' => '08999999999',
        ]);

        $user = User::where('email', 'customer@example.com')->first();
        $this->assertTrue($user->hasRole('User'));

        // Cek link user_id di orders
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $user->id,
        ]);
    }
}
