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
            <th class="px-6 py-3">Порядок</th>
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
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-7 min-w-[2.5rem] items-center justify-center rounded-lg bg-slate-100 text-xs font-semibold text-slate-600">
                            #{{ $course->sort_order }}
                        </span>
                        <div class="flex flex-col gap-1">
                            <form method="POST" action="{{ route('admin.courses.move', ['course' => $course, 'direction' => 'up', 'page' => $courses->currentPage()]) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center gap-1 text-xs font-semibold text-slate-500 transition hover:text-slate-700 disabled:cursor-not-allowed disabled:opacity-40" {{ $course->sort_order <= $minSortOrder ? 'disabled' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                        <path fill-rule="evenodd" d="M10 4a.75.75 0 0 1 .53.22l4.75 4.75a.75.75 0 0 1-1.06 1.06L10 5.81 5.78 10.03a.75.75 0 1 1-1.06-1.06l4.75-4.75A.75.75 0 0 1 10 4Z" clip-rule="evenodd" />
                                    </svg>
                                    Вверх
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.courses.move', ['course' => $course, 'direction' => 'down', 'page' => $courses->currentPage()]) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center gap-1 text-xs font-semibold text-slate-500 transition hover:text-slate-700 disabled:cursor-not-allowed disabled:opacity-40" {{ $course->sort_order >= $maxSortOrder ? 'disabled' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                        <path fill-rule="evenodd" d="M10 15.75a.75.75 0 0 1-.53-.22L4.72 10.78a.75.75 0 1 1 1.06-1.06L10 13.94l4.22-4.22a.75.75 0 0 1 1.06 1.06l-4.75 4.75a.75.75 0 0 1-.53.22Z" clip-rule="evenodd" />
                                    </svg>
                                    Вниз
                                </button>
                            </form>
                        </div>
                    </div>
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
