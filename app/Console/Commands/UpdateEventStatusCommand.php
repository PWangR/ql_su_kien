<?php

namespace App\Console\Commands;

use App\Models\SuKien;
use App\Services\RegistrationService;
use Illuminate\Console\Command;

class UpdateEventStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:update-status {--event-id= : ID của sự kiện cần cập nhật}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật trạng thái tham gia của người dùng khi sự kiện kết thúc';

    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        parent::__construct();
        $this->registrationService = $registrationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eventId = $this->option('event-id');

        if ($eventId) {
            // Cập nhật sự kiện cụ thể
            $event = SuKien::find($eventId);
            if (!$event) {
                $this->error("Sự kiện ID {$eventId} không tồn tại");
                return 1;
            }

            $result = $this->registrationService->updateStatusAfterEventEnds($eventId);
            if ($result['success']) {
                $this->info($result['message']);
                return 0;
            } else {
                $this->warn($result['message']);
                return 1;
            }
        }

        // Cập nhật tất cả sự kiện đã kết thúc
        $events = SuKien::where('thoi_gian_ket_thuc', '<=', now())
            ->where('trang_thai', '!=', 'huy')
            ->get();

        if ($events->isEmpty()) {
            $this->info('Không có sự kiện nào cần cập nhật');
            return 0;
        }

        $bar = $this->output->createProgressBar($events->count());
        $bar->start();

        foreach ($events as $event) {
            $this->registrationService->updateStatusAfterEventEnds($event->ma_su_kien);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Đã cập nhật {$events->count()} sự kiện");

        return 0;
    }
}
