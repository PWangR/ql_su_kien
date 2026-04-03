<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    protected $table = 'thong_bao';
    protected $primaryKey = 'ma_thong_bao';

    protected $fillable = [
        'ma_sinh_vien', 'tieu_de', 'noi_dung',
        'da_doc', 'loai_thong_bao', 'ma_su_kien_lien_quan'
    ];

    protected $casts = [
        'da_doc'        => 'boolean',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'ma_sinh_vien', 'ma_sinh_vien');
    }

    public function suKienLienQuan()
    {
        return $this->belongsTo(SuKien::class, 'ma_su_kien_lien_quan', 'ma_su_kien');
    }

    public function getLoaiLabelAttribute()
    {
        return match($this->loai_thong_bao) {
            'he_thong'         => 'Hệ thống',
            'nhac_nho_su_kien' => 'Nhắc nhở sự kiện',
            'cap_nhat_diem'    => 'Cập nhật điểm',
            default            => 'Khác',
        };
    }

    public function getLoaiIconAttribute()
    {
        return match($this->loai_thong_bao) {
            'he_thong'         => 'bi-gear-fill',
            'nhac_nho_su_kien' => 'bi-calendar-event-fill',
            'cap_nhat_diem'    => 'bi-star-fill',
            default            => 'bi-bell-fill',
        };
    }
}
