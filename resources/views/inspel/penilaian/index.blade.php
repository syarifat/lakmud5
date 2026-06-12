<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Penilaian Akademik Peserta') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Lembar Penilaian Akademik Peserta</h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Sebagai Inspel, Anda bertugas memberikan penilaian akademik kepada seluruh peserta pada setiap sesi materi. Penilaian ini meliputi tiga aspek utama: **Pemahaman Materi**, **Kedisiplinan di Kelas**, dan **Keaktifan Forum**. Nilai akhir (rata-rata) akan dikalkulasi secara otomatis oleh sistem.
                </p>
            </div>

            <!-- Schedules Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($jadwals->isEmpty())
                    <div class="md:col-span-2 text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm text-gray-500 font-medium">
                        Belum ada jadwal sesi materi yang dapat dinilai.
                    </div>
                @else
                    @foreach($jadwals as $j)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $j->materi->nama_materi }}</h4>
                                        <p class="text-xs text-gray-500 mt-1">Pemateri: <span class="font-bold text-rose-800">{{ $j->pemateri->nama }}</span></p>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-800 text-xs font-bold rounded-full border border-rose-100">
                                        {{ $j->peserta_dinilai_count }} / {{ $j->total_peserta }} Dinilai
                                    </span>
                                </div>

                                <div class="text-xs text-gray-500 font-medium mb-6 flex items-center gap-1 bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                    <svg class="w-4 h-4 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Jadwal: {{ \Carbon\Carbon::parse($j->waktu_mulai)->translatedFormat('l, d M Y') }} ({{ \Carbon\Carbon::parse($j->waktu_mulai)->format('H:i') }} WIB)
                                </div>
                            </div>

                            <a href="{{ route('inspel.penilaian.create', ['jadwal_id' => $j->id]) }}" 
                                class="w-full text-center bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-2.5 rounded-xl transition shadow-sm hover:shadow">
                                {{ $j->peserta_dinilai_count > 0 ? 'Perbarui / Edit Nilai' : 'Input Nilai Peserta' }}
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
