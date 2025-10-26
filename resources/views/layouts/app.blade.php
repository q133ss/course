<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'EduX' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">
    @php
        $authModalOpenFlag = session('auth_modal_open');
        $authModalShouldOpen = ($authModalOpenFlag ?? false) || $errors->login->any() || $errors->register->any();
        $authModalTabFromSession = session('auth_modal_tab');
        $authModalDefaultTab = $authModalTabFromSession ?? ($errors->register->any() ? 'register' : 'login');
        $loginOldEmail = ($errors->login->any() || $authModalTabFromSession === 'login') ? old('email') : '';
        $loginRemember = ($errors->login->any() || $authModalTabFromSession === 'login') ? old('remember') !== null : false;
        $registerOldName = ($errors->register->any() || $authModalTabFromSession === 'register') ? old('name') : '';
        $registerOldEmail = ($errors->register->any() || $authModalTabFromSession === 'register') ? old('email') : '';
    @endphp

    @if (session('auth_success'))
        <div class="bg-emerald-500/90 text-white text-sm text-center py-2 px-4" role="status">
            {{ session('auth_success') }}
        </div>
    @endif
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
                                data-register-trigger
                                class="px-4 py-2 rounded-full border border-blue-600 text-blue-600 text-sm font-semibold hover:bg-blue-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                            Регистрация
                        </button>
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
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-full bg-gray-100 text-sm font-semibold text-gray-700 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-gray-400">
                                Выйти
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @yield('content')
    </main>

    <div
        id="auth-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center"
        data-should-open="{{ $authModalShouldOpen ? 'true' : 'false' }}"
        data-default-tab="{{ $authModalDefaultTab }}"
        aria-hidden="true"
    >
        <div class="auth-modal-backdrop absolute inset-0 bg-gray-900/60"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-auto p-6" role="dialog" aria-modal="true" aria-labelledby="auth-modal-heading">
            <h2 id="auth-modal-heading" class="sr-only">Вход и регистрация</h2>
            <button type="button" id="auth-modal-close" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500" aria-label="Закрыть">
                &times;
            </button>
            <div class="flex justify-center mb-6" role="tablist">
                <button
                    type="button"
                    data-auth-tab="login"
                    id="auth-tab-login"
                    class="px-4 py-2 text-sm font-semibold rounded-full flex-1 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500"
                    role="tab"
                    aria-controls="auth-panel-login"
                >
                    Вход
                </button>
                <button
                    type="button"
                    data-auth-tab="register"
                    id="auth-tab-register"
                    class="px-4 py-2 text-sm font-semibold rounded-full flex-1 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500"
                    role="tab"
                    aria-controls="auth-panel-register"
                >
                    Регистрация
                </button>
            </div>

            <div data-auth-panel="login" id="auth-panel-login" role="tabpanel" aria-labelledby="auth-tab-login" class="space-y-4">
                <h2 class="text-xl font-semibold">Добро пожаловать обратно!</h2>
                <p class="text-sm text-gray-600">Введите email и пароль, чтобы продолжить обучение.</p>

                @if ($errors->login->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->login->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4" novalidate>
                    @csrf
                    <div>
                        <label for="login-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="login-email" name="email" type="email" value="{{ $loginOldEmail }}" required autocomplete="email" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="you@example.com">
                        @error('email', 'login')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
                        <input id="login-password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="••••••••">
                        @error('password', 'login')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="remember" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ $loginRemember ? 'checked' : '' }}>
                            <span class="text-gray-600">Запомнить меня</span>
                        </label>
                        <a href="#" class="text-gray-500 hover:text-blue-600">Забыли пароль?</a>
                    </div>
                    <button type="submit" class="w-full py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">Войти</button>
                </form>
                <p class="text-sm text-gray-600 text-center">
                    Нет аккаунта?
                    <a href="#" data-auth-switch="register" class="font-semibold text-blue-600 hover:text-blue-700">Зарегистрируйтесь</a>
                </p>
            </div>

            <div data-auth-panel="register" id="auth-panel-register" role="tabpanel" aria-labelledby="auth-tab-register" class="space-y-4 hidden">
                <h2 class="text-xl font-semibold">Создайте аккаунт</h2>
                <p class="text-sm text-gray-600">Получите доступ ко всем курсам и персональному кабинету.</p>

                @if ($errors->register->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->register->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4" novalidate>
                    @csrf
                    <div>
                        <label for="register-name" class="block text-sm font-medium text-gray-700 mb-1">Имя</label>
                        <input id="register-name" name="name" type="text" value="{{ $registerOldName }}" required autocomplete="name" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Иван Иванов">
                        @error('name', 'register')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="register-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="register-email" name="email" type="email" value="{{ $registerOldEmail }}" required autocomplete="email" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="you@example.com">
                        @error('email', 'register')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="register-password" class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
                        <input id="register-password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Не менее 8 символов">
                        @error('password', 'register')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="register-password-confirmation" class="block text-sm font-medium text-gray-700 mb-1">Подтверждение пароля</label>
                        <input id="register-password-confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Повторите пароль">
                        @error('password_confirmation', 'register')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">Зарегистрироваться</button>
                </form>
                <p class="text-sm text-gray-600 text-center">
                    Уже есть аккаунт?
                    <a href="#" data-auth-switch="login" class="font-semibold text-blue-600 hover:text-blue-700">Войдите</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const authModal = document.getElementById('auth-modal');
            const closeButton = document.getElementById('auth-modal-close');
            const loginButtons = document.querySelectorAll('[data-login-trigger]');
            const registerButtons = document.querySelectorAll('[data-register-trigger]');

            if (!authModal) {
                return;
            }

            const tabButtons = authModal.querySelectorAll('[data-auth-tab]');
            const panels = authModal.querySelectorAll('[data-auth-panel]');
            const switchLinks = authModal.querySelectorAll('[data-auth-switch]');
            const body = document.body;

            let currentTab = authModal.dataset.defaultTab || 'login';
            let lastFocusedElement = null;

            const focusFirstField = (tab) => {
                const panel = authModal.querySelector(`[data-auth-panel="${tab}"]`);
                if (!panel) {
                    return;
                }

                window.requestAnimationFrame(() => {
                    const focusTarget = panel.querySelector('input:not([type="hidden"])')
                        ?? panel.querySelector('button, select, textarea, a[href]');

                    focusTarget?.focus();
                });
            };

            const setActiveTab = (tab) => {
                currentTab = tab;

                tabButtons.forEach((button) => {
                    const isActive = button.dataset.authTab === tab;
                    button.classList.toggle('bg-blue-600', isActive);
                    button.classList.toggle('text-white', isActive);
                    button.classList.toggle('bg-blue-50', !isActive);
                    button.classList.toggle('text-blue-600', !isActive);
                    button.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    button.setAttribute('tabindex', isActive ? '0' : '-1');
                });

                panels.forEach((panel) => {
                    const isActive = panel.dataset.authPanel === tab;
                    panel.classList.toggle('hidden', !isActive);
                    panel.setAttribute('aria-hidden', isActive ? 'false' : 'true');
                });

            };

            const openModal = (tab = 'login') => {
                lastFocusedElement = document.activeElement instanceof HTMLElement ? document.activeElement : null;
                body.classList.add('overflow-hidden');
                authModal.classList.remove('hidden');
                authModal.classList.add('flex');
                authModal.setAttribute('aria-hidden', 'false');
                setActiveTab(tab);
                focusFirstField(tab);
            };

            const closeModal = () => {
                authModal.classList.add('hidden');
                authModal.classList.remove('flex');
                authModal.setAttribute('aria-hidden', 'true');
                body.classList.remove('overflow-hidden');

                if (lastFocusedElement) {
                    lastFocusedElement.focus();
                }

                lastFocusedElement = null;
            };

            window.openAuthModal = openModal;
            window.closeAuthModal = closeModal;

            loginButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    openModal('login');
                });
            });

            registerButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    openModal('register');
                });
            });

            tabButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    const targetTab = button.dataset.authTab || 'login';
                    setActiveTab(targetTab);
                    focusFirstField(targetTab);
                });
            });

            switchLinks.forEach((link) => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    const targetTab = link.dataset.authSwitch || 'login';
                    openModal(targetTab);
                });
            });

            closeButton?.addEventListener('click', (event) => {
                event.preventDefault();
                closeModal();
            });

            authModal.addEventListener('click', (event) => {
                if (event.target === authModal || event.target.classList.contains('auth-modal-backdrop')) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !authModal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            const shouldOpen = authModal.dataset.shouldOpen === 'true';
            setActiveTab(currentTab);

            if (shouldOpen) {
                openModal(authModal.dataset.defaultTab || currentTab);
            }
        });
    </script>
</body>
</html>
