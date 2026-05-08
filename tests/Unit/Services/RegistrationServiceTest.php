<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\RegistrationService;
use App\Models\ChiTietDiemDanh;
use App\Models\DangKy;
use App\Models\LichSuDiem;
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
            $student->ma_sinh_vien,
            $event->ma_su_kien
        );

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('dang_ky', [
            'ma_sinh_vien' => $student->ma_sinh_vien,
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
            $student->ma_sinh_vien,
            $event->ma_su_kien
        );

        $result = $this->registrationService->registerEvent(
            $student->ma_sinh_vien,
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
            'ma_sinh_vien' => $student->ma_sinh_vien,
            'ma_su_kien' => $event->ma_su_kien,
            'trang_thai_tham_gia' => 'da_dang_ky',
        ]);

        $result = $this->registrationService->cancelRegistration(
            $student->ma_sinh_vien,
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
            $student->ma_sinh_vien,
            10,
            1
        );

        $this->assertCount(3, $history->items());
    }

    public function test_check_in_twice_adds_points_once()
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();

        $event = SuKien::factory()
            ->for($admin, 'nguoiTao')
            ->ongoing()
            ->create([
                'so_luong_toi_da' => 100,
                'so_luong_hien_tai' => 0,
                'diem_cong' => 15,
            ]);

        $first = $this->registrationService->checkInEvent(
            $student->ma_sinh_vien,
            $event->ma_su_kien,
            'dau_buoi'
        );

        $second = $this->registrationService->checkInEvent(
            $student->ma_sinh_vien,
            $event->ma_su_kien,
            'cuoi_buoi'
        );

        $this->assertTrue($first['success']);
        $this->assertTrue($second['success']);
        $this->assertTrue($second['data']['da_cong_diem']);

        $registration = DangKy::where('ma_sinh_vien', $student->ma_sinh_vien)
            ->where('ma_su_kien', $event->ma_su_kien)
            ->first();

        $this->assertDatabaseHas('lich_su_diem', [
            'ma_sinh_vien' => $student->ma_sinh_vien,
            'ma_dang_ky' => $registration->ma_dang_ky,
            'diem' => 15,
            'nguon' => 'tham_gia_su_kien',
        ]);

        $duplicate = $this->registrationService->checkInEvent(
            $student->ma_sinh_vien,
            $event->ma_su_kien,
            'cuoi_buoi'
        );

        $this->assertFalse($duplicate['success']);
        $this->assertSame(1, LichSuDiem::where('ma_dang_ky', $registration->ma_dang_ky)->count());
    }

    public function test_admin_check_in_student_marks_both_sessions_and_adds_points_once()
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();

        $event = SuKien::factory()
            ->for($admin, 'nguoiTao')
            ->ongoing()
            ->create([
                'so_luong_toi_da' => 100,
                'so_luong_hien_tai' => 0,
                'diem_cong' => 20,
            ]);

        $result = $this->registrationService->adminCheckInStudent(
            $student->ma_sinh_vien,
            $event->ma_su_kien
        );

        $this->assertTrue($result['success']);
        $this->assertTrue($result['data']['da_cong_diem']);

        $registration = DangKy::where('ma_sinh_vien', $student->ma_sinh_vien)
            ->where('ma_su_kien', $event->ma_su_kien)
            ->first();

        $checkins = ChiTietDiemDanh::where('ma_dang_ky', $registration->ma_dang_ky)
            ->orderBy('loai_diem_danh')
            ->get();

        $this->assertCount(2, $checkins);
        $this->assertEqualsCanonicalizing(['cuoi_buoi', 'dau_buoi'], $checkins->pluck('loai_diem_danh')->all());
        $this->assertTrue($checkins[0]->diem_danh_at->equalTo($checkins[1]->diem_danh_at));

        $this->assertDatabaseHas('lich_su_diem', [
            'ma_sinh_vien' => $student->ma_sinh_vien,
            'ma_dang_ky' => $registration->ma_dang_ky,
            'diem' => 20,
            'nguon' => 'tham_gia_su_kien',
        ]);

        $duplicate = $this->registrationService->adminCheckInStudent(
            $student->ma_sinh_vien,
            $event->ma_su_kien
        );

        $this->assertFalse($duplicate['success']);
        $this->assertSame(1, LichSuDiem::where('ma_dang_ky', $registration->ma_dang_ky)->count());
    }
}
