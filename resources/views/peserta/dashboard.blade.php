<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Peserta LAKMUD V') }}
            </h2>
            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold uppercase tracking-wider rounded-full">
                Peserta Lolos
            </span>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('status'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Welcome & Group Info Card -->
            <div class="bg-gradient-to-r from-emerald-800 to-teal-700 text-white rounded-2xl shadow-xl overflow-hidden relative">
                <div class="absolute -right-16 -top-16 w-48 h-48 rounded-full bg-white opacity-5"></div>
                <div class="absolute -left-12 -bottom-12 w-36 h-36 rounded-full bg-emerald-900 opacity-20"></div>
                
                <div class="p-8 relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h3 class="text-2xl font-extrabold mb-1">Selamat Datang Rekan/Rekanita, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-emerald-100 text-sm max-w-2xl">
                            Selamat mengikuti rangkaian kegiatan Latihan Kader Muda V PAC IPNU IPPNU Kauman. Gunakan portal peserta ini untuk absensi, pengisian evaluasi harian, pemberian rating pemateri, dan pengerjaan tes CBT.
                        </p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-5 border border-white/20 min-w-[240px]">
                        <span class="text-xs uppercase tracking-wider text-emerald-200 block mb-1">Informasi Kelompok</span>
                        @if($kelompok)
                            <h4 class="text-lg font-bold text-white">{{ $kelompok->nama_kelompok }}</h4>
                            <div class="mt-2 text-xs text-emerald-100 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Pendamping: <span class="font-semibold">{{ $kelompok->pendamping->name }}</span>
                            </div>
                        @else
                            <h4 class="text-sm font-semibold text-emerald-200">Belum Diploting</h4>
                            <span class="text-[10px] text-emerald-300 block">Menunggu pembagian kelompok oleh panitia.</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Progress Grid Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Kehadiran -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Persentase Kehadiran</span>
                        <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-3xl font-extrabold text-gray-900">{{ $stats['kehadiran_persen'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $stats['kehadiran_persen'] }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Evaluasi Refleksi -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Refleksi Harian</span>
                        <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-3xl font-extrabold text-gray-900">{{ $stats['refleksi_selesai'] }}/4</span>
                        <p class="text-xs text-gray-500 mt-1">Hari evaluasi terisi</p>
                    </div>
                </div>

                <!-- Rating Pemateri -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Penilaian Pemateri</span>
                        <div class="p-2 rounded-lg bg-amber-50 text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-3xl font-extrabold text-gray-900">{{ $stats['nilai_pemateri'] }}</span>
                        <p class="text-xs text-gray-500 mt-1">Pemateri telah dinilai</p>
                    </div>
                </div>

                <!-- Rating Inspel -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Penilaian Inspel</span>
                        <div class="p-2 rounded-lg bg-rose-50 text-rose-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-3xl font-extrabold text-gray-900">{{ $stats['nilai_inspel'] }}</span>
                        <p class="text-xs text-gray-500 mt-1">Inspel telah dinilai</p>
                    </div>
                </div>
            </div>

            <!-- Hari Ini & Quick Access -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Jadwal Sesi Hari Ini -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-2">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h4 class="text-lg font-bold text-gray-800">Jadwal Sesi Hari Ini</h4>
                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                    </div>

                    <div class="p-6">
                        @if($jadwalHariIni->isEmpty())
                            <div class="text-center py-12">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-gray-500 font-medium">Tidak ada jadwal sesi materi untuk hari ini.</p>
                                <p class="text-xs text-gray-400 mt-1">Periksa secara berkala atau hubungi instruktur/admin.</p>
                            </div>
                        @else
                            <div class="relative border-l-2 border-emerald-200 ml-4 space-y-8 py-2">
                                @foreach($jadwalHariIni as $j)
                                    <div class="relative pl-6">
                                        <!-- Node icon indicator -->
                                        <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full border-2 border-white {{ $j->is_hadir ? 'bg-emerald-500 shadow-[0_0_0_4px_rgba(16,185,129,0.2)]' : 'bg-gray-300' }}"></div>
                                        
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100 hover:border-emerald-200 transition">
                                            <div>
                                                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full block w-max mb-1">
                                                    {{ \Carbon\Carbon::parse($j->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->waktu_selesai)->format('H:i') }} WIB
                                                </span>
                                                <h5 class="font-bold text-gray-900 text-base">{{ $j->materi->nama_materi }}</h5>
                                                <p class="text-xs text-gray-500 mt-1">Pemateri: <span class="font-medium text-gray-700">{{ $j->pemateri->nama }}</span></p>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                @if($j->is_hadir)
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full">
                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                        Hadir
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold rounded-full">
                                                        Belum Absen
                                                    </span>
                                                    
                                                    <form method="POST" action="{{ route('peserta.absensi.tap') }}">
                                                        @csrf
                                                        <input type="hidden" name="jadwal_id" value="{{ $j->id }}">
                                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-3.5 py-1.5 rounded-lg transition shadow-sm">
                                                            Simulasi Tap
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions Menu -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                    <div>
                        <h4 class="text-lg font-bold text-gray-800 mb-6">Akses Cepat Fitur</h4>
                        <div class="space-y-4">
                            <a href="{{ route('peserta.absensi') }}" class="flex items-center p-3.5 rounded-xl bg-gray-50 border border-gray-100 hover:bg-emerald-50 hover:border-emerald-200 transition group">
                                <div class="p-2.5 rounded-lg bg-emerald-100 text-emerald-800 group-hover:bg-emerald-600 group-hover:text-white transition mr-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-bold text-sm text-gray-900 group-hover:text-emerald-800">Riwayat Kehadiran</p>
                                    <p class="text-xs text-gray-500">Lihat total presensi kegiatan</p>
                                </div>
                            </a>

                            <a href="{{ route('peserta.nilai-pemateri') }}" class="flex items-center p-3.5 rounded-xl bg-gray-50 border border-gray-100 hover:bg-amber-50 hover:border-amber-200 transition group">
                                <div class="p-2.5 rounded-lg bg-amber-100 text-amber-800 group-hover:bg-amber-600 group-hover:text-white transition mr-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-bold text-sm text-gray-900 group-hover:text-amber-800">Evaluasi Pemateri</p>
                                    <p class="text-xs text-gray-500">Beri rating pemateri tiap sesi</p>
                                </div>
                            </a>

                            <a href="{{ route('peserta.nilai-inspel') }}" class="flex items-center p-3.5 rounded-xl bg-gray-50 border border-gray-100 hover:bg-rose-50 hover:border-rose-200 transition group">
                                <div class="p-2.5 rounded-lg bg-rose-100 text-rose-800 group-hover:bg-rose-600 group-hover:text-white transition mr-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-bold text-sm text-gray-900 group-hover:text-rose-800">Evaluasi Inspel</p>
                                    <p class="text-xs text-gray-500">Beri nilai kinerja Inspel harian</p>
                                </div>
                            </a>

                            <a href="{{ route('peserta.refleksi') }}" class="flex items-center p-3.5 rounded-xl bg-gray-50 border border-gray-100 hover:bg-indigo-50 hover:border-indigo-200 transition group">
                                <div class="p-2.5 rounded-lg bg-indigo-100 text-indigo-800 group-hover:bg-indigo-600 group-hover:text-white transition mr-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-bold text-sm text-gray-900 group-hover:text-indigo-800">Refleksi Harian</p>
                                    <p class="text-xs text-gray-500">Isi 6 pertanyaan esai harian</p>
                                </div>
                            </a>

                            <a href="{{ route('peserta.ujian') }}" class="flex items-center p-3.5 rounded-xl bg-gray-50 border border-gray-100 hover:bg-purple-50 hover:border-purple-200 transition group">
                                <div class="p-2.5 rounded-lg bg-purple-100 text-purple-800 group-hover:bg-purple-600 group-hover:text-white transition mr-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-bold text-sm text-gray-900 group-hover:text-purple-800">Ujian CBT (Pre/Post)</p>
                                    <p class="text-xs text-gray-500">Ujian pretest dan posttest materi</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
