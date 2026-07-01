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
            'folder' => $theme->folder,
            'identity' => array_replace_recursive([
                'style' => 'default',
                'mood' => ['romantic'],
                'description' => 'Undangan Pernikahan Elegan',
            ], $config['identity'] ?? []),
            'design' => array_replace_recursive([
                'colors' => [
                    'primary' => $config['colors']['primary'] ?? '#000000',
                    'secondary' => $config['colors']['secondary'] ?? '#ffffff',
                    'accent' => $config['colors']['accent'] ?? '#000000',
                    'background' => $config['colors']['background'] ?? '#ffffff',
                    'text' => $config['colors']['text'] ?? '#000000',
                ],
                'typography' => [
                    'heading' => $config['fonts']['heading'] ?? 'Playfair Display',
                    'body' => $config['fonts']['body'] ?? 'sans-serif',
                ],
            ], $config['design'] ?? []),
            'motion' => array_replace_recursive([
                'opening' => $config['animations']['hero_entrance'] ?? 'fade-in',
                'scroll' => $config['motion']['section'] ?? ($config['animations']['section_reveal'] ?? 'fade-up'),
                'gallery' => $config['animations']['gallery'] ?? 'zoom-in',
            ], $config['motion'] ?? []),
            'assets' => array_replace_recursive([
                'hero' => [
                    'background' => 'images/hero/main.jpg',
                    'overlay' => true,
                ],
                'ornaments' => [],
                'background' => [
                    'texture' => null,
                ],
                'audio' => [
                    'enabled' => true,
                ],
            ], $config['assets'] ?? []),
            'features' => array_replace_recursive([
                'music' => true,
                'gallery' => true,
                'countdown' => true,
                'rsvp' => true,
                'stories' => true,
                'gift' => true,
            ], $config['features'] ?? []),
            'layout' => array_replace_recursive([
                'hero' => 'fullscreen',
            ], $config['layout'] ?? []),
        ]);
    }
}
