<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('inspel.penilaian') }}" class="text-gray-400 hover:text-gray-650 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Input Nilai: ') }} {{ $jadwal->materi->nama_materi }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Detail Sesi -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-2">
                <span class="text-xs font-extrabold uppercase tracking-widest text-rose-600 block">Detail Sesi Materi</span>
                <h3 class="text-lg font-bold text-gray-900 leading-none">{{ $jadwal->materi->nama_materi }}</h3>
                <p class="text-sm text-gray-600">
                    Pemateri: <span class="font-semibold text-gray-800">{{ $jadwal->pemateri->nama }}</span> &bull; 
                    Waktu Sesi: <span class="font-semibold text-gray-850">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->translatedFormat('l, d F Y') }} ({{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} WIB)</span>
                </p>
            </div>

            <!-- Instrumen Penilaian Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                    <div class="p-1.5 bg-rose-50 text-rose-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-800">Instrumen Penilaian</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="font-extrabold text-gray-900 block mb-1">📚 Pemahaman</span>
                        <span class="text-gray-600 text-xs">Evaluasi materi (soal, resume)</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="font-extrabold text-gray-900 block mb-1">⏱️ Kedisiplinan</span>
                        <span class="text-gray-600 text-xs">Absensi selama kegiatan, sikap (khusus LAKMUD)</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="font-extrabold text-gray-900 block mb-1">💬 Keaktifan</span>
                        <span class="text-gray-600 text-xs">Bertanya, menjawab, menanggapi, dll.</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="font-extrabold text-gray-900 block mb-1">📊 Nilai Rerata</span>
                        <span class="text-gray-600 text-xs">Hasil penjumlahan 3 unsur penilaian dibagi 3</span>
                    </div>
                    <div class="p-3 bg-rose-50/50 rounded-xl border border-rose-100">
                        <span class="font-extrabold text-rose-900 block mb-1">🎯 Skala Nilai</span>
                        <span class="text-rose-700 text-xs font-semibold">Rentang nilai: 70 - 100</span>
                    </div>
                </div>
            </div>

            <!-- Formulir Penilaian Massal -->
            <form method="POST" action="{{ route('inspel.penilaian.store') }}">
                @csrf
                <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="penilaianTable">
                            <thead>
                                <tr class="bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    <th scope="col" class="px-6 py-4 w-12">No</th>
                                    <th scope="col" class="px-6 py-4">Nama Peserta</th>
                                    <th scope="col" class="px-6 py-4 text-center w-32">Pemahaman (70-100)</th>
                                    <th scope="col" class="px-6 py-4 text-center w-32">Kedisiplinan (70-100)</th>
                                    <th scope="col" class="px-6 py-4 text-center w-32">Keaktifan (70-100)</th>
                                    <th scope="col" class="px-6 py-4 text-center w-32 bg-gray-100/50">Rerata Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @if($pesertas->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center py-10 text-gray-500 text-sm font-medium">
                                            Tidak ada peserta terdaftar.
                                        </td>
                                    </tr>
                                @else
                                    @foreach($pesertas as $index => $p)
                                        @php
                                            $n = $nilaiExist->get($p->id);
                                            $valPemahaman = $n ? $n->pemahaman : '';
                                            $valKedisiplinan = $n ? $n->kedisiplinan : '';
                                            $valKeaktifan = $n ? $n->keaktifan : '';
                                            $valRerata = $n ? number_format($n->rerata, 1) : '-';
                                        @endphp
                                        <tr class="hover:bg-gray-50/40 transition align-middle" data-peserta-id="{{ $p->id }}">
                                            <!-- No -->
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-400">
                                                {{ $index + 1 }}
                                            </td>
                                            <!-- Nama -->
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-gray-950">{{ $p->name }}</div>
                                                <div class="text-[10px] text-gray-400 uppercase font-bold mt-0.5">{{ $p->email }}</div>
                                            </td>
                                            
                                            <!-- Pemahaman -->
                                            <td class="px-6 py-4 text-center">
                                                <input type="number" name="pemahaman[{{ $p->id }}]" min="70" max="100" required
                                                    value="{{ $valPemahaman }}"
                                                    class="input-score w-20 text-center text-sm font-bold rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500 py-1.5"
                                                    placeholder="70">
                                            </td>

                                            <!-- Kedisiplinan -->
                                            <td class="px-6 py-4 text-center">
                                                <input type="number" name="kedisiplinan[{{ $p->id }}]" min="70" max="100" required
                                                    value="{{ $valKedisiplinan }}"
                                                    class="input-score w-20 text-center text-sm font-bold rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500 py-1.5"
                                                    placeholder="70">
                                            </td>

                                            <!-- Keaktifan -->
                                            <td class="px-6 py-4 text-center">
                                                <input type="number" name="keaktifan[{{ $p->id }}]" min="70" max="100" required
                                                    value="{{ $valKeaktifan }}"
                                                    class="input-score w-20 text-center text-sm font-bold rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500 py-1.5"
                                                    placeholder="70">
                                            </td>

                                            <!-- Rerata -->
                                            <td class="px-6 py-4 text-center bg-gray-50/60 text-sm font-extrabold text-rose-800 font-mono label-rerata">
                                                {{ $valRerata }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                        <a href="{{ route('inspel.penilaian') }}" class="text-sm font-bold text-gray-500 hover:text-gray-750 transition">
                            Kembali
                        </a>
                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm px-6 py-3 rounded-xl transition shadow-sm hover:shadow">
                            Simpan Semua Penilaian
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- Client-side Real-time Average calculation script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#penilaianTable tbody tr');
            
            rows.forEach(row => {
                const inputs = row.querySelectorAll('.input-score');
                const labelRerata = row.querySelector('.label-rerata');

                const updateAverage = () => {
                    let total = 0;
                    let count = 0;
                    inputs.forEach(input => {
                        const val = parseInt(input.value);
                        if (!isNaN(val)) {
                            total += val;
                            count++;
                        }
                    });

                    if (count === inputs.length) {
                        const avg = total / count;
                        labelRerata.textContent = avg.toFixed(1);
                    } else {
                        labelRerata.textContent = '-';
                    }
                };

                inputs.forEach(input => {
                    input.addEventListener('input', updateAverage);
                });
            });
        });
    </script>
</x-app-layout>
