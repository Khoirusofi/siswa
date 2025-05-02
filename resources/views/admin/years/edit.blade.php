<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="text-sm text-gray-500 flex items-center gap-1">
                    <a href="{{ route('admin.years.index') }}" class="text-gray-700 font-semibold hover:text-gray-800">
                        Tahun Ajaran
                    </a>

                    <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-500" />

                    <span class="text-gray-500 font-semibold ">
                        {{ isset($year) ? 'Edit Tahun Ajaran' : 'Buat Tahun Ajaran' }}
                    </span>
                </nav>

                <h2 class="font-semibold text-gray-800 text-2xl mt-1">
                    {{ isset($year) ? 'Edit Tahun Ajaran' : 'Buat Tahun Ajaran' }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">
            <form action="{{ isset($year) ? route('admin.years.update', $year->id) : route('admin.years.store') }}"
                method="POST">
                @csrf
                @if (isset($year))
                    @method('PUT')
                @endif

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Tahun Ajaran</label>
                        <input type="text" name="name" value="{{ old('name', $year->name ?? '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            required>
                        <x-input-error :messages="$errors->get('name')" />
                    </div>
                </div>

                <div class="mt-6 flex justify-start gap-3">
                    <x-button type="submit" variant="primary">
                        {{ isset($year) ? 'Simpan' : 'Buat' }}</x-button>
                    <x-button type="button" variant="secondary" onclick="history.back()">Batal</x-button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
