<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Theme;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicInvitationTest extends TestCase
{
    use RefreshDatabase;

    protected Theme $theme;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an active theme
        $this->theme = Theme::create([
            'name' => 'Floral Elegant',
            'slug' => 'floral',
            'folder' => 'floral-elegant',
            'status' => 'active',
        ]);

        // Create a user
        $this->user = User::factory()->create();
    }

    /**
     * Uji membuka undangan yang tidak terdaftar (404).
     */
    public function test_non_existent_invitation_returns_404(): void
    {
        $response = $this->get('/slug-tidak-ada');
        $response->assertStatus(404);
    }

    /**
     * Uji membuka undangan yang masih berupa draft (403).
     */
    public function test_draft_invitation_returns_403(): void
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

        $response = $this->get('/' . $invitation->slug);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('invitation_visits', [
            'invitation_id' => $invitation->id,
        ]);
    }

    /**
     * Uji membuka undangan yang sudah kedaluwarsa (410).
     */
    public function test_expired_invitation_returns_410(): void
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

        $response1 = $this->get('/' . $invitation1->slug);
        $response1->assertStatus(410);

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

        $response2 = $this->get('/' . $invitation2->slug);
        $response2->assertStatus(410);
    }

    /**
     * Uji sukses membuka undangan published, mencatat statistik kunjungan, dan memuat data di DOM.
     */
    public function test_published_invitation_loads_successfully_and_records_visit(): void
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

        $response = $this->get('/' . $invitation->slug);
        $response->assertStatus(200);

        // Pastikan view memuat script JSON data dan info pengantin
        $response->assertSee('window.invitationData =');
        $response->assertSee('Raka Pratama');
        $response->assertSee('Ayu Lestari');

        // Pastikan kunjungan berhasil dicatat ke DB
        $this->assertDatabaseHas('invitation_visits', [
            'invitation_id' => $invitation->id,
        ]);
    }
}
