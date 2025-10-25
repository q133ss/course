@extends('layouts.app')

@section('content')
    @include('partials.nav')

    <div
        x-data="{
            modal: { open: false, type: null },
            course: null,
            lastFocused: null,
            hasAccess(courseId) {
                const accessible = JSON.parse(this.$refs.accessible.value || '[]');
                return accessible.map(Number).includes(Number(courseId));
            },
            openCourse(courseJson) {
                if (!courseJson) return;
                this.lastFocused = document.activeElement;
                const isAuth = this.$refs.isAuth.value === '1';
                const course = JSON.parse(courseJson);
                this.course = course;
                if (!isAuth) {
                    this.modal = { open: true, type: 'login' };
                    return;
                }
                if (course.is_free || this.hasAccess(course.id)) {
                    this.modal = { open: true, type: 'player' };
                } else {
                    this.modal = { open: true, type: 'payment' };
                }
            },
            closeModal() {
                this.modal = { open: false, type: null };
                this.course = null;
                this.$nextTick(() => {
                    if (this.lastFocused && typeof this.lastFocused.focus === 'function') {
                        this.lastFocused.focus();
                    }
                    this.lastFocused = null;
                });
            },
            focusTrap(event) {
                if (!this.modal.open) {
                    return;
                }
                const container = this.$refs.modalContent;
                if (!container) {
                    return;
                }
                const focusable = Array.from(container.querySelectorAll('a[href]:not([aria-disabled="true"]), button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'));
                if (!focusable.length) {
                    return;
                }
                const first = focusable[0];
                const last = focusable[focusable.length - 1];
                if (event.shiftKey) {
                    if (document.activeElement === first) {
                        event.preventDefault();
                        last.focus();
                    }
                } else if (document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            }
        }"
        x-on:open-login-modal.window="lastFocused = document.activeElement; course = null; modal = { open: true, type: 'login' }"
        class="space-y-10 pb-16"
        x-effect="document.body.classList.toggle('overflow-hidden', modal.open)"
    >
        <input type="hidden" x-ref="isAuth" value="{{ auth()->check() ? '1' : '0' }}">
        <input type="hidden" x-ref="accessible" value='@json($accessibleCourseIds ?? [])'>

        <header class="space-y-6 pt-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-wider text-sky-600">Каталог</p>
                    <h1 class="text-3xl font-semibold text-slate-900">Каталог курсов</h1>
                    <p class="max-w-2xl text-sm text-slate-600">Изучайте новые навыки, прокачивайте экспертизу и следите за прогрессом в одном месте.</p>
                </div>
                <div class="flex gap-3">
                    <div class="relative">
                        <input
                            type="search"
                            name="search"
                            placeholder="Поиск по курсам"
                            class="w-full rounded-full border border-slate-300 bg-white px-4 py-2 text-sm shadow-sm transition focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200"
                        >
                        <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M11 18a7 7 0 110-14 7 7 0 010 14z" />
                        </svg>
                    </div>
                    <x-button variant="secondary" size="sm">
                        Фильтры
                    </x-button>
                </div>
            </div>
        </header>

        <section class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($courses as $course)
                @php
                    $videos = collect(data_get($course, 'videos', []))->map(function ($video) {
                        return [
                            'id' => data_get($video, 'id'),
                            'title' => data_get($video, 'title'),
                            'short_description' => data_get($video, 'short_description'),
                            'full_description' => data_get($video, 'full_description'),
                            'video_url' => data_get($video, 'video_url'),
                            'preview_image' => data_get($video, 'preview_image'),
                            'duration' => data_get($video, 'duration'),
                        ];
                    })->values()->all();

                    $coursePayload = [
                        'id' => $course->id,
                        'title' => $course->title,
                        'description' => $course->description,
                        'price' => $course->price,
                        'is_free' => (bool) $course->is_free,
                        'thumbnail' => $course->thumbnail,
                        'videos_count' => $course->videos_count,
                        'videos' => $videos,
                        'checkout_url' => route('checkout.show', $course->id),
                    ];
                @endphp

                <article
                    tabindex="0"
                    role="button"
                    @click="openCourse($el.dataset.course)"
                    @keydown.enter.prevent="openCourse($el.dataset.course)"
                    @keydown.space.prevent="openCourse($el.dataset.course)"
                    data-course='@json($coursePayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)'
                    class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500"
                >
                    <div class="relative h-48 w-full overflow-hidden bg-slate-100">
                        @if ($course->thumbnail)
                            <img src="{{ $course->thumbnail }}" alt="Превью курса {{ $course->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-sm text-slate-500">
                                Изображение появится позже
                            </div>
                        @endif
                        <div class="absolute left-4 top-4 flex gap-2">
                            @if ($course->is_free)
                                <x-badge type="free">Бесплатно</x-badge>
                            @else
                                <x-badge type="paid">Премиум</x-badge>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col gap-4 p-6">
                        <div class="space-y-2">
                            <h2 class="line-clamp-2 text-lg font-semibold text-slate-900" title="{{ $course->title }}">{{ $course->title }}</h2>
                            <p class="line-clamp-2 text-sm text-slate-600">{{ $course->description }}</p>
                        </div>
                        <div class="mt-auto space-y-3">
                            <div class="flex items-center justify-between text-sm font-medium text-slate-700">
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l3 3" />
                                    </svg>
                                    {{ $course->videos_count }} уроков
                                </span>
                                <span class="text-base font-semibold text-slate-900">
                                    @if ($course->is_free)
                                        Бесплатно
                                    @else
                                        {{ number_format($course->price, 0, ',', ' ') }} ₽
                                    @endif
                                </span>
                            </div>
                            <x-button variant="primary" size="md" class="w-full" @click.stop="openCourse($el.closest('article').dataset.course)">
                                Открыть
                            </x-button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-500">
                    Пока нет доступных курсов. Загляните позже!
                </div>
            @endforelse
        </section>

        <div class="flex justify-center">
            {{ $courses->links('pagination::tailwind') }}
        </div>

        <template x-if="modal.open && modal.type">
            <div
                x-show="modal.open && modal.type"
                x-transition.opacity
                class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 px-4 py-8"
                x-on:click.self="closeModal()"
                x-on:keydown.escape.window="closeModal()"
                x-cloak
            >
                <div
                    x-ref="modalContent"
                    tabindex="-1"
                    x-on:keydown.tab="focusTrap($event)"
                :class="{
                    'max-w-6xl': modal.type === 'player',
                    'max-w-lg': modal.type !== 'player'
                }"
                class="relative w-full overflow-hidden rounded-2xl bg-white p-6 shadow-2xl focus:outline-none"
                x-effect="if (modal.open) { $nextTick(() => { const focusTarget = $refs.modalContent?.querySelector('[data-autofocus], button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'); focusTarget?.focus(); }); }"
                role="dialog"
                aria-modal="true"
                :aria-labelledby="`${modal.type}-modal-title`"
            >
                <template x-if="modal.open && modal.type === 'player'">
                    @include('partials.player-modal')
                </template>
                <template x-if="modal.open && modal.type === 'payment'">
                    @include('partials.payment-modal')
                </template>
                <template x-if="modal.open && modal.type === 'login'">
                    @include('partials.login-modal')
                </template>
            </div>
        </template>
    </div>

    {{--
        README
        Разместите файлы в каталоге resources/views/ согласно структуре:
        - layouts/app.blade.php
        - partials/*.blade.php
        - components/*.blade.php
        - courses/index.blade.php

        Контроллер должен передавать в шаблон переменные:
        - $courses (LengthAwarePaginator<CourseResource>)
        - $accessibleCourseIds (array<int> или null)
    --}}
@endsection
