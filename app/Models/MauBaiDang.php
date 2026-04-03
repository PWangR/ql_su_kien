<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MauBaiDang extends Model
{
    use SoftDeletes;

    protected $table = 'mau_bai_dang';
    protected $primaryKey = 'ma_mau';

    protected $fillable = [
        'ten_mau', 'noi_dung', 'ma_nguoi_tao', 'ma_loai_su_kien',
        'dia_diem', 'so_luong_toi_da', 'diem_cong', 'anh_su_kien', 'bo_cuc'
    ];

    protected $casts = [
        'bo_cuc' => 'array',
    ];

    public function nguoiTao()
    {
        return $this->belongsTo(User::class, 'ma_nguoi_tao', 'ma_sinh_vien');
    }

    public function loaiSuKien()
    {
        return $this->belongsTo(LoaiSuKien::class, 'ma_loai_su_kien', 'ma_loai_su_kien');
    }
}
