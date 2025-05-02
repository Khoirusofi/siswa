<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="text-sm text-gray-500 flex items-center gap-1">
                    <a href="{{ route('admin.rooms.index') }}" class="text-gray-700 font-semibold hover:text-gray-800">
                        Kelas
                    </a>

                    <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-500" />

                    <span class="text-gray-500 font-semibold ">
                        {{ $room->name }}
                    </span>
                </nav>

                <h2 class="font-semibold text-gray-800 text-2xl mt-1">
                    Siswa {{ $room->name }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">
            <div class="flex justify-end items-center mb-4 flex-wrap gap-2">
                <form id="filter-form" class="flex items-center gap-2 flex-wrap" onsubmit="return false;">
                    <select id="year-select"
                        class="border border-gray-300 py-2 px-8 rounded-md focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach ($years as $year)
                            <option value="{{ $year->slug }}"
                                {{ optional($selectedYear)->slug == $year->slug ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="relative">
                        <x-heroicon-o-magnifying-glass
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                            class="border border-gray-300 px-10 py-2 rounded-md w-full max-w-xs focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Cari">
                    </div>
                </form>

                <script>
                    const searchInput = document.getElementById('search-input');
                    const yearSelect = document.getElementById('year-select');

                    function buildURL(search = '', year = '') {
                        let base = `{{ url('admin/rooms/' . $room->slug . '/students') }}`;
                        if (year) base += `/${year}`;
                        const params = new URLSearchParams();
                        if (search) params.set('search', search);
                        const query = params.toString();
                        return query ? `${base}?${query}` : base;
                    }

                    searchInput.addEventListener('input', function() {
                        const search = searchInput.value.trim();
                        const year = yearSelect.value;
                        window.location.href = buildURL(search, year);
                    });

                    yearSelect.addEventListener('change', function() {
                        const search = searchInput.value.trim();
                        const year = yearSelect.value;
                        window.location.href = buildURL(search, year);
                    });
                </script>
            </div>

            @if ($selectedYear)
                <div class="overflow-x-auto overflow-y-auto max-h-[500px] border border-gray-200 rounded-xl shadow-sm">
                    <table class="min-w-[1000px] w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">No</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">Nama</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">NISN</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">NIS</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($students as $data)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ $students->firstItem() + $loop->index }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $data->student->user->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $data->student->nisn }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $data->student->nis }}
                                    </td>
                                    <td class="px-4 py-3 flex items-center justify-start gap-2">
                                        <x-link-button href="{{ route('admin.students.edit', $data->student->id) }}"
                                            variant="warning" icon="pencil-square"
                                            title="Edit {{ $data->student->user->name ?? '-' }}">
                                            Edit
                                        </x-link-button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                        Belum ada data.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center text-gray-500 mt-10">
                    <p>Silakan pilih tahun ajaran terlebih dahulu untuk melihat data siswa.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
