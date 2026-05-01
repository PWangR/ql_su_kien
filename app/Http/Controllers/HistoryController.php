<?php

namespace App\Http\Controllers;

use App\Models\DangKy;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $lichSu = DangKy::with('suKien')
            ->where('ma_sinh_vien', auth()->id())
            ->orderByDesc('thoi_gian_dang_ky')
            ->paginate(10);

        // Tính tổng điểm từ các sự kiện đã tham gia
        $tongDiem = DangKy::where('ma_sinh_vien', auth()->id())
            ->where('trang_thai_tham_gia', 'da_tham_gia')
            ->with('suKien')
            ->get()
            ->sum(function ($dk) {
                return $dk->suKien?->diem_cong ?? 0;
            });

        return view('history.index', compact('lichSu', 'tongDiem'));
    }
}
