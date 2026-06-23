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
        // 1. Seed Superadmin User
        $superadmin = User::updateOrCreate(
            ['email' => 'admin@teman-seakad.com'],
            [
                'name' => 'Superadmin Teman Seakad',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'status' => 'active',
            ]
        );

        $superadminRole = \App\Models\Role::where('name', 'Superadmin')->first();
        if ($superadminRole) {
            $superadmin->roles()->sync([$superadminRole->id]);
        }

        // 2. Seed Admin User
        $admin = User::updateOrCreate(
            ['email' => 'staff.admin@teman-seakad.com'],
            [
                'name' => 'Admin Teman Seakad',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'status' => 'active',
            ]
        );

        $adminRole = \App\Models\Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $admin->roles()->sync([$adminRole->id]);
        }

        // 3. Seed Regular User
        $user = User::updateOrCreate(
            ['email' => 'user@teman-seakad.com'],
            [
                'name' => 'User Teman Seakad',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'status' => 'active',
            ]
        );

        $userRole = \App\Models\Role::where('name', 'User')->first();
        if ($userRole) {
            $user->roles()->sync([$userRole->id]);
        }
    }
}
