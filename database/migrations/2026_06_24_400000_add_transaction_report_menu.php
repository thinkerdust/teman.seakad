<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $menuUtama = DB::table('menus')->where('title', 'Menu Utama')->first();
        if ($menuUtama) {
            // Shift existing menus under "Menu Utama" with order >= 3 down by 1
            DB::table('menus')
                ->where('parent_id', $menuUtama->id)
                ->where('order', '>=', 3)
                ->increment('order');

            // Insert Laporan Transaksi at order 3
            DB::table('menus')->insert([
                'parent_id' => $menuUtama->id,
                'title' => 'Laporan Transaksi',
                'icon' => '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
                'route' => 'admin.reports.transactions',
                'permission' => 'order.view',
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
        DB::table('menus')->where('route', 'admin.reports.transactions')->delete();

        $menuUtama = DB::table('menus')->where('title', 'Menu Utama')->first();
        if ($menuUtama) {
            DB::table('menus')
                ->where('parent_id', $menuUtama->id)
                ->where('order', '>', 3)
                ->decrement('order');
        }
    }
};
