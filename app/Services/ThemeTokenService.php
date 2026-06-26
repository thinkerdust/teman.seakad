<?php

namespace App\Services;

class ThemeTokenService
{
    /**
     * Generate CSS Variables string from theme config array.
     *
     * @param array $themeConfig The decoded theme.json content
     * @return string The generated CSS variables block
     */
    public function generateTokens(array $themeConfig): string
    {
        $cssVariables = [];
        $fontFaces = [];

        // 1. Ambil data warna dengan fallback default (Task 7)
        $colors = $themeConfig['design']['colors'] ?? ($themeConfig['colors'] ?? []);
        $defaultColors = [
            'primary' => '#b86b70',
            'secondary' => '#ebdcd7',
            'accent' => '#8fa89b',
            'background' => '#faf6f0',
            'text' => '#4e3e3b',
        ];

        foreach ($defaultColors as $key => $defaultVal) {
            $val = $colors[$key] ?? $defaultVal;
            $cssVariables[] = "--theme-{$key}: {$val};";
        }

        // 2. Ambil data tipografi dengan fallback default dan dukung custom font file (Task 10)
        $typography = $themeConfig['design']['typography'] ?? ($themeConfig['fonts'] ?? []);
        $defaultFonts = [
            'heading' => 'Playfair Display',
            'body' => 'Instrument Sans',
        ];

        foreach ($defaultFonts as $key => $defaultVal) {
            $val = $typography[$key] ?? $defaultVal;
            
            // Periksa jika tipografi berupa berkas font fisik (ttf/woff/woff2/otf)
            if (preg_match('/\.(ttf|woff|woff2|otf)$/i', $val)) {
                $fontFamilyName = pathinfo($val, PATHINFO_FILENAME);
                $themeFolder = $themeConfig['folder'] ?? ($themeConfig['identity']['style'] ?? 'default');
                $fontUrl = asset("themes/{$themeFolder}/{$val}");
                $format = match(strtolower(pathinfo($val, PATHINFO_EXTENSION))) {
                    'ttf' => 'truetype',
                    'woff' => 'woff',
                    'woff2' => 'woff2',
                    'otf' => 'opentype',
                    default => 'truetype'
                };
                
                $fontFaces[] = "@font-face {\n    font-family: '{$fontFamilyName}';\n    src: url('{$fontUrl}') format('{$format}');\n}";
                
                $fallback = $key === 'heading' ? 'Georgia, serif' : 'sans-serif';
                $cssVariables[] = "--theme-font-{$key}: '{$fontFamilyName}', {$fallback};";
            } else {
                $fallback = $key === 'heading' ? 'Georgia, serif' : 'sans-serif';
                $cssVariables[] = "--theme-font-{$key}: '{$val}', {$fallback};";
            }
        }

        // 2.5 Ambil tekstur latar belakang dinamis (Task 9)
        $texture = $themeConfig['assets']['background']['texture'] ?? null;
        if ($texture) {
            $themeFolder = $themeConfig['folder'] ?? ($themeConfig['identity']['style'] ?? 'default');
            $textureUrl = asset("themes/{$themeFolder}/{$texture}");
            $cssVariables[] = "--theme-background-texture: url('{$textureUrl}');";
        } else {
            $cssVariables[] = "--theme-background-texture: none;";
        }

        // 3. Tambahkan pemetaan variabel legacy untuk menjaga backward compatibility dengan style.css
        $cssVariables[] = "";
        $cssVariables[] = "/* Backward compatibility mappings for legacy style.css files */";
        $cssVariables[] = "--primary: var(--theme-primary);";
        $cssVariables[] = "--secondary: var(--theme-secondary);";
        $cssVariables[] = "--accent: var(--theme-accent);";
        $cssVariables[] = "--background: var(--theme-background);";
        $cssVariables[] = "--text-primary: var(--theme-text);";
        $cssVariables[] = "--text-secondary: var(--theme-accent);";
        $cssVariables[] = "--border: var(--theme-secondary);";
        $cssVariables[] = "--theme-surface: #ffffff;";
        $cssVariables[] = "--surface: var(--theme-surface);";
        $cssVariables[] = "--font-heading: var(--theme-font-heading);";
        $cssVariables[] = "--font-body: var(--theme-font-body);";
        $cssVariables[] = "--theme-background-image: var(--theme-background-texture);";
        
        $fontScale = $themeConfig['custom_style']['font_scale'] ?? '1.0';
        $cssVariables[] = "--theme-font-scale: {$fontScale};";

        $cssContent = "";
        if (!empty($fontFaces)) {
            $cssContent .= implode("\n\n", $fontFaces) . "\n\n";
        }
        $cssContent .= ":root {\n    " . implode("\n    ", $cssVariables) . "\n}";

        return $cssContent;
    }

    /**
     * Generate complete <style> tag to be injected into the view
     */
    public function generateStyleTag(array $themeConfig): string
    {
        $tokens = $this->generateTokens($themeConfig);
        
        if (empty($tokens)) {
            return '';
        }

        $fontScale = $themeConfig['custom_style']['font_scale'] ?? '1.0';
        $additionalCss = "\nbody {\n    font-size: calc(100% * var(--theme-font-scale, {$fontScale}));\n}";

        return "<style>\n{$tokens}\n{$additionalCss}\n</style>";
    }
}
