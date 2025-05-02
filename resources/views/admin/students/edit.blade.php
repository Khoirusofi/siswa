<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="text-sm text-gray-500 flex items-center gap-1">
                    <a href="{{ route('admin.students.index') }}" class="text-gray-700 font-semibold hover:text-gray-800">
                        Siswa
                    </a>

                    <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-500" />

                    <span class="text-gray-500 font-semibold ">
                        {{ isset($student) ? 'Edit Siswa' : 'Buat Siswa' }}
                    </span>
                </nav>

                <h2 class="font-semibold text-gray-800 text-2xl mt-1">
                    {{ isset($student) ? 'Edit Siswa' : 'Buat Siswa' }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-1 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">
            <form
                action="{{ isset($student) ? route('admin.students.update', $student->id) : route('admin.students.store') }}"
                method="POST">
                @csrf
                @if (isset($student))
                    @method('PUT')
                @endif

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $student->user->name ?? '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Nama" required>
                        <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">NISN</label>
                        <input type="text" name="nisn" value="{{ old('nisn', $student->nisn ?? '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            placeholder="NISN" {{ isset($student) ? 'readonly' : 'required' }}>
                        <x-input-error :messages="$errors->get('nisn')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIS</label>
                        <input type="text" name="nis" value="{{ old('nis', $student->nis ?? '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            placeholder="NIS" {{ isset($student) ? 'readonly' : 'required' }}>
                        <x-input-error :messages="$errors->get('nis')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="birth_date"
                            value="{{ old('birth_date', isset($student->user->birth_date) ? \Carbon\Carbon::parse($student->user->birth_date)->format('Y-m-d') : '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        <x-input-error :messages="$errors->get('birth_date')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select name="gender"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="male"
                                {{ old('gender', $student->user->gender ?? '') == 'male' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="female"
                                {{ old('gender', $student->user->gender ?? '') == 'female' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                        <x-input-error :messages="$errors->get('gender')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="address" rows="3"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Alamat">{{ old('address', $student->user->address ?? '') }}</textarea>
                        <x-input-error :messages="$errors->get('address')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">No Telepon</label>
                        <input type="text" name="phone_number"
                            value="{{ old('phone_number', $student->user->phone_number ?? '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            placeholder="No Telepon">
                        <x-input-error :messages="$errors->get('phone_number')" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Orang Tua / Wali</label>
                        <input type="text" name="parent" value="{{ old('parent', $student->parent ?? '') }}"
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Nama Orang Tua / Wali">
                        <x-input-error :messages="$errors->get('parent')" />
                    </div>
                </div>

                <div class="mt-6 mb-12 flex justify-start gap-3">
                    <x-button type="submit" variant="primary">
                        {{ isset($student) ? 'Simpan Siswa' : 'Buat Siswa' }}
                    </x-button>
                    <x-button type="button" variant="secondary" onclick="history.back()">Batal</x-button>
                </div>
            </form>

            <form action="{{ route('admin.students.save-class', $student->id ?? '') }}" method="POST"
                x-data="{
                    actionMode: '',
                    enrollmentToEdit: null
                }">
                @csrf

                <input type="hidden" name="student_id" value="{{ $student->id ?? '' }}">
                <input type="hidden" name="action" :value="actionMode">

                <div class="mt-6 border-t pt-4">
                    <div class="mt-6  mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h3 class="text-lg font-semibold text-gray-800">Riwayat Kelas Siswa</h3>

                        <x-button type="button" variant="primary"
                            @click="actionMode = 'tambah'; enrollmentToEdit = null">
                            Tambah Kelas
                        </x-button>
                    </div>

                    @if (isset($student) && $student->enrollments->count())
                        <div
                            class="overflow-x-auto overflow-y-auto max-h-[500px] border border-gray-200 rounded-xl shadow-sm">
                            <table class="min-w-[1000px] w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium text-gray-900">No</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-900">Kelas</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-900">Tahun Ajaran</th>
                                        <th class="px-4 py-3 text-left font-medium text-gray-900">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($student->enrollments as $index => $enrollment)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3 font-medium text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-900">
                                                {{ $enrollment->room ? $enrollment->room->name : 'Tidak ada kelas' }}
                                            </td>
                                            <td class="px-4 py-3 font-medium text-gray-900">
                                                {{ $enrollment->room ? $enrollment->year->name : 'Tidak ada kelas' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <x-link-button type="button" icon="pencil-square" variant="primary"
                                                    title="Edit data"
                                                    @click="actionMode = 'edit'; enrollmentToEdit = {{ $enrollment->id }}">
                                                    Edit
                                                </x-link-button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic text-center">Belum ada riwayat kelas.</p>
                    @endif
                </div>

                <div
                    x-effect="if (actionMode === 'tambah' || actionMode === 'edit') {
                  $nextTick(() => $refs.formSection?.scrollIntoView({ behavior: 'smooth', block: 'start' }))
              }">
                </div>

                <div class="mt-10" x-show="actionMode === 'tambah'" x-ref="formSection">
                    <h4 class="text-md font-semibold mb-3">Tambah Kelas</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kelas</label>
                            <select name="room_id"
                                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                <option value="">-- Pilih --</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}"
                                        {{ old('room_id') == $room ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('room_id')" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                            <select name="year_id"
                                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                <option value="">-- Pilih --</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}"
                                        {{ old('year_id') == $year ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('year_id')" />
                        </div>

                    </div>
                </div>

                <template x-if="actionMode === 'edit' && enrollmentToEdit">
                    <div class="mt-10" x-ref="formSection">
                        <h4 class="text-md font-semibold mb-3">Edit Kelas</h4>
                        <input type="hidden" name="edit_enrollment_id" :value="enrollmentToEdit">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ubah ke Kelas</label>
                                <select name="edit_room_id"
                                    class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}"
                                            {{ isset($subject) && $enrollmentToEdit->room_id == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('edit_room_id')" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ubah ke Tahun Ajaran</label>
                                <select name="edit_year_id"
                                    class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->id }}"
                                            {{ isset($subject) && $enrollmentToEdit->year_id == $year->id ? 'selected' : '' }}>
                                            {{ $year->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('edit_year_id')" />
                            </div>
                        </div>
                    </div>
                </template>

                <div class="mt-6 flex justify-center gap-3" x-show="actionMode">
                    <x-button type="submit" variant="primary">
                        Simpan Kelas
                    </x-button>
                    <x-button type="button" variant="secondary" @click="actionMode = null; enrollmentToEdit = null">
                        Batal
                    </x-button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
