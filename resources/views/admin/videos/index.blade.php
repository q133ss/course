@extends('admin.layout')

@section('content')
<div class="flex items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold text-slate-800">Видео</h1>
        <p class="text-sm text-slate-500">Управляйте уроками и материалами курсов.</p>
    </div>
    <a href="{{ route('admin.videos.create') }}" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Добавить видео</a>
</div>

<div class="mt-6 overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
        <tr>
            <th class="px-6 py-3">Название</th>
            <th class="px-6 py-3">Курс</th>
            <th class="px-6 py-3">Длительность</th>
            <th class="px-6 py-3">Порядок</th>
            <th class="px-6 py-3 text-right">Действия</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
        @foreach($videos as $video)
            <tr>
                <td class="px-6 py-4">
                    <div class="font-semibold text-slate-800">{{ $video->title }}</div>
                    <div class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($video->short_description, 80) }}</div>
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $video->course->title ?? '—' }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $video->duration ? gmdate('i:s', $video->duration) : '—' }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $video->sort_order }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.videos.edit', $video) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Изменить</a>
                        <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Удалить видео?');">
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
    {{ $videos->links() }}
</div>
@endsection
