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
            'ten_su_kien' => 'required|max:200',
            'mo_ta_chi_tiet' => 'nullable|string|max:5000',
            'dia_diem' => 'required|string|max:255',
            'ma_loai_su_kien' => 'required|exists:loai_su_kien,ma_loai_su_kien',
            'thoi_gian_bat_dau' => 'required|date',
            'thoi_gian_ket_thuc' => 'required|date|after:thoi_gian_bat_dau',
            'so_luong_toi_da' => 'required|integer|min:1',
            'diem_cong' => 'required|integer|min:0',
            'module_schema_json' => 'required|string',
            'module_content' => 'nullable|array',
            'module_banner' => 'nullable|array',
            'module_banner.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'module_gallery' => 'nullable|array',
            'module_gallery.*' => 'nullable|array',
            'module_gallery.*.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $diaDiem = $this->input('dia_diem');
            $batDau = $this->input('thoi_gian_bat_dau');
            $ketThuc = $this->input('thoi_gian_ket_thuc');

            if ($diaDiem && $batDau && $ketThuc) {
                // Check collision
                $conflict = \App\Models\SuKien::where('dia_diem', 'like', '%' . $diaDiem . '%')
                    ->where(function ($query) use ($batDau, $ketThuc) {
                        $query->whereBetween('thoi_gian_bat_dau', [$batDau, $ketThuc])
                            ->orWhereBetween('thoi_gian_ket_thuc', [$batDau, $ketThuc])
                            ->orWhere(function ($q) use ($batDau, $ketThuc) {
                                $q->where('thoi_gian_bat_dau', '<=', $batDau)
                                  ->where('thoi_gian_ket_thuc', '>=', $ketThuc);
                            });
                    })
                    ->where('trang_thai', '!=', 'huy')
                    ->first();

                if ($conflict && $this->input('force_create') !== '1') {
                    $validator->errors()->add('dia_diem', 'Trùng lịch với sự kiện khác: "' . $conflict->ten_su_kien . '" diễn ra tại địa điểm này từ ' . \Carbon\Carbon::parse($conflict->thoi_gian_bat_dau)->format('H:i d/m/Y') . ' đến ' . \Carbon\Carbon::parse($conflict->thoi_gian_ket_thuc)->format('H:i d/m/Y') . '.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'ten_su_kien.required' => 'Vui lòng nhập tên sự kiện.',
            'ten_su_kien.max' => 'Tên sự kiện không được vượt quá 200 ký tự.',
            'mo_ta_chi_tiet.string' => 'Nội dung mô tả chi tiết phải là dạng chuỗi văn bản.',
            'mo_ta_chi_tiet.max' => 'Nội dung mô tả chi tiết không được vượt quá 5000 ký tự.',
            'dia_diem.required' => 'Vui lòng nhập địa điểm tổ chức.',
            'dia_diem.string' => 'Địa điểm tổ chức phải là chuỗi ký tự.',
            'dia_diem.max' => 'Tên địa điểm không được vượt quá 255 ký tự.',
            'ma_loai_su_kien.required' => 'Vui lòng chọn phân loại cho sự kiện.',
            'ma_loai_su_kien.exists' => 'Loại sự kiện đã chọn không hợp lệ hoặc đã bị xóa.',
            'thoi_gian_bat_dau.required' => 'Vui lòng xác định thời gian bắt đầu.',
            'thoi_gian_bat_dau.date' => 'Định dạng ngày/giờ bắt đầu không hợp lệ.',
            'thoi_gian_ket_thuc.required' => 'Vui lòng xác định thời gian kết thúc.',
            'thoi_gian_ket_thuc.date' => 'Định dạng ngày/giờ kết thúc không hợp lệ.',
            'thoi_gian_ket_thuc.after' => 'Thời gian kết thúc phải diễn ra SAU thời gian bắt đầu sự kiện.',
            'so_luong_toi_da.required' => 'Vui lòng nhập số lượng người tham gia tối đa.',
            'so_luong_toi_da.integer' => 'Số lượng người tham gia tối đa phải là một số nguyên dương.',
            'so_luong_toi_da.min' => 'Số lượng người tham gia tối đa không được nhỏ hơn 1.',
            'diem_cong.required' => 'Vui lòng nhập điểm cộng rèn luyện.',
            'diem_cong.integer' => 'Điểm cộng rèn luyện phải là một số nguyên dương.',
            'diem_cong.min' => 'Điểm cộng rèn luyện không được là số âm.',
            'module_schema_json.required' => 'Thiếu cấu trúc module của mẫu bài đăng.',
            'module_banner.array' => 'Dữ liệu ảnh banner không hợp lệ.',
            'module_banner.*.image' => 'Ảnh banner phải là tệp hình ảnh hợp lệ.',
            'module_banner.*.mimes' => 'Ảnh banner chỉ hỗ trợ jpeg, png, jpg, gif, webp.',
            'module_banner.*.max' => 'Dung lượng mỗi ảnh banner không được vượt quá 5MB.',
            'module_gallery.array' => 'Dữ liệu gallery theo module không hợp lệ.',
            'module_gallery.*.*.image' => 'Ảnh trong gallery module phải là tệp hình ảnh hợp lệ.',
            'module_gallery.*.*.mimes' => 'Ảnh trong gallery module chỉ hỗ trợ jpeg, png, jpg, gif, webp.',
            'module_gallery.*.*.max' => 'Dung lượng mỗi ảnh trong gallery module không được vượt quá 5MB.',
        ];
    }
}
