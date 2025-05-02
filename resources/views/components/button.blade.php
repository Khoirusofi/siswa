@props(['type' => 'button', 'variant' => 'primary', 'icon' => null])

@php
    $baseClass =
        'inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-lg shadow-sm transition-all';

    $variants = [
        'primary' => 'text-white bg-green-600 border border-transparent hover:bg-green-700',
        'secondary' => 'text-gray-700 bg-gray-100 border border-gray-300 hover:bg-gray-200',
        'warning' => 'text-white bg-yellow-600 border border-transparent hover:bg-yellow-700',
        'danger' => 'text-white bg-red-600 border border-transparent hover:bg-red-700',
    ];

    $classes = $baseClass . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon)
        {{-- Render ikon menggunakan Blade Components --}}
        @if ($icon === 'trash')
            <x-heroicon-o-trash class="w-4 h-4" />
        @elseif($icon === 'arrow-left')
            <x-heroicon-o-arrow-left class="w-4 h-4" />
        @elseif($icon === 'pencil')
            <x-heroicon-o-pencil class="w-4 h-4" />
        @elseif($icon === 'download')
            <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
        @endif
    @endif
    {{ $slot }}
</button>
