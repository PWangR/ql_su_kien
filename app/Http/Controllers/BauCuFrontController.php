<?php

namespace App\Http\Controllers;

use App\Models\BauCu;
use App\Models\UngCuVien;
use App\Models\CuTri;
use Illuminate\Http\Request;

class BauCuFrontController extends Controller
{
    public function index()
    {
        $bauCus = BauCu::hienThi()
            ->withCount([
                'ungCuVien as so_ung_cu_vien',
                'cuTri as so_cu_tri',
                'cuTri as so_da_bo_phieu' => fn($q) => $q->where('da_bo_phieu', true),
            ])
            ->orderByDesc('thoi_gian_bat_dau')
            ->get();

        return view('bau_cu.index', compact('bauCus'));
    }

    public function show($id)
    {
        $bauCu = BauCu::hienThi()->findOrFail($id);
        $ungCuViens = UngCuVien::where('ma_bau_cu', $id)
            ->orderBy('thu_tu_hien_thi')
            ->get();

        // Kiểm tra user đã bỏ phiếu chưa
        $daBoPhieu = false;
        $laCuTri = false;
        if (auth()->check()) {
            $cuTri = CuTri::where('ma_bau_cu', $id)
                ->where('ma_nguoi_dung', auth()->id())
                ->first();
            $laCuTri = $cuTri !== null;
            $daBoPhieu = $cuTri && $cuTri->da_bo_phieu;
        }

        // Kết quả nếu show_result
        $ketQua = null;
        $soVoted = 0;
        $soCuTri = 0;
        if ($bauCu->hien_thi_ket_qua) {
            $ketQua = UngCuVien::layKemSoPhieu($id);
            $soVoted = CuTri::where('ma_bau_cu', $id)->where('da_bo_phieu', true)->count();
            $soCuTri = CuTri::where('ma_bau_cu', $id)->count();
        }

        return view('bau_cu.show', compact(
            'bauCu', 'ungCuViens', 'daBoPhieu', 'laCuTri', 'ketQua', 'soVoted', 'soCuTri'
        ));
    }

    public function ketQua($id)
    {
        $bauCu = BauCu::where('hien_thi_ket_qua', true)->findOrFail($id);
        $ketQua = UngCuVien::layKemSoPhieu($id);
        $soVoted = CuTri::where('ma_bau_cu', $id)->where('da_bo_phieu', true)->count();
        $soCuTri = CuTri::where('ma_bau_cu', $id)->count();

        return view('bau_cu.ket_qua', compact('bauCu', 'ketQua', 'soVoted', 'soCuTri'));
    }
}
