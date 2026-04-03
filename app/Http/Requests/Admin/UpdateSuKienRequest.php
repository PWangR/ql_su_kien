<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSuKienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_su_kien' => 'required|max:200',
            'mo_ta_chi_tiet' => 'nullable|string|max:5000',
            'dia_diem' => 'nullable|string|max:255',
            'ma_loai_su_kien' => 'required|exists:loai_su_kien,ma_loai_su_kien',
            'thoi_gian_bat_dau' => 'required|date',
            'thoi_gian_ket_thuc' => 'required|date|after:thoi_gian_bat_dau',
            'so_luong_toi_da' => 'nullable|integer|min:1',
            'diem_cong' => 'nullable|integer|min:0',
            'trang_thai' => 'nullable|in:sap_to_chuc,dang_dien_ra,da_ket_thuc,huy',
            'anh_su_kien' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'selected_media_ids' => 'nullable|array',
            'selected_media_ids.*' => 'integer|exists:thu_vien_da_phuong_tien,ma_phuong_tien',
            'bo_cuc' => 'nullable|array',
            'bo_cuc.*' => 'in:banner,header,info,description,gallery',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_su_kien.required' => 'Vui long nhap ten su kien',
            'ma_loai_su_kien.required' => 'Vui long chon loai su kien',
            'thoi_gian_bat_dau.required' => 'Vui long chon thoi gian bat dau',
            'thoi_gian_ket_thuc.required' => 'Vui long chon thoi gian ket thuc',
            'thoi_gian_ket_thuc.after' => 'Thoi gian ket thuc phai sau thoi gian bat dau',
            'gallery.*.image' => 'Tat ca anh trong gallery phai la file anh hop le.',
            'selected_media_ids.*.exists' => 'Mot anh trong thu vien khong con ton tai.',
        ];
    }
}
