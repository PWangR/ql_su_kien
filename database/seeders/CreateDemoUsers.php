<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateDemoUsers extends Seeder
{
    public function run()
    {
        // Tài khoản Admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'ho_ten' => 'Quản Trị Viên',
                'ma_sinh_vien' => '00000000',
                'vai_tro' => 'admin',
                'mat_khau' => Hash::make('password'),
                'trang_thai' => 'hoat_dong',
                'so_dien_thoai' => '0123456789',
                'lop' => '64.CNTT-1',
                'email_verified_at' => now(),
            ]
        );

        // Tài khoản Sinh Viên
        User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'ho_ten' => 'Nguyễn Văn A',
                'ma_sinh_vien' => '64130001',
                'vai_tro' => 'sinh_vien',
                'mat_khau' => Hash::make('password'),
                'trang_thai' => 'hoat_dong',
                'so_dien_thoai' => '0987654321',
                'lop' => '64.CNTT-1',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Đã tạo tài khoản demo!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Student: student@example.com / password');
    }
}
