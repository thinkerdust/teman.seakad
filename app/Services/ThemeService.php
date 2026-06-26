<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\File;
use App\Services\ThemeTokenService;
use App\Services\ThemeConfigService;

class ThemeService
{
    protected ThemeTokenService $tokenService;

    public function __construct(ThemeTokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * Get configuration for a theme.
     * First checks if theme has config in database, otherwise reads from theme.json file.
     */
    public function getThemeConfig(Theme $theme): array
    {
        return app(ThemeConfigService::class)->load($theme);
    }

    /**
     * Get dynamic CSS variables string based on theme configuration.
     */
    public function getThemeCssTokens(array $themeConfig): string
    {
        return $this->tokenService->generateStyleTag($themeConfig);
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
