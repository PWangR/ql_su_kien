<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'nguoi_dung';
    protected $primaryKey = 'ma_nguoi_dung';

    protected $fillable = [
        'ma_vai_tro',
        'ma_sinh_vien',
        'ho_ten',
        'email',
        'mat_khau',
        'trang_thai'
    ];

    protected $hidden = [
        'mat_khau'
    ];

    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    public function vaiTro()
    {
        return $this->belongsTo(VaiTro::class, 'ma_vai_tro');
    }
}
