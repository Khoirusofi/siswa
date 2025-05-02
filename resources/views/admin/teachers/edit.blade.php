<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="text-sm text-gray-500 flex items-center gap-1">
                    <a href="{{ route('admin.teachers.index') }}" class="text-gray-700 font-semibold hover:text-gray-800">
                        Guru
                    </a>

                    <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-500" />

                    <span class="text-gray-500 font-semibold ">
                        {{ isset($teacher) ? 'Edit Guru' : 'Buat Guru' }}
                    </span>
                </nav>

                <h2 class="font-semibold text-gray-800 text-2xl mt-1">
                    {{ isset($teacher) ? 'Edit Guru' : 'Buat Guru' }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-1 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">
            <form
                action="{{ isset($teacher) ? route('admin.teachers.update', $teacher->id) : route('admin.teachers.store') }}"
                method="POST">
                @csrf
                @if (isset($teacher))
                    @method('PUT')
                @endif

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $teacher->user->name ?? '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            required placeholder="Nama">
                        <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIP</label>
                        <input type="text" name="nip" value="{{ old('nip', $teacher->nip ?? '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            {{ isset($teacher) ? 'readonly' : 'required' }} placeholder="NIP">
                        <x-input-error :messages="$errors->get('nip')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="birth_date"
                            value="{{ old('birth_date', isset($teacher->user->birth_date) ? \Carbon\Carbon::parse($teacher->user->birth_date)->format('Y-m-d') : '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        <x-input-error :messages="$errors->get('birth_date')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select name="gender"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="male"
                                {{ old('gender', $teacher->user->gender ?? '') == 'male' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="female"
                                {{ old('gender', $teacher->user->gender ?? '') == 'female' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                        <x-input-error :messages="$errors->get('gender')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="address" rows="3"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Alamat">{{ old('address', $teacher->user->address ?? '') }}</textarea>
                        <x-input-error :messages="$errors->get('address')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">No Telepon</label>
                        <input type="text" name="phone_number"
                            value="{{ old('phone_number', $teacher->user->phone_number ?? '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            placeholder="No Telepon">
                        <x-input-error :messages="$errors->get('phone_number')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                        <select name="subject_id"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            required>
                            <option value="">-- Pilih --</option>
                            @foreach ($subjects as $id => $name)
                                <option value="{{ $id }}"
                                    {{ (string) old('subject_id', $teacher->subject_id ?? '') === (string) $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('subject_id')" />
                    </div>
                </div>

                <div class="mt-6 flex justify-start gap-3">
                    <x-button type="submit" variant="primary">
                        {{ isset($teacher) ? 'Simpan' : 'Buat' }}
                    </x-button>
                    <x-button type="button" variant="secondary" onclick="history.back()">Batal</x-button>
                </div>
            </form>
        </div>
    </div>
    </div>

</x-app-layout>
