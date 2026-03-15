<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuKien;
use App\Models\LoaiSuKien;
use Illuminate\Http\Request;
use App\Traits\HasImageUpload;

class TemplateController extends Controller
{
    use HasImageUpload;
    public function index()
    {
        $templates  = SuKien::with(['nguoiTao', 'loaiSuKien'])->where('la_mau_bai_dang', true)->whereNull('deleted_at')->latest()->paginate(10);
        $loaiSuKien = LoaiSuKien::all();
        return view('admin.templates.index', compact('templates', 'loaiSuKien'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_mau'  => 'required|max:100',
            'noi_dung' => 'required',
        ]);

        $data = $request->only(['ten_mau', 'noi_dung', 'ma_loai_su_kien', 'dia_diem', 'so_luong_toi_da', 'diem_cong', 'bo_cuc']);
        $data['ma_nguoi_tao'] = auth()->id();
        $data['anh_su_kien'] = $this->handleImageUpload($request, null);

        $data['la_mau_bai_dang'] = true;
        SuKien::create($data);

        return back()->with('success', 'Đã tạo template mới!');
    }

    public function update(Request $request, $id)
    {
        $template = SuKien::where('la_mau_bai_dang', true)->findOrFail($id);
        $request->validate([
            'ten_mau'  => 'required|max:100',
            'noi_dung' => 'required',
        ]);

        $data = $request->only(['ten_mau', 'noi_dung', 'ma_loai_su_kien', 'dia_diem', 'so_luong_toi_da', 'diem_cong', 'bo_cuc']);
        $data['anh_su_kien'] = $this->handleImageUpload($request, $template);

        $template->update($data);
        return back()->with('success', 'Đã cập nhật template!');
    }

    public function destroy($id)
    {
        SuKien::where('la_mau_bai_dang', true)->findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa template!');
    }
}
