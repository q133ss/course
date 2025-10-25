@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500 rounded-full';

    $variants = [
        'primary' => 'bg-sky-600 text-white shadow-sm hover:bg-sky-700 disabled:bg-sky-300 disabled:text-white',
        'secondary' => 'border border-sky-600 text-sky-700 hover:bg-sky-50 disabled:border-slate-300 disabled:text-slate-400',
        'ghost' => 'text-sky-700 hover:text-sky-900 hover:bg-sky-50 disabled:text-slate-400',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-3 text-base',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
    $isDisabled = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($isDisabled) aria-disabled="true" tabindex="-1" @endif
    >
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($isDisabled) disabled @endif
    >
        {{ $slot }}
    </button>
@endif
