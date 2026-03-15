<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CuTri;
use App\Models\BauCu;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CuTriImport;

class CuTriController extends Controller
{
    public function store(Request $request, $bauCuId)
    {
        $bauCu = BauCu::findOrFail($bauCuId);

        $request->validate([
            'ma_nguoi_dung' => 'required|exists:nguoi_dung,ma_nguoi_dung',
        ]);

        $existing = CuTri::where('ma_bau_cu', $bauCuId)
            ->where('ma_nguoi_dung', $request->ma_nguoi_dung)
            ->first();

        if ($existing) {
            return back()->with('error', 'Người dùng này đã có trong danh sách cử tri.');
        }

        CuTri::create([
            'ma_bau_cu'     => $bauCuId,
            'ma_nguoi_dung' => $request->ma_nguoi_dung,
        ]);

        return redirect()
            ->route('admin.bau-cu.show', $bauCuId)
            ->with('success', 'Đã thêm cử tri.');
    }

    public function importExcel(Request $request, $bauCuId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new CuTriImport($bauCuId), $request->file('file'));
            return back()->with('success', 'Nhập danh sách cử tri thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi khi nhập file: ' . $e->getMessage());
        }
    }

    public function addAll($bauCuId)
    {
        $bauCu = BauCu::findOrFail($bauCuId);

        // Lấy tất cả sinh viên chưa có trong danh sách cử tri
        $existingIds = CuTri::where('ma_bau_cu', $bauCuId)
            ->pluck('ma_nguoi_dung')
            ->toArray();

        $sinhViens = User::where('vai_tro', 'sinh_vien')
            ->where('trang_thai', 'hoat_dong')
            ->whereNotIn('ma_nguoi_dung', $existingIds)
            ->get();

        $count = 0;
        foreach ($sinhViens as $sv) {
            CuTri::create([
                'ma_bau_cu'     => $bauCuId,
                'ma_nguoi_dung' => $sv->ma_nguoi_dung,
            ]);
            $count++;
        }

        return redirect()
            ->route('admin.bau-cu.show', $bauCuId)
            ->with('success', "Đã thêm {$count} sinh viên vào danh sách cử tri.");
    }

    public function destroy($id)
    {
        $cuTri = CuTri::findOrFail($id);
        $bauCuId = $cuTri->ma_bau_cu;
        $cuTri->delete();

        return redirect()
            ->route('admin.bau-cu.show', $bauCuId)
            ->with('success', 'Đã xóa cử tri.');
    }

    public function destroyAll($bauCuId)
    {
        CuTri::where('ma_bau_cu', $bauCuId)->delete();

        return redirect()
            ->route('admin.bau-cu.show', $bauCuId)
            ->with('success', 'Đã xóa tất cả cử tri.');
    }
}
