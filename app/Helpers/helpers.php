<?php

if (!function_exists('themeAnimation')) {
    /**
     * Helper untuk mereturn HTML attributes animasi komponen berdasarkan konfigurasi tema yang sedang aktif.
     *
     * @param string $component
     * @param array|null $themeConfig
     * @return string
     */
    function themeAnimation(string $component, ?array $themeConfig = null): string
    {
        if ($themeConfig === null) {
            $themeConfig = view()->shared('themeConfig') ?? [];
        }
        
        return app(\App\Services\ThemeAnimationService::class)->getAttributes($component, $themeConfig);
    }
}

if (!function_exists('themeAsset')) {
    /**
     * Helper untuk mereturn URL berkas aset tema dinamis berdasarkan key.
     *
     * @param string $key
     * @param array|null $themeConfig
     * @return string
     */
    function themeAsset(string $key, ?array $themeConfig = null): string
    {
        return app(\App\Services\ThemeAssetService::class)->getAssetUrl($key, $themeConfig);
    }
}

if (!function_exists('format_date_safe')) {
    /**
     * Format a date string safely. If it is not a valid date format, return the original string.
     *
     * @param string|null $date
     * @param string $format
     * @return string
     */
    function format_date_safe(?string $date, string $format = 'd F Y'): string
    {
        if (empty($date)) {
            return '-';
        }
        try {
            return \Carbon\Carbon::parse($date)->translatedFormat($format);
        } catch (\Throwable $e) {
            return $date;
        }
    }
}

