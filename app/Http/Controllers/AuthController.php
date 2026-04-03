<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email này chưa được đăng ký trong hệ thống.');
        }

        if ($user->trang_thai === 'bi_khoa') {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên để được hỗ trợ.');
        }

        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'trang_thai' => 'hoat_dong',
        ])) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Mật khẩu không chính xác.');
        }

        $user = Auth::user();

        if (!$user->isAdmin() && !$user->hasVerifiedEmail()) {
            Auth::logout();

            return back()
                ->withInput($request->only('email'))
                ->with(
                    'warning',
                    'Email của bạn chưa được xác thực. Vui lòng kiểm tra hộp thư (kể cả thư mục Spam). Bạn có thể <a href="' . route('verification.notice', ['email' => $user->email]) . '" class="alert-link">gửi lại email xác thực tại đây</a>.'
                );
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
        $request->validate(
            [
                'ho_ten' => 'required|max:100',
                'ma_sinh_vien' => 'required|digits:8|unique:nguoi_dung,ma_sinh_vien',
                'lop' => 'required|max:10|regex:/^[0-9]{2,}\.[A-Za-z]{2,}-[0-9]{1,}$/',
                'email' => 'required|email|unique:nguoi_dung,email',
                'password' => 'required|min:8|confirmed',
            ],
            [
                'ma_sinh_vien.digits' => 'Mã sinh viên phải gồm đúng 8 chữ số.',
                'lop.regex' => 'Định dạng lớp không hợp lệ. Vui lòng nhập theo format: 64.CNTT-1',
            ]
        );

        $user = User::create([
            'ho_ten' => $request->ho_ten,
            'ma_sinh_vien' => $request->ma_sinh_vien,
            'lop' => $request->lop,
            'email' => $request->email,
            'vai_tro' => 'sinh_vien',
            'mat_khau' => Hash::make($request->password),
            'trang_thai' => 'hoat_dong',
        ]);

        $this->sendVerificationEmail($user);

        return redirect()
            ->route('verification.notice', ['email' => $user->email])
            ->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:nguoi_dung,email',
            ],
            [
                'email.required' => 'Vui lòng nhập email đã đăng ký.',
                'email.email' => 'Địa chỉ email không hợp lệ.',
                'email.exists' => 'Email này chưa được đăng ký trong hệ thống.',
            ]
        );

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', $this->passwordStatusMessage($status));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $this->passwordStatusMessage($status)]);
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate(
            [
                'token' => 'required',
                'email' => 'required|email|exists:nguoi_dung,email',
                'password' => 'required|min:8|confirmed',
            ],
            [
                'email.required' => 'Vui lòng nhập email của bạn.',
                'email.email' => 'Địa chỉ email không hợp lệ.',
                'email.exists' => 'Email này chưa được đăng ký trong hệ thống.',
                'password.required' => 'Vui lòng nhập mật khẩu mới.',
                'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            ]
        );

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'mat_khau' => Hash::make($password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('success', $this->passwordStatusMessage($status));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $this->passwordStatusMessage($status)]);
    }

    public function sendVerificationEmail(User $user)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addHours(1),
            ['id' => $user->ma_sinh_vien, 'hash' => sha1($user->email)]
        );

        $user->notify(new VerifyEmailNotification($verificationUrl));
    }

    public function verificationNotice()
    {
        return view('auth.verify-email');
    }

    public function verify($id, $hash)
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
            'email' => 'required|email|exists:nguoi_dung,email',
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

    private function passwordStatusMessage(string $status): string
    {
        return match ($status) {
            Password::RESET_LINK_SENT => 'Liên kết đặt lại mật khẩu đã được gửi tới email của bạn.',
            Password::PASSWORD_RESET => 'Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập bằng mật khẩu mới.',
            Password::INVALID_TOKEN => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.',
            Password::INVALID_USER => 'Không tìm thấy tài khoản tương ứng với email này.',
            Password::RESET_THROTTLED => 'Bạn vừa yêu cầu quá nhanh. Vui lòng thử lại sau ít phút.',
            default => 'Không thể xử lý yêu cầu lúc này. Vui lòng thử lại sau.',
        };
    }
}
