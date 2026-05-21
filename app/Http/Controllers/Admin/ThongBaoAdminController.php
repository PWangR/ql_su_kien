<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LichGuiThongBao;
use App\Models\SuKien;
use App\Models\User;
use App\Services\AdminNotificationScheduleService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ThongBaoAdminController extends Controller
{
    public function index()
    {
        $lichGui = LichGuiThongBao::with(['suKienLienQuan', 'nguoiTao'])
            ->latest('created_at')
            ->paginate(10);

        $nguoiDung = User::whereNull('deleted_at')
            ->where('trang_thai', 'hoat_dong')
            ->orderBy('ho_ten')
            ->get(['ma_sinh_vien', 'ho_ten', 'email', 'lop', 'vai_tro']);

        $suKien = SuKien::where('la_mau_bai_dang', false)
            ->where('trang_thai', '!=', 'huy')
            ->orderByDesc('thoi_gian_bat_dau')
            ->get(['ma_su_kien', 'ten_su_kien', 'thoi_gian_bat_dau']);

        $stats = [
            'cho_gui' => LichGuiThongBao::where('trang_thai', 'cho_gui')->count(),
            'da_gui' => LichGuiThongBao::where('trang_thai', 'da_gui')->count(),
            'loi' => LichGuiThongBao::where('trang_thai', 'loi')->count(),
        ];

        return view('admin.thong_bao.index', compact('lichGui', 'nguoiDung', 'suKien', 'stats'));
    }

    public function store(Request $request, AdminNotificationScheduleService $scheduleService)
    {
        $data = $request->validate([
            'tieu_de' => 'required|string|max:200',
            'noi_dung' => 'required|string|max:5000',
            'loai_thong_bao' => 'required|in:he_thong,nhac_nho_su_kien,cap_nhat_diem,khac',
            'pham_vi' => 'required|in:tat_ca,nguoi_dang_ky_su_kien,tuy_chon',
            'kieu_gui' => 'required|in:ngay_lap_tuc,hen_gio,nhac_nho_su_kien',
            'ma_su_kien_lien_quan' => 'nullable|exists:su_kien,ma_su_kien',
            'nguoi_nhan' => 'nullable|array',
            'nguoi_nhan.*' => 'exists:nguoi_dung,ma_sinh_vien',
            'thoi_gian_gui' => 'nullable|date|after:now',
            'reminder_minutes' => 'nullable|integer|in:15,30,60,120,1440',
        ], [
            'tieu_de.required' => 'Vui lòng nhập tiêu đề thông báo.',
            'noi_dung.required' => 'Vui lòng nhập nội dung thông báo.',
            'pham_vi.required' => 'Vui lòng chọn phạm vi người nhận.',
            'kieu_gui.required' => 'Vui lòng chọn cách gửi thông báo.',
            'thoi_gian_gui.after' => 'Thời gian hẹn gửi phải sau thời điểm hiện tại.',
            'nguoi_nhan.*.exists' => 'Danh sách người nhận có người dùng không hợp lệ.',
        ]);

        if ($data['pham_vi'] === 'nguoi_dang_ky_su_kien' && empty($data['ma_su_kien_lien_quan'])) {
            return back()->withInput()->with('error', 'Vui lòng chọn sự kiện để gửi cho người đăng ký.');
        }

        if ($data['pham_vi'] === 'tuy_chon' && empty($data['nguoi_nhan'])) {
            return back()->withInput()->with('error', 'Vui lòng chọn ít nhất một người nhận.');
        }

        if ($data['kieu_gui'] === 'hen_gio' && empty($data['thoi_gian_gui'])) {
            return back()->withInput()->with('error', 'Vui lòng chọn thời gian hẹn gửi.');
        }

        $scheduledAt = now();
        if ($data['kieu_gui'] === 'hen_gio') {
            $scheduledAt = Carbon::parse($data['thoi_gian_gui']);
        }

        if ($data['kieu_gui'] === 'nhac_nho_su_kien') {
            if (empty($data['ma_su_kien_lien_quan'])) {
                return back()->withInput()->with('error', 'Thông báo nhắc nhở sự kiện cần chọn sự kiện liên quan.');
            }

            $event = SuKien::findOrFail($data['ma_su_kien_lien_quan']);
            $minutes = (int) ($data['reminder_minutes'] ?? 60);
            $scheduledAt = $event->thoi_gian_bat_dau->copy()->subMinutes($minutes);

            if ($scheduledAt->isPast()) {
                return back()->withInput()->with('error', 'Mốc nhắc nhở đã nằm trong quá khứ. Hãy chọn khoảng nhắc ngắn hơn hoặc gửi ngay.');
            }

            $data['loai_thong_bao'] = 'nhac_nho_su_kien';
        }

        $schedule = LichGuiThongBao::create([
            'tieu_de' => $data['tieu_de'],
            'noi_dung' => $data['noi_dung'],
            'loai_thong_bao' => $data['loai_thong_bao'],
            'pham_vi' => $data['pham_vi'],
            'kieu_gui' => $data['kieu_gui'],
            'ma_su_kien_lien_quan' => $data['ma_su_kien_lien_quan'] ?? null,
            'danh_sach_nguoi_nhan' => $data['pham_vi'] === 'tuy_chon' ? array_values($data['nguoi_nhan'] ?? []) : null,
            'thoi_gian_gui' => $scheduledAt,
            'trang_thai' => 'cho_gui',
            'ma_nguoi_tao' => auth()->id(),
        ]);

        if ($data['kieu_gui'] === 'ngay_lap_tuc') {
            $result = $scheduleService->dispatch($schedule);

            return back()->with(
                $result['success'] ? 'success' : 'error',
                $result['success']
                    ? "Đã gửi thông báo đến {$result['recipient_count']} người nhận."
                    : $result['message']
            );
        }

        return back()->with('success', 'Đã tạo lịch gửi thông báo.');
    }

    public function cancel($id)
    {
        $schedule = LichGuiThongBao::where('trang_thai', 'cho_gui')->findOrFail($id);
        $schedule->update(['trang_thai' => 'da_huy']);

        return back()->with('success', 'Đã hủy lịch gửi thông báo.');
    }
}
