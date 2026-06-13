<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_student_can_open_home_page(): void
    {
        $student = User::factory()->student()->create();

        $this->actingAs($student)
            ->get(route('home'))
            ->assertOk()
            ->assertSee(route('chatbot.ask'), false);
    }
}
