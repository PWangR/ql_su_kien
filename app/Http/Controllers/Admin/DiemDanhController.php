<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuKien;
use App\Models\User;
use App\Services\RegistrationService;
use Illuminate\Http\Request;

class DiemDanhController extends Controller
{
    public function __construct(
        protected RegistrationService $registrationService
    ) {}

    /**
     * Man hinh chon su kien de sinh QR Code diem danh (Admin -> User quet).
     */
    public function index()
    {
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
     * Man hinh may quet QR Code (Admin quet User).
     */
    public function scanner()
    {
        return view('admin.diem-danh.scanner');
    }

    /**
     * Xu ly ket qua quet ma tu Admin (POST API noi bo).
     */
    public function processScanner(Request $request)
    {
        $request->validate([
            'mssv' => 'required|digits:8',
            'ma_su_kien' => 'required|integer',
        ]);

        $mssv = $request->input('mssv');
        $maSuKien = $request->input('ma_su_kien');

        $user = User::where('ma_sinh_vien', $mssv)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Khong tim thay sinh vien co ma ' . $mssv
            ], 404);
        }

        $result = $this->registrationService->adminCheckInStudent($user->ma_sinh_vien, $maSuKien);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Khong the diem danh sinh vien nay.',
                'data' => $result['data'] ?? null,
            ], $result['status'] ?? 400);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'] ?? 'Diem danh sinh vien thanh cong!',
            'data' => $result['data'] ?? null,
        ]);
    }
}
