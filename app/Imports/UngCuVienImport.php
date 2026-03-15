<?php

namespace App\Imports;

use App\Models\UngCuVien;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UngCuVienImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $maBauCu;

    public function __construct($maBauCu)
    {
        $this->maBauCu = $maBauCu;
    }

    public function model(array $row)
    {
        // Require ho_ten and ma_sinh_vien at a minimum to create an entry
        if (!isset($row['ho_ten']) || !isset($row['ma_sinh_vien'])) {
            return null;
        }

        return new UngCuVien([
            'ma_bau_cu'        => $this->maBauCu,
            'ho_ten'           => $row['ho_ten'] ?? null,
            'lop'              => $row['lop'] ?? null,
            'ma_sinh_vien'     => $row['ma_sinh_vien'] ?? null,
            'diem_trung_binh'  => $row['diem_trung_binh'] ?? null,
            'diem_ren_luyen'   => $row['diem_ren_luyen'] ?? null,
            'gioi_thieu'       => $row['gioi_thieu'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'ho_ten' => 'required',
            'ma_sinh_vien' => 'required',
        ];
    }
}
