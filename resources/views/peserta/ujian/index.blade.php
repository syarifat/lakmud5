<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Upload Jawaban Pre/Post Test') }}
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
                <h3 class="text-lg font-bold text-gray-800 mb-2">Upload Lembar Jawaban Ujian</h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Sistem pengunggahan lembar jawaban. Kerjakan pertanyaan **Pre-Test** dan **Post-Test** pada kertas fisik secara manual, kemudian foto atau jadikan berkas PDF lembar jawaban tersebut dan unggah menggunakan tombol **Upload** di bawah. Untuk melihat berkas yang sudah diunggah, klik tombol **Lihat**.
                </p>
            </div>

            <!-- CBT List Grid -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800 text-lg">Daftar Unggahan Ujian</h3>
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
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-16 text-center">No</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Materi Pelatihan</th>
                                    <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-64">Pre-Test</th>
                                    <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-64">Post-Test</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($materis as $index => $m)
                                    <tr class="hover:bg-gray-50/80 transition">
                                        <!-- No -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-400 text-center">
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
                                                <div class="flex items-center justify-center gap-2">
                                                    @if($m->pretest_done)
                                                        <a href="{{ asset($m->pretest_file) }}" target="_blank"
                                                            class="inline-flex items-center justify-center bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs px-3.5 py-1.5 rounded-lg border border-indigo-200 transition shadow-sm">
                                                            Lihat
                                                        </a>
                                                        <a href="{{ route('peserta.ujian.mulai', ['materi_id' => $m->id, 'tipe' => 'pretest']) }}" 
                                                            class="inline-flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs px-3.5 py-1.5 rounded-lg transition shadow-sm">
                                                            Upload Ulang
                                                        </a>
                                                    @else
                                                        <a href="{{ route('peserta.ujian.mulai', ['materi_id' => $m->id, 'tipe' => 'pretest']) }}" 
                                                            class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm hover:shadow">
                                                            Upload
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Post-Test -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if(!$m->has_posttest)
                                                <span class="text-xs text-gray-400 italic">Tidak Tersedia</span>
                                            @else
                                                <div class="flex items-center justify-center gap-2">
                                                    @if($m->posttest_done)
                                                        <a href="{{ asset($m->posttest_file) }}" target="_blank"
                                                            class="inline-flex items-center justify-center bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs px-3.5 py-1.5 rounded-lg border border-indigo-200 transition shadow-sm">
                                                            Lihat
                                                        </a>
                                                        <a href="{{ route('peserta.ujian.mulai', ['materi_id' => $m->id, 'tipe' => 'posttest']) }}" 
                                                            class="inline-flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs px-3.5 py-1.5 rounded-lg transition shadow-sm">
                                                            Upload Ulang
                                                        </a>
                                                    @else
                                                        <a href="{{ route('peserta.ujian.mulai', ['materi_id' => $m->id, 'tipe' => 'posttest']) }}" 
                                                            class="inline-flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm hover:shadow">
                                                            Upload
                                                        </a>
                                                    @endif
                                                </div>
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
