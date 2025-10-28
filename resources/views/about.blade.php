@extends('layouts.app')

@section('content')
    <section class="space-y-16">
        <header class="text-center max-w-3xl mx-auto space-y-6">
            <p class="text-sm uppercase tracking-widest text-blue-500 font-semibold">👨‍💻 Об авторе</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">Алексей Мирошкин — разработчик, предприниматель и наставник</h1>
            <p class="text-lg text-gray-600">Я верю, что путь в IT может быть простым и понятным. Делюсь своим опытом, чтобы помочь каждому уверенно сделать первые шаги.</p>
        </header>

        <div class="grid gap-10 lg:grid-cols-[3fr,2fr] items-start">
            <article class="space-y-8 text-base leading-relaxed text-gray-700">
                <p>Привет! Меня зовут Алексей Мирошкин, я — разработчик, предприниматель и человек, который однажды решил, что путь в IT не должен быть сложным и запутанным.</p>
                <p>Я начал с нуля. Без связей, без наставников, просто с ноутбуком и желанием разобраться, как это всё работает. Пять лет спустя — я разрабатываю государственные системы, вывожу продукты на рынок за считанные месяцы и обучаю других, как пройти этот путь быстрее и с меньшими ошибками.</p>
                <p>С моими решениями запускались крупные проекты вроде РГИС ПРИО, ТР Архив, Большое Дело (проект Дмитрия Портнягина). Когда предыдущая команда не смогла запустить продукт за год — я собрал новую, сократил процессы, внедрил ИИ и вывел проект в продакшен за 3 месяца. Это не просто строки в резюме — это реальный опыт, который я теперь превращаю в понятные и применимые курсы.</p>

                <div class="p-6 bg-white rounded-3xl shadow-sm ring-1 ring-gray-100 space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900">🎯 Для кого мои курсы</h2>
                    <ul class="space-y-3 list-disc list-inside text-gray-700">
                        <li>
                            <span class="font-medium text-gray-900">Для новичков,</span>
                            которые хотят войти в IT без страха и хаоса. Я покажу, как начать с нуля, понять, что реально востребовано, и собрать своё первое портфолио.
                        </li>
                        <li>
                            <span class="font-medium text-gray-900">Для фрилансеров,</span>
                            которые уже умеют что-то делать, но не знают, как продать себя. Вместо «учись ради учёбы» — я даю систему, с которой можно зарабатывать на навыках.
                        </li>
                    </ul>
                </div>

                <div class="p-6 bg-blue-50 rounded-3xl border border-blue-100 space-y-4">
                    <h2 class="text-xl font-semibold text-blue-900">💡 Почему мне можно доверять</h2>
                    <p class="text-blue-900/80">Я не «коуч по видео из YouTube». Я разработчик, который каждый день решает реальные задачи — и обучает этому других. За моими плечами проекты для госструктур, бизнеса и крупных блогеров. Я знаю, где новички чаще всего «тонут», и как превратить первые ошибки в рост.</p>
                    <p class="text-blue-900/80">Моя миссия — не просто научить кодить, а показать, что IT — это не про сложность, а про возможности.</p>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-6 p-6 bg-gray-900 text-white rounded-3xl">
                    <div class="space-y-2">
                        <h2 class="text-2xl font-semibold">🚀 Начни свой путь</h2>
                        <p class="text-gray-200">Если ты чувствуешь, что готов сделать шаг в сторону свободы — перейди к моим курсам и выбери тот, с которого начнёшь свой рост.</p>
                    </div>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-white text-gray-900 font-semibold shadow hover:shadow-md transition">
                        👉 Посмотреть курсы
                    </a>
                </div>
            </article>

            <aside class="space-y-6">
                <div class="overflow-hidden rounded-3xl shadow-lg">
                    <img
                        src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=900&q=80"
                        alt="Алексей Мирошкин за работой"
                        class="w-full h-full object-cover"
                    >
                </div>
                <div class="overflow-hidden rounded-3xl shadow-lg">
                    <img
                        src="https://images.unsplash.com/photo-1487611459768-bd414656ea10?auto=format&fit=crop&w=900&q=80"
                        alt="Команда на совместной работе"
                        class="w-full h-full object-cover"
                    >
                </div>
            </aside>
        </div>
    </section>
@endsection
