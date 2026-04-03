<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DangKy extends Model
{
    use HasFactory;

    protected $table = 'dang_ky';
    protected $primaryKey = 'ma_dang_ky';
    public $timestamps = false;

    protected $fillable = [
        'ma_sinh_vien',
        'ma_su_kien',
        'trang_thai_tham_gia'
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }

    public function suKien()
    {
        return $this->belongsTo(SuKien::class, 'ma_su_kien', 'ma_su_kien');
    }

    public function getTrangThaiLabelAttribute()
    {
        return match ($this->trang_thai_tham_gia) {
            'da_dang_ky'  => 'Đã đăng ký',
            'da_tham_gia' => 'Đã tham gia',
            'vang_mat'    => 'Vắng mặt',
            'huy'         => 'Đã hủy',
            default       => 'Không xác định',
        };
    }
}
