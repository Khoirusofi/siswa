<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="text-sm font-semibold text-gray-600">
                    <span class="text-gray-500">Siswa</span>
                </nav>
                <h2 class="text-2xl font-semibold text-gray-800 mt-1">Daftar Siswa</h2>
            </div>
            <x-link-route href="{{ route('admin.students.create') }}" variant="primary">
                Buat
            </x-link-route>
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">
            <div class="flex justify-end items-end text-end mb-4">
                <form action="{{ route('admin.students.index') }}" method="GET" class="flex items-center"
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
                            window.location.href = "{{ route('admin.students.index') }}";
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
                            <th class="px-4 py-3 text-left font-medium whitespace-nowrap text-gray-900">No</th>
                            <th class="px-4 py-3 text-left font-medium whitespace-nowrap text-gray-900">Nama</th>
                            <th class="px-4 py-3 text-left font-medium whitespace-nowrap text-gray-900">NISN</th>
                            <th class="px-4 py-3 text-left font-medium whitespace-nowrap text-gray-900">NIS</th>
                            <th class="px-4 py-3 text-left font-medium whitespace-nowrap text-gray-900">Jurusan</th>
                            <th class="px-4 py-3 text-left font-medium whitespace-nowrap text-gray-900">Tanggal Lahir
                            </th>
                            {{-- <th class="px-4 py-3 text-left font-medium whitespace-nowrap text-gray-900">Email</th> --}}
                            <th class="px-4 py-3 text-left font-medium whitespace-nowrap text-gray-900">No. Telepon</th>
                            <th class="px-4 py-3 text-left font-medium whitespace-nowrap text-gray-900">Jenis Kelamin
                            </th>
                            <th class="px-4 py-3 text-left font-medium whitespace-nowrap text-gray-900">Orang Tua</th>
                            <th class="px-4 py-3 text-left font-medium whitespace-nowrap text-gray-900">Alamat</th>
                            <th class="px-4 py-3 text-left font-medium whitespace-nowrap text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($students as $student)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $students->firstItem() + $loop->index }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $student->user->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                    {{ $student->nisn }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                    {{ $student->nis }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                    {{ optional($student->enrollments->last()?->room?->major)->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                    {{ $student->user->birth_date ? \Carbon\Carbon::parse($student->user->birth_date)->locale('id')->translatedFormat('d F Y') : '-' }}
                                </td>
                                {{-- <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                    {{ $student->user->email ?? '-' }}
                                </td> --}}
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                    {{ $student->user->phone_number ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                    {{ $student->user->gender == 'male' ? 'Laki-laki' : ($student->user->gender == 'female' ? 'Perempuan' : '-') }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                    {{ $student->parent ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                    {{ $student->user->address ?? '-' }}
                                </td>
                                <td class="px-4 py-3 flex items-center justify-start gap-2">
                                    <x-link-button href="{{ route('admin.students.edit', $student->id) }}"
                                        variant="warning" icon="pencil-square"
                                        title="Edit {{ $student->user->name ?? '-' }}">
                                        Edit
                                    </x-link-button>

                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus {{ $student->user->name ?? '-' }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-link-button type="submit" variant="danger" icon="trash"
                                            title="Hapus {{ $student->user->name ?? '-' }}">
                                            Hapus
                                        </x-link-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="px-4 py-3 text-center text-gray-500">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $students->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
