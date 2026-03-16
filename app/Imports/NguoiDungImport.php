<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class NguoiDungImport implements ToModel, WithHeadingRow, WithValidation
{
    public function __construct(private string $defaultPassword = '12345678') {}

    public function model(array $row)
    {
        return new User([
            'ho_ten'       => $row['ho_ten'] ?? null,
            'ma_sinh_vien' => $row['ma_sinh_vien'] ?? null,
            'email'        => $row['email'] ?? null,
            'vai_tro'      => $row['vai_tro'] ?? 'sinh_vien',
            'so_dien_thoai'=> $row['so_dien_thoai'] ?? null,
            'trang_thai'   => $row['trang_thai'] ?? 'hoat_dong',
            'mat_khau'     => Hash::make($row['mat_khau'] ?? $this->defaultPassword),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.ho_ten'       => 'required|max:100',
            '*.ma_sinh_vien' => 'required|max:20|unique:nguoi_dung,ma_sinh_vien',
            '*.email'        => 'required|email|unique:nguoi_dung,email',
            '*.vai_tro'      => 'nullable|in:admin,sinh_vien',
        ];
    }
}
