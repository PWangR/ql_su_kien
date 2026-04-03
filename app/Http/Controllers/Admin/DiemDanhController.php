<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuKien;
use App\Models\User;
use App\Models\DangKy;

class DiemDanhController extends Controller
{
    /**
     * Màn hình chọn sự kiện để sinh QR Code điểm danh (Admin -> User quét)
     */
    public function index()
    {
        // Lấy danh sách sự kiện đang diễn ra
        $danhSachSuKien = SuKien::where('trang_thai', '!=', 'huy')
            ->where('thoi_gian_bat_dau', '<=', now())
            ->where('thoi_gian_ket_thuc', '>=', now())
            ->orderBy('thoi_gian_bat_dau', 'desc')
            ->get();

        return view('admin.diem-danh.index', [
            'danhSachSuKien' => $danhSachSuKien
        ]);
    }

    /**
     * Màn hình máy quét QR Code (Admin quét User)
     */
    public function scanner()
    {
        return view('admin.diem-danh.scanner');
    }

    /**
     * Xử lý kết quả quét mã từ Admin (POST API nội bộ)
     */
    public function processScanner(Request $request)
    {
        $request->validate([
            'mssv' => 'required|digits:8',
            'ma_su_kien' => 'required|integer'
        ]);

        $mssv = $request->input('mssv');
        $maSuKien = $request->input('ma_su_kien');

        $user = User::where('ma_sinh_vien', $mssv)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sinh viên có mã ' . $mssv
            ], 404);
        }

        $suKien = SuKien::find($maSuKien);
        if (!$suKien) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sự kiện'
            ], 404);
        }

        if ($suKien->trang_thai === 'huy') {
            return response()->json([
                'success' => false,
                'message' => 'Sự kiện này đã bị hủy.'
            ], 400);
        }

        $dangKy = DangKy::withTrashed()
            ->where('ma_sinh_vien', $user->ma_sinh_vien)
            ->where('ma_su_kien', $maSuKien)
            ->first();

        if (!$dangKy) {
            // Xem sự kiện có chỗ trống không
            if ($suKien->so_luong_toi_da > 0 && $suKien->so_luong_hien_tai >= $suKien->so_luong_toi_da) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sự kiện đã đủ số lượng, không thể điểm danh thêm.'
                ], 400);
            }

            // Tạo mới & Điểm danh luôn
            DangKy::create([
                'ma_sinh_vien' => $user->ma_sinh_vien,
                'ma_su_kien' => $maSuKien,
                'trang_thai_tham_gia' => 'da_tham_gia'
            ]);
            $suKien->increment('so_luong_hien_tai');

            return response()->json([
                'success' => true,
                'message' => 'Điểm danh thẻ sinh viên thành công!'
            ]);
        }

        if ($dangKy->trashed()) {
            $dangKy->restore();
        }

        if ($dangKy->trang_thai_tham_gia === 'da_tham_gia') {
            return response()->json([
                'success' => false,
                'message' => 'Sinh viên này đã được điểm danh trước đó!'
            ], 400);
        }

        $dangKy->update(['trang_thai_tham_gia' => 'da_tham_gia']);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật điểm danh thành công!'
        ]);
    }
}
