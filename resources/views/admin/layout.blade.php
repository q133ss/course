<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Админ-панель' }} — EduX</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">
<div class="flex min-h-screen">
    <aside class="w-64 bg-white border-r border-slate-200 hidden md:block">
        <div class="p-6 border-b border-slate-200">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-semibold text-blue-600">EduX Admin</a>
            <p class="text-xs text-slate-500 mt-1">Управление платформой</p>
        </div>
        @php
            $adminNav = [
                ['label' => 'Обзор', 'route' => 'admin.dashboard'],
                ['label' => 'Пользователи', 'route' => 'admin.users.index', 'pattern' => 'admin.users.*'],
                ['label' => 'Курсы', 'route' => 'admin.courses.index', 'pattern' => 'admin.courses.*'],
                ['label' => 'Предзаказы', 'route' => 'admin.preorders.index', 'pattern' => 'admin.preorders.*'],
                ['label' => 'Видео', 'route' => 'admin.videos.index', 'pattern' => 'admin.videos.*'],
                ['label' => 'Роли', 'route' => 'admin.roles.index', 'pattern' => 'admin.roles.*'],
                ['label' => 'Транзакции', 'route' => 'admin.transactions.index'],
            ];
        @endphp
        <nav class="p-4 space-y-1">
            @foreach ($adminNav as $item)
                <a href="{{ route($item['route']) }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs($item['pattern'] ?? $item['route']) ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:text-blue-600 hover:bg-blue-50' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
        <div class="px-4 pb-6">
            <a href="{{ route('courses.index') }}" class="flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600">
                <span>&larr;</span>
                <span>На сайт</span>
            </a>
        </div>
    </aside>
    <div class="flex-1 flex flex-col">
        <header class="bg-white border-b border-slate-200">
            <div class="flex items-center justify-between px-6 py-4">
                <button class="md:hidden inline-flex items-center gap-2 text-sm font-semibold text-slate-600" data-admin-menu-toggle>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    Меню
                </button>
                <div>
                    <div class="text-sm text-slate-500">{{ now()->locale('ru')->translatedFormat('d F Y') }}</div>
                    <div class="text-base font-semibold text-slate-800">{{ $pageTitle ?? 'Панель управления' }}</div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <div class="text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-500">Администратор</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-3 py-2 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Выйти</button>
                    </form>
                </div>
            </div>
        </header>
        <main class="flex-1 px-6 py-6">
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <strong class="font-semibold">Проверьте данные:</strong>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>
</div>
<script>
    const toggleButton = document.querySelector('[data-admin-menu-toggle]');
    if (toggleButton) {
        toggleButton.addEventListener('click', () => {
            document.body.classList.toggle('admin-menu-open');
        });
    }
</script>
</body>
</html>
