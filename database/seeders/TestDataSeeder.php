<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LichSuDiem;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 4 sinh viên test
        $students = [
            [
                'ho_ten' => 'Trần Văn A',
                'email' => 'tranvana@ntu.edu.vn',
                'mat_khau' => Hash::make('password123'),
                'vai_tro' => 'sinh_vien',
                'lop' => '64.CNTT-1',
                'ma_sinh_vien' => '62131001',
                'so_dien_thoai' => '0123456789',
                'trang_thai' => 'hoat_dong',
            ],
            [
                'ho_ten' => 'Nguyễn Thị B',
                'email' => 'nguyenthib@ntu.edu.vn',
                'mat_khau' => Hash::make('password123'),
                'vai_tro' => 'sinh_vien',
                'lop' => '64.CNTT-1',
                'ma_sinh_vien' => '62131002',
                'so_dien_thoai' => '0987654321',
                'trang_thai' => 'hoat_dong',
            ],
            [
                'ho_ten' => 'Hoàng Văn C',
                'email' => 'hoangvanc@ntu.edu.vn',
                'mat_khau' => Hash::make('password123'),
                'vai_tro' => 'sinh_vien',
                'lop' => '64.CNTT-2',
                'ma_sinh_vien' => '62131003',
                'so_dien_thoai' => '0111111111',
                'trang_thai' => 'hoat_dong',
            ],
            [
                'ho_ten' => 'Lê Thị D',
                'email' => 'lethid@ntu.edu.vn',
                'mat_khau' => Hash::make('password123'),
                'vai_tro' => 'sinh_vien',
                'lop' => '64.CNTT-2',
                'ma_sinh_vien' => '62131004',
                'so_dien_thoai' => '0222222222',
                'trang_thai' => 'hoat_dong',
            ],
        ];

        // Insert sinh viên
        foreach ($students as $student) {
            User::updateOrCreate(
                ['ma_sinh_vien' => $student['ma_sinh_vien']],
                $student
            );
        }

        // Tạo điểm test cho sinh viên
        $pointData = [
            '62131001' => [100, 85, 90, 120],  // 4 records điểm
            '62131002' => [95, 110, 88],       // 3 records
            '62131003' => [150, 70, 95],       // 3 records
            '62131004' => [80, 100, 105, 90], // 4 records
        ];

        $dates = [
            '2026-01-15',
            '2026-02-10',
            '2026-02-28',
            '2026-03-15',
        ];

        foreach ($pointData as $userId => $points) {
            foreach ($points as $index => $diem) {
                $dateKey = $index % count($dates);
                LichSuDiem::updateOrCreate(
                    [
                        'ma_sinh_vien' => $userId,
                        'thoi_gian_ghi_nhan' => $dates[$dateKey],
                    ],
                    [
                        'diem' => $diem,
                        'thoi_gian_ghi_nhan' => $dates[$dateKey],
                    ]
                );
            }
        }

        echo "✅ Test data created successfully!\n";
    }
}
