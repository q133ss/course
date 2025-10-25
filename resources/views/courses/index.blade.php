@extends('layouts.app')

@section('content')
    <section class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white rounded-2xl shadow p-5">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Каталог курсов</h1>
                <p class="text-sm text-gray-500 mt-1">Выберите курс и начните обучение уже сегодня.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <div class="flex-1">
                    <label class="sr-only" for="filter-search">Поиск</label>
                    <input id="filter-search" type="search" placeholder="Поиск по курсам"
                           class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2 text-sm"
                           disabled>
                    {{-- TODO: добавить рабочий поиск по курсам --}}
                </div>
                <div>
                    <label class="sr-only" for="filter-type">Тип курса</label>
                    <select id="filter-type" class="rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2 text-sm"
                            disabled>
                        <option value="all">Все курсы</option>
                        <option value="free">Бесплатные</option>
                        <option value="paid">Платные</option>
                    </select>
                    {{-- TODO: реализовать фильтрацию по типу курса --}}
                </div>
            </div>
        </div>
    </section>

    @php
        $paidCourseIds = $paidCourseIds ?? array_keys($userPurchases ?? []);
    @endphp

    <section class="space-y-8">
        @forelse ($courses as $course)
            @php
                $hasPurchase = in_array($course->id, $paidCourseIds, true);
                $hasAccess = $course->is_free || (auth()->check() && $hasPurchase);
                $accessMode = $hasAccess ? 'allowed' : (auth()->check() ? 'pay' : 'login');
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
                                    <li class="video-item group py-3 cursor-pointer"
                                        data-course-id="{{ $course->id }}"
                                        data-video-id="{{ $video->id }}"
                                        data-access="{{ $accessMode }}">
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 shrink-0 h-6 w-6 rounded-full border flex items-center justify-center text-xs text-gray-500 group-hover:bg-blue-50 group-hover:text-blue-600">▶</div>
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900">{{ $video->title }}</div>
                                                @if ($video->short_description)
                                                    <div class="text-sm text-gray-600">{{ $video->short_description }}</div>
                                                @endif
                                                <div class="video-panel hidden mt-3">
                                                    <div class="player-panel hidden">
                                                        @if ($video->preview_image)
                                                            <img src="{{ $video->preview_image }}" alt="" class="mb-3 rounded-lg">
                                                        @endif
                                                        <video controls class="w-full rounded-lg bg-black/5">
                                                            <source src="{{ $video->video_url }}" type="video/mp4">
                                                            Ваш браузер не поддерживает воспроизведение видео.
                                                        </video>
                                                        @if ($video->full_description)
                                                            <div class="mt-3 text-sm text-gray-700">{{ $video->full_description }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="pay-panel hidden bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                                        <div class="text-sm mb-2 text-yellow-800">Чтобы смотреть это видео, оплатите доступ к курсу.</div>
                                                        <a href="{{ route('checkout.show', $course) }}"
                                                           class="inline-block px-4 py-2 rounded-lg bg-yellow-500 text-white font-medium hover:bg-yellow-600">
                                                            Перейти к оплате
                                                        </a>
                                                        {{-- TODO: подключить реальную оплату --}}
                                                    </div>
                                                </div>
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
            <div class="text-center py-16 bg-white rounded-2xl shadow">
                <h2 class="text-xl font-semibold text-gray-900">Курсы пока не добавлены</h2>
                <p class="text-sm text-gray-500 mt-2">Загляните позже — мы готовим интересные материалы.</p>
            </div>
        @endforelse
    </section>
@endsection

