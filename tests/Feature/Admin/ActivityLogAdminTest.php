<?php

namespace Tests\Feature\Admin;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_only_records_logs_for_admin_accounts(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();

        $this->actingAs($admin);
        ActivityLog::log('update', 'Admin updated settings');

        $this->actingAs($student);
        ActivityLog::log('update', 'Student updated profile');

        $this->assertDatabaseCount('activity_logs', 1);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->ma_sinh_vien,
            'description' => 'Admin updated settings',
        ]);
        $this->assertDatabaseMissing('activity_logs', [
            'user_id' => $student->ma_sinh_vien,
            'description' => 'Student updated profile',
        ]);
    }

    public function test_activity_log_page_only_shows_admin_logs_and_normalizes_date_range(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->student()->create();

        $this->actingAs($admin);
        ActivityLog::log('update', 'Admin log entry');

        $this->actingAs($student);
        ActivityLog::log('update', 'Student log entry');

        $this->actingAs($admin);
        $response = $this->get('/admin/activity-logs?from=' . now()->addDay()->format('Y-m-d') . '&to=' . now()->subDay()->format('Y-m-d'));

        $response->assertOk();
        $response->assertSee('Admin log entry');
        $response->assertDontSee('Student log entry');
        $response->assertSee('Khoảng ngày đã được tự động chuẩn hóa', false);
    }
}
