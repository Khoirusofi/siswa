<x-guest-layout>
    <section
        class="w-full px-4 lg:px-[160px] lg:flex-row-reverse mt-[30px] max-w-[1512px] mx-auto flex flex-col lg:items-center lg:justify-between gap-8">
        <div class="w-full max-w-[750px]">
            <img src="{{ asset('assets/photos/hero-image.png') }}" alt="Hero Image"
                class="w-full h-auto object-cover rounded-xl aspect-[16/9]" />
        </div>

        <div class="flex flex-col items-start md:items-center lg:items-start text-blue-950">
            <h1 class="text-4xl font-bold leading-tight md:max-w-[443px] text-start md:text-center lg:text-start">
                Seleksi Siswa Berprestasi yang Profesional & Objektif
            </h1>
            <p
                class="text-base leading-[32px] text-blue-950 mt-5 md:max-w-[423px] text-start md:text-center lg:text-start">
                Sistem Pendukung Keputusan SMAN 1 TENJO dirancang untuk membantu memilih siswa berprestasi secara adil,
                transparan, dan berdasarkan kriteria yang terukur.
            </p>
        </div>
    </section>

    <section
        class="w-full px-[31px] md:px-[65px] lg:px-[204px] flex flex-col md:items-center lg:justify-center mt-[60px] max-w-[1512px] lg:mx-auto">
        <div class="mt-[5px]">
            <p class="text-base font-bold text-blue-700 text-center">SPK SISWA BERPRESTASI</p>
        </div>
        <div class="mt-[5px]">
            <h2 class="text-2xl font-bold text-center">Seleksi Lebih Tepat, Hasil Lebih Objektif</h2>
        </div>
        <div class="mt-[20px]">
            <p class="text-base leading-[32px] md:px-0 w-full md:max-w-[700px] text-center text-blue-950">Kami
                menghadirkan sistem pendukung keputusan yang membantu SMAN 1 TENJO menyeleksi siswa berprestasi secara
                adil, terukur, dan transparan.</p>
        </div>
        <div class="mt-[50px] md:mt-[70px] grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-x-[80px] gap-y-[60px]">

            <div class="w-full order-1 md:order-1 lg:order-1">
                <div class="flex items-start gap-x-[24px]">
                    <div class="bg-[#1F7CFF] w-[60px] h-[60px] flex items-center justify-center rounded-full flex-none">
                        <x-heroicon-o-briefcase class="w-6 h-6 text-white" />
                    </div>
                    <div class="space-y-[12px]">
                        <h6 class="text-[20px] font-semibold">Analisis Multi-Kriteria</h6>
                        <p class="text-base leading-[32px] text-blue-950">Menilai siswa berdasarkan berbagai aspek
                            seperti akademik, non-akademik, kehadiran, dan sikap.</p>
                    </div>
                </div>
            </div>

            <div class="w-full order-2 md:order-3 lg:order-3">
                <div class="flex items-start gap-x-[24px]">
                    <div class="bg-[#191046] w-[60px] h-[60px] flex items-center justify-center rounded-full flex-none">
                        <x-heroicon-o-sparkles class="w-6 h-6 text-white" />
                    </div>
                    <div class="space-y-[12px]">
                        <h6 class="text-[20px] font-semibold">Proses Seleksi Transparan</h6>
                        <p class="text-base leading-[32px] text-blue-950">Setiap tahap seleksi dapat dilacak dan
                            ditinjau oleh pihak sekolah secara terbuka.</p>
                    </div>
                </div>
            </div>

            <div class="w-full order-4 md:order-2 lg:order-2">
                <div class="flex items-start gap-x-[24px]">
                    <div class="bg-[#F75C4E] w-[60px] h-[60px] flex items-center justify-center rounded-full flex-none">
                        <x-heroicon-o-circle-stack class="w-6 h-6 text-white" />
                    </div>
                    <div class="space-y-[12px]">
                        <h6 class="text-[20px] font-semibold">Data Terintegrasi</h6>
                        <p class="text-base leading-[32px] text-blue-950">Memudahkan pengambilan keputusan karena semua
                            data siswa tersedia dalam satu sistem yang rapi.</p>
                    </div>
                </div>
            </div>

            <div class="w-full order-3 md:order-5 lg:order-4">
                <div class="flex items-start gap-x-[24px]">
                    <div class="bg-[#FF1FB3] w-[60px] h-[60px] flex items-center justify-center rounded-full flex-none">
                        <x-heroicon-o-document-chart-bar class="w-6 h-6 text-white" />
                    </div>
                    <div class="space-y-[12px]">
                        <h6 class="text-[20px] font-semibold">Laporan Otomatis</h6>
                        <p class="text-base leading-[32px] text-blue-950">Hasil seleksi langsung disajikan dalam bentuk
                            laporan siap cetak yang mudah dipahami.</p>
                    </div>
                </div>
            </div>

            <div class="w-full order-6 md:order-6 lg:order-5">
                <div class="flex items-start gap-x-[24px]">
                    <div class="bg-[#5C4EF7] w-[60px] h-[60px] flex items-center justify-center rounded-full flex-none">
                        <x-heroicon-o-scale class="w-6 h-6 text-white" />
                    </div>
                    <div class="space-y-[12px]">
                        <h6 class="text-[20px] font-semibold">Pemilihan Objektif</h6>
                        <p class="text-base leading-[32px] text-blue-950">Mengurangi unsur subjektivitas dengan
                            pendekatan berbasis data dan perhitungan yang terstandarisasi.</p>
                    </div>
                </div>
            </div>

            <div class="w-full order-5 md:order-4 lg:order-6">
                <div class="flex items-start gap-x-[24px]">
                    <div class="bg-[#F7954E] w-[60px] h-[60px] flex items-center justify-center rounded-full flex-none">
                        <x-heroicon-o-trophy class="w-6 h-6 text-white" />
                    </div>
                    <div class="space-y-[12px]">
                        <h6 class="text-[20px] font-semibold">Dukung Penghargaan yang Adil</h6>
                        <p class="text-base leading-[32px] text-blue-950">Sistem ini mendukung pemberian penghargaan
                            kepada siswa dengan objektif berdasarkan perhitungan yang akurat.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-guest-layout>
