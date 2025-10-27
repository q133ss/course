@extends('admin.layout')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-semibold text-slate-800">Новый пользователь</h1>
    <p class="text-sm text-slate-500">Заполните форму, чтобы добавить пользователя в систему.</p>

    <form method="POST" action="{{ route('admin.users.store') }}" class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
        @csrf
        @include('admin.users._form')
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">Отмена</a>
            <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Сохранить</button>
        </div>
    </form>
</div>
@endsection
