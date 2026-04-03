<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuTri extends Model
{
    protected $table = 'cu_tri';
    protected $primaryKey = 'ma_cu_tri';

    protected $fillable = [
        'ma_bau_cu', 'ma_sinh_vien',
        'da_bo_phieu', 'thoi_gian_bo_phieu',
    ];

    protected $casts = [
        'da_bo_phieu'       => 'boolean',
        'thoi_gian_bo_phieu' => 'datetime',
    ];

    /* ========== Relationships ========== */

    public function bauCu()
    {
        return $this->belongsTo(BauCu::class, 'ma_bau_cu', 'ma_bau_cu');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }
}
