<?php

namespace Tests\Feature\Chatbot;

use App\Models\DangKy;
use App\Models\GeminiSetting;
use App\Models\SuKien;
use App\Models\User;
use App\Services\GeminiChatbotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeminiChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_chatbot_returns_local_upcoming_events_without_gemini(): void
    {
        Http::fake();

        $user = User::factory()->student()->create();

        SuKien::factory()->create([
            'ten_su_kien' => 'Hội thảo AI cơ bản',
            'dia_diem' => 'Phòng A1',
            'diem_cong' => 5,
            'so_luong_toi_da' => 100,
            'so_luong_hien_tai' => 35,
            'trang_thai' => 'sap_to_chuc',
            'thoi_gian_bat_dau' => now()->addDay(),
            'thoi_gian_ket_thuc' => now()->addDay()->addHours(2),
        ]);

        $response = $this->actingAs($user)->postJson(route('chatbot.ask'), [
            'message' => 'Có sự kiện nào sắp tới không?',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertStringContainsString('Hội thảo AI cơ bản', $response->json('reply'));
        Http::assertNothingSent();
    }

    public function test_chatbot_returns_local_registration_history_for_current_user(): void
    {
        Http::fake();

        $user = User::factory()->student()->create();
        $event = SuKien::factory()->create([
            'ten_su_kien' => 'Workshop Laravel',
            'thoi_gian_bat_dau' => now()->addDays(2),
            'thoi_gian_ket_thuc' => now()->addDays(2)->addHours(3),
        ]);

        DangKy::factory()->create([
            'ma_sinh_vien' => $user->ma_sinh_vien,
            'ma_su_kien' => $event->ma_su_kien,
            'trang_thai_tham_gia' => 'da_dang_ky',
        ]);

        $response = $this->actingAs($user)->postJson(route('chatbot.ask'), [
            'message' => 'Tôi đã đăng ký những sự kiện nào?',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertStringContainsString('Workshop Laravel', $response->json('reply'));
        $this->assertStringContainsString('đã đăng ký', $response->json('reply'));
        Http::assertNothingSent();
    }

    public function test_chatbot_returns_service_unavailable_for_open_question_when_not_configured(): void
    {
        $user = User::factory()->student()->create();

        $response = $this->actingAs($user)->postJson(route('chatbot.ask'), [
            'message' => 'Hãy gợi ý vì sao sinh viên nên tham gia hoạt động ngoại khóa.',
        ]);

        $response->assertStatus(503)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_chatbot_uses_gemini_for_open_ended_question(): void
    {
        $user = User::factory()->student()->create();

        SuKien::factory()->create([
            'ten_su_kien' => 'Hội thảo AI cơ bản',
            'dia_diem' => 'Phòng A1',
            'diem_cong' => 5,
            'trang_thai' => 'sap_to_chuc',
            'thoi_gian_bat_dau' => now()->addDay(),
            'thoi_gian_ket_thuc' => now()->addDay()->addHours(2),
        ]);

        GeminiSetting::create([
            'api_key' => 'secret-gemini-key',
            'model' => 'gemini-2.5-flash',
            'system_prompt' => GeminiSetting::defaultSystemPrompt(),
            'temperature' => 0.4,
            'max_output_tokens' => 512,
            'is_active' => true,
        ]);

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Sự kiện này phù hợp cho người mới vì có nội dung cơ bản và điểm cộng rõ ràng.'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($user)->postJson(route('chatbot.ask'), [
            'message' => 'Hãy tóm tắt ngắn vì sao người mới nên tham gia Hội thảo AI cơ bản.',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'reply' => 'Sự kiện này phù hợp cho người mới vì có nội dung cơ bản và điểm cộng rõ ràng.',
            ]);

        Http::assertSent(function ($request) {
            return $request->hasHeader('x-goog-api-key', 'secret-gemini-key')
                && str_contains($request->url(), 'models/gemini-2.5-flash:generateContent');
        });
    }

    public function test_chatbot_caches_open_ended_public_questions(): void
    {
        Cache::flush();
        GeminiChatbotService::flushRuntimeCache();

        $user = User::factory()->student()->create();

        GeminiSetting::create([
            'api_key' => 'secret-gemini-key',
            'model' => 'gemini-2.5-flash',
            'system_prompt' => GeminiSetting::defaultSystemPrompt(),
            'temperature' => 0.4,
            'max_output_tokens' => 512,
            'is_active' => true,
        ]);

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Sự kiện nổi bật có nội dung phù hợp cho sinh viên mới.'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $service = app(GeminiChatbotService::class);
        $message = 'Hãy tóm tắt ngắn lợi ích của hoạt động nổi bật dành cho sinh viên mới.';

        $this->assertSame('Sự kiện nổi bật có nội dung phù hợp cho sinh viên mới.', $service->ask($message, $user));
        $this->assertSame('Sự kiện nổi bật có nội dung phù hợp cho sinh viên mới.', $service->ask($message, $user));

        Http::assertSentCount(1);
    }
}
