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
                                        data-course-title="{{ e($course->title) }}"
                                        data-checkout-url="{{ route('checkout.show', $course) }}"
                                        data-video-id="{{ $video->id }}"
                                        data-video-title="{{ e($video->title) }}"
                                        data-video-url="{{ e($video->video_url) }}"
                                        data-video-preview-image="{{ e($video->preview_image ?? '') }}"
                                        data-video-short-description="{{ e($video->short_description ?? '') }}"
                                        data-video-full-description="{{ e($video->full_description ?? '') }}"
                                        data-access="{{ $accessMode }}">
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 shrink-0 h-6 w-6 rounded-full border flex items-center justify-center text-xs text-gray-500 group-hover:bg-blue-50 group-hover:text-blue-600">▶</div>
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900">{{ $video->title }}</div>
                                                @if ($video->short_description)
                                                    <div class="text-sm text-gray-600">{{ $video->short_description }}</div>
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

    <div id="video-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="video-modal-backdrop absolute inset-0 bg-gray-900/60"></div>
        <div id="video-modal-card" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-auto overflow-hidden">
            <button type="button" id="video-modal-close" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl leading-none" aria-label="Закрыть">
                &times;
            </button>
            <div class="p-6 md:p-8 space-y-4">
                <div id="video-modal-course" class="text-xs font-semibold uppercase tracking-wide text-blue-600 hidden"></div>
                <h2 id="video-modal-title" class="text-2xl font-semibold text-gray-900"></h2>
                <p id="video-modal-short-description" class="text-sm text-gray-600 hidden"></p>
                <div id="video-modal-player-section" class="video-modal-section hidden space-y-4">
                    <img id="video-modal-preview-image" src="" alt="" class="hidden rounded-xl">
                    <div class="aspect-video bg-black/5 rounded-xl overflow-hidden">
                        <video id="video-modal-video" controls class="w-full h-full bg-black text-white rounded-xl">
                            <source id="video-modal-video-source" src="" type="video/mp4">
                            Ваш браузер не поддерживает воспроизведение видео.
                        </video>
                    </div>
                    <div id="video-modal-full-description" class="text-sm text-gray-700 whitespace-pre-line hidden"></div>
                </div>
                <div id="video-modal-pay-section" class="video-modal-section hidden bg-yellow-50 border border-yellow-200 rounded-xl p-4 space-y-3">
                    <div class="text-sm text-yellow-800">
                        <span class="font-semibold block">Доступ ограничен.</span>
                        Оплатите курс <span id="video-modal-pay-course-title" class="font-semibold"></span>, чтобы получить доступ к уроку.
                    </div>
                    <a id="video-modal-pay-link" href="#" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-yellow-500 text-white font-semibold hover:bg-yellow-600">
                        Перейти к оплате
                    </a>
                    {{-- TODO: подключить реальную оплату при интеграции платёжного провайдера --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.body;
            const videoItems = document.querySelectorAll('.video-item');
            const videoModal = document.getElementById('video-modal');
            const closeButton = document.getElementById('video-modal-close');
            const courseTitleEl = document.getElementById('video-modal-course');
            const videoTitleEl = document.getElementById('video-modal-title');
            const shortDescriptionEl = document.getElementById('video-modal-short-description');
            const fullDescriptionEl = document.getElementById('video-modal-full-description');
            const previewImageEl = document.getElementById('video-modal-preview-image');
            const playerSection = document.getElementById('video-modal-player-section');
            const paySection = document.getElementById('video-modal-pay-section');
            const payCourseTitleEl = document.getElementById('video-modal-pay-course-title');
            const payLinkEl = document.getElementById('video-modal-pay-link');
            const videoElement = document.getElementById('video-modal-video');
            const videoSource = document.getElementById('video-modal-video-source');

            if (!videoModal) {
                return;
            }

            const hideElement = (element) => {
                if (!element) return;
                element.classList.add('hidden');
            };

            const showElement = (element) => {
                if (!element) return;
                element.classList.remove('hidden');
            };

            const resetVideoPlayer = () => {
                if (!videoElement) return;
                videoElement.pause();
                try {
                    videoElement.currentTime = 0;
                } catch (error) {
                    // Игнорируем невозможность сброса времени в некоторых браузерах.
                }
                videoElement.removeAttribute('src');
                videoSource?.removeAttribute('src');
                videoElement.load();
            };

            const openVideoModal = () => {
                videoModal.classList.remove('hidden');
                videoModal.classList.add('flex');
                body.classList.add('overflow-hidden');
            };

            const closeVideoModal = () => {
                videoModal.classList.add('hidden');
                videoModal.classList.remove('flex');
                body.classList.remove('overflow-hidden');
                resetVideoPlayer();
            };

            const populateText = (element, value) => {
                if (!element) return;
                if (value) {
                    element.textContent = value;
                    showElement(element);
                } else {
                    element.textContent = '';
                    hideElement(element);
                }
            };

            const populateVideoContent = (item) => {
                const dataset = item.dataset;

                populateText(courseTitleEl, dataset.courseTitle);
                populateText(videoTitleEl, dataset.videoTitle);
                populateText(shortDescriptionEl, dataset.videoShortDescription);
                populateText(fullDescriptionEl, dataset.videoFullDescription);

                if (dataset.videoPreviewImage) {
                    previewImageEl.src = dataset.videoPreviewImage;
                    showElement(previewImageEl);
                } else {
                    previewImageEl.src = '';
                    hideElement(previewImageEl);
                }

                if (dataset.videoUrl) {
                    videoSource?.setAttribute('src', dataset.videoUrl);
                    videoElement?.load();
                }
            };

            const populatePayContent = (item) => {
                const dataset = item.dataset;
                populateText(courseTitleEl, dataset.courseTitle);
                populateText(videoTitleEl, dataset.videoTitle);
                populateText(shortDescriptionEl, dataset.videoShortDescription);
                hideElement(fullDescriptionEl);
                hideElement(previewImageEl);
                resetVideoPlayer();

                populateText(payCourseTitleEl, dataset.courseTitle);
                if (payLinkEl) {
                    payLinkEl.setAttribute('href', dataset.checkoutUrl || '#');
                }
            };

            closeButton?.addEventListener('click', (event) => {
                event.preventDefault();
                closeVideoModal();
            });

            videoModal.addEventListener('click', (event) => {
                if (event.target === videoModal || event.target.classList.contains('video-modal-backdrop')) {
                    closeVideoModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !videoModal.classList.contains('hidden')) {
                    closeVideoModal();
                }
            });

            videoItems.forEach((item) => {
                item.addEventListener('click', () => {
                    const access = item.dataset.access;

                    if (access === 'login') {
                        window.openAuthModal?.('login');
                        return;
                    }

                    if (access === 'pay') {
                        hideElement(playerSection);
                        showElement(paySection);
                        populatePayContent(item);
                        openVideoModal();
                        return;
                    }

                    hideElement(paySection);
                    showElement(playerSection);
                    populateVideoContent(item);
                    openVideoModal();
                });
            });
        });
    </script>
@endsection

