<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

/**
 * Artisan command xóa activity logs cũ hơn 30 ngày
 * Chạy thủ công: php artisan logs:prune
 * Hoặc schedule trong Kernel.php
 */
class PruneActivityLogs extends Command
{
    protected $signature = 'logs:prune {--days=30 : Số ngày giữ lại log}';

    protected $description = 'Xóa activity logs cũ hơn số ngày chỉ định (mặc định: 30 ngày)';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        $deleted = ActivityLog::pruneOlderThan($days);

        $this->info("Đã xóa {$deleted} bản ghi log cũ hơn {$days} ngày.");

        return Command::SUCCESS;
    }
}
