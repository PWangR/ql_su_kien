<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BauCu extends Model
{
    use SoftDeletes;

    protected $table = 'bau_cu';
    protected $primaryKey = 'ma_bau_cu';

    protected $fillable = [
        'tieu_de', 'mo_ta',
        'thoi_gian_bat_dau', 'thoi_gian_ket_thuc',
        'so_chon_toi_thieu', 'so_chon_toi_da',
        'hien_thi', 'hien_thi_ket_qua',
        'trang_thai', 'ma_nguoi_tao',
    ];

    protected $casts = [
        'thoi_gian_bat_dau'  => 'datetime',
        'thoi_gian_ket_thuc' => 'datetime',
        'hien_thi'           => 'boolean',
        'hien_thi_ket_qua'   => 'boolean',
    ];

    /* ========== Relationships ========== */

    public function nguoiTao()
    {
        return $this->belongsTo(User::class, 'ma_nguoi_tao', 'ma_sinh_vien');
    }

    public function ungCuVien()
    {
        return $this->hasMany(UngCuVien::class, 'ma_bau_cu', 'ma_bau_cu')
                    ->orderBy('thu_tu_hien_thi');
    }

    public function cuTri()
    {
        return $this->hasMany(CuTri::class, 'ma_bau_cu', 'ma_bau_cu');
    }

    public function phieuBau()
    {
        return $this->hasMany(PhieuBau::class, 'ma_bau_cu', 'ma_bau_cu');
    }

    /* ========== Accessors ========== */

    public function getTrangThaiThucTeAttribute()
    {
        if ($this->trang_thai === 'huy') return 'huy';

        $now = now();
        if ($now < $this->thoi_gian_bat_dau) return 'nhap';
        if ($now <= $this->thoi_gian_ket_thuc) return 'dang_dien_ra';
        return 'hoan_thanh';
    }

    public function getTrangThaiLabelAttribute()
    {
        return match ($this->trang_thai_thuc_te) {
            'nhap'          => 'Chưa bắt đầu',
            'dang_dien_ra'  => 'Đang diễn ra',
            'hoan_thanh'    => 'Hoàn thành',
            'huy'           => 'Đã hủy',
            default         => 'Không xác định',
        };
    }

    public function getTrangThaiColorAttribute()
    {
        return match ($this->trang_thai_thuc_te) {
            'nhap'          => 'warning',
            'dang_dien_ra'  => 'success',
            'hoan_thanh'    => 'secondary',
            'huy'           => 'danger',
            default         => 'secondary',
        };
    }

    public function getSoLuongDaBoPhieuAttribute()
    {
        return $this->cuTri()->where('da_bo_phieu', true)->count();
    }

    public function getSoLuongCuTriAttribute()
    {
        return $this->cuTri()->count();
    }

    public function getSoLuongUngCuVienAttribute()
    {
        return $this->ungCuVien()->count();
    }

    /* ========== Scopes ========== */

    public function scopeHienThi($query)
    {
        return $query->where('hien_thi', true);
    }
}
