<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationVisit;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Themes
        $themes = [
            [
                'name' => 'Floral',
                'slug' => 'floral',
                'thumbnail' => '/assets/themes/floral-elegant.jpg',
                'description' => 'Tema dengan nuansa bunga berkelas, cocok untuk pernikahan konsep alam.',
                'folder' => 'floral-elegant',
                'status' => 'active',
            ],
            [
                'name' => 'Luxury',
                'slug' => 'luxury',
                'thumbnail' => '/assets/themes/luxury-gold.jpg',
                'description' => 'Nuansa emas mewah berpadu dengan warna hitam elegan untuk kesan premium.',
                'folder' => 'luxury-gold',
                'status' => 'active',
            ],
            [
                'name' => 'Islamic',
                'slug' => 'islamic',
                'thumbnail' => '/assets/themes/islamic-wedding.jpg',
                'description' => 'Tema minimalis dengan ornamen kaligrafi dan ornamen Islami modern.',
                'folder' => 'islamic-wedding',
                'status' => 'active',
            ],
            [
                'name' => 'Rustic',
                'slug' => 'rustic',
                'thumbnail' => '/assets/themes/rustic-forest.jpg',
                'description' => 'Gaya vintage rustic dengan sentuhan kayu dan warna-warna bumi (earthy).',
                'folder' => 'rustic-forest',
                'status' => 'active',
            ],
        ];

        $themeModels = [];
        foreach ($themes as $theme) {
            $themeModels[] = Theme::updateOrCreate(['slug' => $theme['slug']], $theme);
        }

        // 1.5. Seed Demo Music
        $demoMusic = \App\Models\Music::updateOrCreate(
            ['file' => '/assets/demo/music/lagu-nikah.mp3'],
            [
                'title' => 'Lagu Pernikahan Demo',
                'artist' => 'Demo Artist',
                'album' => 'Demo Album',
                'genre' => 'Wedding',
                'mood' => 'Romantic',
                'status' => 'active',
            ]
        );

        // 2. Fetch Users
        $superadmin = User::where('email', 'admin@teman-seakad.com')->first();
        $admin = User::where('email', 'staff.admin@teman-seakad.com')->first();
        $user = User::where('email', 'user@teman-seakad.com')->first();

        if (! $superadmin || ! $admin || ! $user) {
            return;
        }

        // Create some additional users to make the user counts realistic
        $additionalUsers = [
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@gmail.com', 'phone' => '081223344551'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@gmail.com', 'phone' => '081223344552'],
            ['name' => 'Budi Santoso', 'email' => 'budi@gmail.com', 'phone' => '081223344553'],
            ['name' => 'Siti Aminah', 'email' => 'siti@gmail.com', 'phone' => '081223344554'],
            ['name' => 'Rian Hidayat', 'email' => 'rian@gmail.com', 'phone' => '081223344555'],
        ];

        $userRole = Role::where('name', 'User')->first();
        foreach ($additionalUsers as $idx => $u) {
            $createdUser = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => bcrypt('password'),
                    'phone' => $u['phone'],
                    'status' => 'active',
                    // Spread user creation date over the last month
                    'created_at' => Carbon::now()->subDays($idx * 5)->subHours(rand(1, 23)),
                ]
            );
            if ($userRole) {
                $createdUser->roles()->sync([$userRole->id]);
            }
        }

        // 3. Seed Invitations
        $invitationData = [
            // Month 5 ago
            [
                'user_id' => $user->id,
                'title' => 'Pernikahan Raka & Ayu',
                'slug' => 'raka-ayu',
                'groom' => 'Raka Pratama',
                'bride' => 'Ayu Lestari',
                'months_ago' => 5,
            ],
            // Month 4 ago
            [
                'user_id' => $user->id,
                'title' => 'Pernikahan Budi & Ani',
                'slug' => 'budi-ani',
                'groom' => 'Budi Santoso',
                'bride' => 'Ani Wijaya',
                'months_ago' => 4,
            ],
            // Month 3 ago
            [
                'user_id' => $user->id,
                'title' => 'Pernikahan Ahmad & Siti',
                'slug' => 'ahmad-siti',
                'groom' => 'Ahmad Fauzi',
                'bride' => 'Siti Aminah',
                'months_ago' => 3,
            ],
            // Month 2 ago
            [
                'user_id' => $user->id,
                'title' => 'Pernikahan Denny & Firda',
                'slug' => 'denny-firda',
                'groom' => 'Denny Setiawan',
                'bride' => 'Firda Amalia',
                'months_ago' => 2,
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Pernikahan Gading & Gisella',
                'slug' => 'gading-gisella',
                'groom' => 'Gading Marten',
                'bride' => 'Gisella Anastasia',
                'months_ago' => 2,
            ],
            // Month 1 ago (last month)
            [
                'user_id' => $user->id,
                'title' => 'Pernikahan Reza & Farida',
                'slug' => 'reza-farida',
                'groom' => 'Reza Rahadian',
                'bride' => 'Farida Nurhan',
                'months_ago' => 1,
            ],
            [
                'user_id' => $user->id,
                'title' => 'Pernikahan Indra & Indah',
                'slug' => 'indra-indah',
                'groom' => 'Indra Bekti',
                'bride' => 'Indah Sari',
                'months_ago' => 1,
            ],
            // This month
            [
                'user_id' => $user->id,
                'title' => 'Pernikahan Yudha & Yanti',
                'slug' => 'yudha-yanti',
                'groom' => 'Yudha Pratama',
                'bride' => 'Yanti Susanti',
                'months_ago' => 0,
            ],
            [
                'user_id' => $user->id,
                'title' => 'Pernikahan Kevin & Valen',
                'slug' => 'kevin-valen',
                'groom' => 'Kevin Sanjaya',
                'bride' => 'Valencia Tanoe',
                'months_ago' => 0,
            ],
            [
                'user_id' => $superadmin->id,
                'title' => 'Pernikahan Kaesang & Erina',
                'slug' => 'kaesang-erina',
                'groom' => 'Kaesang Pangarep',
                'bride' => 'Erina Gudono',
                'months_ago' => 0,
            ],
        ];

        $invitationModels = [];
        foreach ($invitationData as $idx => $inv) {
            $createdDate = Carbon::now()->subMonths($inv['months_ago'])->subDays(rand(1, 28))->subHours(rand(1, 23));
            $theme = $themeModels[$idx % 4]; // Loop through all 4 active themes

            $invitation = Invitation::updateOrCreate(
                ['slug' => $inv['slug']],
                [
                    'user_id' => $inv['user_id'],
                    'theme_id' => $theme->id,
                    'title' => $inv['title'],
                    'status' => $inv['months_ago'] > 0 ? 'published' : 'draft',
                    'expired_at' => $createdDate->copy()->addMonths(6),
                    'groom_name' => $inv['groom'],
                    'bride_name' => $inv['bride'],
                    'akad_date' => $createdDate->copy()->addMonths(2)->setTime(9, 0),
                    'reception_date' => $createdDate->copy()->addMonths(2)->setTime(11, 0),
                    'venue' => 'Gedung Serbaguna Seakad',
                    'address' => 'Jl. Akademik No. 12, Kota Seakad',
                    'maps_url' => 'https://maps.google.com',
                    'description' => 'Tanpa mengurangi rasa hormat, kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami.',
                    'created_at' => $createdDate,
                    'updated_at' => $createdDate,
                ]
            );

            $invitationModels[] = $invitation;

            // Seed Events
            \App\Models\Event::updateOrCreate(
                [
                    'invitation_id' => $invitation->id,
                    'name' => 'Akad Nikah',
                ],
                [
                    'date' => $createdDate->copy()->addMonths(2)->toDateString(),
                    'time' => '09:00 - 10:30 WIB',
                    'location' => 'Masjid Raya Seakad, Jl. Utama No. 1',
                ]
            );

            \App\Models\Event::updateOrCreate(
                [
                    'invitation_id' => $invitation->id,
                    'name' => 'Resepsi Pernikahan',
                ],
                [
                    'date' => $createdDate->copy()->addMonths(2)->toDateString(),
                    'time' => '11:00 - selesai',
                    'location' => 'Gedung Serbaguna Seakad, Jl. Akademik No. 12',
                ]
            );

            // Seed Stories
            \App\Models\Story::updateOrCreate(
                [
                    'invitation_id' => $invitation->id,
                    'title' => 'Pertama Bertemu',
                ],
                [
                    'description' => 'Kami pertama kali bertemu di bangku perkuliahan Universitas Seakad pada tahun 2020.',
                    'date' => 'Maret 2020',
                    'sort' => 1,
                ]
            );

            \App\Models\Story::updateOrCreate(
                [
                    'invitation_id' => $invitation->id,
                    'title' => 'Lamaran',
                ],
                [
                    'description' => 'Setelah 3 tahun bersama, kami memutuskan untuk melangkah ke jenjang yang lebih serius.',
                    'date' => 'Desember 2023',
                    'sort' => 2,
                ]
            );

            // Sync background music
            $invitation->music()->sync([$demoMusic->id]);

            // Seed Galleries (only if not seeded already for this invitation to avoid duplicates)
            if ($invitation->galleries()->count() === 0) {
                $galleryImages = [
                    '/assets/demo/gallery/IMG_8305.jpg',
                    '/assets/demo/gallery/IMG_8306.jpg',
                    '/assets/demo/gallery/IMG_8309.jpg',
                    '/assets/demo/gallery/IMG_8312.jpg',
                    '/assets/demo/gallery/IMG_8313.jpg',
                    '/assets/demo/gallery/IMG_8314.jpg',
                    '/assets/demo/gallery/IMG_8315.jpg',
                    '/assets/demo/gallery/IMG_8316.jpg',
                    '/assets/demo/gallery/IMG_8317.jpg',
                    '/assets/demo/gallery/IMG_8318.jpg',
                    '/assets/demo/gallery/IMG_8320.jpg',
                ];

                // Shuffle and pick 6 images
                shuffle($galleryImages);
                $pickedImages = array_slice($galleryImages, 0, 6);

                foreach ($pickedImages as $idx => $img) {
                    \App\Models\Gallery::create([
                        'invitation_id' => $invitation->id,
                        'image' => $img,
                        'sort' => $idx + 1,
                    ]);
                }
            }

            // 4. Seed Guests for this invitation
            $guestNames = [
                'Budi Santoso', 'Siti Rahma', 'Joko Widodo', 'Megawati', 'Prabowo',
                'Anies Baswedan', 'Ganjar Pranowo', 'Ridwan Kamil', 'Najwa Shihab',
                'Raffi Ahmad', 'Nagita Slavina', 'Deddy Corbuzier',
            ];

            $attendances = ['hadir', 'tidak_hadir', 'belum_pasti'];
            $messages = [
                'Selamat ya! Semoga menjadi keluarga yang sakinah, mawaddah, warahmah.',
                'Lancar sampai hari H, maaf tidak bisa hadir karena sedang di luar kota.',
                'Insya Allah kami sekeluarga hadir. Selamat Raka & Ayu!',
                'Happy wedding! Semoga bahagia selalu sampai maut memisahkan.',
                'Selamat menempuh hidup baru!',
                'Semoga dilancarkan semua urusannya. Aamiin.',
            ];

            // Seed 4-8 guests per invitation
            $numGuests = rand(4, 8);
            for ($g = 0; $g < $numGuests; $g++) {
                Guest::create([
                    'invitation_id' => $invitation->id,
                    'name' => $guestNames[rand(0, count($guestNames) - 1)].' '.rand(1, 100),
                    'phone' => '0812'.rand(10000000, 99999999),
                    'attendance' => $attendances[rand(0, count($attendances) - 1)],
                    'message' => rand(0, 4) > 0 ? $messages[rand(0, count($messages) - 1)] : null,
                    'created_at' => $createdDate->copy()->addDays(rand(1, 14)),
                ]);
            }

            // 5. Seed Visits (Visitor statistics)
            // Seed visits over the last 14 days
            $totalVisits = rand(20, 50);
            for ($v = 0; $v < $totalVisits; $v++) {
                // Random day within last 14 days
                $visitDate = Carbon::now()->subDays(rand(0, 14))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                InvitationVisit::create([
                    'invitation_id' => $invitation->id,
                    'ip_address' => '192.168.1.'.rand(1, 254),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'created_at' => $visitDate,
                ]);
            }
        }
    }
}
