<?php
$dir = __DIR__ . '/resources/views/themes';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $original = $content;

        // Replace var(--color)
        $content = str_replace('var(--primary)', 'var(--theme-primary)', $content);
        $content = str_replace('var(--accent)', 'var(--theme-accent)', $content);
        $content = str_replace('var(--background)', 'var(--theme-background)', $content);
        
        // Handle text colors carefully
        $content = preg_replace('/var\(--text(?:-primary|-secondary)?\)/', 'var(--theme-text)', $content);
        
        // Handle borders mapping to secondary
        $content = str_replace('var(--border)', 'var(--theme-secondary)', $content);

        // Fonts
        $content = str_replace('var(--font-heading)', 'var(--theme-font-heading)', $content);
        $content = str_replace('var(--font-body)', 'var(--theme-font-body)', $content);
        $content = str_replace('var(--font-accent)', 'var(--theme-font-heading)', $content); // Map accent font to heading

        // Fallbacks in inline styles
        $content = str_replace('var(--primary, #8b5a2b)', 'var(--theme-primary)', $content);

        if ($content !== $original) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
echo "Done.\n";
