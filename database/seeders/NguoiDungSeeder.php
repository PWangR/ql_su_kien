<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NguoiDungSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = DB::table('vai_tro')
                        ->where('ten_vai_tro', 'admin')
                        ->first();

        $sinhVienRole = DB::table('vai_tro')
                        ->where('ten_vai_tro', 'sinh_vien')
                        ->first();

        DB::table('nguoi_dung')->insert([
            [
                'ma_vai_tro' => $adminRole->ma_vai_tro,
                'ma_sinh_vien' => 'ADMIN001',
                'ho_ten' => 'Quản trị viên',
                'email' => 'admin@local.test',
                'mat_khau' => Hash::make('12345678'),
                'trang_thai' => 'hoat_dong',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ma_vai_tro' => $sinhVienRole->ma_vai_tro,
                'ma_sinh_vien' => 'SV001',
                'ho_ten' => 'Sinh viên mẫu',
                'email' => 'sv@local.test',
                'mat_khau' => Hash::make('12345678'),
                'trang_thai' => 'hoat_dong',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}