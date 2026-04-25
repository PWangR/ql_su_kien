<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GeminiSetting;
use App\Services\GeminiChatbotService;
use Illuminate\Http\Request;

class GeminiSettingController extends Controller
{
    public function __construct(
        protected GeminiChatbotService $chatbotService
    ) {}

    public function index()
    {
        $gemini = GeminiSetting::getOrCreate();

        return view('admin.gemini.index', compact('gemini'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'model' => 'required|string|max:100',
            'api_key' => 'nullable|string|max:2048',
            'system_prompt' => 'nullable|string',
            'temperature' => 'required|numeric|min:0|max:2',
            // Cập nhật max_output_tokens lên 8192 cho phù hợp với Gemini 1.5 Flash
            'max_output_tokens' => 'required|integer|min:128|max:8192',
        ]);

        $gemini = GeminiSetting::getOrCreate();

        $data = [
            'model' => $validated['model'],
            'system_prompt' => $validated['system_prompt'] ?: GeminiSetting::defaultSystemPrompt(),
            'temperature' => $validated['temperature'],
            'max_output_tokens' => $validated['max_output_tokens'],
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('api_key')) {
            $data['api_key'] = $validated['api_key'];
        }

        $gemini->fill($data);
        $gemini->save();

        ActivityLog::log(
            'update',
            'Cập nhật cấu hình Gemini AI chatbot',
            GeminiSetting::class,
            $gemini->id
        );

        return redirect()
            ->route('admin.gemini.index')
            ->with('success', 'Cấu hình Gemini AI đã được lưu thành công.');
    }

    public function test(Request $request)
    {
        $validated = $request->validate([
            'prompt' => 'required|string|min:5|max:500',
        ]);

        try {
            $reply = $this->chatbotService->testConnection($validated['prompt'], $request->user());

            ActivityLog::log(
                'update',
                'Kiểm tra kết nối Gemini AI thành công',
                GeminiSetting::class,
                GeminiSetting::getOrCreate()->id
            );

            return response()->json([
                'success' => true,
                'reply' => $reply,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
