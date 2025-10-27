<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Course>
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numerify('###'),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 1000, 5000),
            'is_free' => false,
            'start_date' => now()->addWeeks(2),
            'thumbnail' => fake()->imageUrl(640, 360, 'education', true),
        ];
    }

    public function upcoming(): static
    {
        return $this->state(fn () => [
            'start_date' => now()->addWeeks(2),
        ]);
    }

    public function started(): static
    {
        return $this->state(fn () => [
            'start_date' => now()->subWeek(),
        ]);
    }
}
