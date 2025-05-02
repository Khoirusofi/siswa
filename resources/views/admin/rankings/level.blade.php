<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>

                <nav class="text-sm font-semibold text-gray-600">
                    <span class="text-gray-500">Peringkat
                        @if ($selectedLevel && $selectedMajor && $selectedYear)
                            Tingkat Kelas {{ $selectedLevel->name }}
                            Jurusan {{ $selectedMajor->name }}
                            Tahun Ajaran {{ $selectedYear->name }}
                        @endif
                    </span>
                </nav>

                <h2 class="text-2xl font-semibold text-gray-800 mt-1">
                    Peringkat
                    @if ($selectedLevel && $selectedMajor && $selectedYear)
                        Tingkat Kelas {{ $selectedLevel->name }}<br>
                        Jurusan {{ $selectedMajor->name }}
                        Tahun Ajaran {{ $selectedYear->name }}
                    @endif
                </h2>
            </div>

            <div class="flex gap-2">
                <form method="GET" action="{{ route('admin.rankings.levelpdf') }}">
                    @if ($selectedLevel && $selectedMajor && $selectedYear)
                        <input type="hidden" name="level" value="{{ $selectedLevel->slug }}">
                        <input type="hidden" name="major" value="{{ $selectedMajor->slug }}">
                        <input type="hidden" name="year" value="{{ $selectedYear->slug }}">
                        <x-button type="submit" variant="primary" icon="download">
                            PDF
                        </x-button>
                    @else
                    @endif
                </form>

                <form method="GET" action="{{ route('admin.rankings.levelexcel') }}">
                    @if ($selectedLevel && $selectedMajor && $selectedYear)
                        <input type="hidden" name="level" value="{{ $selectedLevel->slug }}">
                        <input type="hidden" name="major" value="{{ $selectedMajor->slug }}">
                        <input type="hidden" name="year" value="{{ $selectedYear->slug }}">
                        <x-button type="submit" variant="primary" icon="download">
                            Excel
                        </x-button>
                    @else
                    @endif
                </form>
            </div>

        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">

            <div class="flex justify-end mb-4">
                <form action="{{ route('admin.rankings.level') }}" method="GET" id="filter-form"
                    class="flex gap-4 flex-wrap items-end text-end">

                    <select name="level"
                        class="border border-gray-300 px-8 py-2 rounded-md focus:ring-primary-500 focus:border-primary-500"
                        onchange="submitFilterForm()">
                        <option value="">Pilih Tingkat Kelas</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level->slug }}"
                                {{ request('level') == $level->slug ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="major"
                        class="border border-gray-300 px-[30px] py-2 rounded-md focus:ring-primary-500 focus:border-primary-500"
                        onchange="submitFilterForm()">
                        <option value="">Pilih Jurusan</option>
                        @foreach ($majors as $major)
                            <option value="{{ $major->slug }}"
                                {{ request('major') == $major->slug ? 'selected' : '' }}>
                                {{ $major->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="year"
                        class="border border-gray-300 px-8 py-2 rounded-md focus:ring-primary-500 focus:border-primary-500"
                        onchange="submitFilterForm()">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach ($years as $year)
                            <option value="{{ $year->slug }}"
                                {{ request('year') == $year->slug ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex justify-end mb-1 ">
                        @if (request('level') && request('major') && request('year'))
                            <x-button type="button" onclick="clearFilters()" variant="danger" icon="trash"
                                title="Hapus Filter">
                            </x-button>
                        @endif
                    </div>
                </form>
            </div>

            <script>
                function submitFilterForm() {
                    document.getElementById('filter-form').submit();
                }

                function clearFilters() {
                    const form = document.getElementById('filter-form');
                    form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
                    window.location.href = "{{ route('admin.rankings.level') }}";
                }
            </script>

            @if ($rankedStudents && count($rankedStudents))
                <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
                    <table class="min-w-[800px] w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">Peringkat</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">Nama</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">NISN</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">NIS</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">Kelas</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">Pelajaran</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">Akhlak</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">Prestasi</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">Absensi</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">Ekskul</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($rankedStudents as $index => $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ $rankedStudents->firstItem() + $index }}</td>
                                    <td class="px-4 py-3 text-gray-900 whitespace-nowrap">
                                        {{ $item['student']->user->name }}</td>
                                    <td class="px-4 py-3 text-gray-900 whitespace-nowrap">{{ $item['student']->nisn }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 whitespace-nowrap">{{ $item['student']->nis }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 whitespace-nowrap">
                                        {{ $item['enrollment']->room->name }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-900">{{ $item['grades'] }}</td>
                                    <td class="px-4 py-3 text-gray-900">{{ $item['character'] }}</td>
                                    <td class="px-4 py-3 text-gray-900">{{ $item['achievement'] }}</td>
                                    <td class="px-4 py-3 text-gray-900">{{ $item['attendance'] }}</td>
                                    <td class="px-4 py-3 text-gray-900">{{ $item['extracurricular'] }}</td>
                                    <td class="px-4 py-3 text-gray-900 font-semibold">{{ $item['score'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif ($selectedLevel && $selectedMajor && $selectedYear)
                <div class="mt-16 text-center text-gray-500">
                    <div class="flex justify-center mb-2">
                        <x-heroicon-o-information-circle class="w-10 h-10 text-gray-400" />
                    </div>
                    <p class="text-sm">
                        Tidak ada data ranking untuk kombinasi tingkat kelas, jurusan, dan tahun ajaran yang dipilih.
                    </p>
                </div>
            @endif

            <div class="mt-4">
                {{ $rankedStudents->links() }}
            </div>

            @if ($rankedStudents && count($rankedStudents))
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 mb-6 w-full max-w-7xl mx-auto">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 text-center">
                        Grafik Nilai Akhir Siswa -
                        {{ $selectedLevel->name ?? '' }} {{ $selectedMajor->name ?? '' }}
                        {{ $selectedYear->name ?? '' }}
                    </h3>
                    <div class="relative h-[300px] w-full">
                        <canvas id="rankingChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            @endif

            @if ($rankedStudents && count($rankedStudents))
                <script>
                    const ctx = document.getElementById('rankingChart').getContext('2d');

                    const studentNames = @json($rankedStudents->map(fn($item) => $item['student']->user->name));
                    const studentScores = @json($rankedStudents->map(fn($item) => $item['score']));

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: studentNames,
                            datasets: [{
                                label: 'Nilai Akhir',
                                data: studentScores,
                                backgroundColor: '#2563eb',
                                borderColor: 'rgb(96, 165, 250)',
                                borderRadius: 6,
                                tension: 0.2,
                                barThickness: 30
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
            @endif

        </div>
    </div>
</x-app-layout>
