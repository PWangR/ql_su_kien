<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UngCuVien;
use App\Models\BauCu;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UngCuVienImport;

class UngCuVienController extends Controller
{
    public function store(Request $request, $bauCuId)
    {
        $bauCu = BauCu::findOrFail($bauCuId);

        $request->validate([
            'ho_ten'       => 'required|min:2|max:100',
            'lop'          => 'required|max:50',
            'ma_sinh_vien' => 'required|digits:8',
        ]);

        // Kiểm tra trùng MSSV
        $existing = UngCuVien::where('ma_bau_cu', $bauCuId)
            ->where('ma_sinh_vien', $request->ma_sinh_vien)
            ->first();

        if ($existing) {
            return back()->with('error', 'MSSV ' . $request->ma_sinh_vien . ' đã tồn tại trong cuộc bầu cử này.');
        }

        UngCuVien::create([
            'ma_bau_cu'        => $bauCuId,
            'ho_ten'           => $request->ho_ten,
            'lop'              => $request->lop,
            'ma_sinh_vien'     => $request->ma_sinh_vien,
            'diem_trung_binh'  => $request->diem_trung_binh ?: null,
            'diem_ren_luyen'   => $request->diem_ren_luyen ?: null,
            'gioi_thieu'       => $request->gioi_thieu,
            'thu_tu_hien_thi'  => $request->thu_tu_hien_thi ?? 0,
        ]);

        return redirect()
            ->route('admin.bau-cu.show', $bauCuId)
            ->with('success', 'Đã thêm ứng cử viên ' . $request->ho_ten);
    }

    public function importExcel(Request $request, $bauCuId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new UngCuVienImport($bauCuId), $request->file('file'));
            return back()->with('success', 'Nhập danh sách ứng cử viên thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi khi nhập file: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $ucv = UngCuVien::findOrFail($id);

        $request->validate([
            'ho_ten'       => 'required|min:2|max:100',
            'lop'          => 'required|max:50',
            'ma_sinh_vien' => 'required|digits:8',
        ]);

        // Kiểm tra trùng MSSV (trừ chính nó)
        $existing = UngCuVien::where('ma_bau_cu', $ucv->ma_bau_cu)
            ->where('ma_sinh_vien', $request->ma_sinh_vien)
            ->where('ma_ung_cu_vien', '!=', $id)
            ->first();

        if ($existing) {
            return back()->with('error', 'MSSV ' . $request->ma_sinh_vien . ' đã tồn tại.');
        }

        $ucv->update([
            'ho_ten'           => $request->ho_ten,
            'lop'              => $request->lop,
            'ma_sinh_vien'     => $request->ma_sinh_vien,
            'diem_trung_binh'  => $request->diem_trung_binh ?: null,
            'diem_ren_luyen'   => $request->diem_ren_luyen ?: null,
            'gioi_thieu'       => $request->gioi_thieu,
            'thu_tu_hien_thi'  => $request->thu_tu_hien_thi ?? 0,
        ]);

        return redirect()
            ->route('admin.bau-cu.show', $ucv->ma_bau_cu)
            ->with('success', 'Đã cập nhật ứng cử viên ' . $request->ho_ten);
    }

    public function destroy($id)
    {
        $ucv = UngCuVien::findOrFail($id);
        $bauCuId = $ucv->ma_bau_cu;
        $ucv->delete();

        return redirect()
            ->route('admin.bau-cu.show', $bauCuId)
            ->with('success', 'Đã xóa ứng cử viên.');
    }

    public function destroyAll($bauCuId)
    {
        UngCuVien::where('ma_bau_cu', $bauCuId)->delete();

        return redirect()
            ->route('admin.bau-cu.show', $bauCuId)
            ->with('success', 'Đã xóa tất cả ứng cử viên.');
    }
}
