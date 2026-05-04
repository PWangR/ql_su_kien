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

    protected $appends = [
        'trang_thai_label',
        'so_lan_diem_danh',
        'da_diem_danh_dau_buoi',
        'da_diem_danh_cuoi_buoi',
        'ma_diem_danh_ca_nhan',
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

    public function getMaDiemDanhCaNhanAttribute()
    {
        if (!$this->ma_dang_ky || !$this->ma_su_kien || !$this->ma_sinh_vien) {
            return null;
        }

        return implode('|', [
            'event',
            $this->ma_su_kien,
            'registration',
            $this->ma_dang_ky,
            'student',
            $this->ma_sinh_vien,
        ]);
    }
}
