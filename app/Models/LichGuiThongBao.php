<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichGuiThongBao extends Model
{
    protected $table = 'lich_gui_thong_bao';
    protected $primaryKey = 'ma_lich_gui';

    protected $fillable = [
        'tieu_de',
        'noi_dung',
        'loai_thong_bao',
        'pham_vi',
        'kieu_gui',
        'ma_su_kien_lien_quan',
        'danh_sach_nguoi_nhan',
        'thoi_gian_gui',
        'thoi_gian_da_gui',
        'trang_thai',
        'so_nguoi_nhan',
        'loi',
        'ma_nguoi_tao',
    ];

    protected $casts = [
        'danh_sach_nguoi_nhan' => 'array',
        'thoi_gian_gui' => 'datetime',
        'thoi_gian_da_gui' => 'datetime',
    ];

    public function suKienLienQuan()
    {
        return $this->belongsTo(SuKien::class, 'ma_su_kien_lien_quan', 'ma_su_kien');
    }

    public function nguoiTao()
    {
        return $this->belongsTo(User::class, 'ma_nguoi_tao', 'ma_sinh_vien');
    }

    public function getPhamViLabelAttribute(): string
    {
        return match ($this->pham_vi) {
            'tat_ca' => 'Toàn người dùng',
            'nguoi_dang_ky_su_kien' => 'Người đăng ký sự kiện',
            'tuy_chon' => 'Người dùng tùy chọn',
            default => 'Không xác định',
        };
    }

    public function getKieuGuiLabelAttribute(): string
    {
        return match ($this->kieu_gui) {
            'ngay_lap_tuc' => 'Gửi ngay',
            'hen_gio' => 'Hẹn giờ',
            'nhac_nho_su_kien' => 'Nhắc nhở sự kiện',
            default => 'Không xác định',
        };
    }

    public function getTrangThaiLabelAttribute(): string
    {
        return match ($this->trang_thai) {
            'cho_gui' => 'Chờ gửi',
            'da_gui' => 'Đã gửi',
            'da_huy' => 'Đã hủy',
            'loi' => 'Lỗi',
            default => 'Không xác định',
        };
    }
}
