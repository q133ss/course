<div
    x-data="{
        tab: 'login',
        init() {
            this.$watch(() => this.$root.modal.open, value => {
                if (value && this.$root.modal.type === 'login') {
                    this.focusField();
                }
            });
            this.$nextTick(() => this.focusField());
        },
        focusField() {
            this.$nextTick(() => {
                const selector = this.tab === 'login' ? '[data-login-email]' : '[data-register-name]';
                const element = this.$root.$refs.modalContent?.querySelector(selector);
                element?.focus();
            });
        }
    }"
    class="space-y-6"
>
    <div class="flex items-start justify-between gap-4">
        <div>
            <h2 id="login-modal-title" class="text-2xl font-semibold text-slate-900">Добро пожаловать!</h2>
            <p class="text-sm text-slate-600">Войдите в аккаунт или зарегистрируйтесь, чтобы получить доступ к курсам.</p>
        </div>
        <x-button variant="ghost" size="sm" class="shrink-0" @click="$root.closeModal()">
            <span class="sr-only">Закрыть</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </x-button>
    </div>

    <div>
        <div class="flex items-center rounded-full bg-slate-100 p-1 text-sm font-medium text-slate-500">
            <button
                type="button"
                class="flex-1 rounded-full px-4 py-2 transition"
                :class="tab === 'login' ? 'bg-white text-slate-900 shadow' : 'hover:text-slate-700'"
                @click="tab = 'login'; focusField();"
            >
                Вход
            </button>
            <button
                type="button"
                class="flex-1 rounded-full px-4 py-2 transition"
                :class="tab === 'register' ? 'bg-white text-slate-900 shadow' : 'hover:text-slate-700'"
                @click="tab = 'register'; focusField();"
            >
                Регистрация
            </button>
        </div>
    </div>

    <div>
        <div x-show="tab === 'login'" x-transition>
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div class="space-y-1">
                    <label for="login-email" class="text-sm font-medium text-slate-700">Email</label>
                    <input
                        id="login-email"
                        name="email"
                        type="email"
                        required
                        autocomplete="email"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200"
                        value="{{ old('email') }}"
                        data-login-email
                    >
                    @error('email')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="login-password" class="text-sm font-medium text-slate-700">Пароль</label>
                    <input
                        id="login-password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200"
                    >
                    @error('password')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        Запомнить меня
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-sky-600 hover:text-sky-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500">
                        Забыли пароль?
                    </a>
                </div>

                <x-button variant="primary" size="lg" type="submit">
                    Войти
                </x-button>
            </form>
        </div>

        <div x-show="tab === 'register'" x-transition x-cloak>
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div class="space-y-1">
                    <label for="register-name" class="text-sm font-medium text-slate-700">Имя</label>
                    <input
                        id="register-name"
                        name="name"
                        type="text"
                        required
                        autocomplete="name"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200"
                        value="{{ old('name') }}"
                        data-register-name
                    >
                    @error('name')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="register-email" class="text-sm font-medium text-slate-700">Email</label>
                    <input
                        id="register-email"
                        name="email"
                        type="email"
                        required
                        autocomplete="email"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200"
                        value="{{ old('email') }}"
                    >
                    @error('email')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="register-password" class="text-sm font-medium text-slate-700">Пароль</label>
                    <input
                        id="register-password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200"
                    >
                    @error('password')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="register-password_confirmation" class="text-sm font-medium text-slate-700">Подтверждение пароля</label>
                    <input
                        id="register-password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200"
                    >
                </div>

                <x-button variant="primary" size="lg" type="submit">
                    Создать аккаунт
                </x-button>
            </form>
        </div>
    </div>
</div>
