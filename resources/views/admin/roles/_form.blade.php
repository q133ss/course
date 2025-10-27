<div class="grid gap-4">
    <div>
        <label class="text-sm font-semibold text-slate-600" for="name">Название</label>
        <input id="name" name="name" type="text" value="{{ old('name', $role->name ?? '') }}" required class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>
    <div>
        <label class="text-sm font-semibold text-slate-600" for="slug">Slug</label>
        <input id="slug" name="slug" type="text" value="{{ old('slug', $role->slug ?? '') }}" required class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>
</div>
