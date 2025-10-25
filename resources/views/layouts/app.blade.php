<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:top-4 focus:left-4 focus:px-4 focus:py-2 focus:rounded focus:bg-white focus:text-slate-900">
        Перейти к основному контенту
    </a>

    <div class="min-h-screen flex flex-col">
        <main id="main" class="flex-1 w-full">
            <div class="max-w-7xl mx-auto w-full px-4 md:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
        <footer class="mt-16 border-t border-slate-200 bg-white/70 backdrop-blur py-6 text-center text-sm text-slate-500">
            © {{ now()->year }} {{ config('app.name', 'EduHub') }}. Все права защищены.
        </footer>
    </div>
</body>
</html>
