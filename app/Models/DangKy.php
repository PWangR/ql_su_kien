<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DangKy extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dang_ky';
    protected $primaryKey = 'ma_dang_ky';
    public $timestamps = true;

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

    public function chiTietDiemDanh()
    {
        return $this->hasMany(ChiTietDiemDanh::class, 'ma_dang_ky', 'ma_dang_ky');
    }

    /**
     * Lấy số lần điểm danh
     */
    public function getSoLanDiemDanhAttribute()
    {
        return $this->chiTietDiemDanh()->count();
    }

    /**
     * Kiểm tra đã điểm danh đầu buổi chưa
     */
    public function getDaDiemDanhDauBuoiAttribute()
    {
        return $this->chiTietDiemDanh()
            ->where('loai_diem_danh', 'dau_buoi')
            ->exists();
    }

    /**
     * Kiểm tra đã điểm danh cuối buổi chưa
     */
    public function getDaDiemDanhCuoiBuoiAttribute()
    {
        return $this->chiTietDiemDanh()
            ->where('loai_diem_danh', 'cuoi_buoi')
            ->exists();
    }

    public function getTrangThaiLabelAttribute()
    {
        return match ($this->trang_thai_tham_gia) {
            'da_dang_ky'       => 'Đã đăng ký',
            'da_tham_gia'      => 'Đã tham gia',
            'vang_mat'         => 'Vắng mặt',
            'chua_du_dieu_kien' => 'Chưa đủ điều kiện',
            'huy'              => 'Đã hủy',
            default            => 'Không xác định',
        };
    }
}
