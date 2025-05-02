<x-guest-layout>
    <section
        class="w-full px-4 lg:px-[160px] lg:flex-row-reverse mt-[30px] max-w-[1512px] mx-auto flex flex-col lg:items-center lg:justify-between gap-8">
        <div class="w-full max-w-[700px]">
            <img src="{{ asset('assets/photos/hero-image.png') }}" alt="Hero Image"
                class="w-full h-auto object-cover rounded-xl aspect-[16/9]" />
        </div>

        <div class="flex flex-col items-start md:items-center lg:items-start text-blue-950">
            <div class="bg-white p-10 rounded-2xl lg:flex-row-reverse">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <div class="flex flex-col gap-y-7">
                        <h3 class="text-2xl text-blue-950 font-bold leading-relaxed">
                            Seleksi Siswa Berprestasi yang <br class="lg:block hidden">Profesional & Objektif
                        </h3>

                        @if (session('status') == 'verification-link-sent')
                            <div class="font-medium text-sm text-green-600">
                                {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda.') }}
                            </div>
                        @endif

                        <div class="mb-4 text-sm text-gray-600">
                            {{ __('Sebelum memulai, silakan verifikasi email Anda melalui link yang baru saja kami kirim.') }}
                        </div>

                        <div class="flex flex-col gap-y-4">
                            <button type="submit"
                                class=" w-full text-center px-7 rounded-full text-base py-3 font-semibold text-white bg-blue-700">
                                Kirim
                            </button>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <div class="flex flex-col gap-y-4 mt-4">
                        <button type="submit"
                            class=" w-full text-center px-7 rounded-full text-base py-3  text-blue-950 bg-white font-semibold border border-blue-950">
                            Keluar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-guest-layout>
