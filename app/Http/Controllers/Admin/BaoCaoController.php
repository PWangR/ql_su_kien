<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\BaoCaoLopExport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class BaoCaoController extends Controller
{
    /**
     * Constructor - áp dụng middleware auth & admin
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Hiển thị form xuất báo cáo
     * GET /admin/bao-cao
     */
    public function index()
    {
        // Lấy danh sách lớp từ DB (unique values)
        $danhSachLop = User::where('vai_tro', 'sinh_vien')
            ->whereNotNull('lop')
            ->distinct()
            ->pluck('lop')
            ->sort()
            ->values();

        return view('admin.bao_cao.index', [
            'danhSachLop' => $danhSachLop,
        ]);
    }

    /**
     * Xuất Excel báo cáo
     * POST /admin/bao-cao/export
     */
    public function export(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'lop' => 'required|string|exists:nguoi_dung,lop',
                'from_date' => 'nullable|date_format:Y-m-d',
                'to_date' => 'nullable|date_format:Y-m-d',
            ], [
                'lop.required' => 'Vui lòng chọn lớp',
                'lop.exists' => 'Lớp không tồn tại',
                'from_date.date_format' => 'Định dạng ngày không hợp lệ (Y-m-d)',
                'to_date.date_format' => 'Định dạng ngày không hợp lệ (Y-m-d)',
            ]);

            // Kiểm tra date range logic
            if ($validated['from_date'] && $validated['to_date']) {
                if (strtotime($validated['from_date']) > strtotime($validated['to_date'])) {
                    return back()
                        ->withErrors(['date_range' => 'Ngày bắt đầu phải trước ngày kết thúc'])
                        ->withInput();
                }
            }

            // Lấy thông tin lớp
            $lop = $validated['lop'];
            $fromDate = $validated['from_date'] ?? null;
            $toDate = $validated['to_date'] ?? null;

            // Tạo filename
            $now = now()->format('Y-m-d_H-i-s');
            $filename = "bao_cao_lop_{$lop}_{$now}.xlsx";

            // Return Excel download
            return Excel::download(
                new BaoCaoLopExport($lop, $fromDate, $toDate),
                $filename
            );
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Lỗi xuất Excel: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
