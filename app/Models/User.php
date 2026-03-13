<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\VaiTro;

class User extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'nguoi_dung';
    protected $primaryKey = 'ma_nguoi_dung';

    protected $fillable = [
        'vai_tro',
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

    public function isAdmin()
    {
        return $this->vai_tro === 'admin';
    }

    public function isSinhVien()
    {
        return $this->vai_tro === 'sinh_vien';
    }
}
