<?php

namespace App\Console\Commands;

use App\Models\LichGuiThongBao;
use App\Services\AdminNotificationScheduleService;
use Illuminate\Console\Command;

class SendScheduledNotifications extends Command
{
    protected $signature = 'notifications:send-due';

    protected $description = 'Send admin-created notifications that are due.';

    public function handle(AdminNotificationScheduleService $service): int
    {
        $schedules = LichGuiThongBao::where('trang_thai', 'cho_gui')
            ->whereNotNull('thoi_gian_gui')
            ->where('thoi_gian_gui', '<=', now())
            ->orderBy('thoi_gian_gui')
            ->limit(50)
            ->get();

        foreach ($schedules as $schedule) {
            $result = $service->dispatch($schedule);
            $this->line("#{$schedule->ma_lich_gui}: {$result['message']} ({$result['recipient_count']} người nhận)");
        }

        return self::SUCCESS;
    }
}
