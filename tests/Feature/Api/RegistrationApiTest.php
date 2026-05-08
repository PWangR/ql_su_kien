<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\ChiTietDiemDanh;
use App\Models\DangKy;
use App\Models\User;
use App\Models\SuKien;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class RegistrationApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test đăng ký sự kiện
     */
    public function test_register_event()
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();

        $event = SuKien::factory()
            ->for($admin, 'nguoiTao')
            ->create(['so_luong_toi_da' => 100]);

        Sanctum::actingAs($student);
        $response = $this->postJson("/api/registrations/{$event->ma_su_kien}");

        $response->assertStatus(201)
            ->assertJsonPath('success', true);
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

        Sanctum::actingAs($student);

        $this->postJson("/api/registrations/{$event->ma_su_kien}");
        $response = $this->postJson("/api/registrations/{$event->ma_su_kien}");

        $response->assertStatus(400)
            ->assertJsonPath('success', false);
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
            ->create();

        Sanctum::actingAs($student);

        $this->postJson("/api/registrations/{$event->ma_su_kien}");
        $response = $this->deleteJson("/api/registrations/{$event->ma_su_kien}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    /**
     * Test lấy lịch sử tham gia
     */
    public function test_get_user_history()
    {
        $student = User::factory()->student()->create();

        \App\Models\DangKy::factory(3)
            ->for($student, 'nguoiDung')
            ->create();

        Sanctum::actingAs($student);
        $response = $this->getJson('/api/registrations/history');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data', 'pagination']);
    }

    public function test_mobile_admin_can_scan_student_qr()
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();

        $event = SuKien::factory()
            ->for($admin, 'nguoiTao')
            ->ongoing()
            ->create([
                'so_luong_toi_da' => 100,
                'diem_cong' => 10,
            ]);

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/admin/registrations/scan-student', [
            'ma_su_kien' => $event->ma_su_kien,
            'ma_sinh_vien' => $student->ma_sinh_vien,
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true);

        $registration = DangKy::where('ma_sinh_vien', $student->ma_sinh_vien)
            ->where('ma_su_kien', $event->ma_su_kien)
            ->first();

        $this->assertNotNull($registration);
        $this->assertSame(2, ChiTietDiemDanh::where('ma_dang_ky', $registration->ma_dang_ky)->count());
    }

    public function test_student_cannot_call_mobile_admin_scan_endpoint()
    {
        $student = User::factory()->student()->create();
        $targetStudent = User::factory()->student()->create();
        $event = SuKien::factory()->ongoing()->create(['so_luong_toi_da' => 100]);

        Sanctum::actingAs($student);

        $response = $this->postJson('/api/admin/registrations/scan-student', [
            'ma_su_kien' => $event->ma_su_kien,
            'ma_sinh_vien' => $targetStudent->ma_sinh_vien,
        ]);

        $response->assertStatus(403);
    }
}
