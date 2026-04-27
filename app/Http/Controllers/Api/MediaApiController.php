<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ThuVienDaPhuongTien;
use App\Http\Requests\StoreMediaRequest;
use Illuminate\Support\Facades\Storage;

class MediaApiController extends Controller
{
    /**
     * Lấy danh sách media
     */
    public function index()
    {
        try {
            $media = ThuVienDaPhuongTien::orderBy('created_at', 'desc')
                ->paginate(\request('limit', 20));

            return response()->json([
                'success' => true,
                'data' => $media->items(),
                'pagination' => [
                    'current_page' => $media->currentPage(),
                    'per_page' => $media->perPage(),
                    'total' => $media->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách media',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload media
     */
    public function store(StoreMediaRequest $request)
    {
        try {
            $file = $request->file('duong_dan_tep');
            $loaiTep = $request->input('loai_tep');

            // Xác định thư mục lưu trữ
            $folder = match ($loaiTep) {
                'hinh_anh' => 'media/images',
                'video' => 'media/videos',
                'tai_lieu' => 'media/documents',
                default => 'media/other',
            };

            $filePath = $file->store($folder, 'public');

            $media = ThuVienDaPhuongTien::create([
                'ma_su_kien' => $request->input('ma_su_kien'),
                'ma_nguoi_tai_len' => auth()->id(),
                'ten_tep' => $request->input('ten_tep') ?? $file->getClientOriginalName(),
                'duong_dan_tep' => $filePath,
                'loai_tep' => $loaiTep,
                'kich_thuoc' => $file->getSize(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Upload media thành công',
                'data' => $media
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi upload media',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa media
     */
    public function destroy($id)
    {
        try {
            $media = ThuVienDaPhuongTien::find($id);

            if (!$media) {
                return response()->json([
                    'success' => false,
                    'message' => 'Media không tồn tại'
                ], 404);
            }

            // Xóa file từ storage
            Storage::disk('public')->delete($media->duong_dan_tep);

            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa media thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa media',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
