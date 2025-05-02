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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-[poppins] text-gray-900">
    <div class="min-h-screen bg-gray-100">


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


        <div class="container min-h-[200px] w-full mx-auto">
            <header class="bg-gray-100 w-full z-30 py-4">
                <div class="flex items-center justify-between px-6 py-4 md:px-36">
                    <div class="flex items-center gap-x-2">
                        <x-heroicon-o-trophy class="w-8 h-8 text-blue-700" />
                        <a href="/" class="text-2xl font-bold text-blue-950">{{ config('app.name', '') }}</a>
                    </div>

                    <button id="btn-dropdown"
                        class="lg:hidden p-2 rounded-full border border-gray-300 focus:outline-none"
                        aria-expanded="false" aria-controls="dropdown-menu">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path d="M3 7H21M3 12H21M3 17H21" stroke="#292D32" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>
                    </button>

                    <nav class="hidden lg:flex gap-x-7">
                        {{-- <a href="#" class="text-blue-950 hover:text-blue-700 hover:font-semibold transition">Start
                            Here</a>
                        <a href="#"
                            class="text-blue-950 hover:text-blue-700 hover:font-semibold transition">Strategy</a>
                        <a href="#"
                            class="text-blue-950 hover:text-blue-700 hover:font-semibold transition">Showcase</a> --}}
                    </nav>

                    <div class="hidden lg:flex gap-x-3">
                        @if (Route::has('login'))
                            <nav class="flex items-center justify-end gap-4">
                                @auth
                                    @php
                                        $user = auth()->user();
                                        $dashboardRoute = $user->hasRole('student')
                                            ? route('student.dashboard')
                                            : route('admin.dashboard');
                                    @endphp
                                    <a href="{{ $dashboardRoute }}"
                                        class="inline-block mb-4 w-[140px] bg-blue-700 text-white text-base font-semibold text-center py-3 rounded-full hover:shadow-md hover:shadow-blue-700 transition duration-300">
                                        Dashboard
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <button type="submit"
                                            class="inline-block mb-4 w-[140px] bg-red-500 text-white text-base font-semibold text-center py-3 rounded-full hover:shadow-md hover:shadow-red-700 transition duration-300">
                                            Keluar
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="inline-block mb-4 w-[120px] bg-blue-700 text-white text-base font-semibold text-center py-3 rounded-full hover:shadow-md hover:shadow-blue-700 transition duration-300">
                                        Masuk
                                    </a>
                                @endauth
                            </nav>
                        @endif
                    </div>
                </div>

                <div id="dropdown-menu"
                    class="hidden lg:hidden bg-gray-100 shadow-lg p-6 space-y-6 absolute w-full top-28 left-0 transition duration-300 z-20">
                    <nav class="flex flex-col space-y-4">
                        {{-- <a href="#" class="text-blue-950 hover:text-blue-700">Start Here</a>
                        <a href="#" class="text-blue-950 hover:text-blue-700">Strategy</a>
                        <a href="#" class="text-blue-950 hover:text-blue-700">Showcase</a> --}}
                    </nav>
                    <div class="flex flex-col gap-3">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ $dashboardRoute }}"
                                    class="inline-block mb-4 w-[140px] bg-blue-700 text-white text-base font-semibold text-center py-3 rounded-full hover:shadow-md hover:shadow-blue-700 transition duration-300">
                                    Dashboard
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <button type="submit"
                                        class="inline-block mb-4 w-[140px] bg-red-500 text-white text-base font-semibold text-center py-3 rounded-full hover:shadow-md hover:shadow-red-700 transition duration-300">
                                        Keluar
                                    </button>
                                </form>
                            @else
                                <a href="{{ url('login') }}"
                                    class="inline-block mb-4 w-[120px] bg-blue-700 text-white text-base font-semibold text-center py-3 rounded-full hover:shadow-md hover:shadow-blue-700 transition duration-300">
                                    Masuk
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </header>
            {{ $slot }}
        </div>
        <footer class="bg-gray-100 text-blue-950 py-6 mt-10 px-4 md:px-8">
            <div class="max-w-[1200px] mx-auto flex flex-col md:flex-row items-center justify-center gap-4">
                <p class="text-sm text-center md:text-left">&copy; {{ date('Y') }}
                    {{ config('app.name', '') }}. All
                    rights reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnDropdown = document.getElementById('btn-dropdown');
            const dropdownMenu = document.getElementById('dropdown-menu');

            btnDropdown.addEventListener("click", function() {
                dropdownMenu.classList.toggle("hidden");
                const expanded = btnDropdown.getAttribute("aria-expanded") === "true" || false;
                btnDropdown.setAttribute("aria-expanded", !expanded);
            });

            document.addEventListener("click", function(event) {
                if (!btnDropdown.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add("hidden");
                    btnDropdown.setAttribute("aria-expanded", false);
                }
            });
        });
    </script>
</body>

</html>
