<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'ma_sinh_vien' => $this->faker->unique()->numerify('##########'),
            'ho_ten' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'mat_khau' => static::$password ??= Hash::make('password'),
            'so_dien_thoai' => $this->faker->numerify('09########'),
            'vai_tro' => 'sinh_vien',
            'trang_thai' => 'hoat_dong',
        ];
    }

    public function admin(): self
    {
        return $this->state(fn(array $attributes) => [
            'vai_tro' => 'admin',
        ]);
    }

    public function student(): self
    {
        return $this->state(fn(array $attributes) => [
            'vai_tro' => 'sinh_vien',
        ]);
    }

    public function locked(): self
    {
        return $this->state(fn(array $attributes) => [
            'trang_thai' => 'bi_khoa',
        ]);
    }

    public function inactive(): self
    {
        return $this->state(fn(array $attributes) => [
            'trang_thai' => 'khong_hoat_dong',
        ]);
    }
}
