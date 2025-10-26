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
        $users = User::query()->get();
        $courses = Course::query()->get();

        if ($users->isEmpty() || $courses->isEmpty()) {
            return;
        }

        $faker = fake();
        $paidMethods = ['stripe', 'paypal', 'yookassa'];

        foreach ($users as $user) {
            $transactionsToCreate = $faker->numberBetween(1, min(3, $courses->count()));
            $selectedCourses = $courses->shuffle()->take($transactionsToCreate);

            foreach ($selectedCourses as $course) {
                $isFree = (bool) $course->is_free;
                $status = $isFree
                    ? 'paid'
                    : $faker->randomElement(['paid', 'pending', 'failed']);

                Purchase::query()->updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                    ],
                    [
                        'amount' => $isFree ? 0 : ($course->price ?? $faker->randomFloat(2, 10, 150)),
                        'payment_status' => $status,
                        'payment_method' => $isFree ? 'free' : $faker->randomElement($paidMethods),
                        'purchased_at' => Carbon::now()->subDays($faker->numberBetween(1, 30))
                            ->setTimeFromTimeString($faker->time('H:i:s')),
                    ]
                );
            }
        }
    }
}
