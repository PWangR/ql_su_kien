<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class GeminiSetting extends Model
{
    protected $table = 'gemini_settings';

    protected $fillable = [
        'api_key',
        'model',
        'system_prompt',
        'temperature',
        'max_output_tokens',
        'is_active',
    ];

    protected $casts = [
        'temperature' => 'float',
        'max_output_tokens' => 'integer',
        'is_active' => 'boolean',
    ];

    public function setApiKeyAttribute($value): void
    {
        if (!empty($value)) {
            $this->attributes['api_key'] = Crypt::encryptString($value);
        }
    }

    public function getApiKeyAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function getRawApiKey(): ?string
    {
        return $this->attributes['api_key'] ?? null;
    }

    public function hasApiKey(): bool
    {
        return !empty($this->getRawApiKey());
    }

    public static function defaultSystemPrompt(): string
    {
        return <<<'PROMPT'
Bạn là lớp AI fallback của hệ thống Quản Lý Sự Kiện Khoa CNTT.

Quy tắc:
- Chỉ dùng dữ liệu có trong ngữ cảnh.
- Trả lời ngắn gọn, tối đa 4 câu, bằng tiếng Việt.
- Ưu tiên nêu tên sự kiện, thời gian, địa điểm, trạng thái, điểm nếu có.
- Không bịa thông tin, không trả lời ngoài phạm vi hệ thống.
- Nếu thiếu dữ liệu, nói rõ "Chưa có thông tin trong hệ thống".
PROMPT;
    }

    public static function getOrCreate(): self
    {
        $config = static::first();

        if (!$config) {
            $config = static::create([
                'model' => 'gemini-2.5-flash',
                'system_prompt' => static::defaultSystemPrompt(),
                'temperature' => 0.40,
                'max_output_tokens' => 512,
                'is_active' => false,
            ]);
        }

        return $config;
    }

    public static function getActiveConfig(): ?self
    {
        $config = static::first();

        if (!$config || !$config->is_active || !$config->hasApiKey()) {
            return null;
        }

        return $config;
    }
}
