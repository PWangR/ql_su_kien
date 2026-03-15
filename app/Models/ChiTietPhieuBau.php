<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietPhieuBau extends Model
{
    protected $table = 'chi_tiet_phieu_bau';
    protected $primaryKey = 'ma_chi_tiet';

    protected $fillable = [
        'ma_phieu_bau', 'ma_ung_cu_vien',
    ];

    /* ========== Relationships ========== */

    public function phieuBau()
    {
        return $this->belongsTo(PhieuBau::class, 'ma_phieu_bau', 'ma_phieu_bau');
    }

    public function ungCuVien()
    {
        return $this->belongsTo(UngCuVien::class, 'ma_ung_cu_vien', 'ma_ung_cu_vien');
    }
}
