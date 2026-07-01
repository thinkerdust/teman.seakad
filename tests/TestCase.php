<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Spy on Vite to avoid manifest.json exceptions when compiling assets in tests
        \Illuminate\Support\Facades\Vite::spy();
    }
}
