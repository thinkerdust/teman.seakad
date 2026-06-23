<?php

namespace Tests\Feature\Admin;

use App\Models\Music;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MusicManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Role $userRole;
    protected Role $adminRole;
    protected Role $superadminRole;
    protected User $adminUser;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->seed(RolePermissionSeeder::class);

        // Fetch seeded roles
        $this->userRole = Role::where('name', 'User')->first();
        $this->adminRole = Role::where('name', 'Admin')->first();
        $this->superadminRole = Role::where('name', 'Superadmin')->first();

        // Create administrative user
        $this->adminUser = User::factory()->create();
        $this->adminUser->roles()->sync([$this->adminRole->id]);

        // Create regular user
        $this->regularUser = User::factory()->create();
        $this->regularUser->roles()->sync([$this->userRole->id]);
    }

    /**
     * Guest cannot access music management.
     */
    public function test_guest_cannot_access_music_management(): void
    {
        $response = $this->get(route('admin.music.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Regular user cannot access music CRUD routes.
     */
    public function test_user_cannot_access_music_crud_forms(): void
    {
        // View listing is allowed (since regular user needs to browse songs in editor)
        $response = $this->actingAs($this->regularUser)->get(route('admin.music.index'));
        $response->assertStatus(200);

        // Create song form is restricted
        $response = $this->actingAs($this->regularUser)->get(route('admin.music.create'));
        $response->assertStatus(403);
    }

    /**
     * Admin can view music listing.
     */
    public function test_admin_can_view_music_listing(): void
    {
        Music::create([
            'title' => 'A Thousand Years',
            'artist' => 'Christina Perri',
            'genre' => 'Wedding',
            'mood' => 'Romantic',
            'file' => '/storage/music/test.mp3',
            'status' => 'active'
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.music.index'));
        $response->assertStatus(200);
        $response->assertSee('A Thousand Years');
        $response->assertSee('Christina Perri');
    }

    /**
     * Admin can upload new music track with cover and file.
     */
    public function test_admin_can_upload_music_track(): void
    {
        Storage::fake('public');

        $musicFile = UploadedFile::fake()->create('instrumental.mp3', 1024, 'audio/mpeg');
        $coverFile = UploadedFile::fake()->image('cover.jpg');

        $payload = [
            'title' => 'Perfect',
            'artist' => 'Ed Sheeran',
            'album' => 'Divide',
            'genre' => 'Wedding',
            'mood' => 'Romantic',
            'language' => 'Inggris',
            'duration' => '04:23',
            'music_file' => $musicFile,
            'cover' => $coverFile,
            'status' => 'active',
        ];

        $response = $this->actingAs($this->adminUser)->post(route('admin.music.store'), $payload);
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.music.index'));

        // Verify DB record
        $this->assertDatabaseHas('music', [
            'title' => 'Perfect',
            'artist' => 'Ed Sheeran',
            'mood' => 'Romantic',
            'status' => 'active',
        ]);

        $music = Music::where('title', 'Perfect')->first();
        
        // Verify files exist in storage
        $musicFilePath = str_replace('/storage/', '', $music->file);
        $coverFilePath = str_replace('/storage/', '', $music->cover);
        
        Storage::disk('public')->assertExists($musicFilePath);
        Storage::disk('public')->assertExists($coverFilePath);
    }

    /**
     * Admin can update music details and files.
     */
    public function test_admin_can_update_music(): void
    {
        Storage::fake('public');

        $music = Music::create([
            'title' => 'Perfect',
            'artist' => 'Ed Sheeran',
            'genre' => 'Wedding',
            'mood' => 'Romantic',
            'file' => '/storage/music/perfect.mp3',
            'status' => 'active',
        ]);

        $newMusicFile = UploadedFile::fake()->create('perfect_updated.mp3', 500, 'audio/mpeg');

        $payload = [
            'title' => 'Perfect (Acoustic)',
            'artist' => 'Ed Sheeran',
            'genre' => 'Wedding',
            'mood' => 'Acoustic',
            'music_file' => $newMusicFile,
            'status' => 'active',
        ];

        $response = $this->actingAs($this->adminUser)->put(route('admin.music.update', $music->id), $payload);
        $response->assertStatus(302);
        
        $this->assertDatabaseHas('music', [
            'id' => $music->id,
            'title' => 'Perfect (Acoustic)',
            'mood' => 'Acoustic',
        ]);

        $music->refresh();
        $newPath = str_replace('/storage/', '', $music->file);
        Storage::disk('public')->assertExists($newPath);
    }

    /**
     * Admin can delete music track.
     */
    public function test_admin_can_delete_music(): void
    {
        Storage::fake('public');

        $musicFile = UploadedFile::fake()->create('song.mp3', 500, 'audio/mpeg');
        $storedPath = Storage::disk('public')->putFile('music', $musicFile);

        $music = Music::create([
            'title' => 'Delete Me',
            'artist' => 'Some Artist',
            'genre' => 'Wedding',
            'mood' => 'Romantic',
            'file' => '/storage/' . $storedPath,
            'status' => 'active',
        ]);

        Storage::disk('public')->assertExists($storedPath);

        $response = $this->actingAs($this->adminUser)->delete(route('admin.music.destroy', $music->id));
        $response->assertStatus(302);

        $this->assertDatabaseMissing('music', [
            'id' => $music->id,
        ]);

        Storage::disk('public')->assertMissing($storedPath);
    }
}
