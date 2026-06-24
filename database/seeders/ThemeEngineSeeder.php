<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ThemeEngineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = [
            [
                'slug' => 'premium-cinematic',
                'name' => 'Premium Cinematic',
                'description' => 'Tema premium dengan transisi sinematik, warna mewah, dan animasi modern.',
                'folder' => 'premium-cinematic',
                'thumbnail' => '/assets/themes/premium-cinematic.jpg',
            ],
            [
                'slug' => 'floral',
                'name' => 'Floral',
                'description' => 'Tema dengan nuansa bunga berkelas, cocok untuk pernikahan konsep alam.',
                'folder' => 'floral-elegant',
                'thumbnail' => '/assets/themes/floral-elegant.jpg',
            ],
            [
                'slug' => 'luxury',
                'name' => 'Luxury',
                'description' => 'Nuansa emas mewah berpadu dengan warna hitam elegan untuk kesan premium.',
                'folder' => 'luxury-gold',
                'thumbnail' => '/assets/themes/luxury-gold.jpg',
            ],
            [
                'slug' => 'islamic',
                'name' => 'Islamic',
                'description' => 'Tema minimalis dengan ornamen kaligrafi dan ornamen Islami modern.',
                'folder' => 'islamic-wedding',
                'thumbnail' => '/assets/themes/islamic-wedding.jpg',
            ],
            [
                'slug' => 'rustic',
                'name' => 'Rustic',
                'description' => 'Gaya vintage rustic dengan sentuhan kayu dan warna-warna bumi (earthy).',
                'folder' => 'rustic-forest',
                'thumbnail' => '/assets/themes/rustic-forest.jpg',
            ],
        ];

        foreach ($themes as $t) {
            $jsonPath = resource_path("views/themes/{$t['folder']}/theme.json");
            $config = [];

            if (File::exists($jsonPath)) {
                $config = json_decode(File::get($jsonPath), true) ?: [];
            }

            Theme::updateOrCreate(
                ['slug' => $t['slug']],
                [
                    'name' => $t['name'],
                    'thumbnail' => $t['thumbnail'],
                    'description' => $t['description'],
                    'folder' => $t['folder'],
                    'status' => 'active',
                    'view_path' => "themes.{$t['folder']}.index",
                    'config' => $config,
                    'is_active' => true,
                ]
            );
        }
    }
}
