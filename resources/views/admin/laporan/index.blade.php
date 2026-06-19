<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-emerald-900 leading-tight flex items-center gap-2">
            <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            {{ __('Laporan Total & Cetak PDF') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-slate-50 to-emerald-50/30 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert & Instructions -->
            <div class="bg-white/80 backdrop-blur-md overflow-hidden shadow-sm sm:rounded-2xl mb-8 border border-emerald-100 p-6">
                <h3 class="text-lg font-bold text-emerald-800 flex items-center gap-2 mb-1">
                    <span>💡</span> Modul Ekspor Laporan Resmi LAKMUD V
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Halaman ini digunakan oleh Administrator untuk mengunduh berkas laporan dalam format PDF yang format layout, teks, dan tabelnya disesuaikan agar sama persis dengan template berkas Microsoft Word resmi (`.docm`). Pilih filter pada masing-masing kartu laporan di bawah untuk mengunduh.
                </p>
            </div>

            <!-- Grid of 8 Reports -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
                <!-- 1. CV Pemateri -->
                <div class="group bg-white rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-3.5 rounded-2xl bg-indigo-50 text-indigo-600 group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">Laporan #1</span>
                                <h4 class="text-lg font-bold text-slate-800">Curriculum Vitae Pemateri</h4>
                            </div>
                        </div>
                        <p class="text-slate-500 text-xs mb-6">Mencetak biodata lengkap, riwayat pendidikan, riwayat organisasi, dan materi yang diampu oleh pemateri terpilih.</p>
                        
                        <form action="{{ route('admin.laporan.download') }}" method="GET" class="space-y-4">
                            <input type="hidden" name="type" value="cv_pemateri">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2">Pilih Pemateri</label>
                                <select name="pemateri_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition">
                                    <option value="">-- Pilih Pemateri --</option>
                                    @foreach($pemateris as $pemateri)
                                        <option value="{{ $pemateri->id }}">{{ $pemateri->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="p-6 bg-slate-50 border-t border-slate-100 rounded-b-3xl">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-md shadow-indigo-100 hover:shadow-lg hover:shadow-indigo-200 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Cetak PDF
                        </button>
                        </form>
                    </div>
                </div>

                <!-- 2. Daftar Hadir Peserta -->
                <div class="group bg-white rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-3.5 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Laporan #2</span>
                                <h4 class="text-lg font-bold text-slate-800">Daftar Hadir Peserta</h4>
                            </div>
                        </div>
                        <p class="text-slate-500 text-xs mb-6">Mencetak daftar hadir peserta pada sesi materi tertentu lengkap dengan digital signature peserta yang telah tap RFID.</p>
                        
                        <form action="{{ route('admin.laporan.download') }}" method="GET" class="space-y-4">
                            <input type="hidden" name="type" value="daftar_hadir">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2">Pilih Jadwal Sesi</label>
                                <select name="jadwal_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 transition">
                                    <option value="">-- Pilih Sesi Materi --</option>
                                    @foreach($jadwals as $jadwal)
                                        <option value="{{ $jadwal->id }}">{{ $jadwal->materi->nama_materi }} ({{ $jadwal->pemateri->nama }})</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="p-6 bg-slate-50 border-t border-slate-100 rounded-b-3xl">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-md shadow-emerald-100 hover:shadow-lg hover:shadow-emerald-200 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Cetak PDF
                        </button>
                        </form>
                    </div>
                </div>

                <!-- 3. Lembar Penilaian Peserta -->
                <div class="group bg-white rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-3.5 rounded-2xl bg-cyan-50 text-cyan-600 group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-cyan-500 uppercase tracking-widest">Laporan #3</span>
                                <h4 class="text-lg font-bold text-slate-800">Lembar Penilaian Peserta</h4>
                            </div>
                        </div>
                        <p class="text-slate-500 text-xs mb-6">Mencetak rekapitulasi nilai peserta (pemahaman, kedisiplinan, keaktifan, dan rerata) skala 70-100 untuk sesi tertentu.</p>
                        
                        <form action="{{ route('admin.laporan.download') }}" method="GET" class="space-y-4">
                            <input type="hidden" name="type" value="penilaian_peserta">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2">Pilih Jadwal Sesi</label>
                                <select name="jadwal_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-cyan-500 focus:ring-cyan-500 transition">
                                    <option value="">-- Pilih Sesi Materi --</option>
                                    @foreach($jadwals as $jadwal)
                                        <option value="{{ $jadwal->id }}">{{ $jadwal->materi->nama_materi }} ({{ $jadwal->pemateri->nama }})</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="p-6 bg-slate-50 border-t border-slate-100 rounded-b-3xl">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-md shadow-cyan-100 hover:shadow-lg hover:shadow-cyan-200 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Cetak PDF
                        </button>
                        </form>
                    </div>
                </div>

                <!-- 4. Lembar Observasi Harian Peserta -->
                <div class="group bg-white rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-3.5 rounded-2xl bg-amber-50 text-amber-600 group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-amber-500 uppercase tracking-widest">Laporan #4</span>
                                <h4 class="text-lg font-bold text-slate-800">Lembar Observasi Harian Peserta</h4>
                            </div>
                        </div>
                        <p class="text-slate-500 text-xs mb-6">Mencetak lembar checklist observasi perilaku/sikap peserta (skala 1-5, dan nilai akhir 40-80) oleh Pendamping Kelompok.</p>
                        
                        <form action="{{ route('admin.laporan.download') }}" method="GET" class="space-y-4">
                            <input type="hidden" name="type" value="observasi_harian">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Kelompok</label>
                                    <select name="kelompok_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 transition">
                                        <option value="">-- Pilih Kelompok --</option>
                                        @foreach($kelompoks as $kelompok)
                                            <option value="{{ $kelompok->id }}">{{ $kelompok->nama_kelompok }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Hari Ke-</label>
                                    <select name="hari_ke" required class="w-full text-sm rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 transition">
                                        <option value="1">Hari Ke-1</option>
                                        <option value="2">Hari Ke-2</option>
                                        <option value="3">Hari Ke-3</option>
                                        <option value="4">Hari Ke-4</option>
                                    </select>
                                </div>
                            </div>
                    </div>
                    <div class="p-6 bg-slate-50 border-t border-slate-100 rounded-b-3xl">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-md shadow-amber-100 hover:shadow-lg hover:shadow-amber-200 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Cetak PDF
                        </button>
                        </form>
                    </div>
                </div>

                <!-- 5. Lembar Penilaian Pemateri Oleh Peserta -->
                <div class="group bg-white rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-3.5 rounded-2xl bg-teal-50 text-teal-600 group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.371 1.24.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.176 0l-3.97 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-teal-500 uppercase tracking-widest">Laporan #5</span>
                                <h4 class="text-lg font-bold text-slate-800">Penilaian Pemateri Oleh Peserta</h4>
                            </div>
                        </div>
                        <p class="text-slate-500 text-xs mb-6">Mencetak rekap penilaian akhir dari satu peserta untuk seluruh pemateri (skala 50-90) beserta catatan khusus.</p>
                        
                        <form action="{{ route('admin.laporan.download') }}" method="GET" class="space-y-4">
                            <input type="hidden" name="type" value="nilai_pemateri">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2">Pilih Peserta</label>
                                <select name="peserta_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 transition">
                                    <option value="">-- Pilih Peserta --</option>
                                    @foreach($pesertas as $peserta)
                                        <option value="{{ $peserta->id }}">{{ $peserta->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="p-6 bg-slate-50 border-t border-slate-100 rounded-b-3xl">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-md shadow-teal-100 hover:shadow-lg hover:shadow-teal-200 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Cetak PDF
                        </button>
                        </form>
                    </div>
                </div>

                <!-- 6. Lembar Penilaian Instruktur Pelatih Oleh Peserta -->
                <div class="group bg-white rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-3.5 rounded-2xl bg-rose-50 text-rose-600 group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-rose-500 uppercase tracking-widest">Laporan #6</span>
                                <h4 class="text-lg font-bold text-slate-800">Penilaian Instruktur/Pelatih</h4>
                            </div>
                        </div>
                        <p class="text-slate-500 text-xs mb-6">Mencetak rekap penilaian akhir dari satu peserta untuk seluruh Instruktur Inspel (skala 50-90) beserta catatan khusus.</p>
                        
                        <form action="{{ route('admin.laporan.download') }}" method="GET" class="space-y-4">
                            <input type="hidden" name="type" value="nilai_inspel">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2">Pilih Peserta</label>
                                <select name="peserta_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-rose-500 focus:ring-rose-500 transition">
                                    <option value="">-- Pilih Peserta --</option>
                                    @foreach($pesertas as $peserta)
                                        <option value="{{ $peserta->id }}">{{ $peserta->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="p-6 bg-slate-50 border-t border-slate-100 rounded-b-3xl">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-md shadow-rose-100 hover:shadow-lg hover:shadow-rose-200 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Cetak PDF
                        </button>
                        </form>
                    </div>
                </div>

                <!-- 7. Lembar Evaluasi & Refleksi Harian Peserta -->
                <div class="group bg-white rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-3.5 rounded-2xl bg-violet-50 text-violet-600 group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-violet-500 uppercase tracking-widest">Laporan #7</span>
                                <h4 class="text-lg font-bold text-slate-800">Evaluasi & Refleksi Harian</h4>
                            </div>
                        </div>
                        <p class="text-slate-500 text-xs mb-6">Mencetak 6 jawaban esai evaluasi harian seorang peserta untuk merefleksikan proses pembelajarannya pada hari terpilih.</p>
                        
                        <form action="{{ route('admin.laporan.download') }}" method="GET" class="space-y-4">
                            <input type="hidden" name="type" value="evaluasi_refleksi">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Peserta</label>
                                    <select name="peserta_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 transition">
                                        <option value="">-- Pilih --</option>
                                        @foreach($pesertas as $peserta)
                                            <option value="{{ $peserta->id }}">{{ $peserta->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Hari Ke-</label>
                                    <select name="hari_ke" required class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 transition">
                                        <option value="1">Hari Ke-1</option>
                                        <option value="2">Hari Ke-2</option>
                                        <option value="3">Hari Ke-3</option>
                                        <option value="4">Hari Ke-4</option>
                                    </select>
                                </div>
                            </div>
                    </div>
                    <div class="p-6 bg-slate-50 border-t border-slate-100 rounded-b-3xl">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-violet-600 hover:bg-violet-700 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-md shadow-violet-100 hover:shadow-lg hover:shadow-violet-200 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Cetak PDF
                        </button>
                        </form>
                    </div>
                </div>

                <!-- 8. Daftar Pertanyaan Pretest-Posttest -->
                <div class="group bg-white rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-3.5 rounded-2xl bg-fuchsia-50 text-fuchsia-600 group-hover:scale-110 transition duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-fuchsia-500 uppercase tracking-widest">Laporan #8</span>
                                <h4 class="text-lg font-bold text-slate-800">Soal Pretest & Posttest</h4>
                            </div>
                        </div>
                        <p class="text-slate-500 text-xs mb-6">Mencetak lembar daftar pertanyaan ujian pretest/posttest dari bank soal berdasarkan materi yang diujikan.</p>
                        
                        <form action="{{ route('admin.laporan.download') }}" method="GET" class="space-y-4">
                            <input type="hidden" name="type" value="soal_prepost">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2">Pilih Materi</label>
                                <select name="materi_id" class="w-full text-sm rounded-xl border-slate-200 focus:border-fuchsia-500 focus:ring-fuchsia-500 transition">
                                    <option value="all">Semua Materi (Format Grid Asli)</option>
                                    @foreach($materis as $materi)
                                        <option value="{{ $materi->id }}">{{ $materi->nama_materi }}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="p-6 bg-slate-50 border-t border-slate-100 rounded-b-3xl">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-fuchsia-600 hover:bg-fuchsia-700 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-md shadow-fuchsia-100 hover:shadow-lg hover:shadow-fuchsia-200 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Cetak PDF
                        </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
