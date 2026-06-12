<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Penilaian Inspel oleh Peserta') }}
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
                <h3 class="text-lg font-bold text-gray-800 mb-2">Evaluasi Kinerja Inspel (Instruktur Pelatihan)</h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Umpan balik harian ini diisi oleh Rekan/Rekanita untuk menilai kinerja pendampingan serta ketegasan tata tertib akademik oleh Inspel selama forum LAKMUD V. Skala penilaian dibatasi sesuai aturan sistem (50, 60, 70, 80, 90).
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @if($inspels->isEmpty())
                    <div class="md:col-span-2 text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <p class="text-gray-500 font-medium">Belum ada user dengan role Inspel di sistem.</p>
                    </div>
                @else
                    @foreach($inspels as $ins)
                        @php
                            $rating = $nilaiInspel->get($ins->id);
                        @endphp
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
                            <div>
                                <div class="flex justify-between items-start mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-lg">{{ $ins->name }}</h4>
                                            <p class="text-xs text-rose-600 font-semibold tracking-wider uppercase">Inspel Pelatihan</p>
                                        </div>
                                    </div>
                                    @if($rating)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-rose-100 text-rose-800 text-xs font-bold rounded-full">
                                            Skor: {{ $rating->nilai }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 text-amber-800 text-xs font-semibold rounded-full border border-amber-200">
                                            Belum Dinilai
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Rating Form -->
                            <form method="POST" action="{{ route('peserta.nilai-inspel.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="inspel_id" value="{{ $ins->id }}">

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nilai Kinerja Inspel (Skala 50 - 90)</label>
                                    <div class="grid grid-cols-5 gap-2">
                                        @foreach([50, 60, 70, 80, 90] as $score)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="nilai" value="{{ $score }}" class="sr-only peer" 
                                                    {{ ($rating && $rating->nilai == $score) ? 'checked' : '' }} required>
                                                <div class="text-center py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:text-rose-800 transition">
                                                    {{ $score }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <label for="catatan_khusus_{{ $ins->id }}" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Catatan Khusus / Saran</label>
                                    <textarea id="catatan_khusus_{{ $ins->id }}" name="catatan_khusus" rows="2" 
                                        class="w-full text-sm rounded-xl border-gray-200 focus:border-rose-500 focus:ring-rose-500 placeholder-gray-400"
                                        placeholder="Tuliskan kritikan atau saran konstruktif untuk Inspel ini...">{{ $rating ? $rating->catatan_khusus : '' }}</textarea>
                                </div>

                                <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-2.5 rounded-xl transition shadow-sm hover:shadow">
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
