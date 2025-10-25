<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'EduX' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">
    <header class="sticky top-0 inset-x-0 z-40 bg-white/90 backdrop-blur border-b border-gray-100 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('courses.index') }}" class="flex items-center gap-2 text-xl font-semibold text-blue-600">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600 font-bold">E</span>
                    <span>EduX</span>
                </a>
                @php
                    $navItems = [
                        ['label' => 'Главная', 'href' => url('/'), 'active' => request()->routeIs('courses.index') || request()->is('/')],
                        ['label' => 'Мои курсы', 'href' => url('/courses?mine=1'), 'active' => request()->fullUrlIs(url('/courses?mine=1'))],
                        ['label' => 'Профиль', 'href' => url('/profile'), 'active' => request()->is('profile')],
                    ];
                @endphp
                <nav class="flex items-center gap-1 text-sm flex-wrap justify-end">
                    @foreach ($navItems as $item)
                        <a href="{{ $item['href'] }}"
                           class="px-3 py-2 rounded-full text-sm font-medium transition-colors {{ $item['active'] ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
                <div class="flex items-center gap-3">
                    @guest
                        <button type="button"
                                data-login-trigger
                                class="px-4 py-2 rounded-full bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                            Войти
                        </button>
                    @endguest
                    @auth
                        <div class="text-sm text-gray-700 text-right">
                            <div class="font-semibold">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">Добро пожаловать!</div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @yield('content')
    </main>

    <div id="login-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="login-modal-backdrop absolute inset-0 bg-gray-900/60"></div>
        <div class="login-modal-card relative bg-white rounded-2xl shadow-xl w-full max-w-sm mx-auto p-6">
            <button type="button" id="login-modal-close" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600" aria-label="Закрыть">
                &times;
            </button>
            <h2 class="text-xl font-semibold mb-4">Вход в аккаунт</h2>
            <p class="text-sm text-gray-600 mb-4">Введите email и пароль, чтобы продолжить обучение.</p>
            <form method="POST" action="{{ url('/login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="login-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="login-email" name="email" type="email" required autocomplete="email" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="you@example.com">
                </div>
                <div>
                    <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
                    <input id="login-password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="••••••••">
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">Войти</button>
            </form>
            <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                <a href="#" class="hover:text-blue-600">Забыли пароль?</a>
                <a href="#" class="hover:text-blue-600">Регистрация</a>
            </div>
            <p class="mt-4 text-xs text-gray-400">Авторизация пока не реализована — интерфейсная заглушка.</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginModal = document.getElementById('login-modal');
            const loginClose = document.getElementById('login-modal-close');
            const loginButtons = document.querySelectorAll('[data-login-trigger]');

            const openLoginModal = () => {
                if (!loginModal) return;
                loginModal.classList.remove('hidden');
                loginModal.classList.add('flex');
            };

            const closeLoginModal = () => {
                if (!loginModal) return;
                loginModal.classList.add('hidden');
                loginModal.classList.remove('flex');
            };

            window.openLoginModal = openLoginModal;
            window.closeLoginModal = closeLoginModal;

            loginButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    openLoginModal();
                });
            });

            loginClose?.addEventListener('click', (event) => {
                event.preventDefault();
                closeLoginModal();
            });

            loginModal?.addEventListener('click', (event) => {
                if (event.target === loginModal || event.target.classList.contains('login-modal-backdrop')) {
                    closeLoginModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeLoginModal();
                }
            });
        });
    </script>
</body>
</html>
