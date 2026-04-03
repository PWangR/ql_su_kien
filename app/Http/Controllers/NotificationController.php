<?php

namespace App\Http\Controllers;

use App\Models\ThongBao;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $thongBao = ThongBao::where('ma_sinh_vien', auth()->id())
            ->latest('created_at')
            ->paginate(20);

        $chuaDoc = ThongBao::where('ma_sinh_vien', auth()->id())
            ->where('da_doc', false)
            ->count();

        return view('notifications.index', compact('thongBao', 'chuaDoc'));
    }

    public function markRead($id)
    {
        ThongBao::where('ma_thong_bao', $id)
            ->where('ma_sinh_vien', auth()->id())
            ->update(['da_doc' => true]);

        return back()->with('success', 'Đã đánh dấu đã đọc!');
    }

    public function markAllRead()
    {
        ThongBao::where('ma_sinh_vien', auth()->id())
            ->where('da_doc', false)
            ->update(['da_doc' => true]);

        return back()->with('success', 'Đã đọc tất cả thông báo!');
    }
}
