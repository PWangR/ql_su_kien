<?php

namespace Database\Seeders;

use App\Models\SuKien;
use App\Models\LoaiSuKien;
use Illuminate\Database\Seeder;

class CreateDemoEventSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo loại sự kiện nếu chưa tồn tại
        $loai = LoaiSuKien::first();
        if (!$loai) {
            $loai = LoaiSuKien::create([
                'ten_loai' => 'Sự kiện thường',
                'mo_ta'    => 'Sự kiện thường xuyên',
            ]);
        }

        // Tạo sự kiện demo để test QR checkin
        $demoEvent = SuKien::create([
            'ten_su_kien'              => 'Demo Event - Test QR Checkin',
            'mo_ta_chi_tiet'           => 'Đây là sự kiện demo để test QR checkin. Bạn có thể sử dụng token "test-token" để check-in.',
            'ma_loai_su_kien'          => $loai->ma_loai_su_kien,
            'thoi_gian_bat_dau'        => now()->addDays(1),
            'thoi_gian_ket_thuc'       => now()->addDays(1)->addHours(2),
            'dia_diem'                 => 'Lab A5',
            'so_luong_toi_da'          => 50,
            'so_luong_hien_tai'        => 0,
            'diem_cong'                => 10,
            'ma_nguoi_tao'             => 1, // Admin
            'trang_thai'               => 'sap_to_chuc',
            'qr_checkin_token'         => 'test-token',
            'qr_code_path'             => null,
        ]);

        \Log::info('✅ Sự kiện demo tạo thành công!');
        \Log::info('Event ID: ' . $demoEvent->ma_su_kien);
        \Log::info('QR Token: test-token');
        \Log::info('Test URL: http://localhost:8000/qr/checkin/test-token');
    }
}
