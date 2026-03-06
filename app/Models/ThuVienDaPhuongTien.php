<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThuVienDaPhuongTien extends Model
{
    use SoftDeletes;

    protected $table = 'thu_vien_da_phuong_tien';
    protected $primaryKey = 'ma_phuong_tien';

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
        return $this->belongsTo(User::class, 'ma_nguoi_tai_len', 'ma_nguoi_dung');
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
