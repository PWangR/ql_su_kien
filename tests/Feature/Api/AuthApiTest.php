<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_student_can_login_with_api(): void
    {
        $student = User::factory()->student()->create([
            'email' => 'student@example.com',
            'email_verified_at' => now(),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'student@example.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.ma_sinh_vien', $student->ma_sinh_vien)
            ->assertJsonStructure([
                'data' => ['token', 'user'],
            ]);
    }

    public function test_unverified_student_cannot_login_with_api(): void
    {
        User::factory()->student()->create([
            'email' => 'unverified@example.com',
            'email_verified_at' => null,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'unverified@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('success', false);
    }

    public function test_mobile_register_creates_student_and_sends_verification_email(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/register', [
            'ho_ten' => 'Nguyen Van A',
            'ma_sinh_vien' => '64123456',
            'lop' => '64.CNTT-1',
            'email' => 'new-student@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.ma_sinh_vien', '64123456');

        $this->assertDatabaseHas('nguoi_dung', [
            'ma_sinh_vien' => '64123456',
            'email' => 'new-student@example.com',
            'vai_tro' => 'sinh_vien',
        ]);

        $user = User::find('64123456');
        Notification::assertSentTo($user, VerifyEmailNotification::class);
    }

    public function test_mobile_can_resend_verification_email(): void
    {
        Notification::fake();

        $student = User::factory()->student()->create([
            'email' => 'need-verify@example.com',
            'email_verified_at' => null,
        ]);

        $response = $this->postJson('/api/email/resend', [
            'email' => 'need-verify@example.com',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true);

        Notification::assertSentTo($student, VerifyEmailNotification::class);
    }
}
