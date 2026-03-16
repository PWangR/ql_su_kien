<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\SuKien;
use App\Models\LoaiSuKien;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class EventApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test lấy danh sách sự kiện
     */
    public function test_get_events_list()
    {
        $admin = User::factory()->admin()->create();
        SuKien::factory(5)
            ->for($admin, 'nguoiTao')
            ->upcoming()
            ->create();

        $response = $this->getJson('/api/events');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'pagination' => ['current_page', 'per_page', 'total', 'last_page']
            ]);
    }

    /**
     * Test lấy chi tiết sự kiện
     */
    public function test_get_event_detail()
    {
        $admin = User::factory()->admin()->create();
        $event = SuKien::factory()
            ->for($admin, 'nguoiTao')
            ->create();

        $response = $this->getJson("/api/events/{$event->ma_su_kien}");

        $response->assertStatus(200)
            ->assertJsonPath('data.ten_su_kien', $event->ten_su_kien);
    }

    /**
     * Test tìm kiếm sự kiện
     */
    public function test_search_events()
    {
        $admin = User::factory()->admin()->create();
        SuKien::factory()
            ->for($admin, 'nguoiTao')
            ->create(['ten_su_kien' => 'Seminar Web Development']);

        $response = $this->getJson('/api/events/search/Web');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /**
     * Test tạo sự kiện (admin only)
     */
    public function test_create_event_unauthorized()
    {
        $student = User::factory()->student()->create();
        Sanctum::actingAs($student);

        $response = $this->postJson('/api/admin/events', [
            'ten_su_kien' => 'New Event',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test tạo sự kiện (admin)
     */
    public function test_create_event_as_admin()
    {
        $admin = User::factory()->admin()->create();

        // Tạo LoaiSuKien
        $loaiSuKien = LoaiSuKien::firstOrCreate(
            ['ten_loai' => 'Hội thảo'],
            ['mo_ta' => 'Các buổi hội thảo học thuật']
        );

        Sanctum::actingAs($admin);

        $eventData = [
            'ten_su_kien' => 'New Seminar',
            'mo_ta_chi_tiet' => 'Description',
            'ma_loai_su_kien' => $loaiSuKien->ma_loai_su_kien,
            'thoi_gian_bat_dau' => now()->addDays(5)->format('Y-m-d H:i'),
            'thoi_gian_ket_thuc' => now()->addDays(5)->addHours(2)->format('Y-m-d H:i'),
            'dia_diem' => 'Room A',
            'so_luong_toi_da' => 100,
            'diem_cong' => 10,
        ];

        $response = $this->postJson('/api/admin/events', $eventData);

        $response->assertStatus(201)
            ->assertJsonPath('data.ten_su_kien', 'New Seminar');
    }

    /**
     * Test cập nhật sự kiện
     */
    public function test_update_event()
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $event = SuKien::factory()
            ->for($admin, 'nguoiTao')
            ->create();

        $response = $this->putJson("/api/admin/events/{$event->ma_su_kien}", [
            'ten_su_kien' => 'Updated Event',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.ten_su_kien', 'Updated Event');
    }

    /**
     * Test xóa sự kiện
     */
    public function test_delete_event()
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $event = SuKien::factory()
            ->for($admin, 'nguoiTao')
            ->create();

        $response = $this->deleteJson("/api/admin/events/{$event->ma_su_kien}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('su_kien', ['ma_su_kien' => $event->ma_su_kien]);
    }
}
