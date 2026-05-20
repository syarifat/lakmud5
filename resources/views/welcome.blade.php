<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAKMUD V - PAC IPNU IPPNU Kauman</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
</head>
<body class="font-[figtree] antialiased bg-gray-50 text-gray-800 selection:bg-emerald-500 selection:text-white">

    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo.png') }}" alt="Logo LAKMUD" class="h-10 w-auto">
                    <span class="font-bold text-xl text-emerald-800 tracking-tight">LAKMUD V <span class="text-amber-500">Kauman</span></span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#tentang" class="text-gray-600 hover:text-emerald-600 font-medium transition">Tentang</a>
                    <a href="#timeline" class="text-gray-600 hover:text-emerald-600 font-medium transition">Alur Kegiatan</a>
                    <a href="#syarat" class="text-gray-600 hover:text-emerald-600 font-medium transition">Persyaratan</a>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-xs sm:text-sm font-semibold text-emerald-700 hover:text-emerald-800">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-gray-600 hover:text-emerald-600 px-1">Login</a>
                            <a href="{{ route('pendaftar.biodata') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 sm:px-5 sm:py-2 rounded-full text-[10px] sm:text-sm font-semibold shadow-md shadow-emerald-200 transition transform hover:-translate-y-0.5 active:scale-95">
                                Daftar Sekarang
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-white -z-10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center md:text-left flex flex-col md:flex-row items-center gap-12">
            <div class="md:w-1/2 space-y-6">
                <div class="inline-block bg-amber-100 text-amber-700 px-4 py-1.5 rounded-full text-sm font-bold tracking-wide mb-2 border border-amber-200">
                    LATIHAN KADER MUDA V
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight">
                    Steady Growth:<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Mengenali, Beraksi, Berdikari</span>
                </h1>
                <p class="text-lg text-gray-600 leading-relaxed md:pr-10">
                    Menghadapi tantangan zaman yang serba cepat, jadilah pemimpin yang sadar potensi diri, tangguh, dan mandiri dalam menggerakkan roda organisasi.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start pt-4">
                    <a href="{{ route('pendaftar.biodata') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3.5 rounded-full text-lg font-bold shadow-lg shadow-emerald-300 transition transform hover:-translate-y-1 text-center flex justify-center items-center gap-2">
                        Daftar LAKMUD V
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                    <a href="#tentang" class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-8 py-3.5 rounded-full text-lg font-semibold shadow-sm transition text-center">
                        Pelajari Dulu
                    </a>
                </div>
            </div>
            
            <div class="md:w-1/2 w-full relative">
                <div class="aspect-square md:aspect-[4/3] bg-emerald-100 rounded-3xl overflow-hidden shadow-2xl relative border-4 border-white">
                    <div class="absolute inset-0 bg-emerald-800 opacity-10 pattern-dots"></div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-emerald-800 p-8 text-center">
                        <img src="{{ asset('logo.png') }}" alt="Logo" class="w-32 md:w-48 mb-6 drop-shadow-md">
                        <p class="font-bold text-xl md:text-2xl">27 - 30 Juni 2026</p>
                        <p class="font-medium text-emerald-700">SMP Negeri 2 Kauman</p>
                    </div>
                </div>
                <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl border border-gray-100 flex items-center gap-4">
                    <div class="bg-amber-100 p-3 rounded-full text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Penyelenggara</p>
                        <p class="text-sm font-bold text-gray-900">PAC IPNU IPPNU Kauman</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="tentang" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Mengapa LAKMUD V?</h2>
                <div class="w-16 h-1 bg-emerald-500 mx-auto rounded-full"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-2xl border border-gray-100 hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Mengenali</h3>
                    <p class="text-gray-600">Mengenali jati diri dan potensi sebagai fondasi utama sebelum terjun ke masyarakat dan organisasi.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-2xl border border-gray-100 hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Beraksi</h3>
                    <p class="text-gray-600">Berani mengambil langkah dan tindakan nyata secara progresif bagi kemajuan bersama dan lingkungan sekitar.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-2xl border border-gray-100 hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Berdikari</h3>
                    <p class="text-gray-600">Menjadi pribadi mandiri dalam berpikir serta bertanggung jawab penuh atas setiap langkah yang diambil.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="timeline" class="py-20 bg-emerald-900 text-white relative z-0">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-800 rounded-full blur-3xl opacity-50 -z-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold mb-4">Alur Pendaftaran & Kegiatan</h2>
                <p class="text-emerald-200">Catat tanggal pentingnya dan pastikan kamu tidak terlewat.</p>
            </div>
            
            <div class="relative border-l-2 border-emerald-700 ml-3 md:ml-0 md:border-none md:flex md:justify-between md:gap-4 space-y-8 md:space-y-0">
                <div class="hidden md:block absolute top-[11px] left-[10%] right-[10%] h-0.5 bg-emerald-700 z-0"></div>

                <div class="relative pl-8 md:pl-0 md:w-1/5 md:text-center group">
                    <div class="absolute left-[-9px] md:left-1/2 md:-ml-2 top-1 w-4 h-4 rounded-full bg-amber-400 border-4 border-emerald-900 group-hover:scale-125 transition z-10"></div>
                    <div class="md:pt-8">
                        <p class="text-sm text-emerald-300 font-semibold mb-1">17 Mei - 10 Juni</p>
                        <h4 class="text-lg font-bold">Pendaftaran</h4>
                    </div>
                </div>
                <div class="relative pl-8 md:pl-0 md:w-1/5 md:text-center group">
                    <div class="absolute left-[-9px] md:left-1/2 md:-ml-2 top-1 w-4 h-4 rounded-full bg-emerald-500 border-4 border-emerald-900 group-hover:scale-125 transition z-10"></div>
                    <div class="md:pt-8">
                        <p class="text-sm text-emerald-300 font-semibold mb-1">19 Juni</p>
                        <h4 class="text-lg font-bold">Screening</h4>
                    </div>
                </div>
                <div class="relative pl-8 md:pl-0 md:w-1/5 md:text-center group">
                    <div class="absolute left-[-9px] md:left-1/2 md:-ml-2 top-1 w-4 h-4 rounded-full bg-emerald-500 border-4 border-emerald-900 group-hover:scale-125 transition z-10"></div>
                    <div class="md:pt-8">
                        <p class="text-sm text-emerald-300 font-semibold mb-1">20 Juni</p>
                        <h4 class="text-lg font-bold">Pengumuman Lolos</h4>
                    </div>
                </div>
                <div class="relative pl-8 md:pl-0 md:w-1/5 md:text-center group">
                    <div class="absolute left-[-9px] md:left-1/2 md:-ml-2 top-1 w-4 h-4 rounded-full bg-emerald-500 border-4 border-emerald-900 group-hover:scale-125 transition z-10"></div>
                    <div class="md:pt-8">
                        <p class="text-sm text-emerald-300 font-semibold mb-1">22 Juni</p>
                        <h4 class="text-lg font-bold">Pembekalan</h4>
                    </div>
                </div>
                <div class="relative pl-8 md:pl-0 md:w-1/5 md:text-center group">
                    <div class="absolute left-[-9px] md:left-1/2 md:-ml-2 top-1 w-4 h-4 rounded-full bg-amber-400 border-4 border-emerald-900 group-hover:scale-125 transition shadow-[0_0_15px_rgba(251,191,36,0.5)] z-10"></div>
                    <div class="md:pt-8">
                        <p class="text-sm text-amber-300 font-semibold mb-1">27 - 30 Juni 2026</p>
                        <h4 class="text-lg font-bold">Pelaksanaan LAKMUD</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="syarat" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Persyaratan Peserta
                    </h3>
                    <ul class="space-y-4 text-gray-600">
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-500 mt-1">✔</span>
                            <span>Kader IPNU IPPNU Kauman yang telah lulus MAKESTA (dibuktikan dengan Sertifikat/Surat Keterangan).</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-500 mt-1">✔</span>
                            <span>Mengisi formulir dan upload identitas & sertifikat di sistem ini.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-500 mt-1">✔</span>
                            <span>Membawa Pas Foto 3x4 (Background Merah) 2 lembar dan Materai Rp. 10.000 (1 lembar).</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-500 mt-1">✔</span>
                            <span>Membawa logistik beras 1 Kg.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-500 mt-1">✔</span>
                            <span>Infaq Kegiatan: Rp. 110.000 (Internal PAC) | Rp. 130.000 (Eksternal).</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-emerald-50 p-8 rounded-3xl shadow-sm border border-emerald-100">
                    <h3 class="text-2xl font-bold text-emerald-900 mb-6 flex items-center gap-3">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Fasilitas yang Didapat
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-xl flex items-center gap-3 shadow-sm text-sm font-semibold text-gray-700">
                            <span class="text-2xl">🏷️</span> ID Card
                        </div>
                        <div class="bg-white p-4 rounded-xl flex items-center gap-3 shadow-sm text-sm font-semibold text-gray-700">
                            <span class="text-2xl">🎒</span> LAKMUD Kit
                        </div>
                        <div class="bg-white p-4 rounded-xl flex items-center gap-3 shadow-sm text-sm font-semibold text-gray-700">
                            <span class="text-2xl">👕</span> Kaos Pelatihan
                        </div>
                        <div class="bg-white p-4 rounded-xl flex items-center gap-3 shadow-sm text-sm font-semibold text-gray-700">
                            <span class="text-2xl">🍱</span> Konsumsi
                        </div>
                        <div class="bg-white p-4 rounded-xl flex items-center gap-3 shadow-sm text-sm font-semibold text-gray-700">
                            <span class="text-2xl">📁</span> File Materi
                        </div>
                        <div class="bg-white p-4 rounded-xl flex items-center gap-3 shadow-sm text-sm font-semibold text-gray-700">
                            <span class="text-2xl">📜</span> E-Sertifikat
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white border-t border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl p-8 md:p-12 shadow-xl shadow-emerald-200 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="text-center md:text-left">
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">Butuh Panduan Lengkap?</h2>
                    <p class="text-emerald-50 opacity-90">Unduh Terms of Reference (TOR) LAKMUD V untuk informasi lebih detail mengenai materi, jadwal, dan perlengkapan.</p>
                </div>
                <div class="shrink-0">
                    <a href="{{ asset('TOR LAKMUD V - PAC IPNU IPPNU Kauman.pdf') }}" download="TOR LAKMUD V - PAC IPNU IPPNU Kauman.pdf" class="inline-flex items-center gap-3 bg-white text-emerald-700 hover:bg-emerald-50 px-8 py-4 rounded-2xl font-bold text-lg shadow-lg transition transform hover:-translate-y-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        Download TOR (.pdf)
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left md:flex justify-between items-center">
            <div class="mb-6 md:mb-0">
                <div class="flex items-center justify-center md:justify-start gap-2 mb-4">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-8 grayscale opacity-70">
                    <span class="text-white font-bold text-lg">LAKMUD V</span>
                </div>
                <p class="text-sm">Dipersembahkan oleh Departemen Kaderisasi<br>PAC IPNU IPPNU Kecamatan Kauman Masa Khidmat 2025-2027.</p>
            </div>
            <div>
                <p class="text-sm mb-2">Punya Pertanyaan?</p>
                <a href="#" class="text-emerald-400 font-semibold hover:text-emerald-300">Hubungi Panitia (WhatsApp)</a>
                <p class="text-xs mt-4">© 2026 PAC IPNU IPPNU Kauman. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>