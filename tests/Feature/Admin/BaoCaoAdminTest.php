<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaoCaoAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_does_not_export_when_no_matching_report_data_exists(): void
    {
        $admin = User::factory()->admin()->create();

        User::factory()->student()->create([
            'lop' => '64.CNTT-1',
        ]);

        $response = $this->actingAs($admin)->post('/admin/bao-cao/export', [
            'lop' => '64.CNTT-1',
            'from_date' => now()->addDays(30)->format('Y-m-d'),
            'to_date' => now()->addDays(31)->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Không có dữ liệu phù hợp để xuất báo cáo cho bộ lọc đã chọn.');
    }
}
