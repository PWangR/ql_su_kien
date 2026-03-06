<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HinhAnhSuKien extends Model
{
    use HasFactory;

    protected $table = 'hinh_anh_su_kien';
    protected $primaryKey = 'ma_hinh_anh';

    protected $fillable = [
        'ma_su_kien',
        'duong_dan',
    ];

    public function suKien()
    {
        return $this->belongsTo(SuKien::class, 'ma_su_kien', 'ma_su_kien');
    }
}
