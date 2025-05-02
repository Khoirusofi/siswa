<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600&display=swap" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-[poppins]">
    <div class="min-h-screen bg-gray-100 md:pl-64">

        <header class="flex items-center h-20 md:h-auto" x-data="{ open: false }">
            <nav class="relative flex items-center w-full px-4">
                <div class="inline-flex items-center justify-center w-full md:hidden">
                    <a href="#" @click="open = true" @click.away="open = false" class="absolute left-0 pl-2">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path d="M3 7H21M3 12H21M3 17H21" stroke="#292D32" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>
                    </a>
                    @php
                        $user = auth()->user();
                        $dashboardRoute = $user->hasRole('student')
                            ? route('student.dashboard')
                            : route('admin.dashboard');
                    @endphp
                    <div class="flex items-center gap-x-2">
                        <x-heroicon-o-trophy class="w-8 h-8 text-blue-700" />
                        <a href="{{ $dashboardRoute }}"
                            class="text-2xl font-bold text-blue-950">{{ config('app.name', '') }}</a>
                    </div>
                    {{-- <a href="{{ $dashboardRoute }}">
                        <h2 class="text-2xl font-semibold">{{ config('app.name', 'Laravel') }}</h2>
                    </a> --}}
                </div>

                @include('layouts.sidebar')

            </nav>
        </header>

        @foreach (['success' => 'Berhasil', 'error' => 'Terjadi Kesalahan'] as $type => $label)
            @if (session($type))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                    class="fixed top-4 right-4 z-50 max-w-xs w-full
                {{ $type == 'success' ? 'bg-green-100 border border-green-300 text-green-800' : 'bg-red-100 border border-red-300 text-red-800' }}
                text-sm rounded-lg p-4 shadow-lg"
                    role="alert">
                    <strong class="font-semibold">{{ $label }}!</strong>
                    <span class="block mt-1">{{ session($type) }}</span>
                </div>
            @endif
        @endforeach

        @isset($header)
            <header class="bg-gray-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-4 lg:px-4">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="container min-h-[200px] w-full mx-auto">
            {{ $slot }}
        </main>

    </div>
</body>

</html>
