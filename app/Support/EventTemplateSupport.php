<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class EventTemplateSupport
{
    public static function moduleCatalog(): array
    {
        return [
            'banner' => [
                'label' => 'Ảnh bìa',
                'icon' => 'bi-card-image',
                'description' => 'Khối hero hoặc ảnh mở đầu',
                'defaults' => [
                    'title' => 'Ảnh bìa',
                    'settings' => [
                        'caption_label' => 'Chú thích ảnh',
                    ],
                ],
            ],
            'header' => [
                'label' => 'Tiêu đề',
                'icon' => 'bi-type-h1',
                'description' => 'Tiêu đề, phụ đề, badge',
                'defaults' => [
                    'title' => 'Tiêu đề chính',
                    'settings' => [
                        'subtitle_label' => 'Phụ đề',
                        'badge_label' => 'Badge',
                    ],
                ],
            ],
            'info' => [
                'label' => 'Thông tin',
                'icon' => 'bi-info-circle',
                'description' => 'Hiển thị thời gian, địa điểm, chỉ tiêu, điểm',
                'defaults' => [
                    'title' => 'Thông tin sự kiện',
                    'settings' => [
                        'items' => ['time', 'location', 'capacity', 'points'],
                    ],
                ],
            ],
            'description' => [
                'label' => 'Nội dung',
                'icon' => 'bi-text-paragraph',
                'description' => 'Khối văn bản hoặc nội dung mô tả',
                'defaults' => [
                    'title' => 'Nội dung chi tiết',
                    'settings' => [
                        'body_label' => 'Nội dung',
                    ],
                ],
            ],
            'gallery' => [
                'label' => 'Gallery',
                'icon' => 'bi-images',
                'description' => 'Bộ sưu tập ảnh riêng cho một khối',
                'defaults' => [
                    'title' => 'Hình ảnh sự kiện',
                    'settings' => [
                        'hint' => 'Tải nhiều ảnh cho riêng khối này',
                    ],
                ],
            ],
            'documents' => [
                'label' => 'Tài liệu đính kèm',
                'icon' => 'bi-paperclip',
                'description' => 'Đính kèm tài liệu từ thư viện media',
                'defaults' => [
                    'title' => 'Tài liệu đính kèm',
                    'settings' => [
                        'label' => 'Tài liệu',
                    ],
                ],
            ],
        ];
    }

    public static function infoFieldCatalog(): array
    {
        return [
            'time' => 'Thời gian',
            'location' => 'Địa điểm',
            'capacity' => 'Số lượng',
            'points' => 'Điểm cộng',
        ];
    }

    public static function defaultTemplateModules(): array
    {
        return [
            self::makeModule('banner', 1),
            self::makeModule('header', 1),
            self::makeModule('info', 1),
            self::makeModule('description', 1),
            self::makeModule('gallery', 1),
        ];
    }

    public static function makeModule(string $type, ?int $position = null): array
    {
        $catalog = static::moduleCatalog()[$type] ?? null;

        if (!$catalog) {
            throw new \InvalidArgumentException("Unsupported module type [{$type}]");
        }

        $title = $catalog['defaults']['title'] ?? $catalog['label'];

        return [
            'id' => Str::slug($type . '-' . ($position ?? Str::random(6))),
            'type' => $type,
            'title' => $title,
            'settings' => $catalog['defaults']['settings'] ?? [],
            'content' => [],
        ];
    }

    public static function normalizeTemplateModules(mixed $payload): array
    {
        if (blank($payload)) {
            return static::defaultTemplateModules();
        }

        if (is_string($payload)) {
            $decoded = json_decode($payload, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $payload = $decoded;
            }
        }

        if (!is_array($payload)) {
            return static::defaultTemplateModules();
        }

        $isLegacy = static::isLegacyLayout($payload);

        if ($isLegacy) {
            return collect($payload)
                ->filter(fn($type) => array_key_exists($type, static::moduleCatalog()))
                ->values()
                ->map(fn($type, $index) => static::makeModule($type, $index + 1))
                ->all();
        }

        return collect($payload)
            ->filter(fn($module) => is_array($module) && array_key_exists(Arr::get($module, 'type'), static::moduleCatalog()))
            ->values()
            ->map(function (array $module, int $index) {
                $type = $module['type'];
                $fallback = static::makeModule($type, $index + 1);

                return [
                    'id' => (string) ($module['id'] ?? $fallback['id']),
                    'type' => $type,
                    'title' => trim((string) ($module['title'] ?? $fallback['title'])) ?: $fallback['title'],
                    'settings' => static::sanitizeSettings($type, Arr::get($module, 'settings', [])),
                    'content' => is_array($module['content'] ?? null) ? $module['content'] : [],
                ];
            })
            ->all();
    }

    public static function sanitizeSettings(string $type, mixed $settings): array
    {
        $settings = is_array($settings) ? $settings : [];

        return match ($type) {
            'banner' => [
                'caption_label' => trim((string) Arr::get($settings, 'caption_label', 'Chú thích ảnh')) ?: 'Chú thích ảnh',
            ],
            'header' => [
                'subtitle_label' => trim((string) Arr::get($settings, 'subtitle_label', 'Phụ đề')) ?: 'Phụ đề',
                'badge_label' => trim((string) Arr::get($settings, 'badge_label', 'Badge')) ?: 'Badge',
            ],
            'info' => [
                'items' => collect(Arr::get($settings, 'items', ['time', 'location', 'capacity', 'points']))
                    ->filter(fn($item) => array_key_exists($item, static::infoFieldCatalog()))
                    ->values()
                    ->all(),
            ],
            'description' => [
                'body_label' => trim((string) Arr::get($settings, 'body_label', 'Nội dung')) ?: 'Nội dung',
            ],
            'gallery' => [
                'hint' => trim((string) Arr::get($settings, 'hint', 'Tải nhiều ảnh cho riêng khối này')) ?: 'Tải nhiều ảnh cho riêng khối này',
            ],
            'documents' => [
                'label' => trim((string) Arr::get($settings, 'label', 'Tài liệu')) ?: 'Tài liệu',
            ],
            default => [],
        };
    }

    public static function isLegacyLayout(array $payload): bool
    {
        if ($payload === []) {
            return false;
        }

        return collect($payload)->every(fn($item) => is_string($item));
    }
}
