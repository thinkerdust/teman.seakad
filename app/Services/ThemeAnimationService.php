<?php

namespace App\Services;

class ThemeAnimationService
{
    /**
     * Mendapatkan atribut data-animation untuk komponen tertentu berdasarkan konfigurasi tema.
     *
     * @param string $component
     * @param array $themeConfig
     * @return string
     */
    public function getAttributes(string $component, array $themeConfig): string
    {
        // 1. Ambil bagian motion dari konfigurasi
        $motion = $themeConfig['motion'] ?? [];
        
        // 2. Tentukan default behavior untuk setiap komponen
        $defaults = [
            'hero' => ['type' => 'fade-in', 'duration' => 2000],
            'couple' => ['type' => 'fade-up', 'duration' => 1200],
            'story' => ['type' => 'fade-up', 'duration' => 1200],
            'event' => ['type' => 'fade-up', 'duration' => 1200],
            'gallery' => ['type' => 'zoom-in', 'duration' => 800],
            'countdown' => ['type' => 'fade-up', 'duration' => 1000],
            'music' => ['type' => 'hover-scale', 'duration' => null],
            'rsvp' => ['type' => 'fade-up', 'duration' => 1200],
            'gift' => ['type' => 'fade-up', 'duration' => 1200],
            'guest-wish' => ['type' => 'fade-up', 'duration' => 1200],
        ];

        $default = $defaults[$component] ?? ['type' => 'fade-up', 'duration' => 1000];

        // 3. Resolve tipe animasi dari config.
        $configKey = $component;
        if (in_array($component, ['couple', 'story', 'event', 'countdown', 'rsvp'])) {
            // Gunakan key 'scroll' atau 'section' jika ada di theme.json
            $configKey = isset($motion['scroll']) ? 'scroll' : (isset($motion['section']) ? 'section' : 'scroll');
        }

        $configVal = $motion[$configKey] ?? $default;

        if (is_string($configVal)) {
            $type = $configVal;
            $duration = $default['duration'];
        } else {
            $type = $configVal['type'] ?? $default['type'];
            $duration = $configVal['duration'] ?? $default['duration'];
        }

        // 4. Bangun string atribut HTML
        $attributes = [];
        if ($type) {
            $attributes[] = 'data-animation="' . e($type) . '"';
        }
        if ($duration) {
            $attributes[] = 'data-duration="' . e($duration) . '"';
        }

        return implode(' ', $attributes);
    }
}
