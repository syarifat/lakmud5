<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Absensi Peserta') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Schedule Select Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <form method="GET" action="{{ route('inspel.absensi') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-3">
                        <label for="jadwal_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pilih Sesi Materi</label>
                        <select id="jadwal_id" name="jadwal_id" required
                            class="w-full text-sm rounded-xl border-gray-200 focus:border-rose-500 focus:ring-rose-500">
                            <option value="">-- Pilih Sesi Kegiatan --</option>
                            @foreach($jadwals as $j)
                                <option value="{{ $j->id }}" {{ request('jadwal_id') == $j->id ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($j->waktu_mulai)->translatedFormat('l, d M') }} - 
                                    {{ $j->materi->nama_materi }} ({{ $j->pemateri->nama }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-2.5 rounded-xl transition shadow-sm">
                            Tampilkan Monitor
                        </button>
                    </div>
                </form>
            </div>

            @if($selectedJadwal)
                <!-- Attendance Stats & Session Detail -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:col-span-2 space-y-3">
                        <span class="text-xs font-extrabold uppercase tracking-widest text-rose-600">Sesi Terpilih</span>
                        <h3 class="text-lg font-bold text-gray-900">{{ $selectedJadwal->materi->nama_materi }}</h3>
                        <div class="text-sm text-gray-600 space-y-1">
                            <div>🎙️ Pemateri: <span class="font-semibold text-gray-800">{{ $selectedJadwal->pemateri->nama }}</span></div>
                            <div>📅 Waktu: <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($selectedJadwal->waktu_mulai)->translatedFormat('l, d F Y') }} ({{ \Carbon\Carbon::parse($selectedJadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($selectedJadwal->waktu_selesai)->format('H:i') }} WIB)</span></div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-rose-800 to-rose-650 text-white rounded-2xl shadow-md p-6 flex flex-col justify-between">
                        <div>
                            <span class="text-xs uppercase tracking-wider text-rose-200 block">Status Kehadiran Kelas</span>
                            @php
                                $total = count($pesertaAbsen);
                                $hadir = count(array_filter($pesertaAbsen, fn($p) => $p->is_hadir));
                                $persen = $total > 0 ? round(($hadir / $total) * 100) : 0;
                            @endphp
                            <div class="mt-4 flex items-baseline gap-2">
                                <span class="text-4xl font-extrabold">{{ $hadir }}</span>
                                <span class="text-rose-200 text-sm">dari {{ $total }} Peserta</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-rose-100 mb-1">
                                <span>Persentase Presensi</span>
                                <span>{{ $persen }}%</span>
                            </div>
                            <div class="w-full bg-rose-950/40 rounded-full h-1.5">
                                <div class="bg-rose-300 h-1.5 rounded-full" style="width: {{ $persen }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendees Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 bg-gray-50 border-b border-gray-100">
                        <h4 class="font-bold text-gray-800 text-base">Daftar Kehadiran Peserta</h4>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu Tap</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($pesertaAbsen as $index => $p)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-3.5 whitespace-nowrap text-sm font-semibold text-gray-400">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-3.5 whitespace-nowrap text-sm font-bold text-gray-950">
                                            {{ $p->name }}
                                        </td>
                                        <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-500">
                                            {{ $p->email }}
                                        </td>
                                        <td class="px-6 py-3.5 whitespace-nowrap text-center">
                                            @if($p->is_hadir)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">
                                                    Hadir
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold rounded-full">
                                                    Mangkir
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3.5 whitespace-nowrap text-center text-sm font-semibold text-gray-500 font-mono">
                                            {{ $p->waktu_tap ? \Carbon\Carbon::parse($p->waktu_tap)->format('H:i:s') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm text-gray-500">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <p class="font-bold text-gray-700">Monitor Absensi Kelas Real-Time</p>
                    <p class="text-sm text-gray-400 mt-1 max-w-md mx-auto">Pilih salah satu sesi di atas untuk memantau siapa saja peserta LAKMUD V yang sudah hadir di kelas saat ini.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
