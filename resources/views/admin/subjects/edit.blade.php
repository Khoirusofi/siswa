<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="text-sm text-gray-500 flex items-center gap-1">
                    <a href="{{ route('admin.subjects.index') }}" class="text-gray-700 font-semibold hover:text-gray-800">
                        Mata Pelajaran
                    </a>

                    <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-500" />

                    <span class="text-gray-500 font-semibold ">
                        {{ isset($subject) ? 'Edit Mata Pelajaran' : 'Buat Mata Pelajaran' }}
                    </span>
                </nav>

                <h2 class="font-semibold text-gray-800 text-2xl mt-1">
                    {{ isset($subject) ? 'Edit Mata Pelajaran' : 'Buat Mata Pelajaran' }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">
            <form
                action="{{ isset($subject) ? route('admin.subjects.update', $subject->id) : route('admin.subjects.store') }}"
                method="POST">
                @csrf
                @if (isset($subject))
                    @method('PUT')
                @endif

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Mata Pelajaran</label>
                        <input type="text" name="name" value="{{ old('name', $subject->name ?? '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            required>
                        <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Apakah Umum?</label>
                        <select name="is_general" id="is_general"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="">-- Pilih Status --</option>
                            <option value="1"
                                {{ old('is_general', $subject->is_general ?? '') == '1' ? 'selected' : '' }}>Ya</option>
                            <option value="0"
                                {{ old('is_general', $subject->is_general ?? '') == '0' ? 'selected' : '' }}>Tidak
                            </option>
                        </select>
                        <x-input-error :messages="$errors->get('is_general')" />
                    </div>

                    <div id="major_section">
                        <label class="block text-sm font-medium text-gray-700">Jurusan</label>
                        <select name="major_id"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach ($majors as $major)
                                <option value="{{ $major->id }}"
                                    {{ isset($subject) && $subject->major_id == $major->id ? 'selected' : '' }}>
                                    {{ $major->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('major_id')" />
                    </div>
                </div>

                <div class="mt-6 flex justify-start gap-3">
                    <x-button type="submit" variant="primary">
                        {{ isset($subject) ? 'Simpan' : 'Buat' }}</x-button>
                    <x-button type="button" variant="secondary" onclick="history.back()">Batal</x-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const isGeneralSelect = document.getElementById('is_general');
        const majorSection = document.getElementById('major_section');

        function toggleMajorSection() {
            majorSection.style.display = isGeneralSelect.value === '0' ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', toggleMajorSection);
        isGeneralSelect.addEventListener('change', toggleMajorSection);
    </script>

</x-app-layout>
