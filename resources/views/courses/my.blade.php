@extends('layouts.app')

@section('content')
    @php
        $coursesCount = $courses->count();
        $inProgressCount = $statusCounts['in_progress'] ?? 0;
        $completedCount = $statusCounts['completed'] ?? 0;
        $notStartedCount = $statusCounts['not_started'] ?? 0;
        $latestActivityText = null;

        if ($latestActivityAt) {
            $latestActivityText = $latestActivityAt
                ->copy()
                ->locale('ru')
                ->translatedFormat('d F Y, H:i');
        }
    @endphp

    <section class="mb-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-500 text-white shadow-xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.15),_transparent_55%)]"></div>
            <div class="relative px-6 py-10 sm:px-10 md:px-16 md:py-14">
                <div class="max-w-3xl space-y-4">
                    <div class="inline-flex items-center rounded-full bg-white/15 px-4 py-1 text-sm font-medium backdrop-blur">
                        <span class="mr-2 inline-flex h-2 w-2 rounded-full bg-emerald-300"></span>
                        Учитесь в удобном ритме
                    </div>
                    <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">Мои курсы</h1>
                    <p class="text-blue-100 text-base sm:text-lg">
                        Продолжайте обучение, следите за прогрессом и возвращайтесь к урокам в один клик.
                    </p>
                    @if ($latestActivityText)
                        <p class="flex items-center gap-2 text-sm text-blue-100/80">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-white">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </span>
                            Последняя активность: {{ $latestActivityText }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if ($coursesCount === 0)
        <section class="bg-white rounded-3xl shadow p-10 text-center">
            <div class="mx-auto max-w-2xl space-y-4">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-.356 1.263 12a1.125 1.125 0 0 1-1.12 1.231H4.251a1.125 1.125 0 0 1-1.12-1.231l1.263-12A1.125 1.125 0 0 1 5.512 9h12.976a1.125 1.125 0 0 1 1.118 1.144Z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900">У вас пока нет курсов</h2>
                <p class="text-gray-600">
                    Откройте каталог, чтобы подобрать подходящий курс. Бесплатные программы доступны сразу после регистрации.
                </p>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-blue-700">
                        Перейти в каталог
                    </a>
                </div>
            </div>
        </section>
    @else
        <section class="mb-10 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-white/60 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Курсов в обучении</p>
                <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $coursesCount }}</p>
                <p class="mt-1 text-sm text-gray-500">Включая бесплатные программы</p>
            </div>
            <div class="rounded-2xl border border-white/60 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-500">В процессе</p>
                <p class="mt-3 text-3xl font-semibold text-blue-600">{{ $inProgressCount }}</p>
                <p class="mt-1 text-sm text-gray-500">Уроков завершено постепенно</p>
            </div>
            <div class="rounded-2xl border border-white/60 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Завершено</p>
                <p class="mt-3 text-3xl font-semibold text-emerald-600">{{ $completedCount }}</p>
                <p class="mt-1 text-sm text-gray-500">Курсов полностью пройдено</p>
            </div>
            <div class="rounded-2xl border border-white/60 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between text-sm font-medium text-gray-500">
                    <span>Средний прогресс</span>
                    <span class="text-gray-700">{{ $overallProgressPercent }}%</span>
                </div>
                <div class="mt-3 h-2 w-full rounded-full bg-gray-100">
                    <div class="h-full rounded-full bg-blue-500 transition-all" style="width: {{ $overallProgressPercent }}%"></div>
                </div>
                <p class="mt-2 text-sm text-gray-500">На основе всех курсов</p>
            </div>
        </section>

        <section class="mb-8 flex flex-wrap items-center gap-3">
            <span class="text-sm font-semibold text-gray-500">Фильтр статуса:</span>
            <div class="flex flex-wrap gap-2" role="tablist">
                <button type="button" data-course-filter="all" class="course-filter-button inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm" aria-pressed="true">
                    Все курсы
                    <span class="rounded-full bg-white/20 px-2 py-0.5 text-xs">{{ $coursesCount }}</span>
                </button>
                <button type="button" data-course-filter="in_progress" class="course-filter-button inline-flex items-center gap-2 rounded-full bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-200" aria-pressed="false">
                    В процессе
                    <span class="rounded-full bg-gray-200 px-2 py-0.5 text-xs text-gray-600">{{ $inProgressCount }}</span>
                </button>
                <button type="button" data-course-filter="completed" class="course-filter-button inline-flex items-center gap-2 rounded-full bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-200" aria-pressed="false">
                    Завершённые
                    <span class="rounded-full bg-gray-200 px-2 py-0.5 text-xs text-gray-600">{{ $completedCount }}</span>
                </button>
                <button type="button" data-course-filter="not_started" class="course-filter-button inline-flex items-center gap-2 rounded-full bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-200" aria-pressed="false">
                    Не начатые
                    <span class="rounded-full bg-gray-200 px-2 py-0.5 text-xs text-gray-600">{{ $notStartedCount }}</span>
                </button>
            </div>
        </section>

        <section class="space-y-8" data-course-list>
            @foreach ($courses as $course)
                @php
                    $summary = $courseSummaries[$course->id] ?? null;
                    $progressPercent = $summary['progress_percent'] ?? 0;
                    $completedVideos = $summary['completed_videos'] ?? 0;
                    $totalVideos = $summary['total_videos'] ?? $course->videos->count();
                    $status = $summary['status'] ?? 'not_started';
                    $lastWatchedAt = $summary['last_watched_at'] ?? null;
                    $nextVideo = $summary['next_video'] ?? null;
                    $purchase = $userPurchases->get($course->id);
                    $nextVideoTargetId = $nextVideo ? 'course-' . $course->id . '-video-' . $nextVideo->id : null;
                    $courseStartDate = $course->start_date;
                    $courseIsUpcoming = $course->isUpcoming();
                    $courseStartDateFormatted = $courseStartDate
                        ? $courseStartDate->copy()->locale('ru')->translatedFormat('d F Y, H:i')
                        : null;
                    $courseStartDateDiff = $courseStartDate && $courseIsUpcoming
                        ? $courseStartDate->copy()->locale('ru')->diffForHumans(null, true)
                        : null;
                    $courseStartDateShort = $courseStartDate
                        ? $courseStartDate->copy()->locale('ru')->translatedFormat('d.m')
                        : null;

                    $statusLabel = [
                        'completed' => 'Завершён',
                        'in_progress' => 'В процессе',
                        'not_started' => 'Не начат',
                    ][$status] ?? 'Не начат';

                    $statusClasses = [
                        'completed' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100',
                        'in_progress' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-100',
                        'not_started' => 'bg-gray-100 text-gray-700 ring-1 ring-gray-200',
                    ][$status] ?? 'bg-gray-100 text-gray-700 ring-1 ring-gray-200';

                    $lastWatchedText = $lastWatchedAt
                        ? $lastWatchedAt->copy()->locale('ru')->diffForHumans(null, true) . ' назад'
                        : 'ещё не начинали';
                @endphp

                <article class="group relative rounded-3xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg" data-course-card data-course-status="{{ $status }}">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-start">
                        <div class="lg:w-64">
                            <div class="overflow-hidden rounded-2xl bg-gray-100 shadow-inner">
                                @if ($course->thumbnail)
                                    <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="h-40 w-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <div class="flex h-40 w-full items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 text-gray-400">
                                        <svg class="h-12 w-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5v-6a3.75 3.75 0 0 0-7.5 0v6m11.356-.356 1.263 12a1.125 1.125 0 0 1-1.12 1.231H4.251a1.125 1.125 0 0 1-1.12-1.231l1.263-12A1.125 1.125 0 0 1 5.512 9h12.976a1.125 1.125 0 0 1 1.118 1.144Z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4 space-y-3 rounded-2xl bg-gray-50 p-4">
                                <div class="flex items-center justify-between text-sm font-medium text-gray-600">
                                    <span>Прогресс</span>
                                    <span>{{ $progressPercent }}%</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-white shadow-inner">
                                    <div class="h-full rounded-full bg-blue-500 transition-all" style="width: {{ $progressPercent }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>Уроков завершено</span>
                                    <span>{{ $completedVideos }} / {{ $totalVideos }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 space-y-6">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                            {{ $statusLabel }}
                                        </span>
                                        @if ($course->is_free)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                                Бесплатный курс
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700 ring-1 ring-purple-100">
                                                Оплачен{{ $purchase && $purchase->payment_method ? ' · ' . strtoupper($purchase->payment_method) : '' }}
                                            </span>
                                        @endif
                                        @if ($courseStartDateFormatted)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-100">
                                                <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                                @if ($courseIsUpcoming)
                                                    Старт {{ $courseStartDateFormatted }}@if ($courseStartDateDiff) · через {{ $courseStartDateDiff }}@endif
                                                @else
                                                    Стартовал {{ $courseStartDateFormatted }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-semibold text-gray-900">{{ $course->title }}</h2>
                                        <p class="mt-2 text-sm text-gray-600 max-w-2xl">{{ $course->description }}</p>
                                    </div>
                                    <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            Последний просмотр: {{ $lastWatchedText }}
                                        </div>
                                        @if ($purchase?->purchased_at)
                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21a3.75 3.75 0 0 1-3.6-2.775L3 12l6.75-1.5V6.75a2.25 2.25 0 1 1 4.5 0V9L21 12l-1.65 6.225A3.75 3.75 0 0 1 15.75 21h-7.5Z" />
                                                </svg>
                                                Куплен {{ $purchase->purchased_at->copy()->locale('ru')->translatedFormat('d F Y') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col items-stretch justify-start gap-3">
                                    @if ($nextVideoTargetId)
                                        <button type="button" data-course-continue data-continue-target="#{{ $nextVideoTargetId }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-blue-700">
                                            {{ $courseIsUpcoming ? 'Узнать о старте' : ($status === 'not_started' ? 'Начать обучение' : 'Продолжить') }}
                                            <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                            </svg>
                                        </button>
                                    @endif
                                    <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:border-gray-300 hover:bg-gray-50">
                                        К каталогу
                                    </a>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Программа курса</h3>
                                    <span class="text-xs font-medium text-gray-400">{{ $totalVideos }} уроков</span>
                                </div>
                                @if ($course->videos->isNotEmpty())
                                    <ul class="divide-y divide-gray-100 rounded-2xl border border-gray-100 bg-gray-50">
                                        @foreach ($course->videos as $video)
                                            @php
                                                $progressRecord = $videoProgress->get($video->id);
                                                $videoProgressPercent = $progressRecord?->progress_percent ?? 0;
                                                $videoProgressPercent = max(0, min(100, (int) $videoProgressPercent));
                                                $videoCompleted = $videoProgressPercent >= 90;
                                            $durationSeconds = $video->duration ?? 0;
                                            $durationMinutes = $durationSeconds > 0 ? intdiv($durationSeconds, 60) : 0;
                                            $durationSecondsRemainder = $durationSeconds > 0 ? $durationSeconds % 60 : 0;
                                            $durationFormatted = $durationSeconds > 0
                                                ? ($durationMinutes > 0
                                                    ? sprintf('%d мин %02d с', $durationMinutes, $durationSecondsRemainder)
                                                    : sprintf('%d с', $durationSecondsRemainder))
                                                : null;
                                            $videoElementId = 'course-' . $course->id . '-video-' . $video->id;
                                            $videoAccessMode = 'allowed';
                                            $shouldShowPreorderCta = false;

                                            if ($courseIsUpcoming) {
                                                $shouldShowPreorderCta = $video->is_free;

                                                if (! $shouldShowPreorderCta) {
                                                    $videoAccessMode = 'preorder';
                                                }
                                            }
                                        @endphp
                                            <li id="{{ $videoElementId }}"
                                                @class([
                                                    'video-item group relative flex cursor-pointer items-stretch gap-4 overflow-hidden rounded-2xl px-5 py-4 transition hover:bg-white',
                                                    'bg-gray-50' => $courseIsUpcoming,
                                                ])
                                                data-course-id="{{ $course->id }}"
                                                data-course-title="{{ e($course->title) }}"
                                                data-video-id="{{ $video->id }}"
                                                data-video-title="{{ e($video->title) }}"
                                                data-video-url="{{ e($video->video_url) }}"
                                                data-video-preview-image="{{ e($video->preview_image ?? '') }}"
                                                data-video-short-description="{{ e($video->short_description ?? '') }}"
                                                data-video-full-description="{{ e($video->full_description ?? '') }}"
                                                data-preorder-url="{{ route('courses.preorders.store', $course) }}"
                                                data-course-start-date="{{ $course->start_date?->toIso8601String() }}"
                                                data-course-start-date-readable="{{ e($courseStartDateFormatted ?? '') }}"
                                                data-course-start-date-diff="{{ e($courseStartDateDiff ?? '') }}"
                                                data-course-start-date-short="{{ e($courseStartDateShort ?? '') }}"
                                                data-preorder-cta="{{ $shouldShowPreorderCta ? 'true' : 'false' }}"
                                                data-access="{{ $videoAccessMode }}">
                                                @if ($courseIsUpcoming)
                                                    <div class="pointer-events-none absolute inset-0 rounded-2xl bg-white/70 backdrop-blur-[1px] ring-1 ring-blue-100"></div>
                                                    <div class="pointer-events-none absolute top-3 right-4 inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-blue-700 shadow-sm">
                                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                        </svg>
                                                        Старт {{ $courseStartDateShort }}
                                                    </div>
                                                @endif
                                                <div class="relative flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-2xl border border-gray-200 bg-white text-sm font-semibold text-gray-500">
                                                    @if ($videoCompleted)
                                                        <svg class="h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="flex-1 space-y-1">
                                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                                        <div class="font-medium text-gray-900">{{ $video->title }}</div>
                                                        <div class="flex items-center gap-3 text-xs text-gray-500">
                                                            @if ($durationFormatted)
                                                                <span class="inline-flex items-center gap-1">
                                                                    <svg class="h-4 w-4 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                                    </svg>
                                                                    {{ $durationFormatted }}
                                                                </span>
                                                            @endif
                                                            <span class="inline-flex items-center gap-1">
                                                                <svg class="h-4 w-4 text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5m-16.5 5.25h9m-9 5.25h16.5" />
                                                                </svg>
                                                                {{ $videoProgressPercent }}%
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @if ($video->short_description)
                                                        <div class="text-sm text-gray-600">{{ $video->short_description }}</div>
                                                    @endif
                                                    @if ($courseIsUpcoming)
                                                        <div class="mt-2 text-xs font-semibold uppercase tracking-wide text-blue-500">Предзаказ со скидкой 30%</div>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-6 text-sm text-gray-500">
                                        Уроки появятся совсем скоро. Мы уже работаем над новым контентом.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>
    @endif

    @include('courses.partials.video-modal')

    <script>
        (function () {
            const filterButtons = document.querySelectorAll('.course-filter-button');
            const courseCards = document.querySelectorAll('[data-course-card]');
            const continueButtons = document.querySelectorAll('[data-course-continue]');

            const setActiveFilter = (activeButton) => {
                filterButtons.forEach((button) => {
                    const isActive = button === activeButton;
                    button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    button.classList.toggle('bg-blue-600', isActive);
                    button.classList.toggle('text-white', isActive);
                    button.classList.toggle('shadow-sm', isActive);
                    button.classList.toggle('bg-gray-100', !isActive);
                    button.classList.toggle('text-gray-600', !isActive);
                });
            };

            const applyFilter = (status) => {
                courseCards.forEach((card) => {
                    const cardStatus = card.dataset.courseStatus;
                    const shouldShow = status === 'all' || cardStatus === status;
                    card.classList.toggle('hidden', !shouldShow);
                });
            };

            filterButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const status = button.dataset.courseFilter;
                    setActiveFilter(button);
                    applyFilter(status);
                });
            });

            continueButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const targetSelector = button.dataset.continueTarget;
                    if (!targetSelector) {
                        return;
                    }

                    const target = document.querySelector(targetSelector);
                    target?.click();
                });
            });
        })();
    </script>
@endsection
