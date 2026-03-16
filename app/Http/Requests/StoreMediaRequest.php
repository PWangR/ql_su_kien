<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'ma_su_kien'  => 'nullable|integer|exists:su_kien,ma_su_kien',
            'ten_tep'     => 'nullable|string|max:255',
            'duong_dan_tep' => 'required|file|max:102400', // 100MB
            'loai_tep'    => 'required|in:hinh_anh,video,tai_lieu,khac',
            'la_cong_khai' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'duong_dan_tep.required' => 'File không được để trống.',
            'duong_dan_tep.max'      => 'File tối đa 100MB.',
        ];
    }
}
