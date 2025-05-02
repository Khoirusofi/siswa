<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="text-sm text-gray-500 flex items-center gap-1">
                    <a href="{{ route('admin.levels.index') }}" class="text-gray-700 font-semibold hover:text-gray-800">
                        Tingkat Kelas
                    </a>

                    <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-500" />

                    <span class="text-gray-500 font-semibold ">
                        {{ isset($level) ? 'Edit Tingkat Kelas' : 'Buat Tingkat Kelas' }}
                    </span>
                </nav>

                <h2 class="font-semibold text-gray-800 text-2xl mt-1">
                    {{ isset($level) ? 'Edit Tingkat Kelas' : 'Buat Tingkat Kelas' }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">
            <form action="{{ isset($level) ? route('admin.levels.update', $level->id) : route('admin.levels.store') }}"
                method="POST">
                @csrf
                @if (isset($level))
                    @method('PUT')
                @endif

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Tingkat Kelas</label>
                        <input type="text" name="name" value="{{ old('name', $level->name ?? '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            required>
                        <x-input-error :messages="$errors->get('name')" />
                    </div>
                </div>

                <div class="mt-6 flex justify-start gap-3">
                    <x-button type="submit" variant="primary">
                        {{ isset($level) ? 'Simpan' : 'Buat' }}</x-button>
                    <x-button type="button" variant="secondary" onclick="history.back()">Batal</x-button>
                </div>
            </form>
        </div>
    </div>
    </div>

</x-app-layout>
