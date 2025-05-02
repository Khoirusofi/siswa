@props([
    'href' => null,
    'type' => 'submit',
    'variant' => 'primary',
    'icon' => null,
    'title' => null,
])

@php
    $baseClass = 'text-xs font-semibold flex items-center gap-1 transition';

    $variants = [
        'primary' => 'text-blue-500 hover:text-blue-600',
        'secondary' => 'text-gray-500 hover:text-gray-600',
        'danger' => 'text-red-500 hover:text-red-600',
        'warning' => 'text-yellow-500 hover:text-yellow-600',
    ];

    $classes = $baseClass . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $classes }}" title="{{ $title }}" {{ $attributes }}>
        @if ($icon)
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-4 h-4" />
        @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" class="{{ $classes }}" title="{{ $title }}" {{ $attributes }}>
        @if ($icon)
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-4 h-4" />
        @endif
        <span>{{ $slot }}</span>
    </button>
@endif
