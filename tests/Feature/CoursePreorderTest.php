<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CoursePreorder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoursePreorderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_creates_single_preorder_and_subsequent_request_updates_it_using_ip(): void
    {
        $course = Course::factory()->upcoming()->create();

        $payload = [
            'name' => 'Иван',
            'contact' => '@ivanov',
        ];

        $firstResponse = $this
            ->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->postJson(route('courses.preorders.store', $course), $payload);

        $firstResponse
            ->assertOk()
            ->assertJsonFragment([
                'message' => 'Заявка отправлена. Мы свяжемся с вами в ближайшее время!',
            ]);

        $preorderId = $firstResponse->json('preorder_id');
        $this->assertNotNull($preorderId);

        $this->assertDatabaseHas('course_preorders', [
            'course_id' => $course->id,
            'contact' => '@ivanov',
            'name' => 'Иван',
            'user_id' => null,
            'ip_address' => '203.0.113.10',
        ]);

        $secondResponse = $this
            ->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->postJson(route('courses.preorders.store', $course), [
                'name' => 'Иван',
                'contact' => '@ivanov-updated',
                'preorder_id' => $preorderId,
            ]);

        $secondResponse
            ->assertOk()
            ->assertJsonFragment([
                'message' => 'Данные заявки обновлены. Мы свяжемся с вами в ближайшее время!',
                'preorder_id' => $preorderId,
            ]);

        $this->assertSame(1, CoursePreorder::query()->where('course_id', $course->id)->count());

        $this->assertDatabaseHas('course_preorders', [
            'course_id' => $course->id,
            'contact' => '@ivanov-updated',
            'ip_address' => '203.0.113.10',
        ]);
    }

    public function test_authenticated_user_can_only_have_one_preorder_per_course(): void
    {
        $course = Course::factory()->upcoming()->create();
        $user = User::factory()->create();

        $firstResponse = $this
            ->actingAs($user)
            ->postJson(route('courses.preorders.store', $course), [
                'contact' => '@firstcontact',
            ]);

        $firstResponse
            ->assertOk()
            ->assertJsonFragment([
                'message' => 'Заявка отправлена. Мы свяжемся с вами в ближайшее время!',
            ]);

        $this->assertDatabaseHas('course_preorders', [
            'course_id' => $course->id,
            'user_id' => $user->id,
            'contact' => '@firstcontact',
            'ip_address' => '127.0.0.1',
        ]);

        $secondResponse = $this
            ->actingAs($user)
            ->postJson(route('courses.preorders.store', $course), [
                'contact' => '@updatedcontact',
            ]);

        $secondResponse
            ->assertOk()
            ->assertJsonFragment([
                'message' => 'Данные заявки обновлены. Мы свяжемся с вами в ближайшее время!',
            ]);

        $this->assertSame(1, CoursePreorder::query()
            ->where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->count());

        $this->assertDatabaseHas('course_preorders', [
            'course_id' => $course->id,
            'user_id' => $user->id,
            'contact' => '@updatedcontact',
            'ip_address' => '127.0.0.1',
        ]);
    }

    public function test_second_guest_from_same_ip_receives_error(): void
    {
        $course = Course::factory()->upcoming()->create();

        $this
            ->withServerVariables(['REMOTE_ADDR' => '198.51.100.5'])
            ->postJson(route('courses.preorders.store', $course), [
                'name' => 'Алексей',
                'contact' => '@alex',
            ])
            ->assertOk();

        $this
            ->withServerVariables(['REMOTE_ADDR' => '198.51.100.5'])
            ->postJson(route('courses.preorders.store', $course), [
                'name' => 'Мария',
                'contact' => '@maria',
            ])
            ->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'Вы уже отправили заявку на этот курс.',
            ])
            ->assertJsonValidationErrors(['ip_address']);

        $this->assertDatabaseHas('course_preorders', [
            'course_id' => $course->id,
            'contact' => '@alex',
            'ip_address' => '198.51.100.5',
        ]);
    }

    public function test_contact_cannot_be_reused_by_another_user_for_the_same_course(): void
    {
        $course = Course::factory()->upcoming()->create();
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this
            ->actingAs($user)
            ->postJson(route('courses.preorders.store', $course), [
                'contact' => '@sharedcontact',
            ])
            ->assertOk();

        $this
            ->actingAs($otherUser)
            ->postJson(route('courses.preorders.store', $course), [
                'contact' => '@sharedcontact',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['contact']);
    }

    public function test_cannot_preorder_a_course_that_has_already_started(): void
    {
        $course = Course::factory()->started()->create();

        $this
            ->postJson(route('courses.preorders.store', $course), [
                'name' => 'Мария',
                'contact' => '@contact',
            ])
            ->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'Курс уже доступен для просмотра.',
            ]);
    }
}
