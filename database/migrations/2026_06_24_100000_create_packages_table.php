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
        // 1. Create packages table
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('invitation_quota');
            $table->integer('duration_days');
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });

        // 2. Seed default packages
        DB::table('packages')->insert([
            [
                'name' => 'Basic',
                'description' => 'Paket dasar untuk 1 undangan pernikahan digital.',
                'price' => 100000.00,
                'invitation_quota' => 1,
                'duration_days' => 30,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium',
                'description' => 'Paket premium untuk 5 undangan pernikahan digital selama 1 tahun.',
                'price' => 300000.00,
                'invitation_quota' => 5,
                'duration_days' => 365,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. Insert Permissions
        $permissions = [
            ['name' => 'Melihat Daftar Paket', 'key' => 'package.view'],
            ['name' => 'Membuat Paket Baru', 'key' => 'package.create'],
            ['name' => 'Mengubah Paket', 'key' => 'package.update'],
            ['name' => 'Menghapus Paket', 'key' => 'package.delete'],
        ];

        foreach ($permissions as $perm) {
            $permId = DB::table('permissions')->insertGetId([
                'name' => $perm['name'],
                'key' => $perm['key'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Map to Superadmin
            $superadmin = DB::table('roles')->where('name', 'Superadmin')->first();
            if ($superadmin) {
                DB::table('role_permissions')->insertOrIgnore([
                    'role_id' => $superadmin->id,
                    'permission_id' => $permId,
                ]);
            }

            // Map to Admin
            $admin = DB::table('roles')->where('name', 'Admin')->first();
            if ($admin) {
                DB::table('role_permissions')->insertOrIgnore([
                    'role_id' => $admin->id,
                    'permission_id' => $permId,
                ]);
            }
        }

        // 4. Add to navigation menus under "Menu Utama"
        $menuUtama = DB::table('menus')->where('title', 'Menu Utama')->first();
        if ($menuUtama) {
            // Shift existing menus under "Menu Utama" with order >= 3 down by 1
            DB::table('menus')
                ->where('parent_id', $menuUtama->id)
                ->where('order', '>=', 3)
                ->increment('order');

            // Insert Package Management at order 3
            DB::table('menus')->insert([
                'parent_id' => $menuUtama->id,
                'title' => 'Package Management',
                'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4" /></svg>',
                'route' => 'admin.packages.index',
                'permission' => 'package.view',
                'order' => 3,
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
        DB::table('menus')->where('route', 'admin.packages.index')->delete();

        // Restore order of remaining menus under Menu Utama
        $menuUtama = DB::table('menus')->where('title', 'Menu Utama')->first();
        if ($menuUtama) {
            DB::table('menus')
                ->where('parent_id', $menuUtama->id)
                ->where('order', '>', 3)
                ->decrement('order');
        }

        // 2. Delete permissions and their mapping
        $permIds = DB::table('permissions')
            ->whereIn('key', ['package.view', 'package.create', 'package.update', 'package.delete'])
            ->pluck('id');

        if ($permIds->isNotEmpty()) {
            DB::table('role_permissions')->whereIn('permission_id', $permIds)->delete();
            DB::table('permissions')->whereIn('id', $permIds)->delete();
        }

        // 3. Drop packages table
        Schema::dropIfExists('packages');
    }
};
