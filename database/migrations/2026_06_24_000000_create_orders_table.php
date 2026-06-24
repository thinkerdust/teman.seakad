<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('phone');
            $table->string('email');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->integer('quota');
            $table->decimal('price', 12, 2);
            $table->string('status')->default('pending'); // pending, follow_up, confirmed, active, expired, cancelled
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        // 2. Insert Permissions
        $permissions = [
            ['name' => 'Melihat Daftar Order', 'key' => 'order.view'],
            ['name' => 'Membuat Order Baru', 'key' => 'order.create'],
            ['name' => 'Mengubah Order', 'key' => 'order.update'],
            ['name' => 'Menghapus Order', 'key' => 'order.delete'],
        ];

        foreach ($permissions as $perm) {
            $permId = DB::table('permissions')->insertGetId([
                'name' => $perm['name'],
                'key' => $perm['key'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Map to Superadmin (query by name)
            $superadmin = DB::table('roles')->where('name', 'Superadmin')->first();
            if ($superadmin) {
                DB::table('role_permissions')->insertOrIgnore([
                    'role_id' => $superadmin->id,
                    'permission_id' => $permId,
                ]);
            }

            // Map to Admin (query by name)
            $admin = DB::table('roles')->where('name', 'Admin')->first();
            if ($admin) {
                DB::table('role_permissions')->insertOrIgnore([
                    'role_id' => $admin->id,
                    'permission_id' => $permId,
                ]);
            }
        }

        // 3. Add to navigation menus under "Menu Utama"
        $menuUtama = DB::table('menus')->where('title', 'Menu Utama')->first();
        if ($menuUtama) {
            // Shift existing menus under "Menu Utama" with order >= 2 down by 1
            DB::table('menus')
                ->where('parent_id', $menuUtama->id)
                ->where('order', '>=', 2)
                ->increment('order');

            // Insert Order Management at order 2
            DB::table('menus')->insert([
                'parent_id' => $menuUtama->id,
                'title' => 'Order Management',
                'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>',
                'route' => 'admin.orders.index',
                'permission' => 'order.view',
                'order' => 2,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Delete menu item
        DB::table('menus')->where('route', 'admin.orders.index')->delete();

        // Restore order of remaining menus under Menu Utama
        $menuUtama = DB::table('menus')->where('title', 'Menu Utama')->first();
        if ($menuUtama) {
            DB::table('menus')
                ->where('parent_id', $menuUtama->id)
                ->where('order', '>', 2)
                ->decrement('order');
        }

        // 2. Delete permissions and their mapping
        $permIds = DB::table('permissions')
            ->whereIn('key', ['order.view', 'order.create', 'order.update', 'order.delete'])
            ->pluck('id');

        if ($permIds->isNotEmpty()) {
            DB::table('role_permissions')->whereIn('permission_id', $permIds)->delete();
            DB::table('permissions')->whereIn('id', $permIds)->delete();
        }

        // 3. Drop orders table
        Schema::dropIfExists('orders');
    }
};
