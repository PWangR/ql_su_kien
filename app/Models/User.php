<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use App\Models\VaiTro;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $table = 'nguoi_dung';
    protected $primaryKey = 'ma_nguoi_dung';

    protected $fillable = [
        'vai_tro',
        'ma_sinh_vien',
        'ho_ten',
        'email',
        'mat_khau',
        'so_dien_thoai',
        'duong_dan_anh',
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
        return in_array($this->vai_tro, ['admin', 'super_admin']);
    }

    public function isSinhVien()
    {
        return $this->vai_tro === 'sinh_vien';
    }

    public function hasRole($role)
    {
        return $this->vai_tro === $role;
    }
}
