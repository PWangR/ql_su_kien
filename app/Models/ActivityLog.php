<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model ActivityLog — Ghi nhận log hoạt động hệ thống
 */
class ActivityLog extends Model
{
    public $timestamps = false; // Chỉ dùng created_at

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'description',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
        'properties',
        'created_at',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relationship tới User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'ma_sinh_vien')->withTrashed();
    }

    /**
     * Ghi log hoạt động
     */
    public static function log(
        string $action,
        string $description,
        ?string $modelType = null,
        ?string $modelId = null,
        ?array $properties = null
    ): self {
        $user = auth()->user();

        if (!static::shouldLogAuthenticatedAdmin($user)) {
            return new static();
        }

        return static::create([
            'user_id' => $user ? $user->ma_sinh_vien : null,
            'user_name' => $user ? $user->ho_ten : 'Hệ thống',
            'action' => $action,
            'description' => $description,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'properties' => $properties,
            'created_at' => now(),
        ]);
    }

    /**
     * Scope: lọc theo user
     */
    public function scopeOfUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: lọc theo hành động
     */
    public function scopeOfAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: lọc theo khoảng thời gian
     */
    public function scopeBetweenDates($query, $from, $to)
    {
        if ($from && $to && $from > $to) {
            [$from, $to] = [$to, $from];
        }

        if ($from) {
            $query->where('created_at', '>=', $from . ' 00:00:00');
        }
        if ($to) {
            $query->where('created_at', '<=', $to . ' 23:59:59');
        }
        return $query;
    }

    /**
     * Scope: tìm kiếm
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('user_name', 'like', "%{$keyword}%")
              ->orWhere('user_id', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    /**
     * Label hiển thị cho action
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'login' => 'Đăng nhập',
            'logout' => 'Đăng xuất',
            'create' => 'Tạo mới',
            'update' => 'Cập nhật',
            'delete' => 'Xóa',
            default => ucfirst($this->action),
        };
    }

    /**
     * CSS class cho badge action
     */
    public function getActionBadgeClassAttribute(): string
    {
        return match ($this->action) {
            'login' => 'badge-success',
            'logout' => 'badge-secondary',
            'create' => 'badge-primary',
            'update' => 'badge-warning',
            'delete' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Xóa log cũ hơn số ngày chỉ định
     */
    public static function pruneOlderThan(int $days = 30): int
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }

    public function scopeAdminOnly($query)
    {
        return $query->whereHas('user', function ($userQuery) {
            $userQuery->whereIn('vai_tro', ['admin', 'super_admin']);
        });
    }

    public static function shouldLogAuthenticatedAdmin($user): bool
    {
        return $user && method_exists($user, 'isAdmin') && $user->isAdmin();
    }
}
