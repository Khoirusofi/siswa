<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="text-sm font-semibold text-gray-600">
                    <span class="text-gray-500">Riwayat Nilai</span>
                </nav>
                <h2 class="text-2xl font-semibold text-gray-800 mt-1">
                    Riwayat Nilai
                    @if ($selectedRoom && $selectedYear)
                        - Kelas {{ $selectedRoom->name }}
                        <br> Tahun Ajaran {{ $selectedYear->name }}
                    @elseif ($selectedRoom)
                        - {{ $selectedRoom->name }}
                    @elseif ($selectedYear)
                        - Tahun Ajaran {{ $selectedYear->name }}
                    @endif
                </h2>
            </div>

            @if ($selectedRoom && $selectedYear)
                <form method="GET" action="{{ route('student.historypdf') }}">
                    <input type="hidden" name="history" value="{{ $selectedRoom->slug }}%{{ $selectedYear->slug }}">
                    <x-button type="submit" variant="primary" icon="download">
                        PDF
                    </x-button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">
            <div class="flex justify-end mb-4">
                <form action="{{ route('student.history') }}" method="GET" id="filter-form"
                    class="flex gap-4 flex-wrap items-end text-end">
                    <div>
                        <select name="history" class="border border-gray-300 px-16 py-2 rounded-md"
                            onchange="submitFilterForm()">
                            <option value="">Pilih Kelas</option>
                            @foreach ($histories as $history)
                                <option value="{{ $history['slug'] }}"
                                    {{ request('history') == $history['slug'] ? 'selected' : '' }}>
                                    {{ $history['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <script>
                function submitFilterForm() {
                    document.getElementById('filter-form').submit();
                }
            </script>


            @if (($grades && count($grades)) || $components)
                <div class="overflow-x-auto rounded-xl shadow ring-1 ring-gray-200">
                    <table class="min-w-full text-sm divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-gray-600 font-semibold w-10">No</th>
                                <th class="px-6 py-3 text-left text-gray-600 font-semibold">Komponen</th>
                                <th class="px-6 py-3 text-left text-gray-600 font-semibold">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            {{-- Nilai Pelajaran --}}
                            @if ($grades && count($grades))
                                <tr class="bg-blue-50 text-blue-800 font-semibold">
                                    <td class="px-6 py-3 text-gray-900"></td>
                                    <td colspan="3" class="px-6 py-3">
                                        <div class="flex items-center gap-2">
                                            Nilai Pelajaran
                                        </div>
                                    </td>
                                </tr>
                                @php $i = 1; @endphp
                                @foreach ($grades as $grade)
                                    <tr class="hover:bg-blue-50 transition">
                                        <td class="px-6 py-3 text-gray-900">{{ $i++ }}</td>
                                        <td class="px-6 py-3 text-gray-900">{{ $grade->subject->name }}</td>
                                        <td class="px-6 py-3 text-gray-900">{{ $grade->score }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- Komponen Penilaian --}}
                            @if ($components)
                                <tr class="bg-blue-50 text-blue-800 font-semibold">
                                    <td class="px-6 py-3 text-gray-900"></td>
                                    <td colspan="3" class="px-6 py-3">
                                        <div class="flex items-center gap-2">
                                            Penilaian
                                        </div>
                                    </td>
                                </tr>
                                @php $j = 1; @endphp
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-3 text-gray-900">{{ $j++ }}</td>
                                    <td class="px-6 py-3 text-gray-900">Akhlak</td>
                                    <td class="px-6 py-3 text-gray-900">{{ $components->character }}</td>
                                </tr>
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-3 text-gray-900">{{ $j++ }}</td>
                                    <td class="px-6 py-3 text-gray-900">Prestasi</td>
                                    <td class="px-6 py-3 text-gray-900">{{ $components->achievement }}</td>
                                </tr>
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-3 text-gray-900">{{ $j++ }}</td>
                                    <td class="px-6 py-3 text-gray-900">Absen</td>
                                    <td class="px-6 py-3 text-gray-900">{{ $components->attendance }}</td>
                                </tr>
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-3 text-gray-900">{{ $j++ }}</td>
                                    <td class="px-6 py-3 text-gray-900">Ekskul</td>
                                    <td class="px-6 py-3 text-gray-900">{{ $components->extracurricular }}</td>
                                </tr>
                                <tr class="bg-blue-50 text-blue-800 font-semibold">
                                    <td class="px-6 py-3 text-gray-900"></td>
                                    <td colspan="3" class="px-6 py-3">
                                        <div class="flex items-center gap-2">
                                            Total dan Ranking
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-3 text-gray-900"></td>
                                    <td class="px-6 py-3 text-gray-900">Total Nilai</td>
                                    <td class="px-6 py-3 text-gray-900 font-semibold">{{ $totalScore }}</td>
                                </tr>
                                @if ($ranking && $studentCount)
                                    <tr class="hover:bg-blue-50 transition">
                                        <td class="px-6 py-3 text-gray-900"></td>
                                        <td class="px-6 py-3 text-gray-900">Ranking</td>
                                        <td class="px-6 py-3 text-gray-900 font-semibold">{{ $ranking }} dari
                                            {{ $studentCount }} Siswa</td>
                                    </tr>
                                @endif
                            @endif
                        </tbody>
                    </table>
                </div>
            @elseif($selectedRoom && $selectedYear)
                <div class="mt-16 text-center text-gray-500">
                    <div class="flex justify-center mb-2">
                        <x-heroicon-o-information-circle class="w-10 h-10 text-gray-400" />
                    </div>
                    <p class="text-sm">Tidak ada data riwayat nilai untuk kelas dan tahun ajaran yang dipilih.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
