<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuKienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_su_kien'        => 'required|max:200',
            'ma_loai_su_kien'    => 'required|exists:loai_su_kien,ma_loai_su_kien',
            'thoi_gian_bat_dau'  => 'required|date',
            'thoi_gian_ket_thuc' => 'required|date|after:thoi_gian_bat_dau',
            'so_luong_toi_da'    => 'nullable|integer|min:1',
            'diem_cong'          => 'nullable|integer|min:0',
            'bo_cuc'             => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_su_kien.required'        => 'Vui lòng nhập tên sự kiện',
            'ma_loai_su_kien.required'    => 'Vui lòng chọn loại sự kiện',
            'thoi_gian_bat_dau.required'  => 'Vui lòng chọn thời gian bắt đầu',
            'thoi_gian_ket_thuc.required' => 'Vui lòng chọn thời gian kết thúc',
            'thoi_gian_ket_thuc.after'    => 'Thời gian kết thúc phải sau thời gian bắt đầu',
        ];
    }
}
