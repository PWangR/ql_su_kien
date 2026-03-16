<?php

namespace App\Services;

use App\Models\SuKien;
use App\Models\LoaiSuKien;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventService
{
    /**
     * Lấy tất cả sự kiện sắp tới
     */
    public function getUpcomingEvents($limit = 12, $page = 1)
    {
        return SuKien::where('trang_thai', '!=', 'huy')
            ->where('thoi_gian_bat_dau', '>', now())
            ->orderBy('thoi_gian_bat_dau', 'asc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Lấy sự kiện đang diễn ra
     */
    public function getOngoingEvents()
    {
        return SuKien::where('trang_thai', '!=', 'huy')
            ->where('thoi_gian_bat_dau', '<=', now())
            ->where('thoi_gian_ket_thuc', '>=', now())
            ->orderBy('thoi_gian_bat_dau', 'asc')
            ->get();
    }

    /**
     * Tạo sự kiện mới
     */
    public function createEvent(array $data, $userId)
    {
        // Upload ảnh nếu có
        if (isset($data['anh_su_kien'])) {
            $imagePath = $data['anh_su_kien']->store('events', 'public');
            $data['anh_su_kien'] = $imagePath;
        }

        $data['ma_nguoi_tao'] = $userId;
        $data['ma_nguoi_to_chuc'] = $userId;
        $data['trang_thai'] = 'sap_to_chuc';

        return SuKien::create($data);
    }

    /**
     * Cập nhật sự kiện
     */
    public function updateEvent(SuKien $event, array $data)
    {
        // Upload ảnh mới nếu có
        if (isset($data['anh_su_kien'])) {
            // Xóa ảnh cũ
            if ($event->anh_su_kien) {
                Storage::disk('public')->delete($event->anh_su_kien);
            }
            $imagePath = $data['anh_su_kien']->store('events', 'public');
            $data['anh_su_kien'] = $imagePath;
        }

        return $event->update($data);
    }

    /**
     * Xóa sự kiện (soft delete)
     */
    public function deleteEvent(SuKien $event)
    {
        if ($event->anh_su_kien) {
            Storage::disk('public')->delete($event->anh_su_kien);
        }
        return $event->delete();
    }

    /**
     * Khôi phục sự kiện đã xóa
     */
    public function restoreEvent($eventId)
    {
        return SuKien::onlyTrashed()->find($eventId)?->restore();
    }

    /**
     * Lấy tất cả loại sự kiện
     */
    public function getEventTypes()
    {
        return LoaiSuKien::all();
    }

    /**
     * Kiểm tra trùng lịch
     */
    public function checkScheduleConflict($startTime, $endTime, $location, $excludeEventId = null)
    {
        $query = SuKien::where('dia_diem', $location)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('thoi_gian_bat_dau', [$startTime, $endTime])
                    ->orWhereBetween('thoi_gian_ket_thuc', [$startTime, $endTime])
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('thoi_gian_bat_dau', '<', $startTime)
                            ->where('thoi_gian_ket_thuc', '>', $endTime);
                    });
            });

        if ($excludeEventId) {
            $query->where('ma_su_kien', '!=', $excludeEventId);
        }

        return $query->exists();
    }

    /**
     * Cập nhật trạng thái sự kiện theo thời gian
     */
    public function syncEventStatus()
    {
        $now = now();

        // Cập nhật sự kiện đang diễn ra
        SuKien::where('trang_thai', 'sap_to_chuc')
            ->where('thoi_gian_bat_dau', '<=', $now)
            ->where('thoi_gian_ket_thuc', '>=', $now)
            ->update(['trang_thai' => 'dang_dien_ra']);

        // Cập nhật sự kiện đã kết thúc
        SuKien::where('trang_thai', 'dang_dien_ra')
            ->where('thoi_gian_ket_thuc', '<', $now)
            ->update(['trang_thai' => 'da_ket_thuc']);
    }

    /**
     * Lấy sự kiện theo ID với eager loading
     */
    public function getEventById($id)
    {
        return SuKien::with([
            'loaiSuKien',
            'nguoiTao',
            'dangKy.nguoiDung',
            'media'
        ])->find($id);
    }

    /**
     * Thống kê sự kiện
     */
    public function getEventStatistics()
    {
        return [
            'total_events' => SuKien::count(),
            'upcoming_events' => SuKien::where('thoi_gian_bat_dau', '>', now())->count(),
            'ongoing_events' => SuKien::where('thoi_gian_bat_dau', '<=', now())
                ->where('thoi_gian_ket_thuc', '>=', now())->count(),
            'completed_events' => SuKien::where('thoi_gian_ket_thuc', '<', now())->count(),
            'total_registrations' => \App\Models\DangKy::count(),
            'total_participants' => \App\Models\DangKy::where('trang_thai_tham_gia', 'da_tham_gia')->count(),
        ];
    }
}
