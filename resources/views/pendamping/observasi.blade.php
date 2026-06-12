<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Lembar Observasi Harian Kelompok') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Penilaian Sikap & Observasi Peserta</h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Sebagai pendamping kelompok, Anda diharapkan memantau perkembangan kedisiplinan, kemampuan, dan keaktifan anggota kelompok secara harian. Gunakan **skala 1 s.d. 5** untuk setiap aspek penilaian. Anda juga dapat menambahkan catatan perkembangan khusus untuk tiap peserta.
                </p>
            </div>

            <!-- Tab Day Selector (Day 1 - 4) -->
            <div class="flex border-b border-gray-200 bg-white rounded-t-2xl shadow-sm overflow-hidden">
                @for($day = 1; $day <= 4; $day++)
                    <a href="{{ route('pendamping.observasi', ['hari_ke' => $day]) }}" 
                        class="flex-1 py-4 text-center text-sm font-bold border-b-2 transition 
                        {{ $selectedDay == $day ? 'border-emerald-600 text-emerald-800 bg-emerald-50/20' : 'border-transparent text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                        Hari Ke-{{ $day }}
                    </a>
                @endfor
            </div>

            <!-- Form Penilaian Observasi -->
            <form method="POST" action="{{ route('pendamping.observasi.store') }}">
                @csrf
                <input type="hidden" name="hari_ke" value="{{ $selectedDay }}">

                <div class="bg-white rounded-b-2xl shadow-sm border-x border-b border-gray-100 p-6 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="observasiTable">
                            <thead>
                                <tr class="text-left text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-50">
                                    <th scope="col" class="px-6 py-4 w-12">No</th>
                                    <th scope="col" class="px-6 py-4">Nama Anggota</th>
                                    <th scope="col" class="px-6 py-4 text-center w-28">Kedisiplinan (1-5)</th>
                                    <th scope="col" class="px-6 py-4 text-center w-28">Kemampuan (1-5)</th>
                                    <th scope="col" class="px-6 py-4 text-center w-28">Keaktifan (1-5)</th>
                                    <th scope="col" class="px-6 py-4">Catatan Perkembangan</th>
                                    <th scope="col" class="px-6 py-4 text-center w-28 bg-gray-100/50">Nilai Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @if($pesertas->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center py-12 text-gray-500 font-medium italic">
                                            Belum ada anggota yang terploting di kelompok bimbingan Anda.
                                        </td>
                                    </tr>
                                @else
                                    @foreach($pesertas as $index => $p)
                                        @php
                                            $obs = $observasiExist->get($p->id);
                                            $valKedisiplinan = $obs ? $obs->kedisiplinan : '';
                                            $valKemampuan = $obs ? $obs->kemampuan : '';
                                            $valKeaktifan = $obs ? $obs->keaktifan : '';
                                            $valCatatan = $obs ? $obs->catatan : '';
                                            $valRerata = $obs ? number_format($obs->nilai_angka, 1) : '-';
                                        @endphp
                                        <tr class="hover:bg-gray-50/40 transition align-middle" data-peserta-id="{{ $p->id }}">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-400">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900">{{ $p->name }}</div>
                                                <div class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $p->email }}</div>
                                            </td>
                                            
                                            <!-- Kedisiplinan -->
                                            <td class="px-6 py-4 text-center">
                                                <select name="kedisiplinan[{{ $p->id }}]" required
                                                    class="input-score text-center text-sm font-semibold rounded-lg border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 py-1">
                                                    <option value="">-</option>
                                                    @for($score = 1; $score <= 5; $score++)
                                                        <option value="{{ $score }}" {{ $valKedisiplinan == $score ? 'selected' : '' }}>{{ $score }}</option>
                                                    @endfor
                                                </select>
                                            </td>

                                            <!-- Kemampuan -->
                                            <td class="px-6 py-4 text-center">
                                                <select name="kemampuan[{{ $p->id }}]" required
                                                    class="input-score text-center text-sm font-semibold rounded-lg border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 py-1">
                                                    <option value="">-</option>
                                                    @for($score = 1; $score <= 5; $score++)
                                                        <option value="{{ $score }}" {{ $valKemampuan == $score ? 'selected' : '' }}>{{ $score }}</option>
                                                    @endfor
                                                </select>
                                            </td>

                                            <!-- Keaktifan -->
                                            <td class="px-6 py-4 text-center">
                                                <select name="keaktifan[{{ $p->id }}]" required
                                                    class="input-score text-center text-sm font-semibold rounded-lg border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 py-1">
                                                    <option value="">-</option>
                                                    @for($score = 1; $score <= 5; $score++)
                                                        <option value="{{ $score }}" {{ $valKeaktifan == $score ? 'selected' : '' }}>{{ $score }}</option>
                                                    @endfor
                                                </select>
                                            </td>

                                            <!-- Catatan -->
                                            <td class="px-6 py-4">
                                                <textarea name="catatan[{{ $p->id }}]" rows="1" 
                                                    class="w-full text-xs rounded-lg border-gray-250 focus:border-emerald-500 focus:ring-emerald-500 placeholder-gray-300"
                                                    placeholder="Catatan perkembangan khusus...">{{ $valCatatan }}</textarea>
                                            </td>

                                            <!-- Rerata -->
                                            <td class="px-6 py-4 text-center bg-gray-50/60 text-sm font-extrabold text-emerald-800 font-mono label-rerata">
                                                {{ $valRerata }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if(!$pesertas->isEmpty())
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm px-6 py-3 rounded-xl transition shadow-sm hover:shadow">
                                Simpan Observasi Hari Ke-{{ $selectedDay }}
                            </button>
                        </div>
                    @endif
                </div>
            </form>

        </div>
    </div>

    <!-- Client-side Real-time Average calculation script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#observasiTable tbody tr');
            
            rows.forEach(row => {
                const selects = row.querySelectorAll('.input-score');
                const labelRerata = row.querySelector('.label-rerata');

                const updateAverage = () => {
                    let total = 0;
                    let count = 0;
                    selects.forEach(select => {
                        const val = parseInt(select.value);
                        if (!isNaN(val)) {
                            total += val;
                            count++;
                        }
                    });

                    if (count === selects.length) {
                        const avg = total / count;
                        labelRerata.textContent = avg.toFixed(1);
                    } else {
                        labelRerata.textContent = '-';
                    }
                };

                selects.forEach(select => {
                    select.addEventListener('change', updateAverage);
                });
            });
        });
    </script>
</x-app-layout>
