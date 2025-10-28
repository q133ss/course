@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow p-8 space-y-6">
        <div class="space-y-2">
            <h1 class="text-2xl font-semibold text-gray-900">Статус оплаты курса «{{ $course->title }}»</h1>
            <p class="text-sm text-gray-500">Сумма к оплате: <span class="font-medium text-gray-900">{{ number_format($purchase->amount, 2, ',', ' ') }} ₽</span></p>
            <p class="text-xs text-gray-400">Номер заказа: {{ $purchase->id }}</p>
        </div>

        @if ($purchase->payment_status === 'paid')
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl p-4 space-y-2 text-sm">
                <p class="font-medium">Платёж успешно завершён!</p>
                <p>Доступ к курсу уже открыт. Перейдите в раздел «Мои курсы», чтобы начать обучение.</p>
                <a href="{{ route('courses.my') }}" class="inline-flex items-center text-sm font-semibold text-emerald-700 hover:text-emerald-800">Перейти к моим курсам</a>
            </div>
        @elseif ($purchase->payment_status === 'failed')
            <div class="bg-rose-50 border border-rose-200 text-rose-700 rounded-xl p-4 space-y-2 text-sm">
                <p class="font-medium">Оплата не прошла.</p>
                <p>Пожалуйста, попробуйте снова или используйте другую карту. Если списание произошло, обратитесь в поддержку.</p>
                <a href="{{ route('checkout.show', $course) }}" class="inline-flex items-center text-sm font-semibold text-rose-700 hover:text-rose-800">Вернуться к оплате</a>
            </div>
        @else
            <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-xl p-4 space-y-2 text-sm">
                <p class="font-medium">Платёж ещё подтверждается.</p>
                <p>YooKassa обрабатывает транзакцию. Это обычно занимает пару минут. Обновите страницу через некоторое время.</p>
                <a href="{{ route('checkout.status', ['course' => $course, 'purchase' => $purchase]) }}" class="inline-flex items-center text-sm font-semibold text-amber-700 hover:text-amber-800">Обновить статус</a>
            </div>
        @endif

        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600 space-y-2">
            <p class="font-semibold text-gray-700">Детали платежа</p>
            <div class="flex justify-between">
                <span>Статус</span>
                <span class="font-medium">{{ ucfirst($purchase->payment_status) }}</span>
            </div>
            <div class="flex justify-between">
                <span>Платёжная система</span>
                <span class="font-medium">YooKassa</span>
            </div>
            @if ($purchase->provider_payment_id)
                <div class="flex justify-between">
                    <span>ID платежа YooKassa</span>
                    <span class="font-mono text-xs">{{ $purchase->provider_payment_id }}</span>
                </div>
            @endif
            <div class="flex justify-between">
                <span>Дата обновления</span>
                <span class="font-medium">{{ optional($purchase->purchased_at)->format('d.m.Y H:i') ?? '—' }}</span>
            </div>
        </div>

        <div class="space-y-2 text-sm text-gray-500">
            <p>Если возникли вопросы по оплате, свяжитесь с поддержкой и укажите номер заказа и ID платежа.</p>
            <a href="{{ route('courses.index') }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700">Вернуться к курсам</a>
        </div>
    </div>
@endsection
