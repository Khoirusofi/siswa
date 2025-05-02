<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="text-sm font-semibold text-gray-600">
                    <span class="text-gray-500">
                        Input Nilai Eskul Siswa Kelas
                        @if ($selectedRoom && $selectedYear)
                            {{ $selectedRoom->name }} - {{ $selectedYear->name }}
                        @elseif ($selectedRoom)
                            {{ $selectedRoom->name }}
                        @elseif ($selectedYear)
                            {{ $selectedYear->name }}
                        @endif
                    </span>
                </nav>
                <h2 class="text-2xl font-semibold text-gray-800 mt-1">
                    Input Nilai Eskul Siswa Kelas
                    <br>
                    @if ($selectedRoom && $selectedYear)
                        {{ $selectedRoom->name }} - {{ $selectedYear->name }}
                    @elseif ($selectedRoom)
                        {{ $selectedRoom->name }}
                    @elseif ($selectedYear)
                        {{ $selectedYear->name }}
                    @endif
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-end mb-4">
                <form action="{{ route('admin.extracurriculars.index') }}" method="GET" id="filter-form"
                    class="flex gap-4 flex-wrap items-end">
                    <div>
                        <select name="room" id="room"
                            class="border border-gray-300 px-8 py-2 rounded-md focus:ring-primary-500 focus:border-primary-500"
                            onchange="submitFilterForm()">
                            <option value="">Pilih Kelas</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->slug }}"
                                    {{ request('room') === $room->slug ? 'selected' : '' }}>
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="year" id="year"
                            class="border border-gray-300 px-10 py-2 rounded-md focus:ring-primary-500 focus:border-primary-500"
                            onchange="submitFilterForm()">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->slug }}"
                                    {{ request('year') === $year->slug ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <script>
                    function submitFilterForm() {
                        document.getElementById('filter-form').submit();
                    }
                </script>
            </div>

            @if ($enrollments->count())
                <form action="{{ route('admin.extracurriculars.store') }}" method="POST">
                    @csrf

                    <div
                        class="overflow-x-auto overflow-y-auto max-h-[500px] border border-gray-200 rounded-xl shadow-sm">
                        <table class="min-w-[1000px] w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-900">No</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-900">Nama</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-900">NISN</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-900">NIS</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-900">Jurusan</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-900">Nilai Eskul</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($enrollments as $i => $enrollment)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900">
                                            {{ $i + 1 }}
                                            <input type="hidden" name="components[{{ $i }}][enrollment_id]"
                                                value="{{ $enrollment->id }}">
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $enrollment->student->user->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-900 whitespace-nowrap">
                                            {{ $enrollment->student->nisn }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-900 whitespace-nowrap">
                                            {{ $enrollment->student->nis }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-900 whitespace-nowrap">
                                            {{ optional($enrollment->room->major)->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <input type="number" step="0.1" min="0" max="100"
                                                name="components[{{ $i }}][extracurricular]"
                                                value="{{ $existingExtracurricularData->get($enrollment->id) ?? old("components.$i.extracurricular") }}"
                                                class="border-gray-300 rounded-md w-20 focus:ring-primary-500 focus:border-primary-500"
                                                placeholder="Nilai">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        <x-button type="submit" variant="primary">
                            Simpan
                        </x-button>
                    </div>
                </form>
            @elseif (request('room') && request('year'))
                <div class="mt-16 text-center text-gray-500">
                    <div class="flex justify-center mb-2">
                        <x-heroicon-o-information-circle class="w-10 h-10 text-gray-400" />
                    </div>
                    <p class="text-sm">
                        Tidak ada siswa ditemukan untuk kelas dan tahun ajaran yang dipilih.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
