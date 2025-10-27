@php($isEdit = isset($course))
@php(
    $startDateValue = old('start_date')
)
@if ($startDateValue === null && $isEdit && $course->start_date)
    @php($startDateValue = $course->start_date->format('Y-m-d\TH:i'))
@endif
<div class="grid gap-5">
    <div class="grid gap-2">
        <label for="title" class="text-sm font-semibold text-slate-600">Название</label>
        <input id="title" name="title" type="text" value="{{ old('title', $course->title ?? '') }}" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>
    <div class="grid gap-2">
        <label for="slug" class="text-sm font-semibold text-slate-600">Slug</label>
        <input id="slug" name="slug" type="text" value="{{ old('slug', $course->slug ?? '') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Заполнится автоматически, если оставить пустым">
    </div>
    <div class="grid gap-2">
        <label for="description" class="text-sm font-semibold text-slate-600">Описание</label>
        <textarea id="description" name="description" rows="5" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('description', $course->description ?? '') }}</textarea>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="price" class="text-sm font-semibold text-slate-600">Цена</label>
            <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $course->price ?? 0) }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
        </div>
        <div class="flex items-center gap-3 pt-6">
            <input type="checkbox" id="is_free" name="is_free" value="1" @checked(old('is_free', $course->is_free ?? false)) class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <label for="is_free" class="text-sm text-slate-600">Бесплатный курс</label>
        </div>
    </div>
    <div class="grid gap-2 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="start_date" class="text-sm font-semibold text-slate-600">Дата начала курса</label>
            <input
                id="start_date"
                name="start_date"
                type="datetime-local"
                value="{{ $startDateValue }}"
                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            >
        </div>
        <p class="text-xs text-slate-500 sm:pt-6">
            Укажите дату, с которой уроки станут доступны студентам. До старта они увидят предложение предзаказа.
        </p>
    </div>
    <div class="grid gap-2">
        <label for="thumbnail" class="text-sm font-semibold text-slate-600">URL обложки</label>
        <input id="thumbnail" name="thumbnail" type="text" value="{{ old('thumbnail', $course->thumbnail ?? '') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>
</div>
