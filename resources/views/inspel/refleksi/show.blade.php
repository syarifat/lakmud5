<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('inspel.refleksi') }}" class="text-gray-400 hover:text-gray-650 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Refleksi: ') }} {{ $refleksi->peserta->name }} &bull; Hari {{ $refleksi->hari_ke }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Detail Box -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $refleksi->peserta->name }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Hari Evaluasi: <span class="font-bold text-rose-800">Hari Ke-{{ $refleksi->hari_ke }}</span></p>
                </div>
                <span class="text-xs text-rose-600 bg-white border border-rose-100 px-3 py-1.5 rounded-lg shadow-sm font-semibold">
                    Tanggal Pengisian: {{ \Carbon\Carbon::parse($refleksi->tanggal)->translatedFormat('d M Y') }}
                </span>
            </div>

            <!-- Answers -->
            <div class="space-y-6">
                <!-- Q1 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-2">1. Pengalaman belajar yang paling bermanfaat bagi perkembangan diri</h4>
                    <p class="text-sm text-gray-800 leading-relaxed font-medium bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                        {!! nl2br(e($refleksi->q1_pengalaman)) !!}
                    </p>
                </div>

                <!-- Q2 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-2">2. Tingkat partisipasi dalam pelatihan hari ini</h4>
                    <p class="text-sm text-gray-800 leading-relaxed font-medium bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                        {!! nl2br(e($refleksi->q2_partisipasi)) !!}
                    </p>
                </div>

                <!-- Q3 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-2">3. Hal yang menghambat atau mendorong untuk berpartisipasi</h4>
                    <p class="text-sm text-gray-800 leading-relaxed font-medium bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                        {!! nl2br(e($refleksi->q3_hambatan_dorongan)) !!}
                    </p>
                </div>

                <!-- Q4 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-2">4. Kesempatan mengemukakan pendapat, ide, pikiran</h4>
                    <p class="text-sm text-gray-800 leading-relaxed font-medium bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                        {!! nl2br(e($refleksi->q4_kesempatan_pendapat)) !!}
                    </p>
                </div>

                <!-- Q5 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-2">5. Pengetahuan baru yang didapatkan hari ini</h4>
                    <p class="text-sm text-gray-800 leading-relaxed font-medium bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                        {!! nl2br(e($refleksi->q5_pengetahuan_didapat)) !!}
                    </p>
                </div>

                <!-- Q6 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-2">6. Hambatan internal diri sendiri dalam mengikuti latihan</h4>
                    <p class="text-sm text-gray-800 leading-relaxed font-medium bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                        {!! nl2br(e($refleksi->q6_hambatan_diri_sendiri)) !!}
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
