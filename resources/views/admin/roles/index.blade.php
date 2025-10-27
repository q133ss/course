@extends('admin.layout')

@section('content')
<div class="flex items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold text-slate-800">Роли</h1>
        <p class="text-sm text-slate-500">Определяйте доступ и полномочия пользователей.</p>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Добавить роль</a>
</div>

<div class="mt-6 overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
        <tr>
            <th class="px-6 py-3">Название</th>
            <th class="px-6 py-3">Slug</th>
            <th class="px-6 py-3">Пользователей</th>
            <th class="px-6 py-3 text-right">Действия</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
        @foreach($roles as $role)
            <tr>
                <td class="px-6 py-4 font-semibold text-slate-800">{{ $role->name }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $role->slug }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $role->users_count }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Изменить</a>
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Удалить роль?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-700" @disabled($role->slug === \App\Models\Role::ADMIN)>Удалить</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $roles->links() }}
</div>
@endsection
