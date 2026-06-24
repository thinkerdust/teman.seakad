<?php

namespace App\Console\Commands;

use App\Models\Theme;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ThemeMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:migrate {slug : The slug of the theme or "all" to migrate all themes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old theme assets and configurations to the new Theme Engine';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $slug = $this->argument('slug');

        $themesToMigrate = [
            'floral' => [
                'slug' => 'floral',
                'name' => 'Floral',
                'description' => 'Tema dengan nuansa bunga berkelas, cocok untuk pernikahan konsep alam.',
                'folder' => 'floral-elegant',
                'thumbnail' => '/assets/themes/floral-elegant.jpg',
            ],
            'luxury' => [
                'slug' => 'luxury',
                'name' => 'Luxury',
                'description' => 'Nuansa emas mewah berpadu dengan warna hitam elegan untuk kesan premium.',
                'folder' => 'luxury-gold',
                'thumbnail' => '/assets/themes/luxury-gold.jpg',
            ],
            'islamic' => [
                'slug' => 'islamic',
                'name' => 'Islamic',
                'description' => 'Tema minimalis dengan ornamen kaligrafi dan ornamen Islami modern.',
                'folder' => 'islamic-wedding',
                'thumbnail' => '/assets/themes/islamic-wedding.jpg',
            ],
            'rustic' => [
                'slug' => 'rustic',
                'name' => 'Rustic',
                'description' => 'Gaya vintage rustic dengan sentuhan kayu dan warna-warna bumi (earthy).',
                'folder' => 'rustic-forest',
                'thumbnail' => '/assets/themes/rustic-forest.jpg',
            ],
        ];

        if ($slug !== 'all') {
            if (!isset($themesToMigrate[$slug])) {
                $this->error("Theme with slug '{$slug}' is not recognized or already migrated.");
                return 1;
            }
            $targets = [$slug => $themesToMigrate[$slug]];
        } else {
            $targets = $themesToMigrate;
        }

        foreach ($targets as $themeSlug => $t) {
            $this->info("Migrating theme '{$t['name']}'...");

            // 1. Path of theme.json
            $jsonPath = resource_path("views/themes/{$t['folder']}/theme.json");
            $config = [];

            if (File::exists($jsonPath)) {
                $config = json_decode(File::get($jsonPath), true) ?: [];
                $this->line("Read config from theme.json");
            } else {
                $this->warn("theme.json not found for folder '{$t['folder']}'");
            }

            // 2. Copy/migrate assets
            $cssSource = resource_path("js/invitation/templates/{$t['folder']}/style.css");
            $cssDestDir = public_path("themes/{$t['folder']}/css");
            
            if (!File::isDirectory($cssDestDir)) {
                File::makeDirectory($cssDestDir, 0755, true);
            }

            if (File::exists($cssSource)) {
                File::copy($cssSource, "{$cssDestDir}/style.css");
                $this->line("Copied style.css from resources to public themes");
            } else {
                $this->warn("Source CSS not found at: {$cssSource}");
            }

            // 3. Update database record
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

            $this->info("Successfully migrated theme '{$t['name']}' and updated database!");
        }

        return 0;
    }
}
