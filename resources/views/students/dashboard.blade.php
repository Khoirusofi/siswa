<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="text-sm font-semibold text-gray-600">
                    <span class="text-gray-500">Dashboard</span>
                </nav>
                <h2 class="text-2xl font-semibold text-gray-800 mt-1">Dashboard</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">
            <div>
                <h3 class="text-xl font-semibold text-blue-950 mb-3">
                    Data Diri
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-x-7 gap-y-7">

                    {{-- Nama --}}
                    <div class="item-stat bg-white rounded-2xl p-5">
                        <div class="flex items-center justify-between mb-6">
                            <div class="bg-violet-600 rounded-full p-3">
                                <x-heroicon-s-user class="h-6 w-6 text-white" />
                            </div>
                            <span class="text-sm font-semibold text-gray-400">Nama</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Siswa</p>
                        <h3 class="text-md font-bold text-blue-900">{{ $student->user->name }}</h3>
                    </div>

                    {{-- NIS --}}
                    <div class="item-stat bg-white rounded-2xl p-5">
                        <div class="flex items-center justify-between mb-6">
                            <div class="bg-emerald-500 rounded-full p-3">
                                <x-heroicon-s-identification class="h-6 w-6 text-white" />
                            </div>
                            <span class="text-sm font-semibold text-gray-400">NIS</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Nomor Induk</p>
                        <h3 class="text-md font-bold text-blue-900">{{ $student->nis }}</h3>
                    </div>

                    {{-- NISN --}}
                    <div class="item-stat bg-white rounded-2xl p-5">
                        <div class="flex items-center justify-between mb-6">
                            <div class="bg-amber-500 rounded-full p-3">
                                <x-heroicon-s-identification class="h-6 w-6 text-white" />
                            </div>
                            <span class="text-sm font-semibold text-gray-400">NISN</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Nomor Nasional</p>
                        <h3 class="text-md font-bold text-blue-900">{{ $student->nisn }}</h3>
                    </div>

                    {{-- Kelas --}}
                    <div class="item-stat bg-white rounded-2xl p-5">
                        <div class="flex items-center justify-between mb-6">
                            <div class="bg-blue-600 rounded-full p-3">
                                <x-heroicon-s-academic-cap class="h-6 w-6 text-white" />
                            </div>
                            <span class="text-sm font-semibold text-gray-400">Kelas</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Tingkat Terakhir</p>
                        <h3 class="text-md font-bold text-blue-900">
                            {{ optional(optional($enrollments->last())->room)->name ?? '-' }}
                        </h3>
                    </div>

                </div>

            </div>
            <div class="pt-4 grid grid-cols-1 gap-y-3 pb-10">
                <h3 class="text-xl font-semibold text-blue-950">
                    Grafik Nilai
                </h3>
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="relative h-[355px] w-full bg-white rounded-xl">
                        <canvas id="progressChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-5 leading-relaxed">
                Untuk menjaga keamanan dan kenyamanan akun Anda, pastikan data diri yang ditampilkan di bawah ini
                sudah sesuai.
                <br><br>
                <span class="font-medium text-blue-800">• Perbarui email Anda</span> apabila masih menggunakan email
                lama atau tidak aktif, agar Anda tetap bisa menerima informasi penting dari sistem.
                <br>
                <span class="font-medium text-green-700">• Verifikasi email Anda</span> jika belum dilakukan, guna
                memastikan akun Anda aktif dan dapat digunakan dengan optimal.
                <br>
                <span class="font-medium text-red-700">• Ganti password secara berkala</span> dengan kombinasi yang
                kuat dan tidak mudah ditebak. Hindari menggunakan tanggal lahir atau nama sendiri sebagai password.
                <br><br>
                Jika Anda memerlukan bantuan dalam mengganti data, silakan hubungi admin atau wali kelas.
            </p>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('progressChart').getContext('2d');

        const labels = {!! json_encode($progressData->pluck('label')) !!};
        const scores = {!! json_encode($progressData->pluck('score')) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nilai Akhir',
                    data: scores,
                    backgroundColor: '#2563eb', // Warna biru blue-600
                    borderRadius: 6,
                    barThickness: 28
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 10
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#4B5563', // slate-600
                            font: {
                                size: 12,
                                family: 'Inter, sans-serif'
                            }
                        },
                        grid: {
                            color: '#E5E7EB' // gray-200
                        }
                    },
                    x: {
                        ticks: {
                            color: '#4B5563', // slate-600
                            font: {
                                size: 6,
                                family: 'Inter, sans-serif'
                            },
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#111827', // gray-900
                        titleFont: {
                            size: 14,
                            family: 'Inter, sans-serif'
                        },
                        bodyFont: {
                            size: 12,
                            family: 'Inter, sans-serif'
                        },
                        padding: 10,
                        cornerRadius: 6
                    }
                }
            }
        });
    </script>
</x-app-layout>
