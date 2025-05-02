<x-guest-layout>

    <section
        class="w-full px-4 lg:px-[160px] lg:flex-row-reverse mt-[30px] max-w-[1512px] mx-auto flex flex-col lg:items-center lg:justify-between gap-8">
        <div class="w-full max-w-[700px]">
            <img src="{{ asset('assets/photos/hero-image.png') }}" alt="Hero Image"
                class="w-full h-auto object-cover rounded-xl aspect-[16/9]" />
        </div>

        <div class="flex flex-col items-start md:items-center lg:items-start text-blue-950">
            <div class="bg-white p-10 rounded-2xl lg:flex-row-reverse">
                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                                type="email" name="email" :value="old('email', $request->email)" required autofocus
                                autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <p class="font-semibold text-blue-950 text-base mb-2">
                                Kata Sandi
                            </p>
                            <x-text-input id="password"
                                class="w-full py-3 rounded-full pl-5 pr-10 border border-gray-300 text-blue-950 font-semibold"
                                type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <p class="font-semibold text-blue-950 text-base mb-2">
                                Konfirmasi Kata Sandi
                            </p>
                            <x-text-input id="password_confirmation"
                                class="w-full py-3 rounded-full pl-5 pr-10 border border-gray-300 text-blue-950 font-semibold"
                                type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
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
