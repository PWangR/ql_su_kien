<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiSuKien extends Model
{
    protected $table = 'loai_su_kien';
    protected $primaryKey = 'ma_loai_su_kien';

    protected $fillable = ['ten_loai', 'mo_ta'];

    public function suKien()
    {
        return $this->hasMany(SuKien::class, 'ma_loai_su_kien', 'ma_loai_su_kien');
    }
}
