<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiSuKien;
use App\Models\MauBaiDang;
use App\Support\EventTemplateSupport;
use App\Traits\HasImageUpload;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    use HasImageUpload;

    public function index()
    {
        $templates = MauBaiDang::with(['nguoiTao', 'loaiSuKien'])
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(10);

        $loaiSuKien = LoaiSuKien::all();
        $moduleCatalog = EventTemplateSupport::moduleCatalog();

        return view('admin.templates.index', compact('templates', 'loaiSuKien', 'moduleCatalog'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['ma_nguoi_tao'] = auth()->id();
        $data['bo_cuc'] = EventTemplateSupport::normalizeTemplateModules($request->input('bo_cuc_json'));
        $data['anh_su_kien'] = $this->handleImageUpload($request, null);

        MauBaiDang::create($data);

        return back()->with('success', 'Đã tạo mẫu bài đăng mới.');
    }

    public function update(Request $request, $id)
    {
        $template = MauBaiDang::findOrFail($id);

        $data = $this->validatedData($request);
        $data['bo_cuc'] = EventTemplateSupport::normalizeTemplateModules($request->input('bo_cuc_json'));
        $data['anh_su_kien'] = $this->handleImageUpload($request, $template);

        $template->update($data);

        return back()->with('success', 'Đã cập nhật mẫu bài đăng.');
    }

    public function destroy($id)
    {
        MauBaiDang::findOrFail($id)->delete();

        return back()->with('success', 'Đã xóa mẫu bài đăng.');
    }

    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'ten_mau' => 'required|string|max:150',
            'noi_dung' => 'nullable|string',
            'ma_loai_su_kien' => 'nullable|integer|exists:loai_su_kien,ma_loai_su_kien',
            'dia_diem' => 'nullable|string|max:200',
            'so_luong_toi_da' => 'nullable|integer|min:0',
            'diem_cong' => 'nullable|integer|min:0',
            'anh_su_kien' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'bo_cuc_json' => 'required|string',
        ]);
    }
}
