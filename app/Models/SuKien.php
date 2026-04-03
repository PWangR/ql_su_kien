<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuKien extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'su_kien';
    protected $primaryKey = 'ma_su_kien';

    protected $fillable = [
        'ten_su_kien',
        'mo_ta_chi_tiet',
        'ma_loai_su_kien',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'dia_diem',
        'so_luong_toi_da',
        'so_luong_hien_tai',
        'diem_cong',
        'ma_nguoi_tao',
        'ma_nguoi_to_chuc',
        'trang_thai',
        'anh_su_kien',
        'bo_cuc',
        'qr_checkin_token',
        'qr_code_path'
    ];

    protected $casts = [
        'thoi_gian_bat_dau'  => 'datetime',
        'thoi_gian_ket_thuc' => 'datetime',
        'bo_cuc'             => 'array',
    ];

    public function loaiSuKien()
    {
        return $this->belongsTo(LoaiSuKien::class, 'ma_loai_su_kien', 'ma_loai_su_kien');
    }

    public function nguoiTao()
    {
        return $this->belongsTo(User::class, 'ma_nguoi_tao', 'ma_sinh_vien');
    }

    public function dangKy()
    {
        return $this->hasMany(DangKy::class, 'ma_su_kien', 'ma_su_kien');
    }

    public function media()
    {
        return $this->hasMany(ThuVienDaPhuongTien::class, 'ma_su_kien', 'ma_su_kien');
    }

    public function scopeSapDienRa($query)
    {
        return $query->where('trang_thai', 'sap_to_chuc')
            ->where('thoi_gian_bat_dau', '>', now());
    }

    public function getTrangThaiThucTeAttribute()
    {
        if ($this->trang_thai === 'huy') return 'huy';

        $now = now();
        if ($now < $this->thoi_gian_bat_dau) return 'sap_to_chuc';
        if ($now <= $this->thoi_gian_ket_thuc) return 'dang_dien_ra';
        return 'da_ket_thuc';
    }

    public function getTrangThaiLabelAttribute()
    {
        $status = $this->trang_thai_thuc_te;
        return match ($status) {
            'sap_to_chuc'   => 'Sắp tổ chức',
            'dang_dien_ra'  => 'Đang diễn ra',
            'da_ket_thuc'   => 'Đã kết thúc',
            'huy'           => 'Đã hủy',
            default         => 'Không xác định',
        };
    }

    public function getTrangThaiColorAttribute()
    {
        $status = $this->trang_thai_thuc_te;
        return match ($status) {
            'sap_to_chuc'   => 'primary',
            'dang_dien_ra'  => 'success',
            'da_ket_thuc'   => 'secondary',
            'huy'           => 'danger',
            default         => 'secondary',
        };
    }

    public function isFullAttribute()
    {
        return $this->so_luong_toi_da > 0 && $this->so_luong_hien_tai >= $this->so_luong_toi_da;
    }
}
