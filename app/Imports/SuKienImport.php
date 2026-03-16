<?php

namespace App\Imports;

use App\Models\SuKien;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SuKienImport implements ToModel, WithHeadingRow, WithValidation
{
    public function __construct(private int $creatorId) {}

    public function model(array $row)
    {
        return new SuKien([
            'ten_su_kien'       => $row['ten_su_kien'] ?? $row['ten'] ?? null,
            'ma_loai_su_kien'   => $row['ma_loai_su_kien'] ?? null,
            'thoi_gian_bat_dau' => $this->parseDate($row['thoi_gian_bat_dau'] ?? null),
            'thoi_gian_ket_thuc'=> $this->parseDate($row['thoi_gian_ket_thuc'] ?? null),
            'dia_diem'          => $row['dia_diem'] ?? null,
            'so_luong_toi_da'   => (int)($row['so_luong_toi_da'] ?? 0),
            'so_luong_hien_tai' => 0,
            'diem_cong'         => (int)($row['diem_cong'] ?? 0),
            'ma_nguoi_tao'      => $this->creatorId,
            'trang_thai'        => $row['trang_thai'] ?? 'sap_to_chuc',
            'bo_cuc'            => ['banner','header','info','description','gallery'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.ten_su_kien'        => 'required|string|max:200',
            '*.ma_loai_su_kien'    => 'required|integer|exists:loai_su_kien,ma_loai_su_kien',
            '*.thoi_gian_bat_dau'  => 'required',
            '*.thoi_gian_ket_thuc' => 'required',
        ];
    }

    private function parseDate($value): ?Carbon
    {
        if (!$value) return null;
        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
