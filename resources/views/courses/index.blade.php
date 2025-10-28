@extends('layouts.app')

@section('content')
    <section class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white rounded-2xl shadow p-5">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Каталог курсов</h1>
                <p class="text-sm text-gray-500 mt-1">Выберите курс и начните обучение уже сегодня.</p>
            </div>
            <form method="GET" action="{{ route('courses.index') }}" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <div class="flex-1">
                    <label class="sr-only" for="filter-search">Поиск</label>
                    <input
                        id="filter-search"
                        type="search"
                        name="search"
                        placeholder="Поиск по курсам"
                        value="{{ request('search') }}"
                        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2 text-sm"
                    >
                </div>
                <div class="flex items-center gap-3">
                    <div>
                        <label class="sr-only" for="filter-type">Тип курса</label>
                        <select
                            id="filter-type"
                            name="type"
                            class="rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2 text-sm"
                            onchange="this.form.submit()"
                        >
                            <option value="all" @selected(request('type', 'all') === 'all')>Все курсы</option>
                            <option value="free" @selected(request('type') === 'free')>Бесплатные</option>
                            <option value="paid" @selected(request('type') === 'paid')>Платные</option>
                        </select>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Найти
                    </button>
                </div>
            </form>
        </div>
    </section>

    @php
        $paidCourseIds = $paidCourseIds ?? array_keys($userPurchases ?? []);
    @endphp

    <section class="space-y-8">
        @forelse ($courses as $course)
            @php
                $hasPurchase = in_array($course->id, $paidCourseIds, true);
                $courseStartDate = $course->start_date;
                $courseStartDateFormatted = $courseStartDate
                    ? $courseStartDate->copy()->locale('ru')->translatedFormat('d F Y, H:i')
                    : null;
                $courseStartDateDiff = $courseStartDate && $course->isUpcoming()
                    ? $courseStartDate->copy()->locale('ru')->diffForHumans(null, true)
                    : null;
                $courseStartDateShort = $courseStartDate
                    ? $courseStartDate->copy()->locale('ru')->translatedFormat('d.m')
                    : null;
                $courseIsUpcoming = $course->isUpcoming();
            @endphp
            <article class="bg-white rounded-2xl shadow p-6 md:p-8">
                <div class="flex flex-col md:flex-row gap-6">
                    @if ($course->thumbnail_url)
                        <div class="md:w-56 flex-shrink-0">
                            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-36 object-cover rounded-xl">
                        </div>
                    @endif
                    <div class="flex-1 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ $course->title }}</h2>
                                <p class="text-sm text-gray-500 mt-1">{{ $course->description }}</p>
                                @if ($courseStartDate)
                                    <div class="mt-2 inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        @if ($courseIsUpcoming)
                                            Старт {{ $courseStartDateFormatted }}@if ($courseStartDateDiff) · через {{ $courseStartDateDiff }}@endif
                                        @else
                                            Стартовал {{ $courseStartDateFormatted }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                @if ($course->is_free)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Бесплатно</span>
                                @else
                                    <span class="text-lg font-semibold text-gray-900">{{ number_format($course->price, 2, ',', ' ') }} ₽</span>
                                @endif
                            </div>
                        </div>
                        <div>
                                <h3 class="text-sm uppercase tracking-wide text-gray-500">Видео уроки</h3>
                            <ul class="mt-3 divide-y divide-gray-100">
                                @foreach ($course->videos as $video)
                                    @php
                                        $hasVideoAccess = $course->is_free || $video->is_free || (auth()->check() && $hasPurchase);
                                        $videoAccessMode = $hasVideoAccess ? 'allowed' : (auth()->check() ? 'pay' : 'login');
                                        $shouldShowPreorderCta = false;

                                        if ($courseIsUpcoming) {
                                            $shouldShowPreorderCta = $video->is_free && $hasVideoAccess;

                                            if (! $shouldShowPreorderCta) {
                                                $videoAccessMode = 'preorder';
                                            }
                                        }
                                    @endphp
                                    <li @class([
                                        'video-item group relative cursor-pointer rounded-xl px-4 py-3 transition',
                                        'bg-gray-50' => $courseIsUpcoming,
                                    ])
                                        data-course-id="{{ $course->id }}"
                                        data-course-title="{{ e($course->title) }}"
                                        data-checkout-url="{{ route('checkout.show', $course) }}"
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
                                            <div class="pointer-events-none absolute inset-0 rounded-xl bg-white/70 backdrop-blur-[1px] ring-1 ring-blue-100"></div>
                                            <div class="pointer-events-none absolute top-2 right-3 inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-blue-700 shadow-sm">
                                                <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                                Старт {{ $courseStartDateShort }}
                                            </div>
                                        @endif
                                        <div class="relative flex items-start gap-3">
                                            <div class="shrink-0">
                                                @if ($video->preview_image)
                                                    <div class="w-24 aspect-video overflow-hidden rounded-lg border border-gray-200 bg-gray-100 transition group-hover:border-blue-200">
                                                        <img src="{{ $video->preview_image }}" alt="Превью видео «{{ $video->title }}»" class="h-full w-full object-cover">
                                                    </div>
                                                @else
                                                    <div class="mt-1 flex h-6 w-6 items-center justify-center rounded-full border text-xs text-gray-500 transition group-hover:bg-blue-50 group-hover:text-blue-600">▶</div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900">{{ $video->title }}</div>
                                                @if ($video->short_description)
                                                    <div class="text-sm text-gray-600 whitespace-pre-line">{{ $video->short_description }}</div>
                                                @endif
                                                @if ($video->is_free && !$course->is_free)
                                                    <div class="mt-1">
                                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">Бесплатный урок</span>
                                                    </div>
                                                @endif
                                                @if ($courseIsUpcoming)
                                                    <div class="mt-2 text-xs font-semibold uppercase tracking-wide text-blue-500">Предзаказ со скидкой 30%</div>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            @php
                $searchQuery = trim((string) request('search'));
                $typeFilter = request('type');
                $hasTypeFilter = filled($typeFilter) && $typeFilter !== 'all';
            @endphp
            <div class="text-center py-16 bg-white rounded-2xl shadow">
                @if ($searchQuery !== '')
                    <h2 class="text-xl font-semibold text-gray-900">По запросу «{{ e($searchQuery) }}» ничего не найдено</h2>
                    <p class="text-sm text-gray-500 mt-2">Попробуйте изменить поисковую фразу или сбросить фильтры.</p>
                @elseif ($hasTypeFilter)
                    <h2 class="text-xl font-semibold text-gray-900">Курсы по выбранным фильтрам не найдены</h2>
                    <p class="text-sm text-gray-500 mt-2">Измените параметры фильтрации или покажите все курсы.</p>
                @else
                    <h2 class="text-xl font-semibold text-gray-900">Курсы пока не добавлены</h2>
                    <p class="text-sm text-gray-500 mt-2">Загляните позже — мы готовим интересные материалы.</p>
                @endif
                @if ($searchQuery !== '' || $hasTypeFilter)
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center mt-6 px-4 py-2 rounded-lg border border-blue-200 text-sm font-semibold text-blue-600 hover:bg-blue-50">
                        Сбросить фильтры
                    </a>
                @endif
            </div>
        @endforelse
    </section>

    @include('courses.partials.video-modal')
@endsection

