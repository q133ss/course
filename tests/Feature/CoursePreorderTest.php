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

    public function test_guest_creates_single_preorder_and_subsequent_request_updates_it(): void
    {
        $course = Course::factory()->upcoming()->create();

        $payload = [
            'name' => 'Иван',
            'contact' => '@ivanov',
        ];

        $firstResponse = $this->postJson(route('courses.preorders.store', $course), $payload);

        $firstResponse
            ->assertOk()
            ->assertJsonFragment([
                'message' => 'Заявка отправлена. Мы свяжемся с вами в ближайшее время!',
            ]);

        $this->assertDatabaseHas('course_preorders', [
            'course_id' => $course->id,
            'contact' => '@ivanov',
            'name' => 'Иван',
            'user_id' => null,
        ]);

        $secondResponse = $this->postJson(route('courses.preorders.store', $course), $payload);

        $secondResponse
            ->assertOk()
            ->assertJsonFragment([
                'message' => 'Данные заявки обновлены. Мы свяжемся с вами в ближайшее время!',
            ]);

        $this->assertSame(1, CoursePreorder::query()->where('course_id', $course->id)->count());
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
