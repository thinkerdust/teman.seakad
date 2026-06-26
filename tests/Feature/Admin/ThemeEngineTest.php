<?php

namespace Tests\Feature\Admin;

use App\Models\Invitation;
use App\Models\Order;
use App\Models\Package;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use App\Services\ThemeService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeEngineTest extends TestCase
{
    use RefreshDatabase;

    protected Role $superadminRole;

    protected User $superadmin;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->seed(RolePermissionSeeder::class);

        // Fetch superadmin role and user
        $this->superadminRole = Role::where('name', 'Superadmin')->first();
        $this->superadmin = User::factory()->create();
        $this->superadmin->roles()->sync([$this->superadminRole->id]);

        // Mock Vite to prevent manifest.json missing exceptions in tests
        \Illuminate\Support\Facades\Vite::spy();
    }

    /**
     * Test Theme model casts config as array and is_active as boolean.
     */
    public function test_theme_model_has_correct_casts(): void
    {
        $theme = Theme::create([
            'name' => 'Test Theme',
            'slug' => 'test-theme',
            'thumbnail' => '/test.jpg',
            'description' => 'Test',
            'folder' => 'test',
            'status' => 'active',
            'config' => ['colors' => ['primary' => '#000']],
            'is_active' => true,
        ]);

        $this->assertIsArray($theme->config);
        $this->assertEquals('#000', $theme->config['colors']['primary']);
        $this->assertIsBool($theme->is_active);
        $this->assertTrue($theme->is_active);
    }

    /**
     * Test ThemeService resolves configuration and views correctly.
     */
    public function test_theme_service_resolves_config_and_view(): void
    {
        $theme = Theme::create([
            'name' => 'Premium Cinematic',
            'slug' => 'premium-cinematic',
            'thumbnail' => '/test.jpg',
            'description' => 'Test',
            'folder' => 'premium-cinematic',
            'status' => 'active',
            'config' => null,
            'is_active' => true,
        ]);

        $service = app(ThemeService::class);
        $config = $service->getThemeConfig($theme);

        $this->assertIsArray($config);
        $this->assertEquals('Premium Cinematic', $config['name'] ?? null);

        $view = $service->getThemeView($theme);
        $this->assertEquals('themes.premium-cinematic.index', $view);
    }

    /**
     * Test ThemeController store and update saves new engine columns.
     */
    public function test_theme_controller_saves_new_columns(): void
    {
        $response = $this->actingAs($this->superadmin)->post(route('admin.themes.store'), [
            'name' => 'New Theme',
            'slug' => 'new-theme',
            'description' => 'Description',
            'folder' => 'new-theme-folder',
            'status' => 'active',
            'view_path' => 'themes.new.index',
            'config' => json_encode(['foo' => 'bar']),
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.themes.index'));

        $theme = Theme::where('slug', 'new-theme')->first();
        $this->assertNotNull($theme);
        $this->assertEquals('themes.new.index', $theme->view_path);
        $this->assertEquals(['foo' => 'bar'], $theme->config);
        $this->assertTrue($theme->is_active);

        // Test Update
        $responseUpdate = $this->actingAs($this->superadmin)->put(route('admin.themes.update', $theme->id), [
            'name' => 'Updated Theme',
            'slug' => 'updated-theme',
            'description' => 'Updated description',
            'folder' => 'new-theme-folder',
            'status' => 'active',
            'view_path' => 'themes.updated.index',
            'config' => json_encode(['foo' => 'baz']),
            'is_active' => 0,
        ]);

        $responseUpdate->assertRedirect(route('admin.themes.index'));

        $theme->refresh();
        $this->assertEquals('themes.updated.index', $theme->view_path);
        $this->assertEquals(['foo' => 'baz'], $theme->config);
        $this->assertFalse($theme->is_active);
    }

    /**
     * Test public invitation dynamically renders assigned theme view.
     */
    public function test_public_invitation_dynamic_theme_rendering(): void
    {
        $theme = Theme::create([
            'name' => 'Premium Cinematic',
            'slug' => 'premium-cinematic',
            'thumbnail' => '/test.jpg',
            'description' => 'Test',
            'folder' => 'premium-cinematic',
            'status' => 'active',
            'view_path' => 'themes.premium-cinematic.index',
            'config' => ['colors' => ['primary' => '#111']],
            'is_active' => true,
        ]);

        $user = User::factory()->create();
        $userRole = Role::where('name', 'User')->first();
        $user->roles()->sync([$userRole->id]);

        // Create an active package and order/subscription for the user
        $package = Package::create([
            'name' => 'Premium Pack',
            'price' => 200000,
            'invitation_quota' => 10,
            'duration_days' => 30,
            'status' => 'active',
        ]);

        Order::create([
            'customer_name' => $user->name,
            'phone' => '08987654321',
            'email' => $user->email,
            'package_id' => $package->id,
            'quota' => 10,
            'price' => 200000,
            'status' => 'active',
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(25)->toDateString(),
        ]);

        $invitation = Invitation::create([
            'user_id' => $user->id,
            'theme_id' => $theme->id,
            'slug' => 'dynamic-test',
            'title' => 'Dynamic Wedding',
            'groom_name' => 'Groom',
            'bride_name' => 'Bride',
            'venue' => 'Venue',
            'address' => 'Address',
            'status' => 'published',
            'expired_at' => now()->addMonth(),
        ]);

        $response = $this->get(route('public.invitation', 'dynamic-test'));
        $response->assertStatus(200);
        $response->assertViewIs('themes.premium-cinematic.index');
        $response->assertViewHas('themeConfig');
    }

    /**
     * Test theme preview route renders the correct view with dummy data.
     */
    public function test_theme_preview_renders_with_mock_data(): void
    {
        $theme = Theme::create([
            'name' => 'Premium Cinematic',
            'slug' => 'premium-cinematic',
            'thumbnail' => '/test.jpg',
            'description' => 'Test',
            'folder' => 'premium-cinematic',
            'status' => 'active',
            'view_path' => 'themes.premium-cinematic.index',
            'is_active' => true,
        ]);

        $response = $this->get(route('themes.preview', 'premium-cinematic'));
        $response->assertStatus(200);
        $response->assertViewIs('themes.premium-cinematic.index');
        $response->assertViewHas('invitationData');
        $response->assertViewHas('themeConfig');
    }

    /**
     * Test ThemeAssetService resolves assets correctly.
     */
    public function test_theme_asset_service_resolves_assets(): void
    {
        $themeConfig = [
            'folder' => 'floral-elegant',
            'assets' => [
                'hero' => [
                    'background' => 'images/hero/main.jpg',
                ],
                'background' => [
                    'texture' => 'backgrounds/soft.png',
                ],
            ],
        ];

        $service = app(\App\Services\ThemeAssetService::class);

        // Test normal resolution
        $url = $service->getAssetUrl('hero.background', $themeConfig);
        $this->assertStringContainsString('themes/floral-elegant/images/hero/main.jpg', $url);

        // Test absolute URL resolution
        $themeConfig['assets']['hero']['background'] = 'https://example.com/custom.jpg';
        $url = $service->getAssetUrl('hero.background', $themeConfig);
        $this->assertEquals('https://example.com/custom.jpg', $url);

        // Test fallback resolution
        $fallbackAudio = $service->getAssetUrl('audio', []);
        $this->assertStringContainsString('assets/demo/music/lagu-nikah.mp3', $fallbackAudio);
    }

    /**
     * Test ThemeTokenService generates dynamic @font-face and background texture CSS variables.
     */
    public function test_theme_token_service_generates_correct_font_faces_and_textures(): void
    {
        $themeConfig = [
            'folder' => 'floral-elegant',
            'design' => [
                'colors' => [
                    'primary' => '#b86b70',
                ],
                'typography' => [
                    'heading' => 'CustomFont.ttf',
                    'body' => 'Instrument Sans',
                ],
            ],
            'assets' => [
                'background' => [
                    'texture' => 'backgrounds/soft.png',
                ],
            ],
        ];

        $service = app(\App\Services\ThemeTokenService::class);
        $tokens = $service->generateTokens($themeConfig);

        // Should contain @font-face block for CustomFont.ttf
        $this->assertStringContainsString("@font-face {", $tokens);
        $this->assertStringContainsString("font-family: 'CustomFont';", $tokens);
        $this->assertStringContainsString("themes/floral-elegant/CustomFont.ttf", $tokens);

        // Should map CSS variables
        $this->assertStringContainsString("--theme-font-heading: 'CustomFont', Georgia, serif;", $tokens);
        $this->assertStringContainsString("--theme-background-texture: url('", $tokens);
        $this->assertStringContainsString("themes/floral-elegant/backgrounds/soft.png", $tokens);
    }
}

