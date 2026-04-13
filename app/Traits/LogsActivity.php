<?php

namespace App\Traits;

use App\Models\ActivityLog;

/**
 * Trait LogsActivity — Tự động ghi log khi Model được tạo/sửa/xóa
 * Sử dụng: use LogsActivity trong Model cần ghi log
 */
trait LogsActivity
{
    /**
     * Boot trait — đăng ký model events
     */
    public static function bootLogsActivity()
    {
        // Ghi log khi tạo mới
        static::created(function ($model) {
            if (!app()->runningInConsole() || app()->runningUnitTests()) {
                ActivityLog::log(
                    'create',
                    static::getLogDescription('create', $model),
                    get_class($model),
                    $model->getKey(),
                    ['attributes' => $model->getAttributes()]
                );
            }
        });

        // Ghi log khi cập nhật
        static::updated(function ($model) {
            if (!app()->runningInConsole() || app()->runningUnitTests()) {
                $changes = $model->getChanges();
                $original = collect($model->getOriginal())
                    ->only(array_keys($changes))
                    ->toArray();

                // Không log nếu chỉ thay đổi timestamps
                $ignoredFields = ['updated_at', 'created_at', 'remember_token'];
                $meaningfulChanges = array_diff_key($changes, array_flip($ignoredFields));

                if (!empty($meaningfulChanges)) {
                    ActivityLog::log(
                        'update',
                        static::getLogDescription('update', $model),
                        get_class($model),
                        $model->getKey(),
                        [
                            'old' => array_intersect_key($original, $meaningfulChanges),
                            'new' => $meaningfulChanges,
                        ]
                    );
                }
            }
        });

        // Ghi log khi xóa
        static::deleted(function ($model) {
            if (!app()->runningInConsole() || app()->runningUnitTests()) {
                ActivityLog::log(
                    'delete',
                    static::getLogDescription('delete', $model),
                    get_class($model),
                    $model->getKey(),
                    ['attributes' => $model->getAttributes()]
                );
            }
        });
    }

    /**
     * Tạo mô tả log cho model
     * Override trong model con nếu cần mô tả tùy chỉnh
     */
    protected static function getLogDescription(string $action, $model): string
    {
        $modelName = static::getLogModelName();
        $identifier = $model->getKey();

        return match ($action) {
            'create' => "Tạo mới {$modelName}: {$identifier}",
            'update' => "Cập nhật {$modelName}: {$identifier}",
            'delete' => "Xóa {$modelName}: {$identifier}",
            default  => "{$action} {$modelName}: {$identifier}",
        };
    }

    /**
     * Tên hiển thị của model trong log
     * Override trong model con nếu cần
     */
    protected static function getLogModelName(): string
    {
        return class_basename(static::class);
    }
}
