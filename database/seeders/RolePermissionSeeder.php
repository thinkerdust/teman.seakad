<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Definisikan Permissions
        $permissions = [
            [
                'name' => 'Melihat Dashboard',
                'key' => 'dashboard.view',
            ],
            [
                'name' => 'Melihat Daftar User',
                'key' => 'user.view',
            ],
            [
                'name' => 'Membuat User Baru',
                'key' => 'user.create',
            ],
            [
                'name' => 'Mengubah Data User',
                'key' => 'user.update',
            ],
            [
                'name' => 'Menghapus User',
                'key' => 'user.delete',
            ],
            [
                'name' => 'Melihat Tema',
                'key' => 'theme.view',
            ],
            [
                'name' => 'Membuat Tema Baru',
                'key' => 'theme.create',
            ],
            [
                'name' => 'Mengubah Tema',
                'key' => 'theme.update',
            ],
            [
                'name' => 'Menghapus Tema',
                'key' => 'theme.delete',
            ],
            [
                'name' => 'Membuat Undangan',
                'key' => 'invitation.create',
            ],
            [
                'name' => 'Melihat Daftar Undangan',
                'key' => 'invitation.view',
            ],
            [
                'name' => 'Mengubah Undangan',
                'key' => 'invitation.update',
            ],
            [
                'name' => 'Menghapus Undangan',
                'key' => 'invitation.delete',
            ],
            [
                'name' => 'Melihat Daftar Menu',
                'key' => 'menu.view',
            ],
            [
                'name' => 'Membuat Menu Baru',
                'key' => 'menu.create',
            ],
            [
                'name' => 'Mengubah Data Menu',
                'key' => 'menu.update',
            ],
            [
                'name' => 'Menghapus Menu',
                'key' => 'menu.delete',
            ],
            [
                'name' => 'Melihat Musik',
                'key' => 'music.view',
            ],
            [
                'name' => 'Membuat Musik Baru',
                'key' => 'music.create',
            ],
            [
                'name' => 'Mengubah Musik',
                'key' => 'music.update',
            ],
            [
                'name' => 'Menghapus Musik',
                'key' => 'music.delete',
            ],
        ];

        $permissionModels = [];
        foreach ($permissions as $perm) {
            $permissionModels[$perm['key']] = Permission::updateOrCreate(
                ['key' => $perm['key']],
                ['name' => $perm['name']]
            );
        }

        // 2. Definisikan Roles
        $roles = [
            [
                'name' => 'Superadmin',
                'description' => 'Super Administrator dengan akses penuh ke semua fitur sistem.',
                'permissions' => array_keys($permissionModels), // Akses ke semua
            ],
            [
                'name' => 'Admin',
                'description' => 'Administrator dengan akses manajemen user dan tema.',
                'permissions' => [
                    'dashboard.view',
                    'user.view',
                    'user.create',
                    'user.update',
                    'theme.view',
                    'theme.create',
                    'theme.update',
                    'invitation.view',
                    'music.view',
                    'music.create',
                    'music.update',
                    'music.delete',
                ],
            ],
            [
                'name' => 'User',
                'description' => 'Pengguna biasa yang dapat membuat undangan.',
                'permissions' => [
                    'dashboard.view',
                    'theme.view',
                    'invitation.view',
                    'invitation.create',
                    'invitation.update',
                    'invitation.delete',
                    'music.view',
                ],
            ],
        ];

        foreach ($roles as $r) {
            $roleModel = Role::updateOrCreate(
                ['name' => $r['name']],
                ['description' => $r['description']]
            );

            // Petakan permissions ke role
            $permissionIds = [];
            foreach ($r['permissions'] as $permKey) {
                if (isset($permissionModels[$permKey])) {
                    $permissionIds[] = $permissionModels[$permKey]->id;
                }
            }
            $roleModel->permissions()->sync($permissionIds);
        }
    }
}
