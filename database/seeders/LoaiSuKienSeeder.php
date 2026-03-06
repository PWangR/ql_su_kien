<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoaiSuKienSeeder extends Seeder
{
    public function run(): void
    {
        $loai = [
            ['ten_loai' => 'Hội thảo', 'mo_ta' => 'Các buổi hội thảo học thuật và chuyên đề'],
            ['ten_loai' => 'Seminar', 'mo_ta' => 'Seminar nghiên cứu và trao đổi kiến thức'],
            ['ten_loai' => 'Câu lạc bộ', 'mo_ta' => 'Hoạt động câu lạc bộ sinh viên'],
            ['ten_loai' => 'Ngoại khóa', 'mo_ta' => 'Hoạt động ngoại khóa và tình nguyện'],
            ['ten_loai' => 'Thi đấu', 'mo_ta' => 'Cuộc thi học thuật và kỹ năng'],
            ['ten_loai' => 'Workshop', 'mo_ta' => 'Buổi thực hành kỹ năng thực tế'],
        ];

        foreach ($loai as $l) {
            DB::table('loai_su_kien')->insertOrIgnore([
                'ten_loai' => $l['ten_loai'],
                'mo_ta'    => $l['mo_ta'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
