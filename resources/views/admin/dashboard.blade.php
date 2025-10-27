@extends('admin.layout')

@section('content')
<div class="space-y-8">
    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Пользователи</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalUsers }}</p>
            <p class="mt-1 text-xs text-slate-400">Всего зарегистрировано</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Курсы</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalCourses }}</p>
            <p class="mt-1 text-xs text-slate-400">Опубликованных курсов</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Выручка</p>
            <p class="mt-2 text-3xl font-semibold text-emerald-600">₽{{ number_format($totalRevenue, 2, ',', ' ') }}</p>
            <p class="mt-1 text-xs text-slate-400">За все время</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Выручка за месяц</p>
            <p class="mt-2 text-3xl font-semibold text-emerald-600">₽{{ number_format($monthlyRevenue, 2, ',', ' ') }}</p>
            <p class="mt-1 text-xs text-slate-400">Текущий месяц</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-slate-800">Последние покупки</h2>
                <p class="text-sm text-slate-500">Пять последних транзакций</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">Дата</th>
                        <th class="px-6 py-3">Пользователь</th>
                        <th class="px-6 py-3">Курс</th>
                        <th class="px-6 py-3 text-right">Сумма</th>
                        <th class="px-6 py-3">Статус</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($recentPurchases as $purchase)
                        <tr>
                            <td class="px-6 py-4 text-slate-600">{{ optional($purchase->purchased_at)->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">{{ $purchase->user->name ?? '—' }}</div>
                                <div class="text-xs text-slate-500">{{ $purchase->user->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $purchase->course->title ?? '—' }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-slate-800">₽{{ number_format($purchase->amount, 2, ',', ' ') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $purchase->payment_status === 'successful' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ ucfirst($purchase->payment_status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-sm text-slate-500">Еще нет транзакций.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-800">Сводка</h2>
            <dl class="mt-4 space-y-4 text-sm">
                <div class="flex items-center justify-between">
                    <dt class="text-slate-500">Всего покупок</dt>
                    <dd class="font-semibold text-slate-800">{{ $totalPurchases }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-slate-500">Средний чек</dt>
                    <dd class="font-semibold text-slate-800">
                        @if($totalPurchases > 0)
                            ₽{{ number_format($totalRevenue / max($totalPurchases, 1), 2, ',', ' ') }}
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-slate-500">Активных пользователей</dt>
                    <dd class="font-semibold text-slate-800">{{ $totalUsers }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-slate-500">Опубликованных курсов</dt>
                    <dd class="font-semibold text-slate-800">{{ $totalCourses }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
