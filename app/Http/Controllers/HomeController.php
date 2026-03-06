<?php

namespace App\Http\Controllers;

use App\Models\SuKien;
use App\Models\LoaiSuKien;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $suKienNoiBat = SuKien::with('loaiSuKien')
            ->where('trang_thai', '!=', 'huy')
            ->whereNull('deleted_at')
            ->orderBy('thoi_gian_bat_dau')
            ->take(5)
            ->get();

        $suKienMoi = SuKien::with('loaiSuKien')
            ->where('trang_thai', '!=', 'huy')
            ->whereNull('deleted_at')
            ->latest()
            ->take(8)
            ->get();

        $loaiSuKien = LoaiSuKien::withCount('suKien')->get();

        return view('home.index', compact('suKienNoiBat', 'suKienMoi', 'loaiSuKien'));
    }
}
