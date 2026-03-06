<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MauBaiDang;
use App\Models\LoaiSuKien;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates  = MauBaiDang::with(['nguoiTao', 'loaiSuKien'])->whereNull('deleted_at')->latest()->paginate(10);
        $loaiSuKien = LoaiSuKien::all();
        return view('admin.templates.index', compact('templates', 'loaiSuKien'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_mau'  => 'required|max:100',
            'noi_dung' => 'required',
        ]);

        $data = $request->only(['ten_mau', 'noi_dung', 'ma_loai_su_kien', 'dia_diem', 'so_luong_toi_da', 'diem_cong']);
        $data['ma_nguoi_tao'] = auth()->id();

        if ($request->hasFile('anh_su_kien')) {
            $data['anh_su_kien'] = $request->file('anh_su_kien')->store('su_kien', 'public');
        } elseif ($request->filled('media_duong_dan')) {
            $data['anh_su_kien'] = $request->media_duong_dan;
        }

        MauBaiDang::create($data);

        return back()->with('success', 'Đã tạo template mới!');
    }

    public function update(Request $request, $id)
    {
        $template = MauBaiDang::findOrFail($id);
        $request->validate([
            'ten_mau'  => 'required|max:100',
            'noi_dung' => 'required',
        ]);

        $data = $request->only(['ten_mau', 'noi_dung', 'ma_loai_su_kien', 'dia_diem', 'so_luong_toi_da', 'diem_cong']);

        if ($request->hasFile('anh_su_kien')) {
            if ($template->anh_su_kien && !str_starts_with($template->anh_su_kien, 'media/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($template->anh_su_kien);
            }
            $data['anh_su_kien'] = $request->file('anh_su_kien')->store('su_kien', 'public');
        } elseif ($request->filled('media_duong_dan') && $request->media_duong_dan != $template->anh_su_kien) {
            if ($template->anh_su_kien && !str_starts_with($template->anh_su_kien, 'media/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($template->anh_su_kien);
            }
            $data['anh_su_kien'] = $request->media_duong_dan;
        }

        $template->update($data);
        return back()->with('success', 'Đã cập nhật template!');
    }

    public function destroy($id)
    {
        MauBaiDang::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa template!');
    }
}
