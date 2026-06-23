<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Theme;
use App\Models\User;
use App\Models\Gallery;
use App\Models\Story;
use App\Models\Event;
use App\Models\Music;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationApiTest extends TestCase
{
    use RefreshDatabase;

    protected Theme $theme;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat tema aktif untuk pengujian
        $this->theme = Theme::create([
            'name' => 'Floral Elegant',
            'slug' => 'floral',
            'folder' => 'floral-elegant',
            'status' => 'active',
        ]);

        // Buat user pemilik undangan
        $this->user = User::factory()->create();
    }

    /**
     * Uji membuka API undangan yang tidak terdaftar (404).
     */
    public function test_api_non_existent_invitation_returns_404(): void
    {
        $response = $this->getJson('/api/invitation/slug-tidak-ada');
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Undangan tidak ditemukan.'
            ]);
    }

    /**
     * Uji membuka API undangan yang masih berupa draft (403).
     */
    public function test_api_draft_invitation_returns_403(): void
    {
        $invitation = Invitation::create([
            'user_id' => $this->user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-draft',
            'title' => 'Pernikahan Draft',
            'groom_name' => 'Groom Draft',
            'bride_name' => 'Bride Draft',
            'venue' => 'Gedung Draft',
            'address' => 'Alamat Draft',
            'status' => 'draft',
        ]);

        $response = $this->getJson('/api/invitation/' . $invitation->slug);
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Undangan ini masih dalam status draft.'
            ]);
    }

    /**
     * Uji membuka API undangan yang sudah kedaluwarsa (410).
     */
    public function test_api_expired_invitation_returns_410(): void
    {
        // Kasus 1: Status eksplisit 'expired'
        $invitation1 = Invitation::create([
            'user_id' => $this->user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-expired-status',
            'title' => 'Pernikahan Expired Status',
            'groom_name' => 'Groom 1',
            'bride_name' => 'Bride 1',
            'venue' => 'Gedung 1',
            'address' => 'Alamat 1',
            'status' => 'expired',
        ]);

        $response1 = $this->getJson('/api/invitation/' . $invitation1->slug);
        $response1->assertStatus(410)
            ->assertJson([
                'success' => false,
                'message' => 'Undangan ini sudah tidak aktif / melewati masa kedaluwarsa.'
            ]);

        // Kasus 2: Status 'published' namun expired_at terlampaui (di masa lalu)
        $invitation2 = Invitation::create([
            'user_id' => $this->user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'undangan-expired-time',
            'title' => 'Pernikahan Expired Time',
            'groom_name' => 'Groom 2',
            'bride_name' => 'Bride 2',
            'venue' => 'Gedung 2',
            'address' => 'Alamat 2',
            'status' => 'published',
            'expired_at' => Carbon::now()->subDays(1),
        ]);

        $response2 = $this->getJson('/api/invitation/' . $invitation2->slug);
        $response2->assertStatus(410)
            ->assertJson([
                'success' => false,
                'message' => 'Undangan ini sudah tidak aktif / melewati masa kedaluwarsa.'
            ]);
    }

    /**
     * Uji sukses membuka API undangan published beserta relasinya.
     */
    public function test_api_published_invitation_loads_successfully(): void
    {
        $invitation = Invitation::create([
            'user_id' => $this->user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'ayu-raka',
            'title' => 'Pernikahan Ayu & Raka',
            'groom_name' => 'Raka Pratama',
            'bride_name' => 'Ayu Lestari',
            'venue' => 'Hotel Mulia',
            'address' => 'Jakarta Pusat',
            'status' => 'published',
            'expired_at' => Carbon::now()->addDays(30),
        ]);

        // Tambah galeri
        Gallery::create([
            'invitation_id' => $invitation->id,
            'image' => '/storage/invitations/gallery/photo1.jpg',
            'sort' => 0,
        ]);

        // Tambah cerita
        Story::create([
            'invitation_id' => $invitation->id,
            'title' => 'Pertemuan',
            'description' => 'Bertemu di kampus',
            'date' => '2020',
            'sort' => 0,
        ]);

        // Tambah event
        Event::create([
            'invitation_id' => $invitation->id,
            'name' => 'Akad Nikah',
            'date' => '2026-08-23',
            'time' => '09:00',
            'location' => 'Hotel Mulia',
        ]);

        // Tambah musik
        $music = Music::create([
            'title' => 'Background Music',
            'artist' => 'Artist Name',
            'genre' => 'Wedding',
            'mood' => 'Romantic',
            'file' => '/storage/invitations/music/love.mp3',
        ]);
        $invitation->music()->sync([$music->id]);

        $response = $this->getJson('/api/invitation/' . $invitation->slug);
        $response->assertStatus(200);

        // Uji struktur data JSON response
        $response->assertJsonStructure([
            'theme',
            'data' => [
                'title',
                'groom_name',
                'bride_name',
                'akad_date',
                'reception_date',
                'venue',
                'address',
                'maps_url',
                'description',
                'story' => [
                    '*' => ['id', 'title', 'date', 'description', 'sort']
                ]
            ],
            'gallery' => [
                [
                    'id', 'image', 'sort'
                ]
            ],
            'events' => [
                [
                    'id', 'name', 'date', 'time', 'location'
                ]
            ],
            'music' => [
                'title',
                'artist',
                'file',
            ]
        ]);

        // Uji isi nilai JSON response
        $response->assertJson([
            'theme' => 'floral-elegant',
            'data' => [
                'title' => 'Pernikahan Ayu & Raka',
                'groom_name' => 'Raka Pratama',
                'bride_name' => 'Ayu Lestari',
                'venue' => 'Hotel Mulia',
                'address' => 'Jakarta Pusat',
            ],
            'gallery' => [
                [
                    'image' => '/storage/invitations/gallery/photo1.jpg',
                    'sort' => 0,
                ]
            ],
            'events' => [
                [
                    'name' => 'Akad Nikah',
                    'date' => '2026-08-23',
                    'time' => '09:00',
                    'location' => 'Hotel Mulia',
                ]
            ],
            'music' => [
                'title' => 'Background Music',
                'artist' => 'Artist Name',
                'file' => '/storage/invitations/music/love.mp3'
            ]
        ]);
    }
}
