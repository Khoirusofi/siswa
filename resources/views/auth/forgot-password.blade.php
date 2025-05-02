<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <section
        class="w-full px-4 lg:px-[160px] lg:flex-row-reverse mt-[30px] max-w-[1512px] mx-auto flex flex-col lg:items-center lg:justify-between gap-8">
        <div class="w-full max-w-[700px]">
            <img src="{{ asset('assets/photos/hero-image.png') }}" alt="Hero Image"
                class="w-full h-auto object-cover rounded-xl aspect-[16/9]" />
        </div>

        <div class="flex flex-col items-start md:items-center lg:items-start text-blue-950">
            <div class="bg-white p-10 rounded-2xl lg:flex-row-reverse">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="flex flex-col gap-y-7">
                        <h3 class="text-2xl text-blue-950 font-bold leading-relaxed">
                            Seleksi Siswa Berprestasi yang <br class="lg:block hidden">Profesional & Objektif
                        </h3>
                        <div>
                            <p class="font-semibold text-blue-950 text-base mb-2">
                                Email
                            </p>
                            <x-text-input id="email"
                                class="w-full py-3 rounded-full pl-5 pr-10 border border-gray-300 text-blue-950 font-semibold"
                                type="email" name="email" :value="old('email')" required autofocus />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-4 text-sm text-gray-600">
                            {{ __('Masukkan email Anda dan kami akan mengirimkan link untuk mengatur ulang kata sandi.') }}
                        </div>

                        <div class="flex flex-col gap-y-4">
                            <button type="submit"
                                class=" w-full text-center px-7 rounded-full text-base py-3 font-semibold text-white bg-blue-700">
                                Kirim
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-guest-layout>
