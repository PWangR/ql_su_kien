<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThuVienDaPhuongTien;
use App\Models\TheAnh;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index()
    {
        $query = ThuVienDaPhuongTien::with(['suKien', 'nguoiTaiLen', 'theAnh'])
            ->libraryItems()
            ->whereNull('deleted_at');

        // Lọc theo tag nếu có
        if ($request = request()) {
            if ($request->has('tu_khoa') && $request->tu_khoa) {
                $query->where('ten_tep', 'like', '%' . $request->tu_khoa . '%');
            }
            if ($request->has('the_anh') && $request->the_anh) {
                $query->whereHas('theAnh', function ($q) use ($request) {
                    $q->where('the_anh.ma_the_anh', $request->the_anh);
                });
            }
            if ($request->has('loai_tep') && $request->loai_tep) {
                $query->where('loai_tep', $request->loai_tep);
            }
        }

        $media = $query->latest('created_at')->paginate(12);
        $tags = TheAnh::active()->ordered()->get();

        return view('admin.media.index', compact('media', 'tags'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:51200', // 50MB
            'loai_tep' => 'nullable|in:hinh_anh,video,tai_lieu,khac',
            'the_anh' => 'nullable|array',
            'the_anh.*' => 'exists:the_anh,ma_the_anh',
            'ten_the_moi' => 'nullable|string|max:100',
            'mau_sac_moi' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'ten_tep_tuy_chinh' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $loaiTep = $request->input('loai_tep') ?: $this->detectFileType($file->getMimeType());
        $path = $file->store($this->resolveFolder($loaiTep), 'public');

        // Tên hiển thị: dùng tên tùy chỉnh nếu có, fallback về tên file gốc
        $tenTuyChinhRaw = trim($request->input('ten_tep_tuy_chinh', ''));
        $extension = $file->getClientOriginalExtension();
        if ($tenTuyChinhRaw !== '') {
            // Gắn lại phần đuôi mở rộng nếu người dùng không tự thêm
            $tenHienThi = str_ends_with(strtolower($tenTuyChinhRaw), '.' . strtolower($extension))
                ? $tenTuyChinhRaw
                : $tenTuyChinhRaw . ($extension ? '.' . $extension : '');
        } else {
            $tenHienThi = $file->getClientOriginalName();
        }

        $media = ThuVienDaPhuongTien::create([
            'ma_nguoi_tai_len' => auth()->id(),
            'ma_su_kien' => $request->ma_su_kien,
            'ten_tep' => $tenHienThi,
            'duong_dan_tep' => $path,
            'loai_tep' => $loaiTep,
            'kich_thuoc' => $file->getSize(),
        ]);

        // Gán tags
        $tagIds = $request->input('the_anh', []);

        // Tạo tag mới nếu có
        if ($request->has('ten_the_moi') && $request->ten_the_moi) {
            $tagMoi = TheAnh::firstOrCreate(
                ['ten_the' => trim($request->ten_the_moi)],
                [
                    'mau_sac' => $request->input('mau_sac_moi', '#007bff'),
                    'ma_nguoi_tao' => auth()->id(),
                ]
            );
            $tagIds[] = $tagMoi->ma_the_anh;
        }

        if (!empty($tagIds)) {
            $media->theAnh()->sync(array_unique($tagIds));
        }

        return back()->with('success', 'Upload thành công!');
    }

    public function destroy($id)
    {
        $media = ThuVienDaPhuongTien::findOrFail($id);
        $media->theAnh()->detach();
        $media->forceDelete();

        return back()->with('success', 'Đã xóa file!');
    }

    public function tagsJson()
    {
        $tags = TheAnh::active()->ordered()->get();
        return response()->json($tags);
    }

    public function apiList(Request $request)
    {
        $query = ThuVienDaPhuongTien::libraryItems()->whereNull('deleted_at');

        if ($request->filled('loai_tep')) {
            $query->where('loai_tep', $request->loai_tep);
        }

        if ($request->filled('tu_khoa')) {
            $query->where('ten_tep', 'like', '%' . $request->tu_khoa . '%');
        }

        $media = $query->latest('created_at')->paginate(24);

        return response()->json([
            'data' => $media->map(fn($m) => [
                'ma_phuong_tien' => $m->ma_phuong_tien,
                'ten_tep'        => $m->ten_tep,
                'duong_dan_tep'  => $m->duong_dan_tep,
                'loai_tep'       => $m->loai_tep,
                'kich_thuoc'     => $m->kich_thuoc,
                'url'            => asset('storage/' . $m->duong_dan_tep),
            ]),
            'current_page' => $media->currentPage(),
            'last_page'    => $media->lastPage(),
            'total'        => $media->total(),
        ]);
    }

    public function tagsCreate(Request $request)
    {
        $request->validate([
            'ten_the' => 'required|string|max:100|unique:the_anh,ten_the',
            'mo_ta' => 'nullable|string|max:255',
            'mau_sac' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $tag = TheAnh::create([
            'ten_the' => $request->ten_the,
            'mo_ta' => $request->mo_ta,
            'mau_sac' => $request->input('mau_sac', '#007bff'),
            'ma_nguoi_tao' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'tag' => $tag,
            'message' => 'Tạo thẻ thành công!',
        ]);
    }

    private function detectFileType(?string $mimeType): string
    {
        if ($mimeType && str_starts_with($mimeType, 'image/')) {
            return 'hinh_anh';
        }

        if ($mimeType && str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        if (
            $mimeType && (
                str_contains($mimeType, 'pdf') ||
                str_contains($mimeType, 'document') ||
                str_contains($mimeType, 'sheet') ||
                str_contains($mimeType, 'presentation') ||
                str_contains($mimeType, 'text/')
            )
        ) {
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
