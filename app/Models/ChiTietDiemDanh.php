<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChiTietDiemDanh extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_diem_danh';
    protected $primaryKey = 'ma_chi_tiet_diem_danh';

    public $timestamps = false;

    protected $fillable = [
        'ma_dang_ky',
        'ma_su_kien',
        'ma_sinh_vien',
        'loai_diem_danh',
        'diem_danh_at'
    ];

    protected $casts = [
        'diem_danh_at' => 'datetime',
    ];

    // Relationships
    public function dangKy()
    {
        return $this->belongsTo(DangKy::class, 'ma_dang_ky', 'ma_dang_ky');
    }

    public function suKien()
    {
        return $this->belongsTo(SuKien::class, 'ma_su_kien', 'ma_su_kien');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }
}
