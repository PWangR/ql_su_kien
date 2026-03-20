<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NguoiDungSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('nguoi_dung')->insert([
            [
                'vai_tro' => 'admin',
                'ma_sinh_vien' => 'ADMIN001',
                'ho_ten' => 'Quản trị viên',
                'email' => 'admin@local.test',
                'mat_khau' => Hash::make('12345678'),
                'trang_thai' => 'hoat_dong',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'vai_tro' => 'sinh_vien',
                'ma_sinh_vien' => 'SV001',
                'ho_ten' => 'Sinh viên mẫu',
                'email' => 'sv@local.test',
                'mat_khau' => Hash::make('12345678'),
                'trang_thai' => 'hoat_dong',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}