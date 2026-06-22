<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@teman-seakad.com'],
            [
                'name' => 'Admin Teman Seakad',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'status' => 'active',
            ]
        );
    }
}
