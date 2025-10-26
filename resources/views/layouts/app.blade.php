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
                <nav class="hidden md:flex items-center gap-1 text-sm flex-wrap justify-end" data-desktop-nav>
                    @foreach ($navItems as $item)
                        <a href="{{ $item['href'] }}"
                           @if ($item['label'] === 'Мои курсы')
                               @guest
                                   data-requires-auth="login"
                               @endguest
                           @endif
                           class="px-3 py-2 rounded-full text-sm font-medium transition-colors {{ $item['active'] ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
                <div class="hidden md:flex items-center gap-3" data-desktop-actions>
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
                <button type="button" class="md:hidden inline-flex items-center justify-center h-10 w-10 rounded-full border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500" data-mobile-menu-toggle aria-expanded="false" aria-controls="mobile-menu">
                    <span class="sr-only">Открыть меню</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <div id="mobile-menu" class="md:hidden hidden border-b border-gray-100 bg-white shadow-sm" data-mobile-menu-panel>
        <div class="px-4 py-4 space-y-4">
            <nav class="flex flex-col gap-2 text-sm">
                @foreach ($navItems as $item)
                    <a href="{{ $item['href'] }}"
                       @if ($item['label'] === 'Мои курсы')
                           @guest
                               data-requires-auth="login"
                           @endguest
                       @endif
                       class="px-4 py-2 rounded-xl font-medium transition-colors {{ $item['active'] ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
            <div class="border-t border-gray-100 pt-4">
                @guest
                    <div class="flex flex-col gap-2">
                        <button type="button" data-register-trigger class="w-full px-4 py-2 rounded-xl border border-blue-600 text-blue-600 text-sm font-semibold hover:bg-blue-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                            Регистрация
                        </button>
                        <button type="button" data-login-trigger class="w-full px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                            Войти
                        </button>
                    </div>
                @endguest
                @auth
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">Добро пожаловать!</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-xl bg-gray-100 text-sm font-semibold text-gray-700 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-gray-400">
                                Выйти
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>

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
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-auto p-8 ring-1 ring-gray-100" role="dialog" aria-modal="true" aria-labelledby="auth-modal-heading">
            <h2 id="auth-modal-heading" class="sr-only">Вход и регистрация</h2>
            <button type="button" id="auth-modal-close" class="absolute top-4 right-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:text-gray-700 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500" aria-label="Закрыть">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="flex justify-center mb-8 bg-gray-100 rounded-full p-1" role="tablist">
                <button
                    type="button"
                    data-auth-tab="login"
                    id="auth-tab-login"
                    class="px-5 py-2 text-sm font-semibold rounded-full flex-1 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500"
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
                <h2 class="text-2xl font-semibold text-gray-900">Добро пожаловать обратно!</h2>
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
                        <label for="login-email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input id="login-email" name="email" type="email" value="{{ $loginOldEmail }}" required autocomplete="email" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 shadow-inner focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:outline-none caret-blue-600" placeholder="you@example.com">
                        @error('email', 'login')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="login-password" class="block text-sm font-semibold text-gray-700 mb-1">Пароль</label>
                        <input id="login-password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 shadow-inner focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:outline-none caret-blue-600" placeholder="••••••••">
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
                    <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 text-white font-semibold shadow-lg shadow-blue-500/30 hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500 transition">Войти</button>
                </form>
                <p class="text-sm text-gray-600 text-center">
                    Нет аккаунта?
                    <a href="#" data-auth-switch="register" class="font-semibold text-blue-600 hover:text-blue-700">Зарегистрируйтесь</a>
                </p>
            </div>

            <div data-auth-panel="register" id="auth-panel-register" role="tabpanel" aria-labelledby="auth-tab-register" class="space-y-4 hidden">
                <h2 class="text-2xl font-semibold text-gray-900">Создайте аккаунт</h2>
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
                        <label for="register-name" class="block text-sm font-semibold text-gray-700 mb-1">Имя</label>
                        <input id="register-name" name="name" type="text" value="{{ $registerOldName }}" required autocomplete="name" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 shadow-inner focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:outline-none caret-blue-600" placeholder="Иван Иванов">
                        @error('name', 'register')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="register-email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input id="register-email" name="email" type="email" value="{{ $registerOldEmail }}" required autocomplete="email" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 shadow-inner focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:outline-none caret-blue-600" placeholder="you@example.com">
                        @error('email', 'register')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="register-password" class="block text-sm font-semibold text-gray-700 mb-1">Пароль</label>
                        <input id="register-password" name="password" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 shadow-inner focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:outline-none caret-blue-600" placeholder="Не менее 8 символов">
                        @error('password', 'register')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="register-password-confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Подтверждение пароля</label>
                        <input id="register-password-confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 shadow-inner focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:outline-none caret-blue-600" placeholder="Повторите пароль">
                        @error('password_confirmation', 'register')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 text-white font-semibold shadow-lg shadow-blue-500/30 hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500 transition">Зарегистрироваться</button>
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
            const authRequiredLinks = document.querySelectorAll('[data-requires-auth]');
            const mobileToggleButton = document.querySelector('[data-mobile-menu-toggle]');
            const mobileMenuPanel = document.querySelector('[data-mobile-menu-panel]');

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

            const handleAuthRequiredClick = (event, targetTab = 'login') => {
                event.preventDefault();
                closeMobileMenu();
                openModal(targetTab);
            };

            loginButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    handleAuthRequiredClick(event, 'login');
                });
            });

            registerButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    handleAuthRequiredClick(event, 'register');
                });
            });

            authRequiredLinks.forEach((link) => {
                const targetTab = link.dataset.requiresAuth || 'login';
                link.addEventListener('click', (event) => handleAuthRequiredClick(event, targetTab));
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

            const closeMobileMenu = () => {
                if (!mobileMenuPanel || !mobileToggleButton) {
                    return;
                }

                mobileMenuPanel.classList.add('hidden');
                mobileToggleButton.setAttribute('aria-expanded', 'false');
            };

            const toggleMobileMenu = () => {
                if (!mobileMenuPanel || !mobileToggleButton) {
                    return;
                }

                const isOpen = mobileMenuPanel.classList.toggle('hidden');
                mobileToggleButton.setAttribute('aria-expanded', (!isOpen).toString());
            };

            mobileToggleButton?.addEventListener('click', (event) => {
                event.preventDefault();
                toggleMobileMenu();
            });

            mobileMenuPanel?.querySelectorAll('a, button').forEach((interactiveElement) => {
                interactiveElement.addEventListener('click', () => closeMobileMenu());
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
