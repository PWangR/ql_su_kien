<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSuKienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $suKienId = $this->route('su_kien');

        return [
            'ten_su_kien'          => 'sometimes|string|max:200',
            'mo_ta_chi_tiet'       => 'nullable|string|max:5000',
            'ma_loai_su_kien'      => 'sometimes|integer|exists:loai_su_kien,ma_loai_su_kien',
            'thoi_gian_bat_dau'    => 'sometimes|date_format:Y-m-d H:i',
            'thoi_gian_ket_thuc'   => 'sometimes|date_format:Y-m-d H:i|after:thoi_gian_bat_dau',
            'dia_diem'             => 'nullable|string|max:200',
            'so_luong_toi_da'      => 'sometimes|integer|min:1|max:10000',
            'diem_cong'            => 'sometimes|integer|min:0|max:100',
            'trang_thai'           => 'sometimes|in:sap_to_chuc,dang_dien_ra,da_ket_thuc,huy',
            'anh_su_kien'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];
    }
}
