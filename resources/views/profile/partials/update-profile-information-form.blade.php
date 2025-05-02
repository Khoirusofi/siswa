<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Data Diri') }}
        </h2>

        <p class="text-sm text-gray-600 mb-5 leading-relaxed">
            Untuk menjaga keamanan dan kenyamanan akun Anda, pastikan data diri yang ditampilkan di bawah ini
            sudah sesuai.
            <br>
            <span class="font-medium text-blue-800">• Perbarui email Anda</span> apabila masih menggunakan email
            lama atau tidak aktif, agar Anda tetap bisa menerima informasi penting dari sistem.
            <br>
            <span class="font-medium text-green-700">• Verifikasi email Anda</span> jika belum dilakukan, guna
            memastikan akun Anda aktif dan dapat digunakan dengan optimal.
            <br>
            Jika Anda memerlukan bantuan dalam mengganti data, silakan hubungi admin atau wali kelas.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                placeholder="Nama" required>
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
            <input type="date" name="birth_date"
                value="{{ old('birth_date', isset($user->birth_date) ? \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') : '') }}"
                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
            <x-input-error :messages="$errors->get('birth_date')" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
            <select name="gender"
                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="male" {{ old('gender', $user->gender ?? '') == 'male' ? 'selected' : '' }}>
                    Laki-laki</option>
                <option value="female" {{ old('gender', $user->gender ?? '') == 'female' ? 'selected' : '' }}>
                    Perempuan</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Alamat</label>
            <textarea name="address" rows="3"
                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                placeholder="Alamat">{{ old('address', $user->address ?? '') }}</textarea>
            <x-input-error :messages="$errors->get('address')" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">No Telepon</label>
            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number ?? '') }}"
                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                placeholder="No Telepon">
            <x-input-error :messages="$errors->get('phone_number')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
