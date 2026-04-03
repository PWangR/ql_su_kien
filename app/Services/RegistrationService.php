<?php

namespace App\Services;

use App\Models\DangKy;
use App\Models\LichSuDiem;
use App\Models\SuKien;

class RegistrationService
{
    /**
     * Đăng ký tham gia sự kiện
     */
    public function registerEvent($userId, $eventId)
    {
        $event = SuKien::find($eventId);

        if (!$event) {
            return ['success' => false, 'message' => 'Sự kiện không tồn tại'];
        }

        if ($event->so_luong_hien_tai >= $event->so_luong_toi_da) {
            return ['success' => false, 'message' => 'Sự kiện đã đủ số lượng'];
        }

        // Kiểm tra đã đăng ký chưa
        $existingRegistration = DangKy::where('ma_sinh_vien', $userId)
            ->where('ma_su_kien', $eventId)
            ->whereNull('deleted_at')
            ->first();

        if ($existingRegistration) {
            return ['success' => false, 'message' => 'Bạn đã đăng ký sự kiện này'];
        }

        $registration = DangKy::create([
            'ma_sinh_vien' => $userId,
            'ma_su_kien' => $eventId,
            'trang_thai_tham_gia' => 'da_dang_ky',
        ]);

        // Cập nhật số lượng hiện tại
        $event->increment('so_luong_hien_tai');

        return ['success' => true, 'message' => 'Đăng ký thành công', 'data' => $registration];
    }

    /**
     * Hủy đăng ký
     */
    public function cancelRegistration($userId, $eventId)
    {
        $registration = DangKy::where('ma_sinh_vien', $userId)
            ->where('ma_su_kien', $eventId)
            ->first();

        if (!$registration) {
            return ['success' => false, 'message' => 'Đăng ký không tồn tại'];
        }

        $registration->delete();

        // Cập nhật số lượng hiện tại
        SuKien::find($eventId)?->decrement('so_luong_hien_tai');

        return ['success' => true, 'message' => 'Hủy đăng ký thành công'];
    }

    /**
     * Cập nhật trạng thái tham gia
     */
    public function updateParticipationStatus($registrationId, $status)
    {
        $registration = DangKy::find($registrationId);

        if (!$registration) {
            return ['success' => false, 'message' => 'Đăng ký không tồn tại'];
        }

        $validStatuses = ['da_dang_ky', 'da_tham_gia', 'vang_mat', 'huy'];
        if (!in_array($status, $validStatuses)) {
            return ['success' => false, 'message' => 'Trạng thái không hợp lệ'];
        }

        $registration->update(['trang_thai_tham_gia' => $status]);

        // Nếu đã tham gia, cộng điểm
        if ($status === 'da_tham_gia' && !LichSuDiem::where('ma_dang_ky', $registrationId)->exists()) {
            $event = $registration->suKien;
            LichSuDiem::create([
                'ma_sinh_vien' => $registration->ma_sinh_vien,
                'ma_su_kien' => $registration->ma_su_kien,
                'ma_dang_ky' => $registrationId,
                'diem' => $event->diem_cong,
                'nguon' => 'tham_gia_su_kien',
            ]);
        }

        return ['success' => true, 'message' => 'Cập nhật trạng thái thành công'];
    }

    /**
     * Lấy lịch sử tham gia của người dùng
     */
    public function getUserEventHistory($userId, $limit = 10, $page = 1)
    {
        return DangKy::where('ma_sinh_vien', $userId)
            ->with(['suKien', 'suKien.loaiSuKien'])
            ->orderBy('thoi_gian_dang_ky', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Lấy danh sách người tham gia sự kiện
     */
    public function getEventParticipants($eventId)
    {
        return DangKy::where('ma_su_kien', $eventId)
            ->with('nguoiDung')
            ->get();
    }
}
