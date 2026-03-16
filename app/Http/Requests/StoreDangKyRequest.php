<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDangKyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ma_su_kien' => 'required|integer|exists:su_kien,ma_su_kien',
        ];
    }

    public function messages(): array
    {
        return [
            'ma_su_kien.required' => 'Sự kiện không được để trống.',
            'ma_su_kien.exists'   => 'Sự kiện không tồn tại.',
        ];
    }
}
