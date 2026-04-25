<?php

namespace App\Http\Controllers;

use App\Services\GeminiChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function __construct(
        protected GeminiChatbotService $chatbotService
    ) {}

    public function ask(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:2|max:500',
        ]);

        try {
            $reply = $this->chatbotService->ask($validated['message'], $request->user());

            return response()->json([
                'success' => true,
                'reply' => $reply,
            ]);
        } catch (\RuntimeException $e) {
            $status = Str::contains(
                (string) Str::of($e->getMessage())->ascii()->lower(),
                'chua duoc cau hinh'
            )
                ? 503
                : 422;

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $status);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Trợ lý đang bận hoặc gặp lỗi kết nối. Vui lòng thử lại sau.',
            ], 500);
        }
    }
}
