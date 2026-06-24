<?php

namespace Tests\Feature\Admin;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GuestTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Role $userRole;

    protected Theme $theme;

    protected Invitation $invitation;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->seed(RolePermissionSeeder::class);

        $this->userRole = Role::where('name', 'User')->first();

        // Create a user
        $this->user = User::factory()->create();
        $this->user->roles()->sync([$this->userRole->id]);

        // Create a theme
        $this->theme = Theme::create([
            'name' => 'Floral Elegant',
            'slug' => 'floral',
            'folder' => 'floral-elegant',
            'status' => 'active',
        ]);

        // Create an invitation
        $this->invitation = Invitation::create([
            'user_id' => $this->user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'ayu-raka',
            'title' => 'Pernikahan Ayu & Raka',
            'groom_name' => 'Raka Pratama',
            'bride_name' => 'Ayu Lestari',
            'venue' => 'Hotel Mulia',
            'address' => 'Jakarta Pusat',
            'status' => 'published',
        ]);
    }

    /**
     * Uji tamu tidak terdaftar dialihkan ke halaman login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.invitations.guests.index', $this->invitation->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Uji pemilik undangan dapat melihat daftar tamu.
     */
    public function test_owner_can_view_guests_list(): void
    {
        $guest = Guest::create([
            'invitation_id' => $this->invitation->id,
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'attendance' => 'hadir',
            'message' => 'Selamat ya!',
        ]);

        $response = $this->actingAs($this->user)->get(route('admin.invitations.guests.index', $this->invitation->id));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
    }

    /**
     * Uji penambahan tamu baru oleh pemilik undangan.
     */
    public function test_owner_can_create_guest(): void
    {
        $payload = [
            'name' => 'Joni Wijaya',
            'phone' => '08987654321',
            'attendance' => 'belum_pasti',
            'message' => 'Semoga lancar!',
        ];

        $response = $this->actingAs($this->user)->post(route('admin.invitations.guests.store', $this->invitation->id), $payload);
        $response->assertRedirect(route('admin.invitations.guests.index', $this->invitation->id));
        $response->assertSessionHas('success', 'Tamu baru berhasil ditambahkan.');

        $this->assertDatabaseHas('guests', [
            'invitation_id' => $this->invitation->id,
            'name' => 'Joni Wijaya',
            'attendance' => 'belum_pasti',
        ]);
    }

    /**
     * Uji pembaruan data tamu oleh pemilik undangan.
     */
    public function test_owner_can_update_guest(): void
    {
        $guest = Guest::create([
            'invitation_id' => $this->invitation->id,
            'name' => 'Rini Astuti',
            'phone' => '081211112222',
            'attendance' => 'belum_pasti',
        ]);

        $payload = [
            'name' => 'Rini Astuti Updated',
            'phone' => '081211112222',
            'attendance' => 'hadir',
            'message' => 'Sudah pasti hadir.',
        ];

        $response = $this->actingAs($this->user)->put(route('admin.invitations.guests.update', [
            'invitation' => $this->invitation->id,
            'guest' => $guest->id,
        ]), $payload);

        $response->assertRedirect(route('admin.invitations.guests.index', $this->invitation->id));
        $response->assertSessionHas('success', 'Data tamu berhasil diperbarui.');

        $this->assertDatabaseHas('guests', [
            'id' => $guest->id,
            'name' => 'Rini Astuti Updated',
            'attendance' => 'hadir',
        ]);
    }

    /**
     * Uji penghapusan tamu oleh pemilik undangan.
     */
    public function test_owner_can_delete_guest(): void
    {
        $guest = Guest::create([
            'invitation_id' => $this->invitation->id,
            'name' => 'Hapus Saya',
            'phone' => '081200000000',
            'attendance' => 'tidak_hadir',
        ]);

        $response = $this->actingAs($this->user)->delete(route('admin.invitations.guests.destroy', [
            'invitation' => $this->invitation->id,
            'guest' => $guest->id,
        ]));

        $response->assertRedirect(route('admin.invitations.guests.index', $this->invitation->id));
        $response->assertSessionHas('success', 'Tamu berhasil dihapus dari daftar.');

        $this->assertDatabaseMissing('guests', [
            'id' => $guest->id,
        ]);
    }

    /**
     * Uji ekspor tamu ke berkas CSV.
     */
    public function test_owner_can_export_guests_to_csv(): void
    {
        Guest::create([
            'invitation_id' => $this->invitation->id,
            'name' => 'Export Guest 1',
            'phone' => '08111',
            'attendance' => 'hadir',
        ]);

        Guest::create([
            'invitation_id' => $this->invitation->id,
            'name' => 'Export Guest 2',
            'phone' => '08222',
            'attendance' => 'tidak_hadir',
        ]);

        $response = $this->actingAs($this->user)->get(route('admin.invitations.guests.export', $this->invitation->id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();
        $this->assertStringContainsString('Export Guest 1', $content);
        $this->assertStringContainsString('Export Guest 2', $content);
    }

    /**
     * Uji impor tamu dari berkas CSV.
     */
    public function test_owner_can_import_guests_from_csv(): void
    {
        // Siapkan file CSV palsu
        $csvContent = "Nama Tamu,Nomor Telepon,Kehadiran,Pesan / Ucapan\n";
        $csvContent .= "Tamu Impor 1,08555,hadir,Selamat Menikah!\n";
        $csvContent .= "Tamu Impor 2,08666,tidak hadir,Maaf tidak bisa datang.\n";

        $file = UploadedFile::fake()->createWithContent('guests.csv', $csvContent);

        $response = $this->actingAs($this->user)->post(route('admin.invitations.guests.import', $this->invitation->id), [
            'csv_file' => $file,
        ]);

        $response->assertRedirect(route('admin.invitations.guests.index', $this->invitation->id));
        $response->assertSessionHas('success');

        // Pastikan tamu masuk database
        $this->assertDatabaseHas('guests', [
            'invitation_id' => $this->invitation->id,
            'name' => 'Tamu Impor 1',
            'phone' => '08555',
            'attendance' => 'hadir',
            'message' => 'Selamat Menikah!',
        ]);

        $this->assertDatabaseHas('guests', [
            'invitation_id' => $this->invitation->id,
            'name' => 'Tamu Impor 2',
            'phone' => '08666',
            'attendance' => 'tidak_hadir',
            'message' => 'Maaf tidak bisa datang.',
        ]);
    }

    /**
     * Uji pengiriman konfirmasi kehadiran (RSVP) publik.
     */
    public function test_public_user_can_submit_rsvp(): void
    {
        $payload = [
            'name' => 'Tamu RSVP Publik',
            'phone' => '081299999999',
            'attendance' => 'hadir',
            'message' => 'Selamat atas pernikahannya Raka & Ayu!',
        ];

        $response = $this->post(route('public.invitation.rsvp', $this->invitation->slug), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Terima kasih, konfirmasi kehadiran Anda berhasil dikirim!',
        ]);

        $this->assertDatabaseHas('guests', [
            'invitation_id' => $this->invitation->id,
            'name' => 'Tamu RSVP Publik',
            'phone' => '081299999999',
            'attendance' => 'hadir',
            'message' => 'Selamat atas pernikahannya Raka & Ayu!',
        ]);
    }
}
