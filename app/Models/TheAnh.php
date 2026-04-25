<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TheAnh extends Model
{
    use SoftDeletes;

    protected $table = 'the_anh';
    protected $primaryKey = 'ma_the_anh';
    public $timestamps = true;

    protected $fillable = [
        'ten_the',
        'mo_ta',
        'mau_sac',
        'ma_nguoi_tao',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function thuVienDaPhuongTien()
    {
        return $this->belongsToMany(
            ThuVienDaPhuongTien::class,
            'the_anh_thu_vien',
            'ma_the_anh',
            'ma_phuong_tien',
            'ma_the_anh',
            'ma_phuong_tien'
        );
    }

    public function nguoiTao()
    {
        return $this->belongsTo(User::class, 'ma_nguoi_tao', 'ma_sinh_vien');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ten_the', 'asc');
    }

    // Attributes
    public function getStyleAttribute()
    {
        return sprintf(
            'background-color: %s; color: %s;',
            $this->mau_sac,
            $this->getContrastColor($this->mau_sac)
        );
    }

    public static function getContrastColor(string $hexColor): string
    {
        // Chuyển đổi hex thành RGB
        $hex = str_replace('#', '', $hexColor);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Tính độ sáng
        $luminance = ($r * 299 + $g * 587 + $b * 114) / 1000;

        return $luminance > 128 ? '#000000' : '#FFFFFF';
    }
}
