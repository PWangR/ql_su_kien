<?php

namespace Database\Factories;

use App\Models\DangKy;
use App\Models\User;
use App\Models\SuKien;
use Illuminate\Database\Eloquent\Factories\Factory;

class DangKyFactory extends Factory
{
    protected $model = DangKy::class;

    public function definition(): array
    {
        return [
            'ma_nguoi_dung' => User::factory(),
            'ma_su_kien' => SuKien::factory(),
            'trang_thai_tham_gia' => 'da_dang_ky',
        ];
    }

    public function participated(): self
    {
        return $this->state(fn(array $attributes) => [
            'trang_thai_tham_gia' => 'da_tham_gia',
        ]);
    }

    public function absent(): self
    {
        return $this->state(fn(array $attributes) => [
            'trang_thai_tham_gia' => 'vang_mat',
        ]);
    }
}
