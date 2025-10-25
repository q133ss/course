<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Purchase;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class CourseContentSeeder extends Seeder
{
    /**
     * Seed the application's course related tables.
     */
    public function run(): void
    {
        $courseDefinitions = [
            [
                'title' => 'Laravel Fundamentals',
                'slug' => 'laravel-fundamentals',
                'description' => 'Базовый курс по Laravel для тех, кто только начинает работать с фреймворком.',
                'price' => 0,
                'is_free' => true,
                'thumbnail' => 'courses/laravel-fundamentals.jpg',
                'videos' => [
                    [
                        'title' => 'Знакомство с Laravel',
                        'short_description' => 'Первое знакомство с возможностями Laravel.',
                        'full_description' => 'Обзор ключевых возможностей фреймворка, установка и настройка окружения.',
                        'video_url' => 'https://example.com/videos/laravel-intro.mp4',
                        'preview_image' => 'videos/laravel-intro.jpg',
                        'duration' => 620,
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Маршруты и контроллеры',
                        'short_description' => 'Работа с маршрутами и контроллерами.',
                        'full_description' => 'Создание REST-контроллеров, разбор жизненного цикла запроса в Laravel.',
                        'video_url' => 'https://example.com/videos/laravel-routing.mp4',
                        'preview_image' => 'videos/laravel-routing.jpg',
                        'duration' => 840,
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Работа с базой данных',
                        'short_description' => 'Миграции, сиды и фабрики.',
                        'full_description' => 'Создание и запуск миграций, наполнение базы тестовыми данными, использование сидов.',
                        'video_url' => 'https://example.com/videos/laravel-database.mp4',
                        'preview_image' => 'videos/laravel-database.jpg',
                        'duration' => 780,
                        'sort_order' => 3,
                    ],
                ],
            ],
            [
                'title' => 'Vue.js для Laravel разработчиков',
                'slug' => 'vue-for-laravel',
                'description' => 'Интеграция Vue.js с Laravel приложениями: от компонентов до работы с API.',
                'price' => 49.90,
                'is_free' => false,
                'thumbnail' => 'courses/vue-for-laravel.jpg',
                'videos' => [
                    [
                        'title' => 'Настройка фронтенда',
                        'short_description' => 'Настройка Vite и Vue в Laravel проекте.',
                        'full_description' => 'Подключение Vue к проекту, настройка структуры каталогов и базовых зависимостей.',
                        'video_url' => 'https://example.com/videos/vue-setup.mp4',
                        'preview_image' => 'videos/vue-setup.jpg',
                        'duration' => 690,
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Создание компонентов',
                        'short_description' => 'Создание и использование Vue компонентов в приложении.',
                        'full_description' => 'Работа с однофайловыми компонентами, взаимодействие между компонентами и слотом.',
                        'video_url' => 'https://example.com/videos/vue-components.mp4',
                        'preview_image' => 'videos/vue-components.jpg',
                        'duration' => 905,
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Работа с API',
                        'short_description' => 'Интерактивные компоненты и работа с API.',
                        'full_description' => 'Построение SPA, использование Axios и обработка ответов сервера.',
                        'video_url' => 'https://example.com/videos/vue-api.mp4',
                        'preview_image' => 'videos/vue-api.jpg',
                        'duration' => 975,
                        'sort_order' => 3,
                    ],
                ],
            ],
            [
                'title' => 'Продвинутый Laravel',
                'slug' => 'laravel-advanced',
                'description' => 'Глубокое изучение очередей, событий и тестирования в Laravel.',
                'price' => 79.00,
                'is_free' => false,
                'thumbnail' => 'courses/laravel-advanced.jpg',
                'videos' => [
                    [
                        'title' => 'Очереди и фоновые задания',
                        'short_description' => 'Использование очередей для фоновой обработки задач.',
                        'full_description' => 'Настройка драйверов очередей, запуск воркеров и обработка ошибок.',
                        'video_url' => 'https://example.com/videos/laravel-queues.mp4',
                        'preview_image' => 'videos/laravel-queues.jpg',
                        'duration' => 930,
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'События и слушатели',
                        'short_description' => 'Организация событийной модели в приложении.',
                        'full_description' => 'Создание и регистрация событий, слушателей и очередей событий.',
                        'video_url' => 'https://example.com/videos/laravel-events.mp4',
                        'preview_image' => 'videos/laravel-events.jpg',
                        'duration' => 845,
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Тестирование приложений',
                        'short_description' => 'Покрываем приложение тестами.',
                        'full_description' => 'Написание feature и unit тестов, применение фейков и моков.',
                        'video_url' => 'https://example.com/videos/laravel-testing.mp4',
                        'preview_image' => 'videos/laravel-testing.jpg',
                        'duration' => 1015,
                        'sort_order' => 3,
                    ],
                ],
            ],
        ];

        $courses = collect($courseDefinitions)->map(function (array $definition) {
            $videos = Arr::pull($definition, 'videos', []);

            $course = Course::query()->updateOrCreate(
                ['slug' => $definition['slug']],
                $definition
            );

            foreach ($videos as $index => $videoData) {
                $course->videos()->updateOrCreate(
                    ['sort_order' => $videoData['sort_order'] ?? $index + 1],
                    $videoData
                );
            }

            return $course->fresh('videos');
        })->keyBy('slug');

        $users = User::query()->get();

        if ($users->isEmpty()) {
            return;
        }

        $purchaseDefinitions = [
            [
                'user' => $users->first(),
                'course_slug' => 'laravel-fundamentals',
                'amount' => 0,
                'payment_status' => 'paid',
                'payment_method' => 'free',
                'purchased_at' => Carbon::now()->subDays(7),
            ],
            [
                'user' => $users->firstWhere('email', 'test@example.com') ?? $users->get(1),
                'course_slug' => 'vue-for-laravel',
                'amount' => 49.90,
                'payment_status' => 'paid',
                'payment_method' => 'stripe',
                'purchased_at' => Carbon::now()->subDays(3),
            ],
            [
                'user' => $users->get(2) ?? $users->first(),
                'course_slug' => 'laravel-advanced',
                'amount' => 79.00,
                'payment_status' => 'pending',
                'payment_method' => 'paypal',
                'purchased_at' => Carbon::now()->subDay(),
            ],
        ];

        foreach ($purchaseDefinitions as $purchaseDefinition) {
            $user = $purchaseDefinition['user'];
            $course = $courses[$purchaseDefinition['course_slug']] ?? null;

            if (!$user || !$course) {
                continue;
            }

            Purchase::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ],
                [
                    'amount' => $purchaseDefinition['amount'],
                    'payment_status' => $purchaseDefinition['payment_status'],
                    'payment_method' => $purchaseDefinition['payment_method'],
                    'purchased_at' => $purchaseDefinition['purchased_at'],
                ]
            );
        }

        $progressDefinitions = [
            [
                'user' => $users->first(),
                'video' => optional($courses['laravel-fundamentals'] ?? null)->videos->first(),
                'progress_percent' => 100,
                'last_watched_at' => Carbon::now()->subDays(2),
            ],
            [
                'user' => $users->get(1) ?? $users->first(),
                'video' => optional($courses['vue-for-laravel'] ?? null)->videos->get(1),
                'progress_percent' => 65,
                'last_watched_at' => Carbon::now()->subDay(),
            ],
            [
                'user' => $users->get(2) ?? $users->first(),
                'video' => optional($courses['laravel-advanced'] ?? null)->videos->first(),
                'progress_percent' => 30,
                'last_watched_at' => Carbon::now()->subHours(6),
            ],
        ];

        foreach ($progressDefinitions as $progressDefinition) {
            $user = $progressDefinition['user'];
            $video = $progressDefinition['video'];

            if (!$user || !$video) {
                continue;
            }

            UserProgress::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'video_id' => $video->id,
                ],
                [
                    'progress_percent' => $progressDefinition['progress_percent'],
                    'last_watched_at' => $progressDefinition['last_watched_at'],
                ]
            );
        }
    }
}
