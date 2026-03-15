<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UngCuVien extends Model
{
    protected $table = 'ung_cu_vien';
    protected $primaryKey = 'ma_ung_cu_vien';

    protected $fillable = [
        'ma_bau_cu', 'ho_ten', 'lop', 'ma_sinh_vien',
        'diem_trung_binh', 'diem_ren_luyen',
        'gioi_thieu', 'thu_tu_hien_thi',
    ];

    /* ========== Relationships ========== */

    public function bauCu()
    {
        return $this->belongsTo(BauCu::class, 'ma_bau_cu', 'ma_bau_cu');
    }

    public function chiTietPhieuBau()
    {
        return $this->hasMany(ChiTietPhieuBau::class, 'ma_ung_cu_vien', 'ma_ung_cu_vien');
    }

    /* ========== Helpers ========== */

    public function getSoPhieuAttribute()
    {
        return $this->chiTietPhieuBau()->count();
    }

    /**
     * Lấy danh sách ứng cử viên kèm số phiếu bầu cho 1 cuộc bầu cử
     */
    public static function layKemSoPhieu(int $maBauCu)
    {
        return static::where('ma_bau_cu', $maBauCu)
            ->withCount('chiTietPhieuBau as so_phieu')
            ->orderByDesc('so_phieu')
            ->orderBy('thu_tu_hien_thi')
            ->get();
    }
}
