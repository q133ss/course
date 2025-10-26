@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
    <div class="grid gap-8 lg:grid-cols-3">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Личные данные</h1>
                        <p class="mt-1 text-sm text-gray-500">Обновите контактную информацию и пароль.</p>
                    </div>
                </div>

                @if (session('status'))
                    <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $user->name) }}"
                            required
                            autocomplete="name"
                            class="mt-1 block w-full rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            autocomplete="email"
                            class="mt-1 block w-full rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <h2 class="text-sm font-semibold text-gray-900">Пароль</h2>
                        <p class="mt-1 text-xs text-gray-500">Оставьте поля пустыми, если не хотите менять пароль.</p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Новый пароль</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            class="mt-1 block w-full rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Подтверждение пароля</label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            class="mt-1 block w-full rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        >
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                            Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">История транзакций</h2>
                        <p class="mt-1 text-sm text-gray-500">Список покупок и их статусы.</p>
                    </div>
                </div>

                @if ($transactions->isEmpty())
                    <div class="mt-6 rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-6 py-10 text-center">
                        <h3 class="text-sm font-semibold text-gray-900">Нет транзакций</h3>
                        <p class="mt-2 text-sm text-gray-500">Вы ещё не совершали покупок. Выберите курс и начните обучение!</p>
                    </div>
                @else
                    <div class="mt-6 overflow-hidden rounded-2xl border border-gray-100">
                        <table class="min-w-full divide-y divide-gray-100 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-700">Курс</th>
                                    <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-700">Сумма</th>
                                    <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-700">Статус</th>
                                    <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-700">Метод</th>
                                    <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-700">Дата</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($transactions as $transaction)
                                    <tr class="hover:bg-blue-50/40">
                                        <td class="px-4 py-4 align-top">
                                            <div class="font-medium text-gray-900">{{ optional($transaction->course)->title ?? 'Неизвестный курс' }}</div>
                                            <div class="mt-1 text-xs text-gray-500">#{{ $transaction->id }}</div>
                                        </td>
                                        <td class="px-4 py-4 align-top whitespace-nowrap">
                                            @if ($transaction->amount == 0)
                                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-600">Бесплатно</span>
                                            @else
                                                <span class="font-semibold text-gray-900">${{ number_format((float) $transaction->amount, 2, '.', ' ') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 align-top whitespace-nowrap">
                                            @php
                                                $statusStyles = [
                                                    'paid' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                                                    'pending' => 'bg-amber-50 text-amber-700 border border-amber-100',
                                                    'failed' => 'bg-red-50 text-red-700 border border-red-100',
                                                ];
                                                $statusLabel = [
                                                    'paid' => 'Оплачено',
                                                    'pending' => 'В обработке',
                                                    'failed' => 'Ошибка оплаты',
                                                ][$transaction->payment_status] ?? 'Неизвестно';
                                            @endphp
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyles[$transaction->payment_status] ?? 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 align-top whitespace-nowrap text-gray-600">
                                            {{ $transaction->payment_method ? ucfirst($transaction->payment_method) : '—' }}
                                        </td>
                                        <td class="px-4 py-4 align-top whitespace-nowrap text-gray-600">
                                            {{ optional($transaction->purchased_at)->format('d.m.Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
