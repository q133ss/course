<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Seed the application's purchase transactions.
     */
    public function run(): void
    {
        $user = User::query()->where('email', 'user@email.net')->first();
        $course = Course::query()->where('slug', 'fullstack-start')->first();

        if (!$user || !$course) {
            return;
        }

        $isCourseFree = (bool) $course->is_free;

        Purchase::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'amount' => $isCourseFree ? 0 : ($course->price ?? 0),
                'payment_status' => 'paid',
                'payment_method' => $isCourseFree ? 'free' : 'manual',
                'purchased_at' => Carbon::now()->subDays(3)->setTime(10, 15),
            ]
        );
    }
}
