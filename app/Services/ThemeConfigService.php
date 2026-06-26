<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\File;

class ThemeConfigService
{
    /**
     * Memuat konfigurasi tema dari database atau berkas theme.json dengan nilai default.
     *
     * @param Theme $theme
     * @return array
     */
    public function load(Theme $theme): array
    {
        // 1. Ambil data konfigurasi dasar dari database (jika di-override) atau berkas theme.json
        $config = [];
        if ($theme->config) {
            $config = $theme->config;
        } else {
            $jsonPath = resource_path("views/themes/{$theme->folder}/theme.json");
            if (File::exists($jsonPath)) {
                $decoded = json_decode(File::get($jsonPath), true);
                if (is_array($decoded)) {
                    $config = $decoded;
                }
            }
        }

        // 1.5 Load preset if specified
        $preset = $config['preset'] ?? null;
        if (!$preset) {
            $style = $config['identity']['style'] ?? 'default';
            if ($style === 'romantic' || $style === 'traditional') {
                $preset = 'soft';
            } elseif ($style === 'premium' || $style === 'cinematic') {
                $preset = 'cinematic';
            } elseif ($style === 'rustic') {
                $preset = 'minimal';
            }
        }

        if ($preset) {
            $presetPath = resource_path("themes/presets/animations/{$preset}.json");
            if (File::exists($presetPath)) {
                $presetData = json_decode(File::get($presetPath), true);
                if (is_array($presetData) && isset($presetData['motion'])) {
                    $config['motion'] = array_merge($presetData['motion'], $config['motion'] ?? []);
                }
            }
        }

        // 2. Berikan default value untuk memastikan backward compatibility
        return array_merge([
            'name' => $theme->name,
            'version' => '1.0',
            'author' => 'Teman Seakad',
        ], $config, [
            'identity' => [
                'style' => $config['identity']['style'] ?? 'default',
                'mood' => $config['identity']['mood'] ?? ['romantic'],
                'description' => $config['identity']['description'] ?? 'Undangan Pernikahan Elegan',
            ],
            'design' => [
                'colors' => [
                    'primary' => $config['design']['colors']['primary'] ?? ($config['colors']['primary'] ?? '#000000'),
                    'secondary' => $config['design']['colors']['secondary'] ?? ($config['colors']['secondary'] ?? '#ffffff'),
                    'accent' => $config['design']['colors']['accent'] ?? ($config['colors']['accent'] ?? '#000000'),
                    'background' => $config['design']['colors']['background'] ?? ($config['colors']['background'] ?? '#ffffff'),
                    'text' => $config['design']['colors']['text'] ?? ($config['colors']['text'] ?? '#000000'),
                ],
                'typography' => [
                    'heading' => $config['design']['typography']['heading'] ?? ($config['fonts']['heading'] ?? 'Playfair Display'),
                    'body' => $config['design']['typography']['body'] ?? ($config['fonts']['body'] ?? 'sans-serif'),
                ],
            ],
            'motion' => [
                'opening' => $config['motion']['opening'] ?? ($config['animations']['hero_entrance'] ?? 'fade-in'),
                'scroll' => $config['motion']['scroll'] ?? ($config['motion']['section'] ?? ($config['animations']['section_reveal'] ?? 'fade-up')),
                'gallery' => $config['motion']['gallery'] ?? ($config['motion']['gallery'] ?? ($config['animations']['gallery'] ?? 'zoom-in')),
            ],
            'assets' => [
                'hero' => [
                    'background' => $config['assets']['hero']['background'] ?? 'images/hero/main.jpg',
                    'overlay' => $config['assets']['hero']['overlay'] ?? true,
                ],
                'ornaments' => $config['assets']['ornaments'] ?? [],
                'background' => [
                    'texture' => $config['assets']['background']['texture'] ?? null,
                ],
                'audio' => [
                    'enabled' => $config['assets']['audio']['enabled'] ?? true,
                ],
            ],
            'features' => [
                'music' => $config['features']['music'] ?? true,
                'gallery' => $config['features']['gallery'] ?? true,
                'countdown' => $config['features']['countdown'] ?? true,
                'rsvp' => $config['features']['rsvp'] ?? true,
                'stories' => $config['features']['stories'] ?? true,
                'gift' => $config['features']['gift'] ?? true,
            ],
            'layout' => [
                'hero' => $config['layout']['hero'] ?? 'fullscreen',
            ],
        ]);
    }
}
