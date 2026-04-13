<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BauCu;
use App\Models\UngCuVien;
use App\Models\CuTri;
use Illuminate\Http\Request;

class BauCuController extends Controller
{
    public function index()
    {
        $bauCus = BauCu::with('nguoiTao')
            ->withCount([
                'ungCuVien as so_ung_cu_vien',
                'cuTri as so_cu_tri',
                'cuTri as so_da_bo_phieu' => function ($q) {
                    $q->where('da_bo_phieu', true);
                },
            ])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.bau_cu.index', compact('bauCus'));
    }

    public function create()
    {
        return view('admin.bau_cu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tieu_de'             => 'required|max:255',
            'thoi_gian_bat_dau'   => 'required|date',
            'thoi_gian_ket_thuc'  => 'required|date|after:thoi_gian_bat_dau',
            'so_chon_toi_thieu'   => 'required|integer|min:1',
            'so_chon_toi_da'      => 'required|integer|min:1|gte:so_chon_toi_thieu',
        ]);

        $bauCu = BauCu::create([
            'tieu_de'             => $request->tieu_de,
            'mo_ta'               => $request->mo_ta,
            'thoi_gian_bat_dau'   => $request->thoi_gian_bat_dau,
            'thoi_gian_ket_thuc'  => $request->thoi_gian_ket_thuc,
            'so_chon_toi_thieu'   => $request->so_chon_toi_thieu,
            'so_chon_toi_da'      => $request->so_chon_toi_da,
            'ma_nguoi_tao'        => auth()->id(),
        ]);

        return redirect()
            ->route('admin.bau-cu.show', $bauCu->ma_bau_cu)
            ->with('success', 'Tạo cuộc bầu cử thành công!');
    }

    public function show($id)
    {
        $bauCu = BauCu::with('nguoiTao')->findOrFail($id);
        $ungCuViens = UngCuVien::where('ma_bau_cu', $id)
            ->orderBy('thu_tu_hien_thi')
            ->get();
        $cuTris = CuTri::where('ma_bau_cu', $id)
            ->with('nguoiDung')
            ->orderByDesc('created_at')
            ->get();

        // Kết quả bỏ phiếu
        $ketQua = UngCuVien::layKemSoPhieu($id);
        $soVoted = CuTri::where('ma_bau_cu', $id)->where('da_bo_phieu', true)->count();
        $soCuTri = CuTri::where('ma_bau_cu', $id)->count();

        return view('admin.bau_cu.show', compact(
            'bauCu', 'ungCuViens', 'cuTris', 'ketQua', 'soVoted', 'soCuTri'
        ));
    }

    public function edit($id)
    {
        $bauCu = BauCu::findOrFail($id);
        return view('admin.bau_cu.edit', compact('bauCu'));
    }

    public function update(Request $request, $id)
    {
        $bauCu = BauCu::findOrFail($id);

        $request->validate([
            'tieu_de'             => 'required|max:255',
            'thoi_gian_bat_dau'   => 'required|date',
            'thoi_gian_ket_thuc'  => 'required|date|after:thoi_gian_bat_dau',
            'so_chon_toi_thieu'   => 'required|integer|min:1',
            'so_chon_toi_da'      => 'required|integer|min:1|gte:so_chon_toi_thieu',
        ]);

        $bauCu->update([
            'tieu_de'             => $request->tieu_de,
            'mo_ta'               => $request->mo_ta,
            'thoi_gian_bat_dau'   => $request->thoi_gian_bat_dau,
            'thoi_gian_ket_thuc'  => $request->thoi_gian_ket_thuc,
            'so_chon_toi_thieu'   => $request->so_chon_toi_thieu,
            'so_chon_toi_da'      => $request->so_chon_toi_da,
            'trang_thai'          => $request->trang_thai ?? $bauCu->trang_thai,
        ]);

        return redirect()
            ->route('admin.bau-cu.show', $id)
            ->with('success', 'Cập nhật cuộc bầu cử thành công!');
    }

    public function destroy($id)
    {
        BauCu::findOrFail($id)->delete();
        return redirect()
            ->route('admin.bau-cu.index')
            ->with('success', 'Đã xóa cuộc bầu cử.');
    }

    public function toggleVisibility($id)
    {
        $bauCu = BauCu::findOrFail($id);
        $bauCu->update(['hien_thi' => !$bauCu->hien_thi]);
        return back()->with('success', 'Đã cập nhật trạng thái hiển thị.');
    }

    public function toggleResult($id)
    {
        $bauCu = BauCu::findOrFail($id);
        $bauCu->update(['hien_thi_ket_qua' => !$bauCu->hien_thi_ket_qua]);
        return back()->with('success', 'Đã cập nhật trạng thái hiển thị kết quả.');
    }
}
