<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CourseContentSeeder extends Seeder
{
    /**
     * Seed the application's course related tables.
     */
    public function run(): void
    {
        $course = Course::query()->updateOrCreate(
            ['slug' => 'fullstack-start'],
            [
                'title' => 'Fullstack старт: первые шаги в веб-разработке',
                'description' => 'Один практический курс, который знакомит с основами современного фронтенда и бэкенда.',
                'price' => 2990,
                'is_free' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1517430816045-df4b7de11d1d?auto=format&fit=crop&w=900&q=80',
            ]
        );

        $introVideo = $course->videos()->updateOrCreate(
            ['sort_order' => 1],
            [
                'title' => 'Вводный урок: знакомство с курсом',
                'short_description' => 'Короткий обзор программы и целей обучения.',
                'full_description' => "В этом уроке вы узнаете, как устроен курс, какие технологии будем использовать и как получить максимум от занятий. Мы разберём структуру модулей и подготовим рабочее окружение.",
                'video_url' => 'https://samplelib.com/lib/preview/mp4/sample-5s.mp4',
                'preview_image' => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=900&q=80',
                'duration' => 315,
                'is_free' => true,
            ]
        );

        $user = User::query()->where('email', 'user@email.net')->first();

        if (!$user || !$introVideo) {
            return;
        }

        UserProgress::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'video_id' => $introVideo->id,
            ],
            [
                'progress_percent' => 0,
                'last_watched_at' => Carbon::now()->subDay(),
            ]
        );
    }
}
