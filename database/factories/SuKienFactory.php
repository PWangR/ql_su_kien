<?php

namespace Database\Factories;

use App\Models\SuKien;
use App\Models\User;
use App\Models\LoaiSuKien;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuKienFactory extends Factory
{
    protected $model = SuKien::class;

    public function definition(): array
    {
        return [
            'ten_su_kien' => $this->faker->sentence(),
            'mo_ta_chi_tiet' => $this->faker->paragraph(),
            'ma_loai_su_kien' => LoaiSuKien::firstOrCreate(
                ['ten_loai' => 'Hội thảo'],
                ['mo_ta' => 'Các buổi hội thảo học thuật và chuyên đề']
            )->ma_loai_su_kien,
            'thoi_gian_bat_dau' => now()->addDays(5),
            'thoi_gian_ket_thuc' => now()->addDays(5)->addHours(2),
            'dia_diem' => $this->faker->address(),
            'so_luong_toi_da' => $this->faker->numberBetween(50, 500),
            'so_luong_hien_tai' => 0,
            'diem_cong' => $this->faker->numberBetween(5, 20),
            'ma_nguoi_tao' => User::factory(),
            'ma_nguoi_to_chuc' => User::factory(),
            'trang_thai' => 'sap_to_chuc',
        ];
    }

    public function upcoming(): self
    {
        return $this->state(fn(array $attributes) => [
            'thoi_gian_bat_dau' => now()->addDays($this->faker->numberBetween(1, 30)),
            'trang_thai' => 'sap_to_chuc',
        ]);
    }

    public function ongoing(): self
    {
        return $this->state(fn(array $attributes) => [
            'thoi_gian_bat_dau' => now()->subHours(1),
            'thoi_gian_ket_thuc' => now()->addHours(2),
            'trang_thai' => 'dang_dien_ra',
        ]);
    }

    public function completed(): self
    {
        return $this->state(fn(array $attributes) => [
            'thoi_gian_bat_dau' => now()->subDays(10),
            'thoi_gian_ket_thuc' => now()->subDays(9),
            'trang_thai' => 'da_ket_thuc',
        ]);
    }
}
