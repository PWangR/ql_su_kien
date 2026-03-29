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
            'lop'           => 'required|max:10|regex:/^[0-9]{2,}\.[A-Za-z]{2,}-[0-9]{1,}$/', // ← MỚI
            'so_dien_thoai' => 'nullable|regex:/^[0-9]{10,11}$/',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'mat_khau_moi'  => 'nullable|min:8|confirmed',
        ], [
            'ho_ten.required'       => 'Vui lòng nhập họ tên',
            'lop.required'          => 'Vui lòng nhập lớp', // ← MỚI
            'lop.regex'             => 'Định dạng lớp không hợp lệ. Vui lòng nhập theo format: 64.CNTT-1', // ← MỚI
            'so_dien_thoai.regex'   => 'Số điện thoại phải là 10-11 chữ số',
            'avatar.image'          => 'File phải là hình ảnh',
            'avatar.max'            => 'Kích thước ảnh tối đa 5MB',
            'mat_khau_moi.min'      => 'Mật khẩu mới ít nhất 8 ký tự',
            'mat_khau_moi.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        $data = [
            'ho_ten' => $request->ho_ten,
            'lop'    => $request->lop,  // ← MỚI
        ];

        // Chỉ cập nhật số điện thoại nếu được cung cấp
        if ($request->filled('so_dien_thoai')) {
            $data['so_dien_thoai'] = $request->so_dien_thoai;
        }

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
