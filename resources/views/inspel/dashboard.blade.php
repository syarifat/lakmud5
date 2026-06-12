<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Instruktur (Inspel)') }}
            </h2>
            <span class="px-3 py-1 bg-rose-100 text-rose-800 text-xs font-semibold uppercase tracking-wider rounded-full">
                Inspel Pelatihan
            </span>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Welcome Message -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900">Selamat Datang Inspel, {{ Auth::user()->name }}! 🎓</h3>
                <p class="text-sm text-gray-600 mt-1">
                    Anda bertugas memonitor presensi kelas secara real-time, memberikan penilaian akademik terhadap peserta, meninjau lembar refleksi harian, serta memantau profil pemateri LAKMUD V.
                </p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Materi</span>
                    <h4 class="text-3xl font-extrabold text-gray-950 mt-2">{{ $generalStats['total_materi'] }}</h4>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pemateri</span>
                    <h4 class="text-3xl font-extrabold text-gray-950 mt-2">{{ $generalStats['total_pemateri'] }}</h4>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Peserta</span>
                    <h4 class="text-3xl font-extrabold text-gray-950 mt-2">{{ $generalStats['total_peserta'] }}</h4>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Refleksi Dikirim</span>
                    <h4 class="text-3xl font-extrabold text-gray-950 mt-2">{{ $generalStats['total_refleksi'] }}</h4>
                </div>
            </div>

            <!-- Chart/Analysis Rata-rata Pemahaman Peserta -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 text-lg">Rata-rata Pemahaman Peserta Per Materi</h3>
                    <p class="text-xs text-gray-500 mt-1">Grafik rata-rata nilai pemahaman peserta yang Anda inputkan di kelas</p>
                </div>

                <div class="p-6 space-y-6">
                    @if($stats->isEmpty())
                        <div class="text-center py-12 text-gray-500 font-medium">
                            Belum ada data materi untuk dianalisis.
                        </div>
                    @else
                        @foreach($stats as $s)
                            <div class="space-y-2">
                                <div class="flex justify-between items-center text-sm font-semibold">
                                    <span class="text-gray-700">{{ $s->nama_materi }}</span>
                                    <span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md">{{ number_format($s->avg_pemahaman, 1) }} / 100</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-3">
                                    <div class="bg-rose-500 h-3 rounded-full transition-all duration-500" style="width: {{ $s->avg_pemahaman }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
