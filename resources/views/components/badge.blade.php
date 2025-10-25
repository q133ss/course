@props(['type' => 'paid'])

@php
    $baseClasses = 'inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold uppercase tracking-wide';
    $variants = [
        'free' => 'bg-emerald-100 text-emerald-700',
        'paid' => 'bg-slate-200 text-slate-700',
    ];
    $classes = ($variants[$type] ?? $variants['paid']) . ' ' . $baseClasses;
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
