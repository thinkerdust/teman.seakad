<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\File;

class ThemeService
{
    /**
     * Get configuration for a theme.
     * First checks if theme has config in database, otherwise reads from theme.json file.
     */
    public function getThemeConfig(Theme $theme): array
    {
        if ($theme->config) {
            return $theme->config;
        }

        $jsonPath = resource_path("views/themes/{$theme->folder}/theme.json");
        if (File::exists($jsonPath)) {
            $config = json_decode(File::get($jsonPath), true);
            if (is_array($config)) {
                return $config;
            }
        }

        return [];
    }

    /**
     * Resolve the view path for a theme.
     * Falls back to themes.{folder}.index if view_path is empty.
     */
    public function getThemeView(Theme $theme): string
    {
        if ($theme->view_path) {
            if (view()->exists($theme->view_path)) {
                return $theme->view_path;
            }
        }

        $fallbackView = "themes.{$theme->folder}.index";
        if (view()->exists($fallbackView)) {
            return $fallbackView;
        }

        // Default public invitation view
        return 'public.invitation';
    }

    /**
     * Get list of all installed themes (folders under resources/views/themes).
     */
    public function getInstalledThemes(): array
    {
        $themesPath = resource_path('views/themes');
        if (! File::isDirectory($themesPath)) {
            return [];
        }

        $directories = File::directories($themesPath);
        $themes = [];

        foreach ($directories as $dir) {
            $folder = basename($dir);
            $jsonPath = $dir.'/theme.json';

            if (File::exists($jsonPath)) {
                $config = json_decode(File::get($jsonPath), true);
                if (is_array($config)) {
                    $themes[$folder] = $config;
                }
            }
        }

        return $themes;
    }
}
