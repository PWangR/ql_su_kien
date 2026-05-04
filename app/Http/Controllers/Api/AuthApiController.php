<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;

class AuthApiController extends Controller
{
    /**
     * Login with email & password.
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
                'message' => 'Email hoac mat khau khong dung',
            ], 401);
        }

        if (!$user->isAdmin() && !$user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui long xac thuc email truoc khi dang nhap',
            ], 403);
        }

        if ($user->trang_thai !== 'hoat_dong') {
            return response()->json([
                'success' => false,
                'message' => 'Tai khoan cua ban da bi khoa',
            ], 403);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Dang nhap thanh cong',
            'data' => [
                'token' => $token,
                'user' => $this->userPayload($user),
            ],
        ]);
    }

    /**
     * Register a student account from the mobile app.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'ho_ten' => 'required|string|max:100',
            'ma_sinh_vien' => 'required|digits:8|unique:nguoi_dung,ma_sinh_vien',
            'lop' => 'required|string|max:10|regex:/^[0-9]{2,}\.[A-Za-z]{2,}-[0-9]{1,}$/',
            'email' => 'required|email|unique:nguoi_dung,email',
            'password' => 'required|string|min:8|confirmed',
            'so_dien_thoai' => 'nullable|string|max:15',
        ]);

        $user = User::create([
            'ho_ten' => $validated['ho_ten'],
            'ma_sinh_vien' => $validated['ma_sinh_vien'],
            'lop' => $validated['lop'],
            'email' => $validated['email'],
            'so_dien_thoai' => $validated['so_dien_thoai'] ?? null,
            'vai_tro' => 'sinh_vien',
            'mat_khau' => Hash::make($validated['password']),
            'trang_thai' => 'hoat_dong',
        ]);

        $this->sendVerificationEmail($user);

        return response()->json([
            'success' => true,
            'message' => 'Dang ky thanh cong. Vui long kiem tra email de xac thuc tai khoan.',
            'data' => [
                'user' => $this->userPayload($user),
            ],
        ], 201);
    }

    /**
     * Send password reset link to a registered email.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:nguoi_dung,email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Lien ket dat lai mat khau da duoc gui toi email.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Khong the gui lien ket dat lai mat khau luc nay.',
        ], 400);
    }

    /**
     * Reset password with an email reset token.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:nguoi_dung,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'mat_khau' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Dat lai mat khau thanh cong.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Token dat lai mat khau khong hop le hoac da het han.',
        ], 400);
    }

    /**
     * Resend email verification link.
     */
    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:nguoi_dung,email',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'Email da duoc xac thuc.',
            ]);
        }

        $this->sendVerificationEmail($user);

        return response()->json([
            'success' => true,
            'message' => 'Email xac thuc da duoc gui lai.',
        ]);
    }

    /**
     * Get current user profile.
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->userPayload($request->user()),
        ]);
    }

    /**
     * Logout current token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dang xuat thanh cong',
        ]);
    }

    private function sendVerificationEmail(User $user): void
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addHours(1),
            ['id' => $user->ma_sinh_vien, 'hash' => sha1($user->email)]
        );

        $user->notify(new VerifyEmailNotification($verificationUrl));
    }

    private function userPayload(User $user): array
    {
        return [
            'id' => $user->ma_sinh_vien,
            'name' => $user->ho_ten,
            'ho_ten' => $user->ho_ten,
            'email' => $user->email,
            'vai_tro' => $user->vai_tro,
            'ma_sinh_vien' => $user->ma_sinh_vien,
            'lop' => $user->lop,
            'so_dien_thoai' => $user->so_dien_thoai,
            'avatar' => $user->duong_dan_anh ? asset('storage/' . $user->duong_dan_anh) : null,
            'duong_dan_anh' => $user->duong_dan_anh,
            'status' => $user->trang_thai,
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
        ];
    }
}
