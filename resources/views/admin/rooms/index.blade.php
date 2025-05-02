<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="text-sm font-semibold text-gray-600">
                    <span class="text-gray-500">Kelas</span>
                </nav>
                <h2 class="text-2xl font-semibold text-gray-800 mt-1">Daftar Kelas</h2>
            </div>

            <x-link-route href="{{ route('admin.rooms.create') }}" variant="primary">
                Buat
            </x-link-route>
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">
            <div class="flex justify-end items-end text-end mb-4">
                <form action="{{ route('admin.rooms.index') }}" method="GET" class="flex items-center"
                    id="search-form">
                    <div class="relative">
                        <x-heroicon-o-magnifying-glass
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                            class="border border-gray-300 px-10 py-2 rounded-md w-full max-w-xs focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Cari" oninput="handleSearch()">
                    </div>
                </form>

                <script>
                    function handleSearch() {
                        let input = document.getElementById('search-input');
                        let form = document.getElementById('search-form');

                        if (input.value.trim() === '') {
                            window.location.href = "{{ route('admin.rooms.index') }}";
                        } else {
                            form.submit();
                        }
                    }
                </script>
            </div>

            <div class="overflow-x-auto overflow-y-auto max-h-[500px] border border-gray-200 rounded-xl shadow-sm">
                <table class="min-w-[1000px] w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-900 whitespace-nowrap">No</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-900 whitespace-nowrap">Nama</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-900 whitespace-nowrap">Tingkat Kelas
                            </th>
                            <th class="px-4 py-3 text-left font-medium text-gray-900 whitespace-nowrap">Jurusan</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-900 whitespace-nowrap">Wali Kelas</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-900 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($rooms as $room)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $rooms->firstItem() + $loop->index }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $room->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-900 whitespace-nowrap">
                                    {{ $room->level->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-900 whitespace-nowrap">
                                    {{ $room->major->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-900 whitespace-nowrap">
                                    {{ $room->teacher->user->name ?? 'Belum ada wali kelas' }}
                                </td>
                                <td class="px-4 py-3 flex items-center justify-start gap-2">
                                    <x-link-button href="{{ route('admin.rooms.studentroom', $room->slug) }}"
                                        variant="secondary" icon="eye"
                                        title="Lihat Siswa Kelas {{ $room->name ?? '-' }}">
                                        Lihat
                                    </x-link-button>

                                    <x-link-button href="{{ route('admin.rooms.edit', $room->id) }}" variant="warning"
                                        icon="pencil-square" title="Edit {{ $room->name ?? '-' }}">
                                        Edit
                                    </x-link-button>

                                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus {{ $room->name ?? '-' }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-link-button type="submit" variant="danger" icon="trash"
                                            title="Hapus {{ $room->name ?? '-' }}">
                                            Hapus
                                        </x-link-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-4 py-3 text-center text-gray-500">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $rooms->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
