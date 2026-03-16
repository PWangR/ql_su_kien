<?php

namespace App\Services;

use App\Models\ThongBao;
use App\Models\User;

class NotificationService
{
    /**
     * Tạo thông báo
     */
    public function createNotification($userId, $title, $content, $type = 'he_thong', $eventId = null)
    {
        return ThongBao::create([
            'ma_nguoi_dung' => $userId,
            'tieu_de' => $title,
            'noi_dung' => $content,
            'loai_thong_bao' => $type,
            'ma_su_kien_lien_quan' => $eventId,
            'da_doc' => false,
        ]);
    }

    /**
     * Gửi thông báo cho nhiều người
     */
    public function createBulkNotification(array $userIds, $title, $content, $type = 'he_thong', $eventId = null)
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = [
                'ma_nguoi_dung' => $userId,
                'tieu_de' => $title,
                'noi_dung' => $content,
                'loai_thong_bao' => $type,
                'ma_su_kien_lien_quan' => $eventId,
                'da_doc' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return ThongBao::insert($notifications);
    }

    /**
     * Lấy thông báo chưa đọc
     */
    public function getUnreadNotifications($userId)
    {
        return ThongBao::where('ma_nguoi_dung', $userId)
            ->where('da_doc', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Lấy tất cả thông báo
     */
    public function getUserNotifications($userId, $limit = 20, $page = 1)
    {
        return ThongBao::where('ma_nguoi_dung', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Đánh dấu là đã đọc
     */
    public function markAsRead($notificationId)
    {
        $notification = ThongBao::find($notificationId);
        if ($notification) {
            $notification->update(['da_doc' => true]);
        }
        return $notification;
    }

    /**
     * Đánh dấu tất cả là đã đọc
     */
    public function markAllAsRead($userId)
    {
        return ThongBao::where('ma_nguoi_dung', $userId)
            ->where('da_doc', false)
            ->update(['da_doc' => true]);
    }

    /**
     * Gửi thông báo sự kiện sắp diễn ra
     */
    public function sendEventReminderNotifications($eventId)
    {
        $event = \App\Models\SuKien::find($eventId);
        if (!$event) return false;

        $registrations = \App\Models\DangKy::where('ma_su_kien', $eventId)
            ->pluck('ma_nguoi_dung')
            ->toArray();

        $this->createBulkNotification(
            $registrations,
            "Sự kiện {$event->ten_su_kien} sắp diễn ra",
            "Sự kiện {$event->ten_su_kien} sẽ diễn ra vào " . $event->thoi_gian_bat_dau->format('d/m/Y H:i'),
            'nhac_nho_su_kien',
            $eventId
        );

        return true;
    }

    /**
     * Gửi thông báo cộng điểm
     */
    public function sendPointNotification($userId, $points, $reason, $eventId = null)
    {
        return $this->createNotification(
            $userId,
            "Bạn được cộng {$points} điểm",
            "Bạn được cộng {$points} điểm vì: {$reason}",
            'cap_nhat_diem',
            $eventId
        );
    }
}
