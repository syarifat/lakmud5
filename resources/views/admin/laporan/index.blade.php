@php
    $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'inspel';
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Rekap Laporan') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Alerts -->
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

            <!-- Interactive Report Filter Selector -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sm:p-8">
                <form id="laporanFilterForm" method="GET" action="{{ route($routePrefix . '.laporan.index') }}" class="space-y-6">
                    <input type="hidden" name="download_all" id="downloadAllInput" value="0">
                    <input type="hidden" name="paper_size" id="paperSizeInput" value="a4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Dropdown Jenis Laporan -->
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Pilih Jenis Laporan</label>
                            <select name="type" onchange="this.form.action='{{ route($routePrefix . '.laporan.index') }}'; this.form.target=''; this.form.submit();"
                                class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-semibold text-slate-800 transition">
                                <option value="">-- Pilih Jenis Rekap Laporan --</option>
                                <option value="cv_pemateri" {{ $type === 'cv_pemateri' ? 'selected' : '' }}>Laporan #1 - CV Pemateri</option>
                                <option value="daftar_hadir" {{ $type === 'daftar_hadir' ? 'selected' : '' }}>Laporan #2 - Daftar Hadir Peserta</option>
                                <option value="penilaian_peserta" {{ $type === 'penilaian_peserta' ? 'selected' : '' }}>Laporan #3 - Lembar Penilaian Peserta</option>
                                <option value="observasi_harian" {{ $type === 'observasi_harian' ? 'selected' : '' }}>Laporan #4 - Lembar Observasi Harian</option>
                                <option value="nilai_pemateri" {{ $type === 'nilai_pemateri' ? 'selected' : '' }}>Laporan #5 - Penilaian Pemateri oleh Peserta</option>
                                <option value="nilai_inspel" {{ $type === 'nilai_inspel' ? 'selected' : '' }}>Laporan #6 - Penilaian Inspel oleh Peserta</option>
                                <option value="evaluasi_refleksi" {{ $type === 'evaluasi_refleksi' ? 'selected' : '' }}>Laporan #7 - Evaluasi & Refleksi Harian</option>
                                <option value="berkas_jawaban" {{ $type === 'berkas_jawaban' ? 'selected' : '' }}>Berkas Lembar Jawaban Ujian Peserta</option>
                            </select>
                        </div>

                        <!-- Dynamic Filter Inputs based on type -->
                        @if($type)
                            <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if($type === 'cv_pemateri')
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Pilih Narasumber / Pemateri</label>
                                        <select name="pemateri_id" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700 transition">
                                            <option value="">-- Pilih Narasumber --</option>
                                            @foreach($pemateris as $p)
                                                <option value="{{ $p->id }}" {{ request('pemateri_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif($type === 'daftar_hadir' || $type === 'penilaian_peserta')
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Pilih Jadwal Sesi</label>
                                        <select name="jadwal_id" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700 transition">
                                            <option value="">-- Pilih Sesi Materi --</option>
                                            @foreach($jadwals as $j)
                                                <option value="{{ $j->id }}" {{ request('jadwal_id') == $j->id ? 'selected' : '' }}>
                                                    {{ $j->materi->nama_materi }} ({{ $j->pemateri->nama }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif($type === 'observasi_harian')
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Pilih Kelompok</label>
                                        <select name="kelompok_id" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700 transition">
                                            <option value="">-- Pilih Kelompok --</option>
                                            @foreach($kelompoks as $k)
                                                <option value="{{ $k->id }}" {{ request('kelompok_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelompok }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Hari Ke-</label>
                                        <select name="hari_ke" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700 transition">
                                            <option value="1" {{ request('hari_ke') == 1 ? 'selected' : '' }}>Hari Ke-1</option>
                                            <option value="2" {{ request('hari_ke') == 2 ? 'selected' : '' }}>Hari Ke-2</option>
                                            <option value="3" {{ request('hari_ke') == 3 ? 'selected' : '' }}>Hari Ke-3</option>
                                            <option value="4" {{ request('hari_ke') == 4 ? 'selected' : '' }}>Hari Ke-4</option>
                                        </select>
                                    </div>
                                @elseif($type === 'nilai_pemateri' || $type === 'nilai_inspel')
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Pilih Peserta</label>
                                        <select name="peserta_id" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700 transition">
                                            <option value="">-- Pilih Peserta --</option>
                                            @foreach($pesertas as $pe)
                                                <option value="{{ $pe->id }}" {{ request('peserta_id') == $pe->id ? 'selected' : '' }}>{{ $pe->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif($type === 'evaluasi_refleksi')
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Pilih Peserta</label>
                                        <select name="peserta_id" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700 transition">
                                            <option value="">-- Pilih Peserta --</option>
                                            @foreach($pesertas as $pe)
                                                <option value="{{ $pe->id }}" {{ request('peserta_id') == $pe->id ? 'selected' : '' }}>{{ $pe->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Hari Ke-</label>
                                        <select name="hari_ke" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700 transition">
                                            <option value="1" {{ request('hari_ke') == 1 ? 'selected' : '' }}>Hari Ke-1</option>
                                            <option value="2" {{ request('hari_ke') == 2 ? 'selected' : '' }}>Hari Ke-2</option>
                                            <option value="3" {{ request('hari_ke') == 3 ? 'selected' : '' }}>Hari Ke-3</option>
                                            <option value="4" {{ request('hari_ke') == 4 ? 'selected' : '' }}>Hari Ke-4</option>
                                        </select>
                                    </div>
                                @elseif($type === 'berkas_jawaban')
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Filter Materi</label>
                                        <select name="materi_id" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700 transition">
                                            <option value="all">Semua Materi</option>
                                            @foreach($materis as $ma)
                                                <option value="{{ $ma->id }}" {{ request('materi_id') == $ma->id ? 'selected' : '' }}>{{ $ma->nama_materi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Jenis Ujian</label>
                                        <select name="tipe_ujian" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700 transition">
                                            <option value="all" {{ request('tipe_ujian') === 'all' ? 'selected' : '' }}>Semua Jenis</option>
                                            <option value="pretest" {{ request('tipe_ujian') === 'pretest' ? 'selected' : '' }}>Pre-Test</option>
                                            <option value="posttest" {{ request('tipe_ujian') === 'posttest' ? 'selected' : '' }}>Post-Test</option>
                                        </select>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if($type)
                        <div class="flex flex-col sm:flex-row items-center justify-end gap-3 border-t border-slate-100 pt-4">
                            <button type="submit" onclick="document.getElementById('downloadAllInput').value='0'; this.form.action='{{ route($routePrefix . '.laporan.index') }}'; this.form.target='';"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-sm px-6 py-3 rounded-xl transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Tampilkan Data
                            </button>
                            
                            @if($type !== 'berkas_jawaban')
                                <button type="button" onclick="showPaperModal(0)"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm px-6 py-3 rounded-xl transition shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Unduh PDF (Filter)
                                </button>
                                
                                <button type="button" onclick="showPaperModal(1)"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-6 py-3 rounded-xl transition shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Unduh Semua PDF
                                </button>
                            @endif
                        </div>
                    @endif
                </form>
            </div>

            <!-- Report Results View -->
            @if($type)
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sm:p-8">
                    <!-- Report Header -->
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                            <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H3a2 2 0 01-2-2V5a2 2 0 012-2h4l2 2h4l2-2h4a2 2 0 012 2v14a2 2 0 01-2 2z"></path></svg>
                            </span>
                            @if($type === 'cv_pemateri')
                                Kurikulum Vitae (CV) Narasumber
                            @elseif($type === 'daftar_hadir')
                                Daftar Hadir Peserta Sesi
                            @elseif($type === 'penilaian_peserta')
                                Lembar Hasil Penilaian Peserta
                            @elseif($type === 'observasi_harian')
                                Lembar Checklist Observasi Sikap Peserta
                            @elseif($type === 'nilai_pemateri')
                                Lembar Penilaian Kinerja Pemateri oleh Peserta
                            @elseif($type === 'nilai_inspel')
                                Lembar Penilaian Kinerja Instruktur (Inspel) oleh Peserta
                            @elseif($type === 'evaluasi_refleksi')
                                Lembar Hasil Evaluasi & Refleksi Harian Peserta
                            @elseif($type === 'berkas_jawaban')
                                Berkas Lembar Jawaban Ujian Peserta
                            @endif
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">Menampilkan ringkasan data yang saat ini tersimpan di sistem.</p>
                    </div>

                    <!-- Dynamic Tables rendering -->
                    <div class="overflow-x-auto">
                        @if($type === 'cv_pemateri')
                            @if($selectedPemateri)
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50/60 p-6 rounded-2xl border border-slate-100 items-start">
                                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs text-slate-450 uppercase font-semibold">Nama Lengkap</p>
                                                <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $selectedPemateri->nama }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-450 uppercase font-semibold">Tempat, Tanggal Lahir</p>
                                                <p class="text-sm font-bold text-slate-900 mt-0.5">
                                                    {{ $selectedPemateri->tempat_lahir }}, {{ \Carbon\Carbon::parse($selectedPemateri->tanggal_lahir)->translatedFormat('d F Y') }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-450 uppercase font-semibold">Nomor HP / WhatsApp</p>
                                                <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $selectedPemateri->no_telp }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-450 uppercase font-semibold">Akun Instagram</p>
                                                <p class="text-sm font-bold text-slate-900 mt-0.5 text-indigo-600 font-semibold">{{ $selectedPemateri->instagram ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-450 uppercase font-semibold">Alamat Email</p>
                                                <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $selectedPemateri->email ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-450 uppercase font-semibold">Motto Hidup</p>
                                                <p class="text-sm font-medium text-slate-900 mt-0.5">"{{ $selectedPemateri->motto }}"</p>
                                            </div>
                                            <div class="sm:col-span-2">
                                                <p class="text-xs text-slate-450 uppercase font-semibold">Alamat Lengkap</p>
                                                <p class="text-sm font-medium text-slate-950 mt-0.5 leading-relaxed">{{ $selectedPemateri->alamat }}</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-center justify-center p-4 bg-white border border-slate-200/60 rounded-xl shadow-xs">
                                            <p class="text-xs text-slate-450 font-semibold mb-2">Foto Pemateri</p>
                                            @if($selectedPemateri->foto)
                                                <img src="{{ asset('storage/' . $selectedPemateri->foto) }}" class="h-40 w-32 object-cover border border-slate-200 rounded-lg shadow-sm">
                                            @else
                                                <div class="h-40 w-32 bg-slate-50 flex items-center justify-center border border-slate-200 border-dashed rounded-lg text-slate-400 text-xs font-semibold">
                                                    Foto Kosong
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Riwayat Pendidikan -->
                                    <div class="space-y-3">
                                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider pl-1 border-l-4 border-indigo-600 pl-2">Riwayat Pendidikan</h4>
                                        @if($selectedPemateri->riwayatPendidikans->isEmpty())
                                            <p class="text-xs text-slate-400 italic pl-1">Tidak ada riwayat pendidikan.</p>
                                        @else
                                            <table class="min-w-full divide-y divide-slate-200">
                                                <thead>
                                                    <tr class="bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                        <th class="px-6 py-3 w-16 text-center">No</th>
                                                        <th class="px-6 py-3">Tingkat Pendidikan</th>
                                                        <th class="px-6 py-3">Nama Sekolah / Kampus</th>
                                                        <th class="px-6 py-3 text-center w-36">Tahun Lulus</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-100">
                                                    @foreach($selectedPemateri->riwayatPendidikans as $idx => $edu)
                                                        <tr class="text-sm text-slate-700">
                                                            <td class="px-6 py-3 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                                            <td class="px-6 py-3 font-semibold text-slate-800">{{ $edu->jenjang }}</td>
                                                            <td class="px-6 py-3">{{ $edu->nama_sekolah }}</td>
                                                            <td class="px-6 py-3 text-center">{{ $edu->tahun }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>

                                    <!-- Riwayat Organisasi -->
                                    <div class="space-y-3">
                                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider pl-1 border-l-4 border-indigo-600 pl-2">Riwayat Organisasi</h4>
                                        @if($selectedPemateri->riwayatOrganisasis->isEmpty())
                                            <p class="text-xs text-slate-400 italic pl-1">Tidak ada riwayat organisasi.</p>
                                        @else
                                            <table class="min-w-full divide-y divide-slate-200">
                                                <thead>
                                                    <tr class="bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                        <th class="px-6 py-3 w-16 text-center">No</th>
                                                        <th class="px-6 py-3">Nama Organisasi</th>
                                                        <th class="px-6 py-3">Jabatan</th>
                                                        <th class="px-6 py-3 text-center w-36">Periode</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-100">
                                                    @foreach($selectedPemateri->riwayatOrganisasis as $idx => $org)
                                                        <tr class="text-sm text-slate-700">
                                                            <td class="px-6 py-3 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                                            <td class="px-6 py-3 font-semibold text-slate-800">{{ $org->nama_organisasi }}</td>
                                                            <td class="px-6 py-3">{{ $org->jabatan }}</td>
                                                            <td class="px-6 py-3 text-center">{{ $org->tahun }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>

                                    <!-- Riwayat Pengkaderan -->
                                    <div class="space-y-3">
                                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider pl-1 border-l-4 border-indigo-600 pl-2">Riwayat Pengkaderan</h4>
                                        @if($selectedPemateri->riwayatPengkaderans->isEmpty())
                                            <p class="text-xs text-slate-400 italic pl-1">Tidak ada riwayat pengkaderan.</p>
                                        @else
                                            <table class="min-w-full divide-y divide-slate-200">
                                                <thead>
                                                    <tr class="bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                        <th class="px-6 py-3 w-16 text-center">No</th>
                                                        <th class="px-6 py-3">Tingkat Pengkaderan</th>
                                                        <th class="px-6 py-3">Nama Kegiatan / Tempat</th>
                                                        <th class="px-6 py-3 text-center w-36">Tahun</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-100">
                                                    @foreach($selectedPemateri->riwayatPengkaderans as $idx => $pk)
                                                        <tr class="text-sm text-slate-700">
                                                            <td class="px-6 py-3 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                                            <td class="px-6 py-3 font-semibold text-slate-800">{{ $pk->tingkat }}</td>
                                                            <td class="px-6 py-3">{{ $pk->nama }}</td>
                                                            <td class="px-6 py-3 text-center">{{ $pk->tahun }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <p class="text-center text-slate-400 py-8 text-sm italic">Silakan pilih narasumber dan klik "Tampilkan Data".</p>
                            @endif

                        @elseif($type === 'daftar_hadir')
                            @if($selectedJadwal)
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead>
                                        <tr class="bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            <th class="px-6 py-4 w-16 text-center">No</th>
                                            <th class="px-6 py-4">Nama Lengkap Peserta</th>
                                            <th class="px-6 py-4">Utusan / Delegasi</th>
                                            <th class="px-6 py-4 text-center w-40">Status</th>
                                            <th class="px-6 py-4 text-center w-52">Waktu Tap RFID</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100">
                                        @foreach($daftarHadirPesertas as $idx => $pe)
                                            @php $absen = $pe->absensis->first(); @endphp
                                            <tr class="text-sm text-slate-700 hover:bg-slate-50/40 transition">
                                                <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                                <td class="px-6 py-4 font-bold text-slate-900">{{ $pe->name }}</td>
                                                <td class="px-6 py-4 text-slate-550">{{ $pe->pendaftaran ? $pe->pendaftaran->delegasi : '-' }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-extrabold uppercase tracking-wide
                                                        {{ $absen ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                                        {{ $absen ? 'Hadir' : 'Tidak Hadir' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center text-xs text-slate-500">
                                                    {{ $absen ? $absen->created_at->translatedFormat('H:i:s') . ' WIB' : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center text-slate-400 py-8 text-sm italic">Silakan pilih sesi materi dan klik "Tampilkan Data".</p>
                            @endif

                        @elseif($type === 'penilaian_peserta')
                            @if($selectedJadwal)
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead>
                                        <tr class="bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            <th class="px-6 py-4 w-16 text-center">No</th>
                                            <th class="px-6 py-4">Nama Lengkap Peserta</th>
                                            <th class="px-6 py-4 text-center w-36">Nilai Pemahaman</th>
                                            <th class="px-6 py-4 text-center w-36">Nilai Kedisiplinan</th>
                                            <th class="px-6 py-4 text-center w-36">Nilai Keaktifan</th>
                                            <th class="px-6 py-4 text-center w-36">Rerata Skor</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100">
                                        @foreach($penilaianPesertas as $idx => $pe)
                                            @php $nilai = $pe->penilaianPesertas->first(); @endphp
                                            <tr class="text-sm text-slate-700 hover:bg-slate-50/40 transition">
                                                <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                                <td class="px-6 py-4 font-bold text-slate-900">{{ $pe->name }}</td>
                                                <td class="px-6 py-4 text-center font-semibold text-slate-800">{{ $nilai ? $nilai->pemahaman : '-' }}</td>
                                                <td class="px-6 py-4 text-center font-semibold text-slate-800">{{ $nilai ? $nilai->kedisiplinan : '-' }}</td>
                                                <td class="px-6 py-4 text-center font-semibold text-slate-800">{{ $nilai ? $nilai->keaktifan : '-' }}</td>
                                                <td class="px-6 py-4 text-center font-extrabold text-indigo-700">
                                                    {{ $nilai ? number_format($nilai->rerata, 1) : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center text-slate-400 py-8 text-sm italic">Silakan pilih sesi materi dan klik "Tampilkan Data".</p>
                            @endif

                        @elseif($type === 'observasi_harian')
                            @if($selectedKelompok)
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead>
                                        <tr class="bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            <th class="px-4 py-4 w-12 text-center">No</th>
                                            <th class="px-4 py-4">Nama Lengkap</th>
                                            <th class="px-2 py-4 text-center w-24">Hadir</th>
                                            <th class="px-2 py-4 text-center w-24">Rapi</th>
                                            <th class="px-2 py-4 text-center w-24">Sopan</th>
                                            <th class="px-2 py-4 text-center w-24">Aktif</th>
                                            <th class="px-2 py-4 text-center w-24">Kerja Sama</th>
                                            <th class="px-2 py-4 text-center w-24">Ibadah</th>
                                            <th class="px-4 py-4 text-center w-28">Nilai Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100">
                                        @foreach($observasiPesertas as $idx => $pe)
                                            @php $obs = $observasis->get($pe->id); @endphp
                                            <tr class="text-sm text-slate-700 hover:bg-slate-50/40 transition">
                                                <td class="px-4 py-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                                <td class="px-4 py-4 font-bold text-slate-900">{{ $pe->name }}</td>
                                                <td class="px-2 py-4 text-center">{{ $obs->kehadiran ?? '-' }}</td>
                                                <td class="px-2 py-4 text-center">{{ $obs->kerapian ?? '-' }}</td>
                                                <td class="px-2 py-4 text-center">{{ $obs->sopan_santun ?? '-' }}</td>
                                                <td class="px-2 py-4 text-center">{{ $obs->keaktifan ?? '-' }}</td>
                                                <td class="px-2 py-4 text-center">{{ $obs->kerja_sama ?? '-' }}</td>
                                                <td class="px-2 py-4 text-center">{{ $obs->ibadah ?? '-' }}</td>
                                                <td class="px-4 py-4 text-center font-extrabold text-amber-600">
                                                    {{ $obs ? $obs->nilai_akhir : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center text-slate-400 py-8 text-sm italic">Silakan lengkapi parameter kelompok dan klik "Tampilkan Data".</p>
                            @endif

                        @elseif($type === 'nilai_pemateri')
                            @if($selectedPeserta)
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead>
                                        <tr class="bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            <th class="px-6 py-4 w-12 text-center">No</th>
                                            <th class="px-6 py-4">Sesi Materi Pelatihan</th>
                                            <th class="px-6 py-4">Narasumber / Pemateri</th>
                                            <th class="px-6 py-4">Catatan Khusus</th>
                                            <th class="px-6 py-4 text-center w-32">Skor Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100">
                                        @foreach($jadwals as $idx => $j)
                                            @php $rating = $nilaiPemateriRatings->get($j->id); @endphp
                                            <tr class="text-sm text-slate-700 hover:bg-slate-50/40 transition">
                                                <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                                <td class="px-6 py-4 font-bold text-slate-900">{{ $j->materi->nama_materi }}</td>
                                                <td class="px-6 py-4 text-slate-600 font-medium">{{ $j->pemateri->nama }}</td>
                                                <td class="px-6 py-4 text-slate-550 italic">{{ $rating && $rating->catatan_khusus ? $rating->catatan_khusus : '-' }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    @if($rating)
                                                        <span class="inline-flex px-3 py-1 bg-indigo-50 text-indigo-700 font-extrabold rounded-lg border border-indigo-100">
                                                            {{ $rating->nilai }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-405 font-bold">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center text-slate-400 py-8 text-sm italic">Silakan pilih peserta dan klik "Tampilkan Data".</p>
                            @endif

                        @elseif($type === 'nilai_inspel')
                            @if($selectedPeserta)
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead>
                                        <tr class="bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            <th class="px-6 py-4 w-12 text-center">No</th>
                                            <th class="px-6 py-4">Nama Lengkap Instruktur (Inspel)</th>
                                            <th class="px-6 py-4">Catatan Khusus</th>
                                            <th class="px-6 py-4 text-center w-32">Skor Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100">
                                        @foreach($inspels as $idx => $ins)
                                            @php $rating = $nilaiInspelRatings->get($ins->id); @endphp
                                            <tr class="text-sm text-slate-700 hover:bg-slate-50/40 transition">
                                                <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                                <td class="px-6 py-4 font-bold text-slate-900">{{ $ins->name }}</td>
                                                <td class="px-6 py-4 text-slate-550 italic">{{ $rating && $rating->catatan_khusus ? $rating->catatan_khusus : '-' }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    @if($rating)
                                                        <span class="inline-flex px-3 py-1 bg-violet-50 text-violet-700 font-extrabold rounded-lg border border-violet-100">
                                                            {{ $rating->nilai }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-405 font-bold">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center text-slate-400 py-8 text-sm italic">Silakan pilih peserta dan klik "Tampilkan Data".</p>
                            @endif

                        @elseif($type === 'evaluasi_refleksi')
                            @if($selectedPeserta)
                                @if($evaluasiRefleksi)
                                    <div class="space-y-6 max-w-4xl">
                                        <div class="bg-slate-50/60 p-4 rounded-xl border border-slate-100 text-xs">
                                            <span class="font-bold text-slate-800">Nama Peserta:</span> {{ $selectedPeserta->name }} &nbsp;|&nbsp; 
                                            <span class="font-bold text-slate-800">Hari Ke:</span> {{ request('hari_ke') }}
                                        </div>
                                        
                                        <div class="space-y-4">
                                            <div>
                                                <p class="text-xs font-bold text-indigo-750 uppercase tracking-wide">Pertanyaan #1: Pengalaman belajar apa yang rekan/rekanita dapat dari pelatihan hari ini, yang paling bermanfaat bagi perkembangan diri anda ?</p>
                                                <p class="text-sm font-medium text-slate-850 bg-white border border-slate-100 p-3.5 rounded-xl shadow-xs mt-1.5 leading-relaxed">{!! nl2br(e($evaluasiRefleksi->q1_pengalaman)) !!}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-indigo-750 uppercase tracking-wide">Pertanyaan #2: Menurut rekan/rekanita, bagaimana tingkat partisipasi anda dalam pelatihan hari ini ?</p>
                                                <p class="text-sm font-medium text-slate-850 bg-white border border-slate-100 p-3.5 rounded-xl shadow-xs mt-1.5 leading-relaxed">{!! nl2br(e($evaluasiRefleksi->q2_partisipasi)) !!}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-indigo-750 uppercase tracking-wide">Pertanyaan #3: Adakah hal yang menghambat atau mendorong rekan/rekanita untuk berpartisipasi dalam latihan hari ini ?</p>
                                                <p class="text-sm font-medium text-slate-850 bg-white border border-slate-100 p-3.5 rounded-xl shadow-xs mt-1.5 leading-relaxed">{!! nl2br(e($evaluasiRefleksi->q3_hambatan_dorongan)) !!}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-indigo-750 uppercase tracking-wide">Pertanyaan #4: Adakah rekan/rekanita dalam sesi hari ini mempunyai kesempatan untuk mengemukakan pendapat, ide pikiran. Kapan dan dalam kesempatan apa ?</p>
                                                <p class="text-sm font-medium text-slate-850 bg-white border border-slate-100 p-3.5 rounded-xl shadow-xs mt-1.5 leading-relaxed">{!! nl2br(e($evaluasiRefleksi->q4_kesempatan_pendapat)) !!}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-indigo-750 uppercase tracking-wide">Pertanyaan #5: Pengetahuan apa saja kah yang rekan/rekanita dapatkan pada hari ini ?</p>
                                                <p class="text-sm font-medium text-slate-850 bg-white border border-slate-100 p-3.5 rounded-xl shadow-xs mt-1.5 leading-relaxed">{!! nl2br(e($evaluasiRefleksi->q5_pengetahuan_didapat)) !!}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-indigo-750 uppercase tracking-wide">Pertanyaan #6: Hal apa saja kah yang menghambat rekan/rekanita dalam mengikuti latihan hari ini, terutama yang bersumber dalam diri anda sendiri ?</p>
                                                <p class="text-sm font-medium text-slate-850 bg-white border border-slate-100 p-3.5 rounded-xl shadow-xs mt-1.5 leading-relaxed">{!! nl2br(e($evaluasiRefleksi->q6_hambatan_diri_sendiri)) !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-10 bg-amber-50/40 border border-amber-100 rounded-2xl">
                                        <svg class="w-10 h-10 text-amber-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <p class="text-slate-500 font-semibold text-sm">Peserta terpilih belum mengisi evaluasi refleksi untuk hari ini.</p>
                                    </div>
                                @endif
                            @else
                                <p class="text-center text-slate-400 py-8 text-sm italic">Silakan pilih peserta serta hari ke- dan klik "Tampilkan Data".</p>
                            @endif

                        @elseif($type === 'berkas_jawaban')
                            @if($jawabanUjians->isEmpty())
                                <div class="text-center py-12">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-slate-500 font-medium text-sm">Tidak ditemukan lembar jawaban ujian yang cocok.</p>
                                </div>
                            @else
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead>
                                        <tr class="bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            <th scope="col" class="px-6 py-4 w-12 text-center">No</th>
                                            <th scope="col" class="px-6 py-4">Peserta</th>
                                            <th scope="col" class="px-6 py-4">Delegasi</th>
                                            <th scope="col" class="px-6 py-4">Materi Pelatihan</th>
                                            <th scope="col" class="px-6 py-4 text-center w-32">Jenis Ujian</th>
                                            <th scope="col" class="px-6 py-4 text-center w-40">Waktu Unggah</th>
                                            <th scope="col" class="px-6 py-4 text-center w-36">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100">
                                        @foreach($jawabanUjians as $index => $j)
                                            <tr class="hover:bg-slate-50/40 transition align-middle">
                                                <td class="px-6 py-4 text-sm font-semibold text-slate-400 text-center">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 font-bold text-slate-900 text-sm">
                                                    {{ $j->peserta->name }}
                                                </td>
                                                <td class="px-6 py-4 text-slate-600 text-sm">
                                                    {{ $j->peserta->pendaftaran ? $j->peserta->pendaftaran->delegasi : '-' }}
                                                </td>
                                                <td class="px-6 py-4 text-slate-700 text-sm font-medium">
                                                    {{ $j->bankSoal && $j->bankSoal->materi ? $j->bankSoal->materi->nama_materi : '-' }}
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                                        {{ $j->bankSoal && $j->bankSoal->tipe == 'pretest' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                                        {{ $j->bankSoal && $j->bankSoal->tipe == 'pretest' ? 'Pre-Test' : 'Post-Test' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center text-xs text-slate-500 font-medium">
                                                    {{ $j->created_at->translatedFormat('d M Y, H:i') }} WIB
                                                </td>
                                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <a href="{{ asset($j->jawaban) }}" target="_blank"
                                                            class="inline-flex items-center gap-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs px-3 py-1.5 rounded-lg border border-slate-200 transition shadow-sm">
                                                            Lihat
                                                        </a>
                                                        <a href="{{ route($routePrefix . '.laporan.download-jawaban', $j->id) }}"
                                                            class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-3 py-1.5 rounded-lg transition shadow-sm hover:shadow">
                                                            Unduh
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        @endif
                    </div>
                </div>
            @else
                <!-- Empty State default selection -->
                <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <div class="w-20 h-20 bg-indigo-50 text-indigo-650 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H3a2 2 0 01-2-2V5a2 2 0 012-2h4l2 2h4l2-2h4a2 2 0 012 2v14a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Pilih Rekap Laporan Anda</h3>
                    <p class="text-sm text-slate-500 max-w-md mx-auto">Gunakan opsi dropdown di atas untuk memilih dan menampilkan data laporan secara interaktif di layar, lalu unduh berkas cetak PDF/berkas lampiran yang dibutuhkan.</p>
                </div>
            @endif

        </div>
    </div>

<!-- Modal Pilih Ukuran Kertas -->
<div id="paperSizeModal" class="fixed inset-0 z-50 flex items-center justify-center" style="display:none; background: rgba(15,23,42,0.55); backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-sm mx-4 transform transition-all" style="animation: modalIn 0.2s ease;">
        <div class="flex items-center gap-3 mb-2">
            <div class="p-2 bg-indigo-50 rounded-xl">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Pilih Ukuran Kertas</h3>
        </div>
        <p class="text-sm text-slate-500 mb-6 pl-10">Pilih ukuran kertas untuk file PDF yang akan diunduh.</p>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <!-- A4 -->
            <button onclick="submitWithPaper('a4')"
                class="group flex flex-col items-center justify-center p-5 border-2 border-slate-200 hover:border-indigo-500 hover:bg-indigo-50 rounded-xl transition-all cursor-pointer">
                <div class="w-10 h-14 border-2 border-slate-300 group-hover:border-indigo-400 rounded-sm mb-3 flex items-end justify-center pb-1 relative transition-all">
                    <div class="absolute top-0 right-0 w-3 h-3 border-l-2 border-b-2 border-slate-300 group-hover:border-indigo-400 transition-all bg-white" style="border-top-right-radius:3px;"></div>
                    <span class="text-xs font-bold text-slate-400 group-hover:text-indigo-500 transition-all" style="font-size:8px;">A4</span>
                </div>
                <span class="font-bold text-slate-700 group-hover:text-indigo-600 transition-all">A4</span>
                <span class="text-xs text-slate-400 mt-0.5">210 × 297 mm</span>
            </button>

            <!-- F4 / Folio -->
            <button onclick="submitWithPaper('f4')"
                class="group flex flex-col items-center justify-center p-5 border-2 border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 rounded-xl transition-all cursor-pointer">
                <div class="w-10 h-16 border-2 border-slate-300 group-hover:border-emerald-400 rounded-sm mb-3 flex items-end justify-center pb-1 relative transition-all">
                    <div class="absolute top-0 right-0 w-3 h-3 border-l-2 border-b-2 border-slate-300 group-hover:border-emerald-400 transition-all bg-white" style="border-top-right-radius:3px;"></div>
                    <span class="text-xs font-bold text-slate-400 group-hover:text-emerald-500 transition-all" style="font-size:8px;">F4</span>
                </div>
                <span class="font-bold text-slate-700 group-hover:text-emerald-600 transition-all">F4 / Folio</span>
                <span class="text-xs text-slate-400 mt-0.5">215 × 330 mm</span>
            </button>
        </div>

        <button onclick="closePaperModal()" class="w-full text-center text-sm text-slate-400 hover:text-slate-600 transition py-1">
            Batal
        </button>
    </div>
</div>

<style>
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(8px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
</style>

<script>
    let _pendingDownloadAll = 0;

    function showPaperModal(downloadAll) {
        _pendingDownloadAll = downloadAll;
        const modal = document.getElementById('paperSizeModal');
        modal.style.display = 'flex';
    }

    function closePaperModal() {
        document.getElementById('paperSizeModal').style.display = 'none';
    }

    function submitWithPaper(size) {
        document.getElementById('paperSizeInput').value = size;
        document.getElementById('downloadAllInput').value = _pendingDownloadAll;
        const form = document.getElementById('laporanFilterForm');
        form.action = '{{ route($routePrefix . ".laporan.download") }}';
        form.target = '_blank';
        closePaperModal();
        form.submit();
        // Reset form action after brief delay so future Tampilkan Data works normally
        setTimeout(() => { form.action = '{{ route($routePrefix . ".laporan.index") }}'; form.target = ''; }, 500);
    }

    // Close modal on backdrop click
    document.getElementById('paperSizeModal').addEventListener('click', function(e) {
        if (e.target === this) closePaperModal();
    });
</script>

</x-app-layout>
