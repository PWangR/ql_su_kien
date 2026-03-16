<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\RegistrationService;
use App\Models\DangKy;
use App\Models\SuKien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $registrationService;

    public function setUp(): void
    {
        parent::setUp();
        $this->registrationService = app(RegistrationService::class);
    }

    /**
     * Test đăng ký tham gia sự kiện
     */
    public function test_register_event_success()
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();

        $event = SuKien::factory()
            ->for($admin, 'nguoiTao')
            ->create([
                'so_luong_toi_da' => 100,
                'so_luong_hien_tai' => 0,
            ]);

        $result = $this->registrationService->registerEvent(
            $student->ma_nguoi_dung,
            $event->ma_su_kien
        );

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('dang_ky', [
            'ma_nguoi_dung' => $student->ma_nguoi_dung,
            'ma_su_kien' => $event->ma_su_kien,
        ]);
    }

    /**
     * Test không thể đăng ký 2 lần
     */
    public function test_cannot_register_twice()
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();

        $event = SuKien::factory()
            ->for($admin, 'nguoiTao')
            ->create(['so_luong_toi_da' => 100]);

        $this->registrationService->registerEvent(
            $student->ma_nguoi_dung,
            $event->ma_su_kien
        );

        $result = $this->registrationService->registerEvent(
            $student->ma_nguoi_dung,
            $event->ma_su_kien
        );

        $this->assertFalse($result['success']);
    }

    /**
     * Test hủy đăng ký
     */
    public function test_cancel_registration()
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();

        $event = SuKien::factory()
            ->for($admin, 'nguoiTao')
            ->create(['so_luong_toi_da' => 100]);

        DangKy::create([
            'ma_nguoi_dung' => $student->ma_nguoi_dung,
            'ma_su_kien' => $event->ma_su_kien,
            'trang_thai_tham_gia' => 'da_dang_ky',
        ]);

        $result = $this->registrationService->cancelRegistration(
            $student->ma_nguoi_dung,
            $event->ma_su_kien
        );

        $this->assertTrue($result['success']);
    }

    /**
     * Test cập nhật trạng thái tham gia
     */
    public function test_update_participation_status()
    {
        $registration = DangKy::factory()->create();

        $result = $this->registrationService->updateParticipationStatus(
            $registration->ma_dang_ky,
            'da_tham_gia'
        );

        $this->assertTrue($result['success']);
        $this->assertEquals('da_tham_gia', $registration->fresh()->trang_thai_tham_gia);
    }

    /**
     * Test lấy lịch sử tham gia
     */
    public function test_get_user_event_history()
    {
        $student = User::factory()->student()->create();

        DangKy::factory(3)
            ->for($student, 'nguoiDung')
            ->create();

        $history = $this->registrationService->getUserEventHistory(
            $student->ma_nguoi_dung,
            10,
            1
        );

        $this->assertCount(3, $history->items());
    }
}
