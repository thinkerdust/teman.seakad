<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class ThemeAssetService
{
    /**
     * Resolve the asset URL based on key and theme config.
     *
     * @param string $key (e.g. 'hero.background', 'ornaments.0', 'background.texture')
     * @param array|null $themeConfig
     * @return string
     */
    public function getAssetUrl(string $key, ?array $themeConfig = null): string
    {
        if ($themeConfig === null) {
            $themeConfig = view()->shared('themeConfig') ?? [];
        }

        // 1. Ambil folder tema dari config
        $themeFolder = $themeConfig['folder'] ?? ($themeConfig['identity']['style'] ?? 'default');
        
        // 2. Ambil nilai dari key secara nested
        $value = $this->getNestedValue($themeConfig['assets'] ?? [], $key);

        if (is_array($value)) {
            $value = $value['file'] ?? ($value['url'] ?? ($value['path'] ?? null));
        }

        if (!$value || !is_string($value)) {
            // Fallback default jika tidak terdefinisi
            return $this->getFallbackAsset($key, $themeFolder);
        }

        // 3. Bangun path URL. Jika berupa URL absolut, return langsung.
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, '/')) {
            return $value;
        }

        // 4. Return path relatif terhadap tema publik: /themes/{folder}/{path}
        return asset("themes/{$themeFolder}/{$value}");
    }

    /**
     * Membaca array menggunakan dot-notation (seperti 'hero.background').
     */
    protected function getNestedValue(array $array, string $key)
    {
        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return null;
            }
        }

        return $array;
    }

    /**
     * Memberikan fallback asset demo jika aset yang dikonfigurasi kosong.
     */
    protected function getFallbackAsset(string $key, string $themeFolder): string
    {
        $fallbacks = [
            'hero.background' => '/assets/demo/gallery/IMG_8305.jpg',
            'background.texture' => null,
            'audio' => '/assets/demo/music/lagu-nikah.mp3',
        ];

        // Specific defaults if we can find something relative
        if ($key === 'audio' || $key === 'audio.file') {
            return asset('assets/demo/music/lagu-nikah.mp3');
        }

        return $fallbacks[$key] ?? '';
    }
}
