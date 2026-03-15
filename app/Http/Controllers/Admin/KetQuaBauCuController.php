<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BauCu;
use App\Models\UngCuVien;
use App\Models\CuTri;
use Illuminate\Http\Request;

class KetQuaBauCuController extends Controller
{
    public function index($id)
    {
        $bauCu = BauCu::findOrFail($id);
        $ketQua = UngCuVien::layKemSoPhieu($id);
        $soVoted = CuTri::where('ma_bau_cu', $id)->where('da_bo_phieu', true)->count();
        $soCuTri = CuTri::where('ma_bau_cu', $id)->count();

        return view('admin.bau_cu.ket_qua', compact('bauCu', 'ketQua', 'soVoted', 'soCuTri'));
    }

    public function apiResults($id)
    {
        $bauCu = BauCu::findOrFail($id);
        $ketQua = UngCuVien::layKemSoPhieu($id);
        $soVoted = CuTri::where('ma_bau_cu', $id)->where('da_bo_phieu', true)->count();
        $soCuTri = CuTri::where('ma_bau_cu', $id)->count();

        $data = [];
        foreach ($ketQua as $ucv) {
            $pct = $soVoted > 0 ? round(($ucv->so_phieu / $soVoted) * 100, 1) : 0;
            $data[] = [
                'id'         => $ucv->ma_ung_cu_vien,
                'ho_ten'     => $ucv->ho_ten,
                'lop'        => $ucv->lop,
                'so_phieu'   => $ucv->so_phieu,
                'phan_tram'  => $pct,
            ];
        }

        return response()->json([
            'ma_bau_cu'   => (int)$id,
            'tieu_de'     => $bauCu->tieu_de,
            'so_cu_tri'   => $soCuTri,
            'so_da_bo_phieu' => $soVoted,
            'ung_cu_vien' => $data,
            'cap_nhat'    => now()->toISOString(),
        ]);
    }
}
