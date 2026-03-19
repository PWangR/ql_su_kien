<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthApiController extends Controller
{
    /**
     * Login with email & password
     * POST /api/login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->mat_khau)) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng'
            ], 401);
        }

        // Check email verification
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng xác thực email trước khi đăng nhập'
            ], 403);
        }

        // Check account status
        if ($user->trang_thai !== 'hoat_dong') {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản của bạn đã bị khóa'
            ], 403);
        }

        // Generate token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->ma_nguoi_dung,
                    'name' => $user->ho_ten,
                    'email' => $user->email,
                    'vai_tro' => $user->vai_tro,
                    'ma_sinh_vien' => $user->ma_sinh_vien,
                    'so_dien_thoai' => $user->so_dien_thoai,
                    'avatar' => $user->duong_dan_anh ? asset('storage/' . $user->duong_dan_anh) : null,
                ]
            ]
        ], 200);
    }

    /**
     * Get current user profile
     * GET /api/profile
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->ma_nguoi_dung,
                'name' => $user->ho_ten,
                'email' => $user->email,
                'ma_sinh_vien' => $user->ma_sinh_vien,
                'vai_tro' => $user->vai_tro,
                'so_dien_thoai' => $user->so_dien_thoai,
                'avatar' => $user->duong_dan_anh ? asset('storage/' . $user->duong_dan_anh) : null,
                'status' => $user->trang_thai,
                'created_at' => $user->created_at,
            ]
        ], 200);
    }

    /**
     * Logout
     * POST /api/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công'
        ], 200);
    }
}
