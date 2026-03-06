<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\VaiTro;
class VaiTroSeeder extends Seeder
{
        public function run(): void
        {
            VaiTro::create([
            'ten_vai_tro' => 'admin'
        ]);

        VaiTro::create([
            'ten_vai_tro' => 'sinh_vien'
        ]);
        DB::table('vai_tro')->insert([
            [
                'ten_vai_tro' => 'admin',
                'mo_ta' => 'Quản trị viên hệ thống',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ten_vai_tro' => 'sinh_vien',
                'mo_ta' => 'Sinh viên tham gia sự kiện',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
