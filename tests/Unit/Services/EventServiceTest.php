<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\EventService;
use App\Models\SuKien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $eventService;

    public function setUp(): void
    {
        parent::setUp();
        $this->eventService = app(EventService::class);
    }

    /**
     * Test lấy sự kiện sắp tới
     */
    public function test_get_upcoming_events()
    {
        $admin = User::factory()->admin()->create();

        SuKien::factory(3)
            ->for($admin, 'nguoiTao')
            ->upcoming()
            ->create();

        $events = $this->eventService->getUpcomingEvents(12, 1);

        $this->assertCount(3, $events->items());
    }

    /**
     * Test tạo sự kiện mới
     */
    public function test_create_event()
    {
        $admin = User::factory()->admin()->create();

        // LoaiSuKien sẽ được tạo tự động qua factory
        $loaiSuKien = \App\Models\LoaiSuKien::firstOrCreate(
            ['ten_loai' => 'Hội thảo'],
            ['mo_ta' => 'Các buổi hội thảo học thuật']
        );

        $data = [
            'ten_su_kien' => 'Test Event',
            'mo_ta_chi_tiet' => 'Test Description',
            'ma_loai_su_kien' => $loaiSuKien->ma_loai_su_kien,
            'thoi_gian_bat_dau' => now()->addDays(5),
            'thoi_gian_ket_thuc' => now()->addDays(5)->addHours(2),
            'dia_diem' => 'Test Location',
            'so_luong_toi_da' => 100,
            'diem_cong' => 10,
        ];

        $event = $this->eventService->createEvent($data, $admin->ma_nguoi_dung);

        $this->assertDatabaseHas('su_kien', [
            'ten_su_kien' => 'Test Event',
            'ma_nguoi_tao' => $admin->ma_nguoi_dung,
        ]);
    }

    /**
     * Test cập nhật sự kiện
     */
    public function test_update_event()
    {
        $admin = User::factory()->admin()->create();
        $event = SuKien::factory()
            ->for($admin, 'nguoiTao')
            ->create();

        $updated = $this->eventService->updateEvent($event, [
            'ten_su_kien' => 'Updated Event Name',
        ]);

        $this->assertTrue($updated);
        $this->assertEquals('Updated Event Name', $event->fresh()->ten_su_kien);
    }

    /**
     * Test kiểm tra trùng lịch
     */
    public function test_check_schedule_conflict()
    {
        $admin = User::factory()->admin()->create();

        $event = SuKien::factory()
            ->for($admin, 'nguoiTao')
            ->create([
                'dia_diem' => 'Room A',
                'thoi_gian_bat_dau' => now()->addDays(1)->setHour(10),
                'thoi_gian_ket_thuc' => now()->addDays(1)->setHour(12),
            ]);

        // Kiểm tra có trùng lịch
        $conflict = $this->eventService->checkScheduleConflict(
            now()->addDays(1)->setHour(11),
            now()->addDays(1)->setHour(13),
            'Room A'
        );

        $this->assertTrue($conflict);
    }

    /**
     * Test thống kê sự kiện
     */
    public function test_event_statistics()
    {
        $admin = User::factory()->admin()->create();

        SuKien::factory(3)
            ->for($admin, 'nguoiTao')
            ->create();

        $stats = $this->eventService->getEventStatistics();

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_events', $stats);
        $this->assertGreaterThanOrEqual(3, $stats['total_events']);
    }
}
