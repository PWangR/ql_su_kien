<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuKien;

class SyncEventStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-event-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật trạng thái sự kiện trong database dựa trên thời gian thực';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu đồng bộ trạng thái sự kiện...');

        $suKiens = SuKien::where('trang_thai', '!=', 'huy')->get();
        $updatedCount = 0;

        foreach ($suKiens as $sk) {
            $currentStatus = $sk->trang_thai;
            $newStatus = $sk->trang_thai_thuc_te;

            if ($currentStatus !== $newStatus) {
                $sk->update(['trang_thai' => $newStatus]);
                $updatedCount++;
            }
        }

        $this->info("Đã cập nhật {$updatedCount} sự kiện.");
    }
}
