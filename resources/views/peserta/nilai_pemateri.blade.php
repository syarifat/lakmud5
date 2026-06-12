<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Penilaian Pemateri oleh Peserta') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Alert messages -->
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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Lembar Penilaian Pemateri</h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Rekan/Rekanita diharapkan memberikan penilaian yang objektif terhadap kinerja masing-masing pemateri/narasumber demi perbaikan kualitas penyampaian materi di masa mendatang. Skala penilaian dibatasi sesuai aturan sistem (50, 60, 70, 80, 90).
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @if($jadwals->isEmpty())
                    <div class="md:col-span-2 text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-gray-500 font-medium">Belum ada jadwal sesi materi untuk dinilai.</p>
                    </div>
                @else
                    @foreach($jadwals as $j)
                        @php
                            $rating = $nilaiPemateri->get($j->id);
                        @endphp
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-lg">{{ $j->materi->nama_materi }}</h4>
                                        <p class="text-xs text-gray-500 mt-0.5">Pemateri: <span class="font-bold text-emerald-800">{{ $j->pemateri->nama }}</span></p>
                                    </div>
                                    @if($rating)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">
                                            Skor: {{ $rating->nilai }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 text-amber-800 text-xs font-semibold rounded-full border border-amber-200">
                                            Belum Dinilai
                                        </span>
                                    @endif
                                </div>

                                <div class="text-xs text-gray-500 font-medium mb-6 flex items-center gap-1 bg-gray-50 p-2.5 rounded-lg">
                                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Jadwal: {{ \Carbon\Carbon::parse($j->waktu_mulai)->translatedFormat('l, d M Y') }} ({{ \Carbon\Carbon::parse($j->waktu_mulai)->format('H:i') }} WIB)
                                </div>
                            </div>

                            <!-- Rating Form -->
                            <form method="POST" action="{{ route('peserta.nilai-pemateri.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="jadwal_id" value="{{ $j->id }}">

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nilai Pemateri (Skala 50 - 90)</label>
                                    <div class="grid grid-cols-5 gap-2">
                                        @foreach([50, 60, 70, 80, 90] as $score)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="nilai" value="{{ $score }}" class="sr-only peer" 
                                                    {{ ($rating && $rating->nilai == $score) ? 'checked' : '' }} required>
                                                <div class="text-center py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 peer-checked:border-emerald-600 peer-checked:bg-emerald-50 peer-checked:text-emerald-800 transition">
                                                    {{ $score }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <label for="catatan_khusus_{{ $j->id }}" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Catatan Khusus / Saran</label>
                                    <textarea id="catatan_khusus_{{ $j->id }}" name="catatan_khusus" rows="2" 
                                        class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 placeholder-gray-400"
                                        placeholder="Tuliskan umpan balik atau saran rekan/rekanita di sini...">{{ $rating ? $rating->catatan_khusus : '' }}</textarea>
                                </div>

                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm py-2.5 rounded-xl transition shadow-sm hover:shadow">
                                    {{ $rating ? 'Perbarui Nilai' : 'Kirim Penilaian' }}
                                </button>
                            </form>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
