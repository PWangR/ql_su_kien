<?php

namespace App\Http\Controllers;

use App\Models\SuKien;
use App\Models\LoaiSuKien;
use App\Models\DangKy;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = SuKien::with('loaiSuKien')
            ->where('trang_thai', '!=', 'huy')
            ->whereNull('deleted_at');

        // Lọc theo loại
        if ($request->loai) {
            $query->where('ma_loai_su_kien', $request->loai);
        }

        // Tìm kiếm
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('ten_su_kien', 'like', '%'.$request->search.'%')
                  ->orWhere('dia_diem', 'like', '%'.$request->search.'%');
            });
        }

        // Lọc trạng thái
        if ($request->trang_thai) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $suKien     = $query->orderBy('thoi_gian_bat_dau')->paginate(9)->withQueryString();
        $loaiSuKien = LoaiSuKien::all();

        // IDs sự kiện đã đăng ký
        $daDangKyIds = [];
        if (auth()->check()) {
            $daDangKyIds = DangKy::where('ma_nguoi_dung', auth()->id())
                ->whereIn('trang_thai_tham_gia', ['da_dang_ky', 'da_tham_gia'])
                ->whereNull('deleted_at')
                ->pluck('ma_su_kien')
                ->toArray();
        }

        return view('events.index', compact('suKien', 'loaiSuKien', 'daDangKyIds'));
    }

    public function show($id)
    {
        $suKien = SuKien::with(['loaiSuKien', 'media', 'nguoiTao'])->findOrFail($id);

        $daDangKy = false;
        if (auth()->check()) {
            $daDangKy = DangKy::where('ma_nguoi_dung', auth()->id())
                ->where('ma_su_kien', $id)
                ->whereIn('trang_thai_tham_gia', ['da_dang_ky', 'da_tham_gia'])
                ->whereNull('deleted_at')
                ->exists();
        }

        // Sự kiện liên quan
        $suKienLienQuan = SuKien::where('ma_loai_su_kien', $suKien->ma_loai_su_kien)
            ->where('ma_su_kien', '!=', $id)
            ->where('trang_thai', '!=', 'huy')
            ->whereNull('deleted_at')
            ->take(3)
            ->get();

        return view('events.show', compact('suKien', 'daDangKy', 'suKienLienQuan'));
    }

    public function qrCheckin($token)
    {
        $suKien = SuKien::where('qr_checkin_token', $token)->firstOrFail();

        if ($suKien->trang_thai === 'huy') {
            return back()->with('error', 'Sự kiện đã bị hủy.');
        }

        $dangKy = DangKy::withTrashed()
            ->where('ma_nguoi_dung', auth()->id())
            ->where('ma_su_kien', $suKien->ma_su_kien)
            ->first();

        $wasCreated = false;

        if (!$dangKy) {
            if ($suKien->so_luong_toi_da > 0 && $suKien->so_luong_hien_tai >= $suKien->so_luong_toi_da) {
                return back()->with('error', 'Sự kiện đã đủ số lượng, không thể điểm danh thêm.');
            }

            $dangKy = DangKy::create([
                'ma_nguoi_dung'       => auth()->id(),
                'ma_su_kien'          => $suKien->ma_su_kien,
                'trang_thai_tham_gia' => 'da_tham_gia',
            ]);
            $wasCreated = true;
        } else {
            if ($dangKy->trashed()) {
                $dangKy->restore();
            }
            $dangKy->update(['trang_thai_tham_gia' => 'da_tham_gia']);
        }

        if ($wasCreated) {
            $suKien->increment('so_luong_hien_tai');
        }

        return view('events.qr-checkin', [
            'suKien' => $suKien,
            'dangKy' => $dangKy
        ])->with('success', 'Điểm danh thành công!');
    }

    public function dangKy($id)
    {
        $suKien = SuKien::findOrFail($id);

        // Kiểm tra đã đăng ký chưa
        $exists = DangKy::where('ma_nguoi_dung', auth()->id())
            ->where('ma_su_kien', $id)
            ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã đăng ký sự kiện này rồi!');
        }

        // Kiểm tra đã đầy chưa
        if ($suKien->so_luong_toi_da > 0 && $suKien->so_luong_hien_tai >= $suKien->so_luong_toi_da) {
            return back()->with('error', 'Sự kiện đã đủ số lượng!');
        }

        // Kiểm tra trạng thái
        if ($suKien->trang_thai === 'da_ket_thuc' || $suKien->trang_thai === 'huy') {
            return back()->with('error', 'Sự kiện đã kết thúc hoặc bị hủy!');
        }

        DangKy::create([
            'ma_nguoi_dung'      => auth()->id(),
            'ma_su_kien'         => $id,
            'trang_thai_tham_gia' => 'da_dang_ky',
        ]);

        // Tăng so_luong_hien_tai
        $suKien->increment('so_luong_hien_tai');

        return back()->with('success', 'Đăng ký tham gia thành công!');
    }

    public function huyDangKy($id)
    {
        $dangKy = DangKy::where('ma_nguoi_dung', auth()->id())
            ->where('ma_su_kien', $id)
            ->where('trang_thai_tham_gia', 'da_dang_ky')
            ->whereNull('deleted_at')
            ->firstOrFail();

        $dangKy->update(['trang_thai_tham_gia' => 'huy']);
        $dangKy->delete(); // soft delete

        // Giảm so_luong_hien_tai
        SuKien::findOrFail($id)->decrement('so_luong_hien_tai');

        return back()->with('success', 'Hủy đăng ký thành công!');
    }
}
