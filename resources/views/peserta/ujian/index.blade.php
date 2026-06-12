<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Ujian CBT LAKMUD V') }}
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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Computer Based Test (CBT)</h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Sistem ujian mandiri esai. Silakan kerjakan **Pre-test** sebelum materi diberikan untuk menguji pemahaman awal, dan **Post-test** setelah sesi materi selesai untuk mengevaluasi pemahaman akhir. Jawaban Anda akan dinilai secara manual oleh Inspel pendamping kelas.
                </p>
            </div>

            <!-- CBT List Grid -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800 text-lg">Daftar Tes Materi Pelatihan</h3>
                </div>

                @if($materis->isEmpty())
                    <div class="text-center py-16">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <p class="text-gray-500 font-medium">Belum ada materi pelatihan yang terdaftar.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Materi Pelatihan</th>
                                    <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Pre-Test</th>
                                    <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Post-Test</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($materis as $index => $m)
                                    <tr class="hover:bg-gray-50/80 transition">
                                        <!-- No -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-400 w-12">
                                            {{ $index + 1 }}
                                        </td>
                                        <!-- Materi -->
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $m->nama_materi }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $m->deskripsi ?? 'Tidak ada deskripsi materi.' }}</div>
                                        </td>
                                        
                                        <!-- Pre-Test -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if(!$m->has_pretest)
                                                <span class="text-xs text-gray-400 italic">Tidak Tersedia</span>
                                            @else
                                                @if($m->pretest_done)
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                        Selesai
                                                    </span>
                                                @else
                                                    <a href="{{ route('peserta.ujian.mulai', ['materi_id' => $m->id, 'tipe' => 'pretest']) }}" 
                                                        class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm hover:shadow">
                                                        Mulai Pre-Test
                                                    </a>
                                                @endif
                                            @endif
                                        </td>

                                        <!-- Post-Test -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if(!$m->has_posttest)
                                                <span class="text-xs text-gray-400 italic">Tidak Tersedia</span>
                                            @else
                                                @if($m->posttest_done)
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                        Selesai
                                                    </span>
                                                @else
                                                    <a href="{{ route('peserta.ujian.mulai', ['materi_id' => $m->id, 'tipe' => 'posttest']) }}" 
                                                        class="inline-flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm hover:shadow">
                                                        Mulai Post-Test
                                                    </a>
                                                @endif
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
