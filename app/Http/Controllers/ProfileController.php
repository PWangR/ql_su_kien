<?php

namespace App\Http\Controllers;

use App\Models\DangKy;
use App\Models\LichSuDiem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $registrationStats = DangKy::where('ma_sinh_vien', $user->ma_sinh_vien)
            ->selectRaw("
                COUNT(*) as total,
                SUM(trang_thai_tham_gia = 'da_tham_gia') as attended,
                SUM(trang_thai_tham_gia = 'da_dang_ky') as upcoming,
                SUM(trang_thai_tham_gia = 'huy') as cancelled
            ")
            ->first();

        $totalPoints = LichSuDiem::where('ma_sinh_vien', $user->ma_sinh_vien)->sum('diem');

        $recentRegistrations = DangKy::with('suKien')
            ->where('ma_sinh_vien', $user->ma_sinh_vien)
            ->orderByDesc('thoi_gian_dang_ky')
            ->take(3)
            ->get();

        $nextEvent = DangKy::with('suKien')
            ->where('ma_sinh_vien', $user->ma_sinh_vien)
            ->where('trang_thai_tham_gia', 'da_dang_ky')
            ->whereHas('suKien', function ($query) {
                $query->where('thoi_gian_bat_dau', '>=', now());
            })
            ->join('su_kien', 'dang_ky.ma_su_kien', '=', 'su_kien.ma_su_kien')
            ->orderBy('su_kien.thoi_gian_bat_dau')
            ->select('dang_ky.*')
            ->first();

        return view('profile.index', compact(
            'user',
            'registrationStats',
            'totalPoints',
            'recentRegistrations',
            'nextEvent'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'ho_ten' => 'required|max:100',
            'lop' => 'required|max:10|regex:/^[0-9]{2,}\.[A-Za-z]{2,}-[0-9]{1,}$/', // ← MỚI
            'so_dien_thoai' => 'nullable|regex:/^[0-9]{10,11}$/',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'mat_khau_moi' => 'nullable|min:8|confirmed',
        ], [
            'ho_ten.required' => 'Vui lòng nhập họ tên',
            'lop.required' => 'Vui lòng nhập lớp', // ← MỚI
            'lop.regex' => 'Định dạng lớp không hợp lệ. Vui lòng nhập theo format: 64.CNTT-1', // ← MỚI
            'so_dien_thoai.regex' => 'Số điện thoại phải là 10-11 chữ số',
            'avatar.image' => 'File phải là hình ảnh',
            'avatar.max' => 'Kích thước ảnh tối đa 5MB',
            'mat_khau_moi.min' => 'Mật khẩu mới ít nhất 8 ký tự',
            'mat_khau_moi.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        $data = [
            'ho_ten' => $request->ho_ten,
            'lop' => $request->lop,  // ← MỚI
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
