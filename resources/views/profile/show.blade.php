@extends('layouts.app')

@section('content')
    @include('partials.nav')

    <div class="mx-auto max-w-3xl space-y-8 py-12">
        <header class="space-y-2">
            <p class="text-sm font-semibold uppercase tracking-wide text-sky-600">Профиль</p>
            <h1 class="text-3xl font-semibold text-slate-900">{{ $user->name }}</h1>
            <p class="text-slate-600">{{ $user->email }}</p>
        </header>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Настройки аккаунта</h2>
            <p class="mt-2 text-sm text-slate-600">Здесь в будущем появятся настройки профиля и прогресс обучения.</p>
        </section>
    </div>
@endsection
