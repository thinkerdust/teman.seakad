<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\Gallery;
use App\Models\Invitation;
use App\Models\Music;
use App\Models\Role;
use App\Models\Story;
use App\Models\Theme;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InvitationContentTest extends TestCase
{
    use RefreshDatabase;

    protected Theme $theme;
    protected Role $userRole;
    protected Role $adminRole;
    protected Role $superadminRole;
    protected User $user;
    protected Invitation $invitation;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->seed(RolePermissionSeeder::class);

        // Fetch seeded roles
        $this->userRole = Role::where('name', 'User')->first();
        $this->adminRole = Role::where('name', 'Admin')->first();
        $this->superadminRole = Role::where('name', 'Superadmin')->first();

        // Create an active theme for testing
        $this->theme = Theme::create([
            'name' => 'Rustic',
            'slug' => 'rustic',
            'folder' => 'rustic-forest',
            'status' => 'active',
        ]);

        // Create user and user's invitation
        $this->user = User::factory()->create();
        $this->user->roles()->sync([$this->userRole->id]);

        $this->invitation = Invitation::create([
            'user_id' => $this->user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'budi-ani',
            'title' => 'Pernikahan Budi & Ani',
            'groom_name' => 'Budi',
            'bride_name' => 'Ani',
            'venue' => 'Rumah',
            'address' => 'Jl. Merdeka No. 1',
            'status' => 'draft',
        ]);
    }

    /**
     * Guest cannot access invitation content edit page.
     */
    public function test_guest_cannot_access_content_edit_page(): void
    {
        $response = $this->get(route('admin.invitations.content.edit', $this->invitation->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * User cannot access another user's invitation content edit page.
     */
    public function test_user_cannot_access_others_content_edit_page(): void
    {
        $otherUser = User::factory()->create();
        $otherUser->roles()->sync([$this->userRole->id]);

        $response = $this->actingAs($otherUser)->get(route('admin.invitations.content.edit', $this->invitation->id));
        $response->assertStatus(403);
    }

    /**
     * User can access own invitation content edit page.
     */
    public function test_user_can_access_own_content_edit_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.invitations.content.edit', $this->invitation->id));
        $response->assertStatus(200);
        $response->assertSee('Kelola Konten');
        $response->assertSee($this->invitation->title);
    }

    /**
     * User can upload photo to gallery.
     */
    public function test_user_can_upload_gallery_photo(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('myphoto.jpg');

        $response = $this->actingAs($this->user)
            ->post(route('admin.invitations.content.gallery', $this->invitation->id), [
                'action' => 'upload',
                'image' => $image,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Foto berhasil ditambahkan ke galeri.');

        // Verify database entry
        $this->assertDatabaseHas('galleries', [
            'invitation_id' => $this->invitation->id,
            'sort' => 0,
        ]);

        // Verify storage file exists
        $gallery = Gallery::where('invitation_id', $this->invitation->id)->first();
        $path = str_replace('/storage/', '', $gallery->image);
        Storage::disk('public')->assertExists($path);
    }

    /**
     * User can delete gallery photo.
     */
    public function test_user_can_delete_gallery_photo(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('myphoto.jpg');
        $storedPath = Storage::disk('public')->putFile('invitations/gallery', $image);
        
        $gallery = Gallery::create([
            'invitation_id' => $this->invitation->id,
            'image' => '/storage/' . $storedPath,
            'sort' => 0,
        ]);

        Storage::disk('public')->assertExists($storedPath);

        $response = $this->actingAs($this->user)
            ->post(route('admin.invitations.content.gallery', $this->invitation->id), [
                'action' => 'delete',
                'gallery_id' => $gallery->id,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Foto berhasil dihapus dari galeri.');

        $this->assertDatabaseMissing('galleries', [
            'id' => $gallery->id,
        ]);

        Storage::disk('public')->assertMissing($storedPath);
    }

    /**
     * User can add and update love stories.
     */
    public function test_user_can_add_update_delete_stories(): void
    {
        // 1. Add new stories
        $payload = [
            'stories' => [
                [
                    'title' => 'Pertemuan Pertama',
                    'date' => 'Desember 2020',
                    'description' => 'Kami pertama kali bertemu di kampus.',
                ],
                [
                    'title' => 'Tunangan',
                    'date' => 'Maret 2024',
                    'description' => 'Mengikat janji pertunangan resmi.',
                ]
            ]
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.invitations.content.story', $this->invitation->id), $payload);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Cerita cinta berhasil diperbarui.');

        $this->assertDatabaseHas('stories', [
            'invitation_id' => $this->invitation->id,
            'title' => 'Pertemuan Pertama',
            'sort' => 0,
        ]);
        $this->assertDatabaseHas('stories', [
            'invitation_id' => $this->invitation->id,
            'title' => 'Tunangan',
            'sort' => 1,
        ]);

        // 2. Update and delete stories
        $story1 = Story::where('title', 'Pertemuan Pertama')->first();
        $story2 = Story::where('title', 'Tunangan')->first();

        $updatePayload = [
            'stories' => [
                [
                    'id' => $story1->id,
                    'title' => 'Pertemuan Pertama (Updated)',
                    'date' => 'Desember 2020',
                    'description' => 'Kami pertama kali bertemu di kantin kampus.',
                ]
            ],
            'delete_story_ids' => (string) $story2->id,
        ];

        $response2 = $this->actingAs($this->user)
            ->post(route('admin.invitations.content.story', $this->invitation->id), $updatePayload);

        $response2->assertStatus(302);
        $response2->assertSessionHas('success', 'Cerita cinta berhasil diperbarui.');

        $this->assertDatabaseHas('stories', [
            'id' => $story1->id,
            'title' => 'Pertemuan Pertama (Updated)',
        ]);

        $this->assertDatabaseMissing('stories', [
            'id' => $story2->id,
        ]);
    }

    /**
     * User can add and update events.
     */
    public function test_user_can_add_update_delete_events(): void
    {
        // 1. Add new events
        $payload = [
            'events' => [
                [
                    'name' => 'Ngunduh Mantu',
                    'date' => '2026-07-15',
                    'time' => '10:00 - Selesai',
                    'location' => 'Gedung Serbaguna',
                ]
            ]
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.invitations.content.event', $this->invitation->id), $payload);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Susunan acara berhasil diperbarui.');

        $this->assertDatabaseHas('events', [
            'invitation_id' => $this->invitation->id,
            'name' => 'Ngunduh Mantu',
            'time' => '10:00 - Selesai',
        ]);

        // 2. Update and delete events
        $event = Event::where('name', 'Ngunduh Mantu')->first();

        $updatePayload = [
            'events' => [
                [
                    'id' => $event->id,
                    'name' => 'Ngunduh Mantu (Diubah)',
                    'date' => '2026-07-16',
                    'time' => '09:00 - 13:00',
                    'location' => 'Rumah Mempelai Pria',
                ]
            ],
            'delete_event_ids' => '',
        ];

        $response2 = $this->actingAs($this->user)
            ->post(route('admin.invitations.content.event', $this->invitation->id), $updatePayload);

        $response2->assertStatus(302);
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'name' => 'Ngunduh Mantu (Diubah)',
            'time' => '09:00 - 13:00',
        ]);

        // 3. Delete event
        $deletePayload = [
            'events' => [],
            'delete_event_ids' => (string) $event->id,
        ];

        $response3 = $this->actingAs($this->user)
            ->post(route('admin.invitations.content.event', $this->invitation->id), $deletePayload);

        $response3->assertStatus(302);
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
    }

    /**
     * User can upload background music (MP3).
     */
    public function test_user_can_upload_music(): void
    {
        Storage::fake('public');

        $musicFile = UploadedFile::fake()->create('instrumental.mp3', 1024, 'audio/mpeg');

        $response = $this->actingAs($this->user)
            ->post(route('admin.invitations.content.music', $this->invitation->id), [
                'action' => 'upload',
                'music_file' => $musicFile,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Musik latar berhasil diperbarui.');

        $this->assertDatabaseHas('music', [
            'invitation_id' => $this->invitation->id,
        ]);

        $music = Music::where('invitation_id', $this->invitation->id)->first();
        $path = str_replace('/storage/', '', $music->file);
        Storage::disk('public')->assertExists($path);
    }

    /**
     * User can delete background music.
     */
    public function test_user_can_delete_music(): void
    {
        Storage::fake('public');

        $musicFile = UploadedFile::fake()->create('instrumental.mp3', 500, 'audio/mpeg');
        $storedPath = Storage::disk('public')->putFile('invitations/music', $musicFile);

        $music = Music::create([
            'invitation_id' => $this->invitation->id,
            'file' => '/storage/' . $storedPath,
        ]);

        Storage::disk('public')->assertExists($storedPath);

        $response = $this->actingAs($this->user)
            ->post(route('admin.invitations.content.music', $this->invitation->id), [
                'action' => 'delete',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Musik latar berhasil dihapus.');

        $this->assertDatabaseMissing('music', [
            'id' => $music->id,
        ]);

        Storage::disk('public')->assertMissing($storedPath);
    }
}
