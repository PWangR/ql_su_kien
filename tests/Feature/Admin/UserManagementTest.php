<?php

namespace Tests\Feature\Admin;

use App\Models\SmtpSetting;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_user_with_avatar_and_send_verification_email(): void
    {
        Notification::fake();
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        SmtpSetting::create([
            'mail_host' => 'smtp.example.com',
            'mail_port' => 587,
            'mail_username' => 'mailer@example.com',
            'mail_password' => 'secret-password',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'noreply@example.com',
            'mail_from_name' => 'Event System',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.nguoi-dung.store'), [
            'ho_ten' => 'Nguyen Van B',
            'ma_sinh_vien' => '62131234',
            'lop' => '64.CNTT-1',
            'email' => 'student1@example.com',
            'vai_tro' => 'sinh_vien',
            'so_dien_thoai' => '0912345678',
            'mat_khau' => 'password123',
            'mat_khau_confirmation' => 'password123',
            'duong_dan_anh' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user = User::where('ma_sinh_vien', '62131234')->first();

        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);
        $this->assertNotNull($user->duong_dan_anh);
        Storage::disk('public')->assertExists($user->duong_dan_anh);

        Notification::assertSentTo($user, VerifyEmailNotification::class);
    }

    public function test_admin_cannot_create_user_when_smtp_is_not_ready(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.nguoi-dung.store'), [
            'ho_ten' => 'Nguyen Van C',
            'ma_sinh_vien' => '62139999',
            'lop' => '64.CNTT-1',
            'email' => 'student2@example.com',
            'vai_tro' => 'sinh_vien',
            'mat_khau' => 'password123',
            'mat_khau_confirmation' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('nguoi_dung', [
            'ma_sinh_vien' => '62139999',
        ]);
    }

    public function test_admin_can_filter_users_by_class(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->student()->create([
            'ho_ten' => 'Sinh Vien A',
            'lop' => '64.CNTT-1',
        ]);
        User::factory()->student()->create([
            'ho_ten' => 'Sinh Vien B',
            'lop' => '65.CNTT-2',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.nguoi-dung.index', [
            'lop' => '64.CNTT-1',
        ]));

        $response->assertOk();
        $response->assertSee('Sinh Vien A');
        $response->assertDontSee('Sinh Vien B');
    }
}
