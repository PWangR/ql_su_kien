<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');

        // Bước 1: Kiểm tra email có tồn tại không
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email này chưa được đăng ký trong hệ thống.');
        }

        // Bước 2: Kiểm tra tài khoản có bị khóa không
        if ($user->trang_thai === 'bi_khoa') {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên để được hỗ trợ.');
        }

        // Bước 3: Kiểm tra mật khẩu và đăng nhập
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password, 'trang_thai' => 'hoat_dong'])) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Mật khẩu không chính xác.');
        }

        $user = Auth::user();

        // Admin bỏ qua xác thực email, sinh viên phải xác thực
        if (!$user->isAdmin() && !$user->hasVerifiedEmail()) {
            Auth::logout();
            return back()
                ->withInput($request->only('email'))
                ->with('warning', 'Email của bạn chưa được xác thực. Vui lòng kiểm tra hộp thư (kể cả thư mục Spam). Bạn có thể <a href="' . route('verification.resend.form') . '" class="alert-link">gửi lại email xác thực tại đây</a>.');
        }

        $request->session()->regenerate();
        $fallback = $user->isAdmin() ? route('admin.dashboard') : route('home');
        return redirect()->intended($fallback);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'ho_ten'       => 'required|max:100',
            'ma_sinh_vien' => 'required|max:20|unique:nguoi_dung,ma_sinh_vien',
            'lop'          => 'required|max:10|regex:/^[0-9]{2,}\.[A-Za-z]{2,}-[0-9]{1,}$/', // ← MỚI
            'email'        => 'required|email|unique:nguoi_dung,email',
            'password'     => 'required|min:8|confirmed',
        ], [
            'lop.regex' => 'Định dạng lớp không hợp lệ. Vui lòng nhập theo format: 64.CNTT-1',
        ]);

        $user = User::create([
            'ho_ten'       => $request->ho_ten,
            'ma_sinh_vien' => $request->ma_sinh_vien,
            'lop'          => $request->lop,  // ← MỚI
            'email'        => $request->email,
            'vai_tro'      => 'sinh_vien',
            'mat_khau'     => Hash::make($request->password),
            'trang_thai'   => 'hoat_dong',
        ]);

        // Send verification email
        $this->sendVerificationEmail($user);

        return redirect()->route('verification.notice')->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.');
    }

    public function sendVerificationEmail(User $user)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addHours(1),
            ['id' => $user->ma_nguoi_dung, 'hash' => sha1($user->email)]
        );

        $user->notify(new VerifyEmailNotification($verificationUrl));
    }

    public function verificationNotice()
    {
        return view('auth.verify-email');
    }

    public function verify($id, $hash, Request $request)
    {
        $user = User::findOrFail($id);

        if (sha1($user->email) !== $hash) {
            return redirect()->route('login')->with('error', 'Email xác thực không hợp lệ');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Email của bạn đã được xác thực rồi');
        }

        if ($user->markEmailAsVerified()) {
            event(new Registered($user));
        }

        return redirect()->route('login')->with('success', 'Email của bạn đã được xác thực thành công. Vui lòng đăng nhập!');
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:nguoi_dung,email'
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', 'Email của bạn đã được xác thực rồi');
        }

        $this->sendVerificationEmail($user);

        return back()->with('success', 'Email xác thực đã được gửi lại. Vui lòng kiểm tra email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
