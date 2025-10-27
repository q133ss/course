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
            @endphp
            <article class="bg-white rounded-2xl shadow p-6 md:p-8">
                <div class="flex flex-col md:flex-row gap-6">
                    @if ($course->thumbnail)
                        <div class="md:w-56 flex-shrink-0">
                            <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-36 object-cover rounded-xl">
                        </div>
                    @endif
                    <div class="flex-1 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ $course->title }}</h2>
                                <p class="text-sm text-gray-500 mt-1">{{ $course->description }}</p>
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
                                    @endphp
                                    <li class="video-item group py-3 cursor-pointer"
                                        data-course-id="{{ $course->id }}"
                                        data-course-title="{{ e($course->title) }}"
                                        data-checkout-url="{{ route('checkout.show', $course) }}"
                                        data-video-id="{{ $video->id }}"
                                        data-video-title="{{ e($video->title) }}"
                                        data-video-url="{{ e($video->video_url) }}"
                                        data-video-preview-image="{{ e($video->preview_image ?? '') }}"
                                        data-video-short-description="{{ e($video->short_description ?? '') }}"
                                        data-video-full-description="{{ e($video->full_description ?? '') }}"
                                        data-access="{{ $videoAccessMode }}">
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 shrink-0 h-6 w-6 rounded-full border flex items-center justify-center text-xs text-gray-500 group-hover:bg-blue-50 group-hover:text-blue-600">▶</div>
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900">{{ $video->title }}</div>
                                                @if ($video->short_description)
                                                    <div class="text-sm text-gray-600">{{ $video->short_description }}</div>
                                                @endif
                                                @if ($video->is_free && !$course->is_free)
                                                    <div class="mt-1">
                                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">Бесплатный урок</span>
                                                    </div>
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

