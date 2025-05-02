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
                        {{ isset($room) ? 'Edit Kelas' : 'Buat Kelas' }}
                    </span>
                </nav>

                <h2 class="font-semibold text-gray-800 text-2xl mt-1">
                    {{ isset($room) ? 'Edit Kelas' : 'Buat Kelas' }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">
            <form action="{{ isset($room) ? route('admin.rooms.update', $room->id) : route('admin.rooms.store') }}"
                method="POST">
                @csrf
                @if (isset($room))
                    @method('PUT')
                @endif

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jurusan</label>
                        <select name="major_id"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="">-- Pilih --</option>
                            @foreach ($majors as $major)
                                <option value="{{ $major->id }}"
                                    {{ isset($room) && $room->major_id == $major->id ? 'selected' : '' }}>
                                    {{ $major->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('major_id')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tingkat Kelas</label>
                        <select name="level_id"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="">-- Pilih --</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level->id }}"
                                    {{ isset($room) && $room->level_id == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('level_id')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                        <input type="text" name="name" value="{{ old('name', $room->name ?? '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            required>
                        <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Wali Kelas</label>
                        <select name="teacher_id"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="">-- Pilih --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ isset($room) && $room->teacher_id == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('teacher_id')" />
                    </div>
                </div>

                <div class="mt-6 flex justify-start gap-3">
                    <x-button type="submit" variant="primary">
                        {{ isset($room) ? 'Simpan' : 'Buat' }}</x-button>
                    <x-button type="button" variant="secondary" onclick="history.back()">Batal</x-button>
                </div>
            </form>
        </div>
    </div>
    </div>

</x-app-layout>
