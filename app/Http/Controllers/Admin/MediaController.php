<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThuVienDaPhuongTien;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index()
    {
        $media = ThuVienDaPhuongTien::with(['suKien', 'nguoiTaiLen'])
            ->whereNull('deleted_at')
            ->latest('created_at')
            ->paginate(10);

        return view('admin.media.index', compact('media'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file'      => 'required|file|max:51200', // 50MB
            'loai_tep'  => 'required|in:hinh_anh,video,tai_lieu,khac',
        ]);

        $file     = $request->file('file');
        $path     = $file->store('media', 'public');

        ThuVienDaPhuongTien::create([
            'ma_nguoi_tai_len' => auth()->id(),
            'ma_su_kien'       => $request->ma_su_kien,
            'ten_tep'          => $file->getClientOriginalName(),
            'duong_dan_tep'    => $path,
            'loai_tep'         => $request->loai_tep,
            'kich_thuoc'       => $file->getSize(),
            'la_cong_khai'     => $request->has('la_cong_khai'),
        ]);

        return back()->with('success', 'Upload thành công!');
    }

    public function destroy($id)
    {
        $media = ThuVienDaPhuongTien::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($media->duong_dan_tep);
        $media->delete();
        return back()->with('success', 'Đã xóa file!');
    }
}
