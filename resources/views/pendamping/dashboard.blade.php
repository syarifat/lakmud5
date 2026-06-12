<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Pendamping Kelompok') }}
            </h2>
            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold uppercase tracking-wider rounded-full">
                Pendamping / Fasilitator
            </span>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Welcome Message -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 font-sans">Selamat Datang Pendamping, {{ Auth::user()->name }}! 👋</h3>
                <p class="text-sm text-gray-600 mt-1">
                    Anda bertugas melakukan pendampingan kelompok secara intensif, memantau kehadiran absensi anggota kelompok Anda, serta mengisi lembar observasi sikap/kemampuan harian (skala 1-5) dan memberikan catatan perkembangan individu.
                </p>
            </div>

            <!-- Managed Groups & Members -->
            <div class="space-y-6">
                <h3 class="font-extrabold text-gray-900 text-xl tracking-tight">Kelompok Bimbingan Anda</h3>

                @if($kelompoks->isEmpty())
                    <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm text-gray-500 font-medium">
                        Anda belum diploting untuk mendampingi kelompok mana pun.
                    </div>
                @else
                    @foreach($kelompoks as $kel)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-6 bg-emerald-850 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <h4 class="text-xl font-extrabold">{{ $kel->nama_kelompok }}</h4>
                                    <p class="text-xs text-emerald-250 mt-1">Total Anggota Kelompok: <span class="font-bold">{{ $kel->pesertas->count() }} Orang</span></p>
                                </div>
                                <a href="{{ route('pendamping.observasi') }}" class="bg-white hover:bg-emerald-50 text-emerald-800 font-bold text-xs px-4 py-2.5 rounded-xl transition shadow-sm self-start sm:self-auto">
                                    Input Observasi Harian
                                </a>
                            </div>

                            <div class="p-6">
                                <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Daftar Anggota & Perkembangan Terakhir</h5>
                                
                                @if($kel->pesertas->isEmpty())
                                    <p class="text-sm text-gray-500 italic">Belum ada anggota yang dimasukkan ke kelompok ini.</p>
                                @else
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        @foreach($kel->pesertas as $p)
                                            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 hover:shadow transition flex flex-col justify-between">
                                                <div class="space-y-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm">
                                                            {{ substr($p->name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <h6 class="font-bold text-gray-900 text-sm leading-tight">{{ $p->name }}</h6>
                                                            <p class="text-[10px] text-gray-400 font-mono">{{ $p->email }}</p>
                                                        </div>
                                                    </div>

                                                    <hr class="border-gray-200">

                                                    <!-- Observasi Terakhir -->
                                                    <div class="space-y-2">
                                                        <div class="flex justify-between text-xs">
                                                            <span class="text-gray-500 font-medium">Observasi Terakhir:</span>
                                                            @if($p->latest_observasi)
                                                                <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">Hari Ke-{{ $p->latest_observasi->hari_ke }} (Skor: {{ number_format($p->latest_observasi->nilai_angka, 1) }}/5)</span>
                                                            @else
                                                                <span class="text-gray-400 italic">Belum ada observasi</span>
                                                            @endif
                                                        </div>
                                                        
                                                        @if($p->latest_observasi && $p->latest_observasi->catatan)
                                                            <div class="text-xs text-gray-650 bg-white p-3 rounded-lg border border-gray-100 leading-relaxed font-medium">
                                                                📝 <span class="italic">"{{ $p->latest_observasi->catatan }}"</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
