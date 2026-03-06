<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'ho_ten'        => 'required|max:100',
            'so_dien_thoai' => 'nullable|max:15',
            'mat_khau_moi'  => 'nullable|min:8|confirmed',
        ], [
            'ho_ten.required'       => 'Vui lòng nhập họ tên',
            'mat_khau_moi.min'      => 'Mật khẩu mới ít nhất 8 ký tự',
            'mat_khau_moi.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        $data = [
            'ho_ten'        => $request->ho_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
        ];

        // Đổi mật khẩu
        if ($request->filled('mat_khau_moi')) {
            if (!Hash::check($request->mat_khau_cu, $user->mat_khau)) {
                return back()->with('error', 'Mật khẩu hiện tại không đúng!');
            }
            $data['mat_khau'] = Hash::make($request->mat_khau_moi);
        }

        // Upload avatar
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['duong_dan_anh'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Cập nhật hồ sơ thành công!');
    }
}
