@props([
    'href' => '#',
    'variant' => 'primary',
])

@php
    $baseClasses =
        'inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-lg shadow-sm transition-all';
    $variants = [
        'primary' => 'text-white bg-green-600 border border-transparent hover:bg-green-700',
        'secondary' => 'text-gray-700 bg-gray-100 border border-gray-300 hover:bg-gray-200',
        'danger' => 'text-white bg-red-600 border border-transparent hover:bg-red-700',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
