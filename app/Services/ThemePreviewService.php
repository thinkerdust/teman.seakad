<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\Theme;
use Carbon\Carbon;

class ThemePreviewService
{
    /**
     * Generate complete mock data for theme preview.
     *
     * @param Theme $theme
     * @return array
     */
    public function getPreviewData(Theme $theme): array
    {
        $targetDate = Carbon::parse('2026-10-10 09:00:00');

        $invitationData = [
            'title' => 'Pernikahan Romantis ' . $theme->name,
            'groom_name' => 'Rakhmadani',
            'bride_name' => 'Ayu',
            'akad_date' => $targetDate->toIso8601String(),
            'reception_date' => $targetDate->copy()->addHours(2)->toIso8601String(),
            'venue' => 'Lembah Hijau Sentul',
            'address' => 'Jl. Pinus Emas No. 8, Babakan Madang, Bogor',
            'maps_url' => 'https://maps.google.com',
            'description' => 'Preview tema ' . $theme->name . ' dengan data dummy.',
            'recipient_name' => 'Tamu Undangan Terhormat',
            'theme' => [
                'name' => $theme->name,
                'slug' => $theme->slug,
                'folder' => $theme->folder,
            ],
            'gallery' => [
                ['id' => 1, 'image' => '/assets/demo/gallery/IMG_8305.jpg', 'sort' => 1],
                ['id' => 2, 'image' => '/assets/demo/gallery/IMG_8306.jpg', 'sort' => 2],
                ['id' => 3, 'image' => '/assets/demo/gallery/IMG_8309.jpg', 'sort' => 3],
                ['id' => 4, 'image' => '/assets/demo/gallery/IMG_8312.jpg', 'sort' => 4],
                ['id' => 5, 'image' => '/assets/demo/gallery/IMG_8313.jpg', 'sort' => 5],
                ['id' => 6, 'image' => '/assets/demo/gallery/IMG_8314.jpg', 'sort' => 6],
            ],
            'story' => [
                ['id' => 1, 'title' => 'Pertama Bertemu', 'date' => '2020', 'description' => 'Pertemuan pertama kami di kedai kopi hangat.', 'sort' => 1],
                ['id' => 2, 'title' => 'Menjalin Kasih', 'date' => '2022', 'description' => 'Mulai berkomitmen untuk saling menjaga.', 'sort' => 2],
                ['id' => 3, 'title' => 'Lamaran', 'date' => '2025', 'description' => 'Pertemuan keluarga untuk menyatukan langkah kami.', 'sort' => 3],
            ],
            'events' => [
                [
                    'id' => 1,
                    'name' => 'Akad Nikah',
                    'date' => $targetDate->format('Y-m-d'),
                    'time' => '08:00 - 10:00',
                    'location' => 'Masjid Al-Hikmah, Lembah Hijau Sentul',
                ],
                [
                    'id' => 2,
                    'name' => 'Resepsi Pernikahan',
                    'date' => $targetDate->format('Y-m-d'),
                    'time' => '11:00 - selesai',
                    'location' => 'Grand Ballroom, Lembah Hijau Sentul',
                ],
            ],
            'music' => [
                'title' => 'Lagu Pernikahan Demo',
                'artist' => 'Demo Artist',
                'file' => '/assets/demo/music/lagu-nikah.mp3',
            ],
        ];

        // Create a mock invitation instance
        $invitation = new Invitation;
        $invitation->slug = $theme->slug;
        $invitation->title = $invitationData['title'];
        $invitation->groom_name = $invitationData['groom_name'];
        $invitation->bride_name = $invitationData['bride_name'];
        $invitation->akad_date = $targetDate;
        $invitation->reception_date = $targetDate->copy()->addHours(2);
        $invitation->venue = $invitationData['venue'];
        $invitation->address = $invitationData['address'];
        $invitation->maps_url = $invitationData['maps_url'];
        $invitation->theme = $theme;

        // Custom relation mocking so Blade doesn't crash
        $invitation->setRelation('guests', collect());
        $invitation->setRelation('galleries', collect());
        $invitation->setRelation('stories', collect());
        $invitation->setRelation('events', collect());
        $invitation->setRelation('music', collect());

        return [
            'invitation' => $invitation,
            'invitationData' => $invitationData,
        ];
    }
}
