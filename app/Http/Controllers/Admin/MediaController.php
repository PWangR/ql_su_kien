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
            ->libraryItems()
            ->whereNull('deleted_at')
            ->latest('created_at')
            ->paginate(10);

        return view('admin.media.index', compact('media'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file'      => 'required|file|max:51200', // 50MB
            'loai_tep'  => 'nullable|in:hinh_anh,video,tai_lieu,khac',
        ]);

        $file     = $request->file('file');
        $loaiTep  = $request->input('loai_tep') ?: $this->detectFileType($file->getMimeType());
        $path     = $file->store($this->resolveFolder($loaiTep), 'public');

        ThuVienDaPhuongTien::create([
            'ma_nguoi_tai_len' => auth()->id(),
            'ma_su_kien'       => $request->ma_su_kien,
            'ten_tep'          => $file->getClientOriginalName(),
            'duong_dan_tep'    => $path,
            'loai_tep'         => $loaiTep,
            'kich_thuoc'       => $file->getSize(),
            'la_cong_khai'     => $request->has('la_cong_khai'),
        ]);

        return back()->with('success', 'Upload thành công!');
    }

    public function destroy($id)
    {
        $media = ThuVienDaPhuongTien::findOrFail($id);
        $media->forceDelete();

        return back()->with('success', 'Đã xóa file!');
    }

    private function detectFileType(?string $mimeType): string
    {
        if ($mimeType && str_starts_with($mimeType, 'image/')) {
            return 'hinh_anh';
        }

        if ($mimeType && str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        if ($mimeType && (
            str_contains($mimeType, 'pdf') ||
            str_contains($mimeType, 'document') ||
            str_contains($mimeType, 'sheet') ||
            str_contains($mimeType, 'presentation') ||
            str_contains($mimeType, 'text/')
        )) {
            return 'tai_lieu';
        }

        return 'khac';
    }

    private function resolveFolder(string $loaiTep): string
    {
        return match ($loaiTep) {
            'hinh_anh' => 'media/images',
            'video' => 'media/videos',
            'tai_lieu' => 'media/documents',
            default => 'media/other',
        };
    }
}
