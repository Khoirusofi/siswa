<div :class="{ '!translate-x-0': open }"
    class="fixed top-0 left-0 z-20 w-9/12 h-screen overflow-y-auto transition duration-300 ease-in-out transform -translate-x-full bg-white sm:w-64 md:translate-x-0">
    <div class="flex items-center h-20">
        <div class="inline-flex items-center justify-center w-full md:justify-center">
            <a href="#" @click="open = !open"
                class="absolute right-0 top-0 mr-1.5 mt-1.5 inline-flex p-1 items-center justify-center rounded-md hover:bg-blue-100 md:hidden">
            </a>
            @php
                $user = auth()->user();
                $dashboardRoute = $user->hasRole('student') ? route('student.dashboard') : route('admin.dashboard');
            @endphp
            <a href="{{ $dashboardRoute }}" class="text-2xl font-bold text-blue-950">{{ config('app.name', '') }}</a>
        </div>
    </div>

    @php
        $user = auth()->user();
        $menuGroups = [];

        if ($user->hasRole('admin')) {
            $menuGroups[] = [
                'title' => 'Akademik',
                'menus' => [
                    [
                        'route' => 'admin.years.index',
                        'label' => 'Tahun Ajaran',
                        'icon' => 'calendar',
                        'active' => request()->routeIs('admin.years.*'),
                    ],
                    [
                        'route' => 'admin.majors.index',
                        'label' => 'Jurusan',
                        'icon' => 'academic-cap',
                        'active' => request()->routeIs('admin.majors.*'),
                    ],
                    [
                        'route' => 'admin.levels.index',
                        'label' => 'Tingkat Kelas',
                        'icon' => 'academic-cap',
                        'active' => request()->routeIs('admin.levels.*'),
                    ],
                    [
                        'route' => 'admin.rooms.index',
                        'label' => 'Kelas',
                        'icon' => 'building-office-2',
                        'active' => request()->routeIs('admin.rooms.*'),
                    ],
                    [
                        'route' => 'admin.subjects.index',
                        'label' => 'Mata Pelajaran',
                        'icon' => 'book-open',
                        'active' => request()->routeIs('admin.subjects.*'),
                    ],
                ],
            ];
        }

        if ($user->hasRole(['admin', 'teacher'])) {
            $menuGroups[] = [
                'title' => 'Penilaian',
                'menus' => [
                    [
                        'route' => 'admin.grades.index',
                        'label' => 'Pelajaran',
                        'icon' => 'book-open',
                        'active' => request()->routeIs('admin.grades.*'),
                    ],
                    [
                        'route' => 'admin.characters.index',
                        'label' => 'Akhlak',
                        'icon' => 'heart',
                        'active' => request()->routeIs('admin.characters.*'),
                    ],
                    [
                        'route' => 'admin.achievements.index',
                        'label' => 'Prestasi',
                        'icon' => 'trophy',
                        'active' => request()->routeIs('admin.achievements.*'),
                    ],
                    [
                        'route' => 'admin.attendances.index',
                        'label' => 'Absen',
                        'icon' => 'calendar-days',
                        'active' => request()->routeIs('admin.attendances.*'),
                    ],
                    [
                        'route' => 'admin.extracurriculars.index',
                        'label' => 'Ekskul',
                        'icon' => 'star',
                        'active' => request()->routeIs('admin.extracurriculars.*'),
                    ],
                ],
            ];
        }

        if ($user->hasRole(['admin', 'teacher'])) {
            $menuGroups[] = [
                'title' => 'Peringkat',
                'menus' => [
                    [
                        'route' => 'admin.rankings.room',
                        'label' => 'Kelas',
                        'icon' => 'academic-cap',
                        'active' => request()->routeIs('admin.rankings.room'),
                    ],
                    [
                        'route' => 'admin.rankings.level',
                        'label' => 'Angkatan',
                        'icon' => 'academic-cap',
                        'active' => request()->routeIs('admin.rankings.level'),
                    ],
                ],
            ];
        }

        if ($user->hasRole('admin')) {
            $menuGroups[] = [
                'title' => 'Pengguna',
                'menus' => [
                    [
                        'route' => 'admin.teachers.index',
                        'label' => 'Guru',
                        'icon' => 'user-group',
                        'active' => request()->routeIs('admin.teachers.*'),
                    ],
                    [
                        'route' => 'admin.students.index',
                        'label' => 'Siswa',
                        'icon' => 'user-group',
                        'active' => request()->routeIs('admin.students.*'),
                    ],
                ],
            ];
        }

        if ($user->hasRole('student')) {
            $menuGroups[] = [
                'title' => 'Akademik',
                'menus' => [
                    [
                        'route' => 'student.history',
                        'label' => 'Riwayat Nilai',
                        'icon' => 'book-open',
                        'active' => request()->routeIs('student.history'),
                    ],
                ],
            ];
        }
    @endphp

    <div class="flex flex-col mb-0 pl-2 pr-2 space-y-4">
        @role('student')
            <x-sidebar-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')">
                <x-heroicon-o-home class="w-5 h-5 mr-2" />
                Dashboard
            </x-sidebar-nav-link>
        @else
            <x-sidebar-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                <x-heroicon-o-home class="w-5 h-5 mr-2" />
                Dashboard
            </x-sidebar-nav-link>
        @endrole

        @foreach ($menuGroups as $group)
            @php
                $dropdownActive = collect($group['menus'])->contains(fn($menu) => $menu['active']);
            @endphp

            <div x-data="{ open: {{ $dropdownActive ? 'true' : 'false' }} }">
                <button @click.stop="open = !open"
                    class="flex items-center justify-between w-full px-4 py-2 text-left text-sm font-medium rounded-md transition"
                    :class="open ? 'text-gray-600 ' : 'text-gray-600'">
                    <span class="flex items-center gap-2">
                        {{ $group['title'] }}
                    </span>
                    <x-heroicon-o-chevron-down class="w-4 h-4 transform transition-all duration-200"
                        x-bind:class="open ? 'rotate-180' : ''" />
                </button>

                <div x-show="open" class="mt-2 space-y-1">
                    @foreach ($group['menus'] as $menu)
                        <x-sidebar-nav-link :href="route($menu['route'])" :active="$menu['active']">
                            <x-dynamic-component :component="'heroicon-o-' . $menu['icon']" :class="$menu['active'] ? 'text-blue-900 w-5 h-5 mr-2' : 'text-gray-600 w-5 h-5 mr-2'" />
                            {{ $menu['label'] }}
                        </x-sidebar-nav-link>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-sidebar-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    <x-heroicon-o-user class="w-5 h-5 mr-2" />
                    {{ __('Saya') }}
                </x-sidebar-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-sidebar-nav-link :href="route('logout')" :active="request()->routeIs('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                        <x-heroicon-o-arrow-right-start-on-rectangle class="w-5 h-5 mr-2" />
                        {{ __('Keluar') }}
                    </x-sidebar-nav-link>
                </form>
            </div>
        </div>
    </div>

</div>
<div :class="{ '!inline': open }"
    class="z-10 fixed top-0 left-0 w-screen h-screen bg-gray-900 bg-opacity-30 hidden md:!hidden transition ease-in-out duration-300">
</div>
