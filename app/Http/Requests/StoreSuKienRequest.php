<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuKienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'ten_su_kien'          => 'required|string|max:200',
            'mo_ta_chi_tiet'       => 'nullable|string|max:5000',
            'ma_loai_su_kien'      => 'required|integer|exists:loai_su_kien,ma_loai_su_kien',
            'thoi_gian_bat_dau'    => 'required|date_format:Y-m-d H:i|after_or_equal:now',
            'thoi_gian_ket_thuc'   => 'required|date_format:Y-m-d H:i|after:thoi_gian_bat_dau',
            'dia_diem'             => 'nullable|string|max:200',
            'so_luong_toi_da'      => 'required|integer|min:1|max:10000',
            'diem_cong'            => 'required|integer|min:0|max:100',
            'anh_su_kien'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_su_kien.required'        => 'Tên sự kiện không được để trống.',
            'ma_loai_su_kien.required'    => 'Loại sự kiện không được để trống.',
            'ma_loai_su_kien.exists'      => 'Loại sự kiện không tồn tại.',
            'thoi_gian_bat_dau.required'  => 'Thời gian bắt đầu không được để trống.',
            'thoi_gian_bat_dau.after_or_equal' => 'Thời gian bắt đầu phải từ bây giờ trở đi.',
            'thoi_gian_ket_thuc.after'    => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
            'so_luong_toi_da.required'    => 'Số lượng tối đa không được để trống.',
            'anh_su_kien.image'           => 'File upload phải là ảnh.',
            'anh_su_kien.mimes'           => 'Ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
        ];
    }
}
