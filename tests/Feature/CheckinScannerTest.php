<?php

namespace Tests\Feature;

use App\Models\DangKy;
use App\Models\SuKien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckinScannerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_open_checkin_scanner_page(): void
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->get(route('events.scanner'));

        $response->assertOk();
        $response->assertSee('Quét QR điểm danh');
    }

    public function test_authenticated_user_can_check_in_by_scanning_admin_qr(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $event = SuKien::factory()->for($admin, 'nguoiTao')->create([
            'trang_thai' => 'dang_dien_ra',
            'so_luong_toi_da' => 100,
        ]);

        $response = $this->actingAs($student)->postJson(route('events.process-scanner'), [
            'action' => 'diem_danh',
            'ma_su_kien' => $event->ma_su_kien,
            'diff' => 1000,
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('dang_ky', [
            'ma_sinh_vien' => $student->ma_sinh_vien,
            'ma_su_kien' => $event->ma_su_kien,
            'trang_thai_tham_gia' => 'da_tham_gia',
        ]);
    }

    public function test_scanner_rejects_expired_qr_payload(): void
    {
        $student = User::factory()->student()->create();

        $response = $this->actingAs($student)->postJson(route('events.process-scanner'), [
            'action' => 'diem_danh',
            'ma_su_kien' => 1,
            'diff' => 15000,
        ]);

        $response->assertStatus(400)
            ->assertJsonPath('success', false);
    }

    public function test_admin_scanner_can_mark_student_qr_as_attended(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();
        $event = SuKien::factory()->for($admin, 'nguoiTao')->create([
            'trang_thai' => 'dang_dien_ra',
            'so_luong_toi_da' => 100,
        ]);

        DangKy::create([
            'ma_sinh_vien' => $student->ma_sinh_vien,
            'ma_su_kien' => $event->ma_su_kien,
            'trang_thai_tham_gia' => 'da_dang_ky',
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.diem-danh.process-scanner'), [
            'mssv' => $student->ma_sinh_vien,
            'ma_su_kien' => $event->ma_su_kien,
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('dang_ky', [
            'ma_sinh_vien' => $student->ma_sinh_vien,
            'ma_su_kien' => $event->ma_su_kien,
            'trang_thai_tham_gia' => 'da_tham_gia',
        ]);
    }
}
