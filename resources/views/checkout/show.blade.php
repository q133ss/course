@extends('layouts.app')

@section('content')
    @include('partials.nav')

    <div class="mx-auto max-w-3xl space-y-8 py-12">
        <header class="space-y-3">
            <p class="text-sm font-semibold uppercase tracking-wide text-sky-600">Оплата курса</p>
            <h1 class="text-3xl font-semibold text-slate-900">{{ $course->title }}</h1>
            <p class="text-slate-600">Перед подтверждением оплаты убедитесь, что данные курса корректны.</p>
        </header>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <dl class="grid gap-4 text-sm text-slate-700">
                <div class="flex items-center justify-between">
                    <dt class="font-medium text-slate-500">Стоимость</dt>
                    <dd class="text-lg font-semibold text-slate-900">
                        @if ($course->is_free)
                            Бесплатно
                        @else
                            {{ number_format((float) $course->price, 2, ',', ' ') }} ₽
                        @endif
                    </dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="font-medium text-slate-500">Количество уроков</dt>
                    <dd>{{ $course->videos_count }}</dd>
                </div>
            </dl>
            <div class="mt-6 flex gap-3">
                <x-button variant="primary" size="md" href="{{ route('checkout.show', $course) }}">Перейти к оплате</x-button>
                <x-button variant="ghost" size="md" href="{{ route('courses.index') }}">Вернуться в каталог</x-button>
            </div>
        </section>
    </div>
@endsection
