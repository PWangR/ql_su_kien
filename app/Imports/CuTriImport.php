<?php

namespace App\Imports;

use App\Models\CuTri;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class CuTriImport implements ToModel, WithHeadingRow
{
    protected $maBauCu;
    protected $addedIds = [];

    public function __construct($maBauCu)
    {
        $this->maBauCu = $maBauCu;
    }

    public function model(array $row)
    {
        if (!isset($row['ma_sinh_vien']) && !isset($row['email'])) {
            return null; // Ignore rows without identifer
        }

        $user = null;

        // Try mapping by ma_sinh_vien first
        if (isset($row['ma_sinh_vien'])) {
            $user = User::where('ma_sinh_vien', $row['ma_sinh_vien'])
                        ->where('vai_tro', 'sinh_vien')
                        ->first();
        }

        // Fallback to email if ma_sinh_vien is not found or not provided
        if (!$user && isset($row['email'])) {
            $user = User::where('email', $row['email'])
                        ->where('vai_tro', 'sinh_vien')
                        ->first();
        }

        if ($user) {
            // Prevent duplicate insertion in DB
            $existing = CuTri::where('ma_bau_cu', $this->maBauCu)
                             ->where('ma_sinh_vien', $user->ma_sinh_vien)
                             ->exists();
            if (!$existing && !in_array($user->ma_sinh_vien, $this->addedIds, true)) {
                $this->addedIds[] = $user->ma_sinh_vien;
                return new CuTri([
                    'ma_bau_cu'     => $this->maBauCu,
                    'ma_sinh_vien' => $user->ma_sinh_vien,
                ]);
            }
        }

        return null;
    }
}
