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
    public function index(\Illuminate\Http\Request $request)
    {
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');

        $querySuKien = SuKien::whereNull('deleted_at');
        $queryDangKy = DangKy::whereNull('deleted_at');

        if ($tuNgay) {
            $querySuKien->where('thoi_gian_bat_dau', '>=', $tuNgay);
            $queryDangKy->whereHas('suKien', function ($q) use ($tuNgay) {
                $q->where('thoi_gian_bat_dau', '>=', $tuNgay);
            });
        }

        if ($denNgay) {
            $querySuKien->where('thoi_gian_bat_dau', '<=', $denNgay . ' 23:59:59');
            $queryDangKy->whereHas('suKien', function ($q) use ($denNgay) {
                $q->where('thoi_gian_bat_dau', '<=', $denNgay . ' 23:59:59');
            });
        }

        // Widget stats
        $tongSuKien = (clone $querySuKien)->count();
        $tongNguoiDung = User::whereNull('deleted_at')->count();
        $tongDangKy = (clone $queryDangKy)->count();
        $suKienSapDienRa = (clone $querySuKien)->where('trang_thai', 'sap_to_chuc')->count();

        // Biểu đồ sự kiện theo tháng
        $suKienTheoThangQuery = DB::table('su_kien')
            ->selectRaw('MONTH(thoi_gian_bat_dau) as thang, COUNT(*) as so_luong')
            ->whereNull('deleted_at')
            ->whereYear('thoi_gian_bat_dau', date('Y'));

        if ($tuNgay)
            $suKienTheoThangQuery->where('thoi_gian_bat_dau', '>=', $tuNgay);
        if ($denNgay)
            $suKienTheoThangQuery->where('thoi_gian_bat_dau', '<=', $denNgay . ' 23:59:59');

        $suKienTheoThang = $suKienTheoThangQuery->groupBy('thang')->orderBy('thang')->get();

        // Bảng phân tích sự kiện
        $danhSachSuKien = (clone $querySuKien)
            ->withCount(['dangKy as tong_dang_ky'])
            ->withCount([
                'dangKy as da_diem_danh' => function ($q) {
                    $q->where('trang_thai_tham_gia', 'da_tham_gia');
                }
            ])
            ->withCount([
                'dangKy as vang_mat' => function ($q) {
                    $q->where('trang_thai_tham_gia', 'vang_mat');
                }
            ])
            ->orderByDesc('thoi_gian_bat_dau')
            ->paginate(10);

        $danhSachSuKien->appends($request->except('page'));

        // Top sự kiện nhiều người đăng ký
        $topSuKien = (clone $querySuKien)
            ->withCount('dangKy')
            ->orderByDesc('dang_ky_count')
            ->take(5)
            ->get();

        return view('admin.thong_ke.index', compact(
            'tongSuKien',
            'tongNguoiDung',
            'tongDangKy',
            'suKienSapDienRa',
            'suKienTheoThang',
            'danhSachSuKien',
            'topSuKien',
            'tuNgay',
            'denNgay'
        ));
    }

    public function apiChiTietSuKien($id)
    {
        $suKien = SuKien::findOrFail($id);

        $tongDangKy = DangKy::where('ma_su_kien', $id)->count();
        $daDiemDanh = DangKy::where('ma_su_kien', $id)->where('trang_thai_tham_gia', 'da_tham_gia')->count();
        $vangMat = DangKy::where('ma_su_kien', $id)->where('trang_thai_tham_gia', 'vang_mat')->count();
        $chuaDiemDanh = DangKy::where('ma_su_kien', $id)->where('trang_thai_tham_gia', 'chua_diem_danh')->count();

        // Top lớp tham gia đông nhất
        $topLop = DangKy::where('ma_su_kien', $id)
            ->where('trang_thai_tham_gia', 'da_tham_gia')
            ->join('nguoi_dung', 'dang_ky.ma_sinh_vien', '=', 'nguoi_dung.ma_sinh_vien')
            ->select('nguoi_dung.lop', DB::raw('count(*) as so_luong'))
            ->groupBy('nguoi_dung.lop')
            ->orderByDesc('so_luong')
            ->take(5)
            ->get();

        return response()->json([
            'su_kien' => $suKien->ten_su_kien,
            'tong_dang_ky' => $tongDangKy,
            'da_diem_danh' => $daDiemDanh,
            'vang_mat' => $vangMat,
            'chua_diem_danh' => $chuaDiemDanh,
            'top_lop' => $topLop
        ]);
    }

    public function diem()
    {
        $tongDiem = DB::table('lich_su_diem')
            ->join('nguoi_dung', 'lich_su_diem.ma_sinh_vien', '=', 'nguoi_dung.ma_sinh_vien')
            ->select('nguoi_dung.ho_ten', 'nguoi_dung.ma_sinh_vien', DB::raw('SUM(lich_su_diem.diem) as tong_diem'))
            ->groupBy('lich_su_diem.ma_sinh_vien', 'nguoi_dung.ho_ten', 'nguoi_dung.ma_sinh_vien')
            ->orderByDesc('tong_diem')
            ->paginate(10);

        return view('admin.thong_ke.diem', compact('tongDiem'));
    }
}
