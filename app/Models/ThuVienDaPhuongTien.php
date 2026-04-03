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
            // Khi thực hiện hard delete (hoặc nếu bạn muốn xóa file ngay cả khi soft delete)
            // Ở đây ta check nếu là force deleting hoặc không dùng soft delete
            if (!$media->useSoftDeletes || $media->isForceDeleting()) {
                if ($media->duong_dan_tep && Storage::disk('public')->exists($media->duong_dan_tep)) {
                    Storage::disk('public')->delete($media->duong_dan_tep);
                }
            }
        });
    }

    protected $fillable = [
        'ma_su_kien', 'ma_nguoi_tai_len', 'ten_tep',
        'duong_dan_tep', 'loai_tep', 'kich_thuoc', 'la_cong_khai'
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

    public function getLoaiTepLabelAttribute()
    {
        return match($this->loai_tep) {
            'hinh_anh'  => 'Hình ảnh',
            'video'     => 'Video',
            'tai_lieu'  => 'Tài liệu',
            default     => 'Khác',
        };
    }
}
