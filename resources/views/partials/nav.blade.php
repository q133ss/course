@php
    $navLinks = [
        ['label' => 'Главная', 'route' => route('home'), 'active' => request()->routeIs('home')],
        ['label' => 'Мои курсы', 'route' => route('courses.my'), 'active' => request()->routeIs('courses.my')],
    ];
    $isAuthenticated = auth()->check();
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur shadow-md">
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8">
        <div class="flex items-center justify-between py-4">
            <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight text-slate-900">
                EduHub
            </a>
            <div class="hidden md:flex items-center gap-6">
                @foreach ($navLinks as $link)
                    <a
                        href="{{ $link['route'] }}"
                        @class([
                            'text-sm font-medium transition hover:text-slate-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500',
                            'text-slate-500' => ! $link['active'],
                            'text-slate-900 underline underline-offset-8 decoration-2 font-semibold' => $link['active'],
                        ])
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach

                @if ($isAuthenticated)
                    <a
                        href="{{ route('profile') }}"
                        @class([
                            'text-sm font-medium transition hover:text-slate-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500',
                            'text-slate-500' => ! request()->routeIs('profile'),
                            'text-slate-900 underline underline-offset-8 decoration-2 font-semibold' => request()->routeIs('profile'),
                        ])
                    >
                        Профиль
                    </a>
                @else
                    <x-button variant="primary" size="sm" @click.prevent="$dispatch('open-login-modal')">
                        Войти
                    </x-button>
                @endif
            </div>

            <button
                type="button"
                class="md:hidden inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-2.5 py-2 text-slate-700 shadow-sm transition hover:bg-slate-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500"
                @click="open = !open"
                :aria-expanded="open"
                aria-controls="mobile-nav"
            >
                <span class="sr-only">Открыть меню</span>
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div
            x-show="open"
            x-transition
            id="mobile-nav"
            class="md:hidden pb-4 space-y-2"
        >
            @foreach ($navLinks as $link)
                <a
                    href="{{ $link['route'] }}"
                    @class([
                        'block rounded-lg px-4 py-2 text-sm font-medium transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500',
                        'bg-slate-100 text-slate-900' => $link['active'],
                        'text-slate-600 hover:bg-slate-50' => ! $link['active'],
                    ])
                    @click="open = false"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach

            @if ($isAuthenticated)
                <a
                    href="{{ route('profile') }}"
                    @class([
                        'block rounded-lg px-4 py-2 text-sm font-medium transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500',
                        'bg-slate-100 text-slate-900' => request()->routeIs('profile'),
                        'text-slate-600 hover:bg-slate-50' => ! request()->routeIs('profile'),
                    ])
                    @click="open = false"
                >
                    Профиль
                </a>
            @else
                <x-button variant="primary" size="md" class="w-full" @click.prevent="$dispatch('open-login-modal'); open = false;">
                    Войти
                </x-button>
            @endif
        </div>
    </div>
</nav>
