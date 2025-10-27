@extends('admin.layout')

@section('content')
<div class="flex items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold text-slate-800">Управление пользователями</h1>
        <p class="text-sm text-slate-500">Создание, редактирование и удаление пользователей платформы.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Добавить пользователя</a>
</div>

<div class="mt-6 overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
        <tr>
            <th class="px-6 py-3">Имя</th>
            <th class="px-6 py-3">Email</th>
            <th class="px-6 py-3">Роль</th>
            <th class="px-6 py-3">Создан</th>
            <th class="px-6 py-3 text-right">Действия</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
        @foreach($users as $user)
            <tr>
                <td class="px-6 py-4">
                    <div class="font-semibold text-slate-800">{{ $user->name }}</div>
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                        {{ $user->role->name ?? '—' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-slate-500">{{ optional($user->created_at)->format('d.m.Y') }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Изменить</a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Удалить пользователя?');">
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
    {{ $users->links() }}
</div>
@endsection
