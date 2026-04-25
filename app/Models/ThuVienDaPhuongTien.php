<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ThuVienDaPhuongTien extends Model
{
    use SoftDeletes;

    protected $table = 'thu_vien_da_phuong_tien';
    protected $primaryKey = 'ma_phuong_tien';

    protected static function booted()
    {
        static::deleting(function ($media) {
            if (!$media->usesSoftDeletes() || $media->isForceDeleting()) {
                if ($media->canDeletePhysicalFile() && $media->duong_dan_tep && Storage::disk('public')->exists($media->duong_dan_tep)) {
                    Storage::disk('public')->delete($media->duong_dan_tep);
                }
            }
        });
    }

    protected $fillable = [
        'ma_su_kien',
        'ma_nguoi_tai_len',
        'ten_tep',
        'duong_dan_tep',
        'loai_tep',
        'kich_thuoc',
        'la_cong_khai'
    ];

    protected $casts = [
        'la_cong_khai'   => 'boolean',
    ];

    public function suKien()
    {
        return $this->belongsTo(SuKien::class, 'ma_su_kien', 'ma_su_kien');
    }

    public function nguoiTaiLen()
    {
        return $this->belongsTo(User::class, 'ma_nguoi_tai_len', 'ma_sinh_vien');
    }

    public function theAnh()
    {
        return $this->belongsToMany(
            TheAnh::class,
            'the_anh_thu_vien',
            'ma_phuong_tien',
            'ma_the_anh',
            'ma_phuong_tien',
            'ma_the_anh'
        );
    }

    public function getLoaiTepLabelAttribute()
    {
        return match ($this->loai_tep) {
            'hinh_anh'  => 'Hình ảnh',
            'video'     => 'Video',
            'tai_lieu'  => 'Tài liệu',
            default     => 'Khác',
        };
    }

    public function scopeLibraryItems($query)
    {
        return $query->whereNull('ma_su_kien');
    }

    protected function usesSoftDeletes(): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive(static::class), true);
    }

    public function canDeletePhysicalFile(): bool
    {
        if (!$this->duong_dan_tep) {
            return false;
        }

        return !static::withTrashed()
            ->where('duong_dan_tep', $this->duong_dan_tep)
            ->where($this->getKeyName(), '!=', $this->getKey())
            ->exists();
    }
}
