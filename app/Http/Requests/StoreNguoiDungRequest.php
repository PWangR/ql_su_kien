<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNguoiDungRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'ma_sinh_vien' => 'required|digits:8|unique:nguoi_dung,ma_sinh_vien',
            'ho_ten'       => 'required|string|max:100',
            'email'        => 'required|email|max:100|unique:nguoi_dung,email',
            'mat_khau'     => 'required|string|min:6|confirmed',
            'so_dien_thoai' => 'nullable|string|max:15',
            'vai_tro'      => 'required|in:admin,sinh_vien',
            'trang_thai'   => 'nullable|in:hoat_dong,khong_hoat_dong,bi_khoa',
        ];
    }

    public function messages(): array
    {
        return [
            'ma_sinh_vien.digits'   => 'Mã sinh viên phải gồm đúng 8 chữ số.',
            'ma_sinh_vien.unique'   => 'Mã sinh viên này đã tồn tại.',
            'email.unique'          => 'Email này đã được đăng ký.',
            'mat_khau.confirmed'    => 'Xác nhận mật khẩu không khớp.',
        ];
    }
}
