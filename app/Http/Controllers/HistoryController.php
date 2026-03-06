<?php

namespace App\Http\Controllers;

use App\Models\DangKy;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $lichSu = DangKy::with('suKien')
            ->where('ma_nguoi_dung', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(15);

        $tongDiem = auth()->user()->lichSuDiem()->sum('diem') ?? 0;

        return view('history.index', compact('lichSu', 'tongDiem'));
    }
}
