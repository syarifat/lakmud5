<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Lembar Evaluasi & Refleksi Harian Peserta') }}
        </h2>
    </x-slot>

    <div class="py-10" x-data="{ activeDay: 1 }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

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

            <!-- Instruction Box -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Evaluasi Harian Peserta</h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Setiap malam setelah seluruh rangkaian materi selesai, Rekan/Rekanita wajib mengisi lembar evaluasi dan refleksi harian. Hal ini bertujuan untuk mengukur efektivitas materi serta memonitor hambatan psikologis maupun fisik peserta selama kegiatan berlangsung.
                </p>
            </div>

            <!-- Tab Navigation for Day 1-4 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex border-b border-gray-100 bg-gray-50">
                    @for($day = 1; $day <= 4; $day++)
                        <button @click="activeDay = {{ $day }}" 
                            class="flex-1 py-4 text-center text-sm font-bold border-b-2 transition"
                            :class="activeDay === {{ $day }} ? 'border-emerald-600 text-emerald-800 bg-white' : 'border-transparent text-gray-400 hover:text-gray-600 hover:bg-gray-100/50'">
                            Hari Ke-{{ $day }}
                            @if($refleksis->has($day))
                                <span class="ml-1.5 inline-block w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>
                            @endif
                        </button>
                    @endfor
                </div>

                <div class="p-8">
                    @for($day = 1; $day <= 4; $day++)
                        @php
                            $refleksi = $refleksis->get($day);
                        @endphp
                        <div x-show="activeDay === {{ $day }}" class="space-y-6">
                            
                            @if($refleksi)
                                <!-- Read-only Mode (Submitted) -->
                                <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-4 flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        <span class="text-sm font-bold text-emerald-800">Evaluasi Hari Ke-{{ $day }} Telah Dikirim</span>
                                    </div>
                                    <span class="text-xs text-emerald-600 bg-white px-2.5 py-1 rounded-lg shadow-sm font-semibold">
                                        Tanggal: {{ \Carbon\Carbon::parse($refleksi->tanggal)->translatedFormat('d M Y') }}
                                    </span>
                                </div>

                                <div class="space-y-6">
                                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">1. Pengalaman Belajar yang Paling Bermanfaat</h4>
                                        <p class="text-sm text-gray-800 leading-relaxed">{{ $refleksi->q1_pengalaman }}</p>
                                    </div>

                                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">2. Tingkat Partisipasi Hari Ini</h4>
                                        <p class="text-sm text-gray-800 leading-relaxed">{{ $refleksi->q2_partisipasi }}</p>
                                    </div>

                                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">3. Faktor Pendorong atau Penghambat Partisipasi</h4>
                                        <p class="text-sm text-gray-800 leading-relaxed">{{ $refleksi->q3_hambatan_dorongan }}</p>
                                    </div>

                                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">4. Kesempatan Mengemukakan Pendapat (Kapan & Sesi Apa)</h4>
                                        <p class="text-sm text-gray-800 leading-relaxed">{{ $refleksi->q4_kesempatan_pendapat }}</p>
                                    </div>

                                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">5. Pengetahuan Baru yang Didapatkan</h4>
                                        <p class="text-sm text-gray-800 leading-relaxed">{{ $refleksi->q5_pengetahuan_didapat }}</p>
                                    </div>

                                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">6. Faktor Hambatan Internal Diri Sendiri</h4>
                                        <p class="text-sm text-gray-800 leading-relaxed">{{ $refleksi->q6_hambatan_diri_sendiri }}</p>
                                    </div>
                                </div>
                            @else
                                <!-- Edit/Fill Mode -->
                                <form method="POST" action="{{ route('peserta.refleksi.store') }}" class="space-y-6">
                                    @csrf
                                    <input type="hidden" name="hari_ke" value="{{ $day }}">

                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-amber-50/50 border border-amber-100 rounded-xl mb-6">
                                        <span class="text-sm text-amber-800 font-semibold">Anda sedang mengisi Evaluasi untuk Hari Ke-{{ $day }}.</span>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Pengisian</label>
                                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                                                class="text-sm rounded-lg border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 py-1">
                                        </div>
                                    </div>

                                    <!-- Question 1 -->
                                    <div>
                                        <label for="q1_pengalaman_{{ $day }}" class="block text-sm font-bold text-gray-800 leading-relaxed mb-2">
                                            1. Pengalaman belajar apa yang rekan/rekanita dapat dari pelatihan hari ini, yang paling bermanfaat bagi perkembangan diri anda? <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="q1_pengalaman_{{ $day }}" name="q1_pengalaman" rows="3" required
                                            class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 placeholder-gray-400"
                                            placeholder="Tuliskan pengalaman belajar di sini..."></textarea>
                                    </div>

                                    <!-- Question 2 -->
                                    <div>
                                        <label for="q2_partisipasi_{{ $day }}" class="block text-sm font-bold text-gray-800 leading-relaxed mb-2">
                                            2. Menurut rekan/rekanita, bagaimana tingkat partisipasi anda dalam pelatihan hari ini? <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="q2_partisipasi_{{ $day }}" name="q2_partisipasi" rows="3" required
                                            class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 placeholder-gray-400"
                                            placeholder="Gambarkan tingkat partisipasi Anda (aktif, cukup aktif, kurang aktif) beserta penjelasannya..."></textarea>
                                    </div>

                                    <!-- Question 3 -->
                                    <div>
                                        <label for="q3_hambatan_dorongan_{{ $day }}" class="block text-sm font-bold text-gray-800 leading-relaxed mb-2">
                                            3. Adakah hal yang menghambat atau mendorong rekan/rekanita untuk berpartisipasi dalam latihan hari ini? <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="q3_hambatan_dorongan_{{ $day }}" name="q3_hambatan_dorongan" rows="3" required
                                            class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 placeholder-gray-400"
                                            placeholder="Tuliskan faktor pendorong atau penghambat partisipasi di sini..."></textarea>
                                    </div>

                                    <!-- Question 4 -->
                                    <div>
                                        <label for="q4_kesempatan_pendapat_{{ $day }}" class="block text-sm font-bold text-gray-800 leading-relaxed mb-2">
                                            4. Adakah rekan/rekanita dalam sesi hari ini mempunyai kesempatan untuk mengemukakan pendapat, ide, pikiran. Kapan dan dalam kesempatan apa? <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="q4_kesempatan_pendapat_{{ $day }}" name="q4_kesempatan_pendapat" rows="3" required
                                            class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 placeholder-gray-400"
                                            placeholder="Tuliskan kesempatan mengutarakan ide di sini..."></textarea>
                                    </div>

                                    <!-- Question 5 -->
                                    <div>
                                        <label for="q5_pengetahuan_didapat_{{ $day }}" class="block text-sm font-bold text-gray-800 leading-relaxed mb-2">
                                            5. Pengetahuan apa saja kah yang rekan/rekanita dapatkan pada hari ini? <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="q5_pengetahuan_didapat_{{ $day }}" name="q5_pengetahuan_didapat" rows="3" required
                                            class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 placeholder-gray-400"
                                            placeholder="Rangkum poin-poin pengetahuan/materi baru di sini..."></textarea>
                                    </div>

                                    <!-- Question 6 -->
                                    <div>
                                        <label for="q6_hambatan_diri_sendiri_{{ $day }}" class="block text-sm font-bold text-gray-800 leading-relaxed mb-2">
                                            6. Hal apa saja kah yang menghambat rekan/rekanita dalam mengikuti latihan hari ini, terutama yang bersumber dalam diri anda sendiri? <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="q6_hambatan_diri_sendiri_{{ $day }}" name="q6_hambatan_diri_sendiri" rows="3" required
                                            class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 placeholder-gray-400"
                                            placeholder="Tuliskan kendala internal (misal: kantuk, kurang fokus, lelah, cemas, dsb) di sini..."></textarea>
                                    </div>

                                    <div class="flex justify-end pt-4">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-6 py-3 rounded-xl transition shadow-sm hover:shadow">
                                            Kirim Lembar Refleksi Hari Ke-{{ $day }}
                                        </button>
                                    </div>
                                </form>
                            @endif

                        </div>
                    @endfor
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
