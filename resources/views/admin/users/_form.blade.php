@php($isEdit = isset($user))
<div class="grid gap-4">
    <div>
        <label class="text-sm font-semibold text-slate-600" for="name">Имя</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name ?? '') }}" required class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>
    <div>
        <label class="text-sm font-semibold text-slate-600" for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}" required class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>
    <div class="grid gap-2">
        <label class="text-sm font-semibold text-slate-600" for="role_id">Роль</label>
        <select id="role_id" name="role_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            @foreach($roles as $role)
                <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id ?? '') == $role->id)>{{ $role->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm font-semibold text-slate-600" for="password">Пароль</label>
        <input id="password" name="password" type="password" @if(!$isEdit) required @endif placeholder="{{ $isEdit ? 'Оставьте пустым, чтобы не менять' : '' }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
    </div>
</div>
