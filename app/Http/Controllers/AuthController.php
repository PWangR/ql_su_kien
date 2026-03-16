<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'email'      => $request->email,
            'password'   => $request->password,
            'trang_thai' => 'hoat_dong',
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $fallback = $user->isAdmin() ? route('admin.dashboard') : route('home');
            return redirect()->intended($fallback);
        }

        return back()->with('error', 'Email hoặc mật khẩu không đúng hoặc tài khoản bị khóa');
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
            'email'        => 'required|email|unique:nguoi_dung,email',
            'password'     => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'ho_ten'       => $request->ho_ten,
            'ma_sinh_vien' => $request->ma_sinh_vien,
            'email'        => $request->email,
            'vai_tro'      => 'sinh_vien',
            'mat_khau'     => Hash::make($request->password),
            'trang_thai'   => 'hoat_dong',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Đăng ký thành công!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
