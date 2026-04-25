<?php

namespace Tests\Feature\Admin;

use App\Models\GeminiSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeminiSettingAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_gemini_settings(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.gemini.update'), [
            'is_active' => '1',
            'model' => 'gemini-2.5-flash',
            'api_key' => 'secret-gemini-key',
            'system_prompt' => 'Test prompt',
            'temperature' => '0.5',
            'max_output_tokens' => '600',
        ]);

        $response->assertRedirect(route('admin.gemini.index'));
        $response->assertSessionHas('success');

        $gemini = GeminiSetting::first();

        $this->assertNotNull($gemini);
        $this->assertTrue($gemini->is_active);
        $this->assertSame('gemini-2.5-flash', $gemini->model);
        $this->assertSame('Test prompt', $gemini->system_prompt);
        $this->assertSame('secret-gemini-key', $gemini->api_key);
        $this->assertNotSame('secret-gemini-key', $gemini->getRawApiKey());
    }

    public function test_admin_can_test_gemini_connection(): void
    {
        $admin = User::factory()->admin()->create();

        GeminiSetting::create([
            'api_key' => 'secret-gemini-key',
            'model' => 'gemini-2.5-flash',
            'system_prompt' => GeminiSetting::defaultSystemPrompt(),
            'temperature' => 0.4,
            'max_output_tokens' => 512,
            'is_active' => false,
        ]);

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Gemini test ok'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.gemini.test'), [
            'prompt' => 'Có sự kiện nào sắp diễn ra không?',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'reply' => 'Gemini test ok',
            ]);
    }
}
