<?php

namespace App\Services;

use App\Models\DangKy;
use App\Models\LichGuiThongBao;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminNotificationScheduleService
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function dispatch(LichGuiThongBao $schedule): array
    {
        if ($schedule->trang_thai !== 'cho_gui') {
            return [
                'success' => false,
                'message' => 'Lịch gửi không còn ở trạng thái chờ gửi.',
                'recipient_count' => $schedule->so_nguoi_nhan,
            ];
        }

        return DB::transaction(function () use ($schedule) {
            $schedule = LichGuiThongBao::whereKey($schedule->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($schedule->trang_thai !== 'cho_gui') {
                return [
                    'success' => false,
                    'message' => 'Lịch gửi đã được xử lý trước đó.',
                    'recipient_count' => $schedule->so_nguoi_nhan,
                ];
            }

            $recipients = $this->resolveRecipients($schedule);

            if (empty($recipients)) {
                $schedule->update([
                    'trang_thai' => 'loi',
                    'loi' => 'Không có người nhận phù hợp.',
                    'so_nguoi_nhan' => 0,
                ]);

                return [
                    'success' => false,
                    'message' => 'Không có người nhận phù hợp.',
                    'recipient_count' => 0,
                ];
            }

            $this->notificationService->createBulkNotification(
                $recipients,
                $schedule->tieu_de,
                $schedule->noi_dung,
                $schedule->loai_thong_bao,
                $schedule->ma_su_kien_lien_quan
            );

            $schedule->update([
                'trang_thai' => 'da_gui',
                'thoi_gian_da_gui' => now(),
                'so_nguoi_nhan' => count($recipients),
                'loi' => null,
            ]);

            return [
                'success' => true,
                'message' => 'Đã gửi thông báo.',
                'recipient_count' => count($recipients),
            ];
        });
    }

    public function resolveRecipients(LichGuiThongBao $schedule): array
    {
        return match ($schedule->pham_vi) {
            'tat_ca' => User::whereNull('deleted_at')
                ->where('trang_thai', 'hoat_dong')
                ->pluck('ma_sinh_vien')
                ->all(),
            'nguoi_dang_ky_su_kien' => DangKy::where('ma_su_kien', $schedule->ma_su_kien_lien_quan)
                ->whereNull('deleted_at')
                ->whereHas('nguoiDung', function ($query) {
                    $query->whereNull('deleted_at')
                        ->where('trang_thai', 'hoat_dong');
                })
                ->pluck('ma_sinh_vien')
                ->unique()
                ->values()
                ->all(),
            'tuy_chon' => User::whereIn('ma_sinh_vien', $schedule->danh_sach_nguoi_nhan ?: [])
                ->whereNull('deleted_at')
                ->where('trang_thai', 'hoat_dong')
                ->pluck('ma_sinh_vien')
                ->all(),
            default => [],
        };
    }
}
