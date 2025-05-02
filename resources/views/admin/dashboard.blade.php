<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="text-sm font-semibold text-gray-600">
                    <span class="text-gray-500">Dashboard</span>
                </nav>
                <h2 class="text-2xl font-semibold text-gray-800 mt-1">Dashboard</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">
            <div>
                <h3 class="text-xl font-semibold text-blue-950 mb-3">
                    Statistik
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-x-7 gap-y-7">
                    <div class="item-stat bg-white rounded-2xl p-5">
                        <div class="flex flex-row mb-7 justify-between">
                            <div class="bg-violet-700 rounded-full w-fit p-3">
                                <x-heroicon-s-users class="h-6 w-6 text-white" />
                            </div>
                            <div class="flex flex-row gap-x-1 font-semibold items-center text-sm text-green-600 ">
                                <x-heroicon-s-arrow-trending-up class="h-6 w-6" /> 124%
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">
                            Total Siswa
                        </p>
                        <h3 class="text-2xl font-bold">
                            {{ $totalStudents }} Siswa
                        </h3>
                    </div>

                    <div class="item-stat bg-white rounded-2xl p-5">
                        <div class="flex flex-row mb-7 justify-between">
                            <div class="bg-blue-700 rounded-full w-fit p-3">
                                <x-heroicon-s-user-group class="h-6 w-6 text-white" />
                            </div>
                            <div class="flex flex-row gap-x-1 font-semibold items-center text-sm text-green-600">
                                <x-heroicon-s-arrow-trending-up class="h-6 w-6" /> 8%
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">
                            Total Guru
                        </p>
                        <h3 class="text-2xl font-bold">
                            {{ $totalTeachers }} Guru
                        </h3>
                    </div>

                    <div class="item-stat bg-white rounded-2xl p-5">
                        <div class="flex flex-row mb-7 justify-between">
                            <div class="bg-orange-500 rounded-full w-fit p-3">
                                <x-heroicon-s-book-open class="h-6 w-6 text-white" />
                            </div>
                            <div class="flex flex-row gap-x-1 font-semibold items-center text-sm text-green-600">
                                <x-heroicon-s-arrow-trending-up class="h-6 w-6" /> 14%
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">
                            Total Mapel
                        </p>
                        <h3 class="text-2xl text-blue-950 font-bold">
                            {{ $totalSubjects }} Mapel
                        </h3>
                    </div>

                    <div class="item-stat bg-white rounded-2xl p-5">
                        <div class="flex flex-row mb-7 justify-between">
                            <div class="bg-cyan-700 rounded-full w-fit p-3">
                                <x-heroicon-s-home class="h-6 w-6 text-white" />
                            </div>
                            <div class="flex flex-row gap-x-1 font-semibold items-center text-sm text-green-600">
                                <x-heroicon-s-arrow-trending-up class="h-6 w-6" /> 63%
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">
                            Total Kelas
                        </p>
                        <h3 class="text-2xl text-blue-950 font-bold">
                            {{ $totalRooms }} Kelas
                        </h3>
                    </div>
                </div>
            </div>

            <div>
                <div class="grid grid-cols-1 gap-x-7 gap-y-7  pt-4 pb-10">
                    <div class="flex flex-col gap-y-3">
                        <h3 class="text-xl font-semibold text-blue-950">
                            Siswa Terbaik
                        </h3>
                        <div class="bg-white rounded-2xl p-6">
                            <div class="relative h-[355px] w-full bg-white rounded-xl py-4">
                                <canvas id="studentScoreChart" class="w-full h-full"></canvas>
                            </div>
                            <table class="w-full mt-4">
                                <tbody class="flex flex-col gap-y-6">
                                    @foreach ($topStudents as $index => $item)
                                        <tr
                                            class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-y-4 lg:gap-y-0 border-b pb-4">
                                            <td class="flex items-center gap-x-4">
                                                <x-heroicon-o-user-circle class="h-10 w-10 text-blue-600" />
                                                <div>
                                                    <h3 class="text-blue-950 font-semibold text-sm">
                                                        {{ $item['student']->user->name }}
                                                    </h3>
                                                    <p class="text-sm text-gray-500">{{ $item['student']->nisn }}</p>
                                                </div>
                                            </td>

                                            <td class="flex flex-col items-start text-sm text-gray-700 space-y-1">
                                                <div class="flex items-center gap-2">
                                                    <x-heroicon-o-building-office class="h-4 w-4 text-blue-500" />
                                                    <p class="font-medium">
                                                        {{ $item['enrollment']->room->name }} -
                                                        {{ $item['enrollment']->room->major->name }}
                                                    </p>
                                                </div>
                                                <div class="flex items-center gap-2 text-gray-500">
                                                    <x-heroicon-o-calendar class="h-4 w-4 text-gray-400" />
                                                    <p>{{ $item['enrollment']->year->name }}</p>
                                                </div>
                                            </td>

                                            <td class="text-sm text-gray-600 space-y-2">
                                                <div class="flex items-center gap-2">
                                                    <x-heroicon-o-book-open class="h-4 w-4 text-blue-500" />
                                                    <p class="min-w-[70px]">Mapel</p>
                                                    <span
                                                        class="text-blue-950 font-semibold">{{ $item['grades'] }}</span>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <x-heroicon-o-heart class="h-4 w-4 text-pink-500" />
                                                    <p class="min-w-[70px]">Akhlak</p>
                                                    <span
                                                        class="text-blue-950 font-semibold">{{ $item['character'] }}</span>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <x-heroicon-o-trophy class="h-4 w-4 text-yellow-500" />
                                                    <p class="min-w-[70px]">Prestasi</p>
                                                    <span
                                                        class="text-blue-950 font-semibold">{{ $item['achievement'] }}</span>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <x-heroicon-o-calendar-days class="h-4 w-4 text-blue-500" />
                                                    <p class="min-w-[70px]">Absen</p>
                                                    <span
                                                        class="text-blue-950 font-semibold">{{ $item['attendance'] }}</span>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <x-heroicon-o-star class="h-4 w-4 text-green-500" />
                                                    <p class="min-w-[70px]">Eskul</p>
                                                    <span
                                                        class="text-blue-950 font-semibold">{{ $item['extracurricular'] }}</span>
                                                </div>
                                            </td>

                                            <td class="text-right w-full lg:w-auto">
                                                <div class="flex items-center justify-end gap-2">
                                                    <x-heroicon-o-chart-bar class="h-5 w-5 text-blue-600" />
                                                    <p class="text-blue-950 font-semibold text-lg">
                                                        {{ $item['score'] }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const ctx = document.getElementById('studentScoreChart').getContext('2d');

            const studentNames = @json(collect($topStudents)->pluck('student.user.name'));
            const studentScores = @json(collect($topStudents)->pluck('score'));

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: studentNames,
                    datasets: [{
                        label: 'Nilai Akhir',
                        data: studentScores,
                        backgroundColor: '#2563eb',
                        borderRadius: 6,
                        barThickness: 28
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: 10
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#4B5563',
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: '#E5E7EB'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#4B5563',
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#111827',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 12
                            },
                            padding: 10
                        }
                    }
                }
            });
        </script>
</x-app-layout>
