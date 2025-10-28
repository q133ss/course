@php($isEdit = isset($video))
<div class="grid gap-5">
    <div class="grid gap-2">
        <label for="course_id" class="text-sm font-semibold text-slate-600">Курс</label>
        <select id="course_id" name="course_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            @foreach($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', $video->course_id ?? '') == $course->id)>{{ $course->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid gap-2">
        <label for="title" class="text-sm font-semibold text-slate-600">Название</label>
        <input id="title" name="title" type="text" value="{{ old('title', $video->title ?? '') }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>
    <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
        <input
            type="checkbox"
            id="is_free"
            name="is_free"
            value="1"
            @checked(old('is_free', $video->is_free ?? false))
            class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
        >
        <div class="space-y-1">
            <label for="is_free" class="text-sm font-semibold text-slate-600">Бесплатный урок</label>
            <p class="text-xs text-slate-500">
                Отметьте, если видео должно быть доступно всем пользователям, даже если сам курс платный.
            </p>
        </div>
    </div>
    <div class="grid gap-2">
        <label for="short_description" class="text-sm font-semibold text-slate-600">Короткое описание</label>
        <input id="short_description" name="short_description" type="text" value="{{ old('short_description', $video->short_description ?? '') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>
    <div class="grid gap-2">
        <label for="full_description" class="text-sm font-semibold text-slate-600">Полное описание</label>
        <textarea id="full_description" name="full_description" rows="5" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('full_description', $video->full_description ?? '') }}</textarea>
        <p class="text-xs text-slate-500">
            Для блоков кода и копируемого текста используйте тройные кавычки &laquo;```&raquo;. Всё, что будет внутри блока,
            отобразится с сохранением отступов и кнопкой &laquo;Копировать&raquo;.<br>
            Пример:<br>
            <span class="font-mono">```<br>composer install<br>php artisan migrate<br>```</span>
        </p>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="video_file" class="text-sm font-semibold text-slate-600">Файл видео</label>
            <input
                id="video_file"
                name="video_file"
                type="file"
                accept="video/*"
                @if(!$isEdit) required @endif
                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            >
            <p class="text-xs text-slate-500">Загрузите файл урока в формате MP4, MOV, AVI, MKV или WEBM.</p>
            @if($isEdit && $video->video_url)
                <p class="text-xs text-slate-500">
                    Текущее видео:
                    <a href="{{ $video->video_url }}" target="_blank" rel="noopener" class="font-semibold text-blue-600 hover:underline">Скачать</a>
                </p>
            @endif
        </div>
        <div class="grid gap-2">
            <label for="preview_image_file" class="text-sm font-semibold text-slate-600">Изображение превью</label>
            <input
                id="preview_image_file"
                name="preview_image_file"
                type="file"
                accept="image/*"
                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            >
            <p class="text-xs text-slate-500">Необязательно. Добавьте изображение, которое будет показываться перед началом видео.</p>
            @if($isEdit && $video->preview_image)
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-3">
                    <img src="{{ $video->preview_image }}" alt="Текущее превью" class="h-16 w-16 rounded-lg object-cover">
                    <a href="{{ $video->preview_image }}" target="_blank" rel="noopener" class="text-xs font-semibold text-blue-600 hover:underline">Открыть изображение</a>
                </div>
            @endif
        </div>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="duration" class="text-sm font-semibold text-slate-600">Длительность (сек)</label>
            <input id="duration" name="duration" type="number" min="0" step="1" value="{{ old('duration', $video->duration ?? '') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        </div>
        <div class="grid gap-2">
            <label for="sort_order" class="text-sm font-semibold text-slate-600">Порядок</label>
            <input id="sort_order" name="sort_order" type="number" step="1" value="{{ old('sort_order', $video->sort_order ?? 0) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        </div>
    </div>
</div>
