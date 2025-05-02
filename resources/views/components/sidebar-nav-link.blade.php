@props(['active'])

@php
    $classes = $active
        ? 'flex items-center px-4 py-2 font-medium text-blue-900 bg-gray-100 rounded-md'
        : 'flex items-center px-4 py-2 font-medium text-gray-600 hover:bg-gray-200 rounded-md';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
