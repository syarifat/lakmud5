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
                                    <th scope="col" class="px-6 py-4 text-center w-32">Pemahaman (0-100)</th>
                                    <th scope="col" class="px-6 py-4 text-center w-32">Kedisiplinan (0-100)</th>
                                    <th scope="col" class="px-6 py-4 text-center w-32">Keaktifan (0-100)</th>
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
                                                <input type="number" name="pemahaman[{{ $p->id }}]" min="0" max="100" required
                                                    value="{{ $valPemahaman }}"
                                                    class="input-score w-20 text-center text-sm font-bold rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500 py-1.5"
                                                    placeholder="0">
                                            </td>

                                            <!-- Kedisiplinan -->
                                            <td class="px-6 py-4 text-center">
                                                <input type="number" name="kedisiplinan[{{ $p->id }}]" min="0" max="100" required
                                                    value="{{ $valKedisiplinan }}"
                                                    class="input-score w-20 text-center text-sm font-bold rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500 py-1.5"
                                                    placeholder="0">
                                            </td>

                                            <!-- Keaktifan -->
                                            <td class="px-6 py-4 text-center">
                                                <input type="number" name="keaktifan[{{ $p->id }}]" min="0" max="100" required
                                                    value="{{ $valKeaktifan }}"
                                                    class="input-score w-20 text-center text-sm font-bold rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500 py-1.5"
                                                    placeholder="0">
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
