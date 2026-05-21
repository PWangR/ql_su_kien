<?php

namespace App\Http\Controllers;

use App\Models\DangKy;
use App\Models\LichSuDiem;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $lichSu = DangKy::with('suKien')
            ->where('ma_sinh_vien', auth()->id())
            ->orderByDesc('thoi_gian_dang_ky')
            ->paginate(10);

        // Tính từ lịch sử điểm để giữ nguyên điểm kể cả khi sự kiện đã bị xóa.
        $tongDiem = LichSuDiem::where('ma_sinh_vien', auth()->id())->sum('diem');

        return view('history.index', compact('lichSu', 'tongDiem'));
    }
}
