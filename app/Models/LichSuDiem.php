<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuDiem extends Model
{
    protected $table = 'lich_su_diem';
    protected $primaryKey = 'ma_lich_su_diem';

    public $timestamps = false;

    protected $fillable = [
        'ma_nguoi_dung', 'ma_su_kien', 'ma_dang_ky',
        'diem', 'nguon'
    ];

    protected $casts = [
        'thoi_gian_ghi_nhan' => 'datetime',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }

    public function suKien()
    {
        return $this->belongsTo(SuKien::class, 'ma_su_kien', 'ma_su_kien');
    }
}
