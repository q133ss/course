@extends('admin.layout')

@section('content')
<div class="flex items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold text-slate-800">Курсы</h1>
        <p class="text-sm text-slate-500">Управление каталогом курсов и их параметрами.</p>
    </div>
    <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Добавить курс</a>
</div>

<div class="mt-6 overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
        <tr>
            <th class="px-6 py-3">Название</th>
            <th class="px-6 py-3">Slug</th>
            <th class="px-6 py-3">Цена</th>
            <th class="px-6 py-3">Старт</th>
            <th class="px-6 py-3">Видео</th>
            <th class="px-6 py-3 text-right">Действия</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
        @foreach($courses as $course)
            <tr>
                <td class="px-6 py-4">
                    <div class="font-semibold text-slate-800">{{ $course->title }}</div>
                    <div class="text-xs text-slate-500 line-clamp-1">{{ \Illuminate\Support\Str::limit($course->description, 80) }}</div>
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $course->slug }}</td>
                <td class="px-6 py-4">
                    @if($course->is_free)
                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Бесплатно</span>
                    @else
                        <span class="text-sm font-semibold text-slate-800">₽{{ number_format($course->price, 2, ',', ' ') }}</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if ($course->start_date)
                        <div class="text-sm font-medium text-slate-700">{{ $course->start_date->format('d.m.Y H:i') }}</div>
                        @if ($course->isUpcoming())
                            <div class="text-xs font-semibold text-blue-600">Старт через {{ $course->start_date->diffForHumans(null, true) }}</div>
                        @else
                            <div class="text-xs text-emerald-600">Уже в доступе</div>
                        @endif
                    @else
                        <span class="text-xs text-slate-500">Доступен сразу</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $course->videos_count }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.courses.edit', $course) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Изменить</a>
                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Удалить курс?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-700">Удалить</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $courses->links() }}
</div>
@endsection
