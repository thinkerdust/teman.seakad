<?php

namespace Tests\Unit;

use Tests\TestCase;

class HelperTest extends TestCase
{
    /**
     * Test format_date_safe helper function.
     */
    public function test_format_date_safe_with_valid_date(): void
    {
        $result = format_date_safe('2026-07-15');
        // '15 Juli 2026' in Indonesian, depending on translatedFormat locale.
        // Let's assert it starts with '15' and contains '2026'
        $this->assertStringContainsString('15', $result);
        $this->assertStringContainsString('2026', $result);
    }

    /**
     * Test format_date_safe returns raw text when parsing fails.
     */
    public function test_format_date_safe_with_invalid_date_text(): void
    {
        $result1 = format_date_safe('Pertemuan Pertama');
        $this->assertEquals('Pertemuan Pertama', $result1);

        $result2 = format_date_safe('Desember 2020');
        $this->assertEquals('Desember 2020', $result2);
    }

    /**
     * Test format_date_safe handles empty/null values.
     */
    public function test_format_date_safe_handles_empty_values(): void
    {
        $this->assertEquals('-', format_date_safe(null));
        $this->assertEquals('-', format_date_safe(''));
    }
}
