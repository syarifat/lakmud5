<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <span class="text-xs uppercase font-extrabold tracking-widest text-emerald-600 block mb-1">Pengerjaan Ujian CBT</span>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    {{ $materi->nama_materi }}
                </h2>
            </div>
            <div>
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm 
                    {{ $tipe === 'pretest' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800' }}">
                    {{ $tipe === 'pretest' ? 'Pre-Test' : 'Post-Test' }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-10" x-data="{ openConfirm: false }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Warning Box -->
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-2xl shadow-sm mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-bold text-amber-800">Petunjuk Penting Pengerjaan:</h4>
                        <ul class="text-xs text-amber-700 list-disc pl-4 mt-1 space-y-1">
                            <li>Bacalah pertanyaan dengan teliti sebelum menjawab.</li>
                            <li>Tuliskan jawaban dalam bentuk esai/uraian singkat pada kolom yang telah disediakan.</li>
                            <li>Jangan me-refresh atau menutup halaman ini sebelum menekan tombol <strong>Kirim Jawaban</strong> untuk menghindari hilangnya jawaban Anda.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Exam Form -->
            <form id="ujianForm" method="POST" action="{{ route('peserta.ujian.store', ['materi_id' => $materi->id]) }}" 
                @submit.prevent="openConfirm = true" class="space-y-8">
                @csrf
                <input type="hidden" name="tipe" value="{{ $tipe }}">

                <!-- Questions List -->
                @foreach($soals as $index => $soal)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-4 hover:shadow-md transition">
                        <div class="flex items-start gap-4">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-gray-100 text-gray-700 font-bold text-sm flex-shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <div class="text-sm sm:text-base font-semibold text-gray-800 leading-relaxed pt-1">
                                {!! nl2br(e($soal->pertanyaan)) !!}
                            </div>
                        </div>

                        <div class="pl-0 sm:pl-12">
                            <label for="jawaban_{{ $soal->id }}" class="sr-only">Jawaban Anda</label>
                            <textarea id="jawaban_{{ $soal->id }}" name="jawaban[{{ $soal->id }}]" rows="5" required
                                class="w-full text-sm rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 placeholder-gray-400"
                                placeholder="Ketik jawaban esai rekan/rekanita di sini secara lengkap..."></textarea>
                        </div>
                    </div>
                @endforeach

                <!-- Action buttons -->
                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('peserta.ujian') }}" class="inline-flex items-center justify-center bg-gray-150 hover:bg-gray-200 text-gray-700 font-bold text-sm px-6 py-3 rounded-xl transition border border-gray-200 shadow-sm">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-8 py-3 rounded-xl transition shadow-md hover:shadow-lg">
                        Kirim Jawaban
                    </button>
                </div>

                <!-- Confirmation Modal -->
                <div x-show="openConfirm" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <!-- Background overlay -->
                        <div x-show="openConfirm" @click="openConfirm = false" 
                            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <!-- Modal panel -->
                        <div x-show="openConfirm" 
                            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                            class="inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                            
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 text-emerald-600 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-extrabold text-gray-900" id="modal-title">
                                        Konfirmasi Kirim Jawaban
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Apakah Anda yakin ingin mengirim semua jawaban ujian ini? Jawaban yang sudah dikirim **tidak dapat diubah kembali**.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 sm:mt-4 sm:flex sm:flex-row-reverse gap-3">
                                <button type="button" @click="document.getElementById('ujianForm').submit()" 
                                    class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-emerald-600 text-base font-bold text-white hover:bg-emerald-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                    Ya, Kirim Sekarang
                                </button>
                                <button type="button" @click="openConfirm = false" 
                                    class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                                    Periksa Kembali
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
