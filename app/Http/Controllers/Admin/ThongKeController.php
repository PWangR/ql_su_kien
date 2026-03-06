<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuKien;
use App\Models\User;
use App\Models\DangKy;
use App\Models\LichSuDiem;
use Illuminate\Support\Facades\DB;

class ThongKeController extends Controller
{
    public function index()
    {
        // Widget stats
        $tongSuKien      = SuKien::whereNull('deleted_at')->count();
        $tongNguoiDung   = User::whereNull('deleted_at')->count();
        $tongDangKy      = DangKy::whereNull('deleted_at')->count();
        $suKienSapDienRa = SuKien::where('trang_thai', 'sap_to_chuc')->whereNull('deleted_at')->count();

        // Biểu đồ sự kiện theo tháng
        $suKienTheoThang = DB::table('su_kien')
            ->selectRaw('MONTH(thoi_gian_bat_dau) as thang, COUNT(*) as so_luong')
            ->whereNull('deleted_at')
            ->whereYear('thoi_gian_bat_dau', date('Y'))
            ->groupBy('thang')
            ->orderBy('thang')
            ->get();

        // Top sự kiện nhiều người đăng ký
        $topSuKien = SuKien::withCount('dangKy')
            ->whereNull('deleted_at')
            ->orderByDesc('dang_ky_count')
            ->take(5)
            ->get();

        return view('admin.thong_ke.index', compact(
            'tongSuKien', 'tongNguoiDung', 'tongDangKy', 'suKienSapDienRa',
            'suKienTheoThang', 'topSuKien'
        ));
    }

    public function diem()
    {
        $tongDiem = DB::table('lich_su_diem')
            ->join('nguoi_dung', 'lich_su_diem.ma_nguoi_dung', '=', 'nguoi_dung.ma_nguoi_dung')
            ->select('nguoi_dung.ho_ten', 'nguoi_dung.ma_sinh_vien', DB::raw('SUM(lich_su_diem.diem) as tong_diem'))
            ->groupBy('lich_su_diem.ma_nguoi_dung', 'nguoi_dung.ho_ten', 'nguoi_dung.ma_sinh_vien')
            ->orderByDesc('tong_diem')
            ->paginate(20);

        return view('admin.thong_ke.diem', compact('tongDiem'));
    }
}
