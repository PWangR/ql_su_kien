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
            'ma_sinh_vien' => 'required|digits:8|exists:nguoi_dung,ma_sinh_vien',
        ]);

        $mssv = $request->input('ma_sinh_vien');

        $existing = CuTri::where('ma_bau_cu', $bauCuId)
            ->where('ma_sinh_vien', $mssv)
            ->first();

        if ($existing) {
            return back()->with('error', 'Người dùng này đã có trong danh sách cử tri.');
        }

        CuTri::create([
            'ma_bau_cu'     => $bauCuId,
            'ma_sinh_vien' => $mssv,
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
            ->pluck('ma_sinh_vien')
            ->toArray();

        $sinhViens = User::where('vai_tro', 'sinh_vien')
            ->where('trang_thai', 'hoat_dong')
            ->whereNotIn('ma_sinh_vien', $existingIds)
            ->get();

        $count = 0;
        foreach ($sinhViens as $sv) {
            CuTri::create([
                'ma_bau_cu'     => $bauCuId,
                'ma_sinh_vien' => $sv->ma_sinh_vien,
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

    /**
     * API: Lấy danh sách sinh viên có thể thêm làm cử tri (với tìm kiếm/lọc)
     */
    public function apiStudentList(Request $request, $bauCuId)
    {
        try {
            $search = $request->query('search', '');
            $class = $request->query('class', '');
            $limit = $request->query('limit', 50);

            // Lấy những sinh viên đã là cử tri của bầu cử này
            $existingStudents = CuTri::where('ma_bau_cu', $bauCuId)
                ->pluck('ma_sinh_vien')
                ->toArray();

            // Xây dựng query
            $query = User::where('vai_tro', 'sinh_vien')
                ->where('trang_thai', 'hoat_dong')
                ->whereNotIn('ma_sinh_vien', $existingStudents);

            // Tìm kiếm theo họ tên, MSSV, email
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('ho_ten', 'LIKE', "%{$search}%")
                        ->orWhere('ma_sinh_vien', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                });
            }

            // Lọc theo lớp
            if ($class) {
                $query->where('lop', $class);
            }

            $students = $query->orderBy('ho_ten')
                ->limit($limit)
                ->get(['ma_sinh_vien as id', 'ma_sinh_vien', 'ho_ten', 'lop', 'email']);

            return response()->json($students);
        } catch (\Exception $e) {
            // Return lỗi để debug trong console
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    /**
     * API: Thêm cử tri từ danh sách chọn (AJAX)
     * Always returns JSON since this is called via AJAX only
     */
    public function addSelected(Request $request, $bauCuId)
    {
        try {
            $bauCu = BauCu::findOrFail($bauCuId);

            $request->validate([
                'student_ids' => 'required|array|min:1',
                'student_ids.*' => 'string|digits:8|exists:nguoi_dung,ma_sinh_vien',
            ]);

            $students = User::whereIn('ma_sinh_vien', $request->student_ids)->get();

            if ($students->isEmpty()) {
                return response()->json(['success' => false, 'error' => 'Không tìm thấy sinh viên nào.'], 400);
            }

            $existingMssv = CuTri::where('ma_bau_cu', $bauCuId)
                ->pluck('ma_sinh_vien')
                ->toArray();

            $added = 0;
            foreach ($students as $student) {
                if (!in_array($student->ma_sinh_vien, $existingMssv)) {
                    CuTri::create([
                        'ma_bau_cu' => $bauCuId,
                        'ma_sinh_vien' => $student->ma_sinh_vien,
                    ]);
                    $added++;
                }
            }

            // Always return JSON for AJAX requests
            return response()->json(['success' => true, 'message' => "Đã thêm {$added} cử tri."]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'error' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
