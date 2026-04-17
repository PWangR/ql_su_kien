<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuKien;
use App\Models\User;
use App\Services\RegistrationService;

class DiemDanhController extends Controller
{
    public function __construct(
        protected RegistrationService $registrationService
    ) {}

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
            'ma_su_kien' => 'required|integer',
            'loai_diem_danh' => 'nullable|string|in:dau_buoi,cuoi_buoi',
        ]);

        $mssv = $request->input('mssv');
        $maSuKien = $request->input('ma_su_kien');
        $loaiDiemDanh = $request->input('loai_diem_danh', 'dau_buoi');

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

        $result = $this->registrationService->checkInEvent($user->ma_sinh_vien, $maSuKien, $loaiDiemDanh);

        if (!$result['success']) {
            $message = $result['message'] ?? 'Không thể điểm danh sinh viên này.';

            if (str_contains($message, 'Bạn đã được điểm danh')) {
                $message = "Sinh viên này đã được điểm danh {$loaiDiemDanh} trước đó!";
            }

            return response()->json([
                'success' => false,
                'message' => $message,
            ], $result['status'] ?? 400);
        }

        $label = $loaiDiemDanh === 'dau_buoi' ? 'đầu buổi' : 'cuối buổi';
        $message = "Điểm danh {$label} thẻ sinh viên thành công!";

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $result['data'] ?? null,
        ]);
    }
}
