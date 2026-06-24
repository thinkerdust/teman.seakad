<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThemeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->seed(RolePermissionSeeder::class);

        // Use fake storage disk
        Storage::fake('public');
    }

    /**
     * Uji tamu tidak terdaftar dialihkan ke halaman login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.themes.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Uji pengguna tanpa izin 'theme.view' diblokir (403).
     */
    public function test_unauthorized_user_cannot_view_themes(): void
    {
        // Buat user tanpa role apa pun (permission: theme.view tidak ada)
        $user = User::factory()->create();

        // Create active subscription to bypass SubscriptionMiddleware
        \App\Models\Order::create([
            'order_number' => 'ORD-TEST-1',
            'customer_name' => $user->name,
            'phone' => '08123456789',
            'email' => $user->email,
            'price' => 100000,
            'quota' => 10,
            'status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('admin.themes.index'));
        $response->assertStatus(403);
    }

    /**
     * Uji pengguna dengan izin 'theme.view' dapat melihat antarmuka tema.
     */
    public function test_authorized_user_can_view_themes(): void
    {
        $user = User::factory()->create();
        $userRole = Role::where('name', 'User')->first(); // memiliki theme.view
        $user->roles()->sync([$userRole->id]);

        // Create active subscription to bypass SubscriptionMiddleware
        \App\Models\Order::create([
            'order_number' => 'ORD-TEST-2',
            'customer_name' => $user->name,
            'phone' => '08123456789',
            'email' => $user->email,
            'price' => 100000,
            'quota' => 10,
            'status' => 'active',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('admin.themes.index'));
        $response->assertStatus(200);
    }

    /**
     * Uji penambahan tema baru dengan unggahan thumbnail.
     */
    public function test_authorized_user_can_create_theme(): void
    {
        $user = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first(); // memiliki theme.create
        $user->roles()->sync([$adminRole->id]);

        $thumbnail = UploadedFile::fake()->image('floral-theme.jpg', 800, 600);

        $response = $this->actingAs($user)->post(route('admin.themes.store'), [
            'name' => 'Rustic Forest Test',
            'slug' => 'rustic-forest-test',
            'folder' => 'floral-elegant', // Menggunakan folder stub yang ada
            'thumbnail' => $thumbnail,
            'description' => 'Ini deskripsi tema rustic forest test.',
            'status' => 'active',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.themes.index'));

        // Pastikan record ada di DB
        $this->assertDatabaseHas('themes', [
            'slug' => 'rustic-forest-test',
            'folder' => 'floral-elegant',
            'status' => 'active',
        ]);

        // Ambil theme yang baru dibuat untuk cek path gambar
        $theme = Theme::where('slug', 'rustic-forest-test')->first();
        $this->assertNotNull($theme->thumbnail);

        // Ambil relative path dari string '/storage/themes/thumbnails/xxx.jpg'
        $relativePath = str_replace('/storage/', '', $theme->thumbnail);

        // Pastikan berkas thumbnail disimpan di storage
        Storage::disk('public')->assertExists($relativePath);
    }

    /**
     * Uji pembaruan data tema dan penggantian berkas thumbnail lama.
     */
    public function test_authorized_user_can_update_theme(): void
    {
        $user = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first(); // memiliki theme.update
        $user->roles()->sync([$adminRole->id]);

        // Buat tema awal dengan thumbnail lama
        $oldThumbnail = UploadedFile::fake()->image('old-thumb.jpg');
        $oldPath = $oldThumbnail->store('themes/thumbnails', 'public');

        $theme = Theme::create([
            'name' => 'Old Theme Name',
            'slug' => 'old-theme-name',
            'folder' => 'floral-elegant',
            'thumbnail' => '/storage/'.$oldPath,
            'status' => 'active',
        ]);

        Storage::disk('public')->assertExists($oldPath);

        // Upload thumbnail baru
        $newThumbnail = UploadedFile::fake()->image('new-thumb.jpg');

        $response = $this->actingAs($user)->put(route('admin.themes.update', $theme), [
            'name' => 'Updated Theme Name',
            'slug' => 'updated-theme-name',
            'folder' => 'luxury-gold',
            'thumbnail' => $newThumbnail,
            'status' => 'inactive',
        ]);

        $response->assertStatus(302);

        // Pastikan database diperbarui
        $theme->refresh();
        $this->assertEquals('updated-theme-name', $theme->slug);
        $this->assertEquals('luxury-gold', $theme->folder);
        $this->assertEquals('inactive', $theme->status);

        // Pastikan berkas lama sudah dihapus
        Storage::disk('public')->assertMissing($oldPath);

        // Pastikan berkas baru ada
        $newRelativePath = str_replace('/storage/', '', $theme->thumbnail);
        Storage::disk('public')->assertExists($newRelativePath);
    }

    /**
     * Uji penghapusan tema beserta berkas fisiknya (hanya Superadmin).
     */
    public function test_superadmin_can_delete_theme_with_files(): void
    {
        $user = User::factory()->create();
        $superadminRole = Role::where('name', 'Superadmin')->first(); // memiliki theme.delete
        $user->roles()->sync([$superadminRole->id]);

        $thumbnail = UploadedFile::fake()->image('delete-me.jpg');
        $path = $thumbnail->store('themes/thumbnails', 'public');

        $theme = Theme::create([
            'name' => 'Theme To Delete',
            'slug' => 'theme-to-delete',
            'folder' => 'floral-elegant',
            'thumbnail' => '/storage/'.$path,
            'status' => 'active',
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($user)->delete(route('admin.themes.destroy', $theme));
        $response->assertStatus(302);

        // Pastikan terhapus di database
        $this->assertDatabaseMissing('themes', ['id' => $theme->id]);

        // Pastikan berkas fisik thumbnail juga terhapus
        Storage::disk('public')->assertMissing($path);
    }

    /**
     * Uji Admin biasa tidak boleh menghapus tema (403).
     */
    public function test_regular_admin_cannot_delete_theme(): void
    {
        $user = User::factory()->create();
        $adminRole = Role::where('name', 'Admin')->first(); // TIDAK memiliki theme.delete
        $user->roles()->sync([$adminRole->id]);

        $theme = Theme::create([
            'name' => 'Theme Safe',
            'slug' => 'theme-safe',
            'folder' => 'floral-elegant',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->delete(route('admin.themes.destroy', $theme));
        $response->assertStatus(403);

        // Pastikan data masih aman di database
        $this->assertDatabaseHas('themes', ['id' => $theme->id]);
    }
}
