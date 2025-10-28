@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow p-8 space-y-6">
        <div class="space-y-2">
            <h1 class="text-2xl font-semibold text-gray-900">Оплата курса «{{ $course->title }}»</h1>
            <p class="text-sm text-gray-500">Цена курса: <span class="font-medium text-gray-900">{{ $course->is_free ? 'Бесплатно' : number_format($course->price, 2, ',', ' ') . ' ₽' }}</span></p>
        </div>

        @if ($errors->has('payment'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 rounded-xl p-4 text-sm">
                {{ $errors->first('payment') }}
            </div>
        @endif

        @isset($existingPurchase)
            @if ($existingPurchase && $existingPurchase->payment_status === 'paid')
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl p-4 text-sm space-y-2">
                    <p class="font-medium">Оплата успешно выполнена.</p>
                    <p>Вы уже получили доступ к курсу. Перейдите в раздел «Мои курсы», чтобы начать обучение.</p>
                    <a href="{{ route('courses.my') }}" class="inline-flex items-center text-sm font-semibold text-emerald-700 hover:text-emerald-800">Перейти к моим курсам</a>
                </div>
            @elseif ($existingPurchase && $existingPurchase->payment_status === 'pending' && $existingPurchase->provider_payment_id)
                <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-xl p-4 text-sm space-y-2">
                    <p class="font-medium">Платёж обрабатывается.</p>
                    <p>Вы можете проверить актуальный статус платежа на отдельной странице.</p>
                    <a href="{{ route('checkout.status', ['course' => $course, 'purchase' => $existingPurchase]) }}" class="inline-flex items-center text-sm font-semibold text-amber-700 hover:text-amber-800">Проверить статус оплаты</a>
                </div>
            @endif
        @endisset

        @if (!isset($existingPurchase) || ($existingPurchase?->payment_status ?? null) !== 'paid')
            <div class="space-y-4">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-blue-700 space-y-2">
                    <p class="font-medium">Оплата через YooKassa</p>
                    <p>После нажатия на кнопку вы будете перенаправлены на защищённую страницу YooKassa для ввода данных карты. После успешной оплаты вы автоматически вернётесь на сайт.</p>
                </div>

                @auth
                    <form method="POST" action="{{ route('checkout.process', $course) }}" class="space-y-3">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            Оплатить через YooKassa
                        </button>
                    </form>
                @else
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm text-slate-600">
                        Чтобы продолжить оплату, пожалуйста, войдите в аккаунт или зарегистрируйтесь.
                    </div>
                @endauth
            </div>
        @endif

        <div class="space-y-2">
            <a href="{{ route('courses.index') }}" class="block text-center text-sm text-blue-600 hover:text-blue-700">Вернуться к курсам</a>
            @auth
                <p class="text-xs text-gray-400 text-center">Возникли сложности? Свяжитесь с поддержкой, указав номер заказа {{ $existingPurchase?->id ?? '—' }}.</p>
            @endauth
        </div>
    </div>
@endsection
