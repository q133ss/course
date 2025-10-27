@extends('admin.layout')

@section('content')
<div class="max-w-4xl">
    <h1 class="text-2xl font-semibold text-slate-800">Новый курс</h1>
    <p class="text-sm text-slate-500">Опишите содержание курса и его параметры.</p>

    <form method="POST" action="{{ route('admin.courses.store') }}" class="mt-6 space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @include('admin.courses._form')
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.courses.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">Отмена</a>
            <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Сохранить</button>
        </div>
    </form>
</div>
@endsection
