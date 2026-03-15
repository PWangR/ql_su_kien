<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuBau extends Model
{
    protected $table = 'phieu_bau';
    protected $primaryKey = 'ma_phieu_bau';

    protected $fillable = [
        'ma_bau_cu', 'hash_ip', 'thoi_gian_gui',
    ];

    protected $casts = [
        'thoi_gian_gui' => 'datetime',
    ];

    /* ========== Relationships ========== */

    public function bauCu()
    {
        return $this->belongsTo(BauCu::class, 'ma_bau_cu', 'ma_bau_cu');
    }

    public function chiTiet()
    {
        return $this->hasMany(ChiTietPhieuBau::class, 'ma_phieu_bau', 'ma_phieu_bau');
    }
}
