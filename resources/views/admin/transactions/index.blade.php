@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">Транзакции</h1>
            <p class="text-sm text-slate-500">История оплат и статистика продаж.</p>
        </div>
        <form method="GET" class="flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div>
                <label class="text-xs font-semibold uppercase text-slate-500" for="search">Поиск</label>
                <input id="search" name="search" type="text" value="{{ $filters['search'] ?? '' }}" placeholder="Имя, email или курс" class="mt-1 w-56 rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>
            <div>
                <label class="text-xs font-semibold uppercase text-slate-500" for="status">Статус</label>
                <select id="status" name="status" class="mt-1 w-40 rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <option value="">Все</option>
                    <option value="successful" @selected(($filters['status'] ?? '') === 'successful')>Успешные</option>
                    <option value="failed" @selected(($filters['status'] ?? '') === 'failed')>Неуспешные</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase text-slate-500" for="method">Метод</label>
                <select id="method" name="method" class="mt-1 w-40 rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <option value="">Все</option>
                    <option value="card" @selected(($filters['method'] ?? '') === 'card')>Банковская карта</option>
                    <option value="paypal" @selected(($filters['method'] ?? '') === 'paypal')>PayPal</option>
                    <option value="crypto" @selected(($filters['method'] ?? '') === 'crypto')>Криптовалюта</option>
                </select>
            </div>
            <div class="flex items-center gap-2 pt-6">
                <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Применить</button>
                <a href="{{ route('admin.transactions.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">Сбросить</a>
            </div>
        </form>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase text-slate-500">Всего выручка</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900">₽{{ number_format($stats['totalRevenue'], 2, ',', ' ') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase text-slate-500">Успешных оплат</p>
            <p class="mt-2 text-2xl font-semibold text-emerald-600">{{ $stats['successfulPayments'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase text-slate-500">Неуспешных оплат</p>
            <p class="mt-2 text-2xl font-semibold text-rose-600">{{ $stats['failedPayments'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase text-slate-500">Средний чек</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900">
                @if($transactions->total())
                    ₽{{ number_format($stats['totalRevenue'] / max($transactions->total(), 1), 2, ',', ' ') }}
                @else
                    —
                @endif
            </p>
        </div>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-6 py-3">Дата</th>
                <th class="px-6 py-3">Пользователь</th>
                <th class="px-6 py-3">Курс</th>
                <th class="px-6 py-3">Метод</th>
                <th class="px-6 py-3">Статус</th>
                <th class="px-6 py-3 text-right">Сумма</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($transactions as $transaction)
                <tr>
                    <td class="px-6 py-4 text-slate-600">{{ optional($transaction->purchased_at)->format('d.m.Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-800">{{ $transaction->user->name ?? '—' }}</div>
                        <div class="text-xs text-slate-500">{{ $transaction->user->email ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $transaction->course->title ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ ucfirst($transaction->payment_method ?? '') }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $transaction->payment_status === 'successful' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ ucfirst($transaction->payment_status ?? '') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-semibold text-slate-800">₽{{ number_format($transaction->amount, 2, ',', ' ') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-6 text-center text-sm text-slate-500">Транзакции не найдены.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between">
        <div class="text-sm text-slate-500">
            Показано {{ $transactions->firstItem() ?? 0 }}–{{ $transactions->lastItem() ?? 0 }} из {{ $transactions->total() }}
        </div>
        {{ $transactions->links() }}
    </div>
</div>
@endsection
