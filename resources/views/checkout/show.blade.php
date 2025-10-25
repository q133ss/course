@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow p-8 space-y-6">
        <div class="space-y-2">
            <h1 class="text-2xl font-semibold text-gray-900">Оплата курса «{{ $course->title }}»</h1>
            <p class="text-sm text-gray-500">Цена курса: <span class="font-medium text-gray-900">{{ $course->is_free ? 'Бесплатно' : number_format($course->price, 2, ',', ' ') . ' ₽' }}</span></p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-blue-700">
            Оплата не реализована. Это заглушка. В будущем тут будет интеграция с провайдером платежей.
        </div>
        <div class="space-y-3">
            <button type="button" class="w-full px-4 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">Оплатить</button>
            {{-- TODO: подключить реальную оплату --}}
            <a href="{{ route('courses.index') }}" class="block text-center text-sm text-blue-600 hover:text-blue-700">Вернуться к курсам</a>
        </div>
    </div>
@endsection
