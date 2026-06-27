<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Kehadiran & Absensi Peserta') }}
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

            <!-- Info Card & Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Instruction Box -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:col-span-2 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Panduan Absensi RFID LAKMUD</h3>
                        <p class="text-sm text-gray-600 leading-relaxed mb-4">
                            Metode presensi utama dilakukan menggunakan **Kartu RFID** yang dipasang pada ID Card Anda. Silakan tempelkan kartu pada alat *RFID Reader* yang dibawa oleh panitia atau diletakkan di meja depan sebelum sesi materi dimulai.
                        </p>
                        <p class="text-xs text-amber-600 bg-amber-50 p-3 rounded-lg border border-amber-200 font-medium">
                            💡 <strong>Catatan:</strong> Jika sistem RFID mengalami kendala teknis atau presensi Anda belum tercatat, silakan laporkan langsung ke panitia atau pendamping agar kehadiran Anda dapat dibantu konfirmasi secara manual.
                        </p>
                    </div>
                </div>

                <!-- Stats summary -->
                <div class="bg-gradient-to-br from-emerald-800 to-teal-700 text-white rounded-2xl shadow-md p-6 flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-emerald-200">Kehadiran Presensi</h4>
                        <div class="mt-4 flex items-baseline gap-2">
                            @php
                                $totalJadwal = count($jadwals);
                                $totalHadir = $jadwals->where('is_hadir', true)->count();
                                $persen = $totalJadwal > 0 ? round(($totalHadir / $totalJadwal) * 100) : 0;
                            @endphp
                            <span class="text-4xl font-extrabold">{{ $totalHadir }}</span>
                            <span class="text-emerald-300 text-sm">dari {{ $totalJadwal }} Sesi</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <div class="flex justify-between text-xs text-emerald-100 mb-1">
                            <span>Progres Presensi</span>
                            <span>{{ $persen }}%</span>
                        </div>
                        <div class="w-full bg-emerald-900/50 rounded-full h-2">
                            <div class="bg-emerald-400 h-2 rounded-full" style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List of Sessions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 text-lg">Daftar Sesi Kegiatan & Absensi</h3>
                </div>

                @if($jadwals->isEmpty())
                    <div class="text-center py-16">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-gray-500 font-medium">Belum ada jadwal sesi materi yang diinput oleh Admin.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Materi & Pemateri</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jadwal Sesi</th>
                                    <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($jadwals as $index => $j)
                                    <tr class="hover:bg-gray-50/80 transition">
                                        <!-- No -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-400">
                                            {{ $index + 1 }}
                                        </td>
                                        <!-- Materi -->
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $j->materi->nama_materi }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">Pemateri: <span class="font-medium text-gray-700">{{ $j->pemateri->nama }}</span></div>
                                        </td>
                                        <!-- Jadwal -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 font-medium">
                                                {{ \Carbon\Carbon::parse($j->waktu_mulai)->translatedFormat('l, d M Y') }}
                                            </div>
                                            <div class="text-xs text-emerald-700 font-semibold mt-0.5">
                                                {{ \Carbon\Carbon::parse($j->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->waktu_selesai)->format('H:i') }} WIB
                                            </div>
                                        </td>
                                        <!-- Status -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($j->is_hadir)
                                                <span class="inline-flex flex-col items-center">
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                        Hadir
                                                    </span>
                                                    <span class="text-[10px] text-gray-400 mt-1">
                                                        Tap: {{ \Carbon\Carbon::parse($j->waktu_absen)->format('H:i:s') }}
                                                    </span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold rounded-full">
                                                    Belum Hadir
                                                </span>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
