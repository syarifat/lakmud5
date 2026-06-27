@php
    $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'inspel';
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Slide CV Pemateri') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Selection Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sm:p-8">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-6">
                    <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1-1H3" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Presentasi Profil Pemateri</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pilih narasumber untuk menampilkan profil penuh di layar proyektor dengan animasi latar bergerak.</p>
                    </div>
                </div>

                <form method="GET" action="{{ route($routePrefix . '.slide-cv') }}" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-end">
                        <div class="sm:col-span-2">
                            <x-input-label for="pemateri_id" value="Pilih Pemateri / Narasumber" />
                            <select id="pemateri_id" name="pemateri_id" onchange="this.form.submit()"
                                class="mt-1.5 block w-full text-sm rounded-xl border-slate-350 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <option value="">-- Pilih Pemateri --</option>
                                @foreach($pemateris as $p)
                                    <option value="{{ $p->id }}" {{ request('pemateri_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            @if($selectedPemateri)
                                <button type="button" onclick="startPresentation()"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-6 py-3 rounded-xl transition shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Mulai Presentasi
                                </button>
                            @else
                                <button type="button" disabled
                                    class="w-full inline-flex items-center justify-center gap-2 bg-slate-100 text-slate-400 font-bold text-sm px-6 py-3 rounded-xl cursor-not-allowed border border-slate-200">
                                    Pilih Pemateri Dahulu
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Preview panel -->
            @if($selectedPemateri)
                <div class="bg-indigo-50/50 rounded-3xl border border-indigo-100 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ $selectedPemateri->foto ? asset('storage/' . $selectedPemateri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($selectedPemateri->nama) }}" 
                            class="h-16 w-16 rounded-2xl object-cover border border-white shadow-sm">
                        <div>
                            <h4 class="font-bold text-slate-800 text-base">{{ $selectedPemateri->nama }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">CV siap diproyeksikan dalam 1 halaman interaktif penuh.</p>
                        </div>
                    </div>
                    <button type="button" onclick="startPresentation()"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-700 bg-white hover:bg-indigo-50 border border-indigo-200 px-4 py-2.5 rounded-xl shadow-xs transition">
                        Klik untuk Layar Penuh
                    </button>
                </div>
            @endif

        </div>
    </div>

    <!-- Presentation Overlay Container -->
    @if($selectedPemateri)
        <style>
            #cv-canvas {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                z-index: 0;
            }
            #slideContainer {
                background: #04040a;
            }
            /* Custom thin scrollbar for dashboard lists */
            .cv-scroll::-webkit-scrollbar {
                width: 5px;
            }
            .cv-scroll::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.02);
                border-radius: 8px;
            }
            .cv-scroll::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.12);
                border-radius: 8px;
            }
            .cv-scroll::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.25);
            }
        </style>

        <div id="slideContainer" class="hidden fixed inset-0 z-50 text-white overflow-hidden select-none"
             @keydown.escape.window="exitPresentation()">
             
            <!-- Canvas live animation -->
            <canvas id="cv-canvas"></canvas>

            <!-- Top Header controls -->
            <div class="absolute top-0 left-0 right-0 p-6 flex justify-between items-center z-20 bg-gradient-to-b from-slate-950/90 via-slate-950/40 to-transparent">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo.png') }}" class="h-10 w-auto">
                    <div>
                        <h1 class="text-[10px] font-bold tracking-widest text-emerald-400 uppercase">LAKMUD V - PAC IPNU IPPNU KAUMAN</h1>
                        <p class="text-sm font-extrabold text-slate-100 mt-0.5">Curriculum Vitae Pemateri</p>
                    </div>
                </div>
                
                <button type="button" onclick="exitPresentation()"
                    class="p-2.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl transition text-slate-300 hover:text-white shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Master Fullscreen Layout Grid -->
            <div class="w-full h-full pt-24 pb-8 px-6 sm:px-10 md:px-12 flex flex-col justify-between z-10 relative">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch h-[calc(100vh-140px)] overflow-hidden">
                    
                    <!-- COLUMN 1: Profile Photo, Name, Motto, & Biodata -->
                    <div class="lg:col-span-1 bg-white/5 border border-white/10 rounded-3xl p-6 flex flex-col justify-between overflow-hidden shadow-2xl backdrop-blur-xl">
                        <!-- Profile Card -->
                        <div class="flex flex-col items-center text-center space-y-4">
                            <div class="relative flex-shrink-0">
                                <div class="absolute inset-0 bg-indigo-500 rounded-2xl blur-xl opacity-20"></div>
                                @if($selectedPemateri->foto)
                                    <img src="{{ asset('storage/' . $selectedPemateri->foto) }}" 
                                         class="relative h-[220px] w-[170px] rounded-2xl object-cover border-2 border-white/20 shadow-lg">
                                @else
                                    <div class="relative h-[220px] w-[170px] rounded-2xl bg-slate-900 border-2 border-white/20 flex flex-col items-center justify-center shadow-lg">
                                        <svg class="w-12 h-12 text-slate-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">No Foto</span>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-1">
                                <h2 class="text-2xl font-black tracking-tight leading-tight text-transparent bg-clip-text bg-gradient-to-r from-white to-indigo-200">
                                    {{ $selectedPemateri->nama }}
                                </h2>
                                <p class="text-xs text-indigo-400 font-bold tracking-widest uppercase">Narasumber / Pemateri</p>
                            </div>

                            <div class="text-slate-350 text-xs italic font-medium bg-white/5 border border-white/5 rounded-xl px-4 py-2.5 max-w-xs leading-relaxed">
                                "{{ $selectedPemateri->motto }}"
                            </div>
                        </div>

                        <!-- Biodata List -->
                        <div class="space-y-3.5 border-t border-white/10 pt-4 mt-4 text-xs">
                            <div class="flex justify-between items-start gap-4">
                                <span class="text-slate-450 font-bold uppercase tracking-wider flex-shrink-0">Lahir</span>
                                <span class="text-slate-200 text-right font-semibold">
                                    {{ $selectedPemateri->tempat_lahir }}, {{ \Carbon\Carbon::parse($selectedPemateri->tanggal_lahir)->translatedFormat('d F Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center gap-4">
                                <span class="text-slate-450 font-bold uppercase tracking-wider flex-shrink-0">HP / WA</span>
                                <span class="text-slate-200 font-semibold">{{ $selectedPemateri->no_telp }}</span>
                            </div>
                            <div class="flex justify-between items-center gap-4">
                                <span class="text-slate-450 font-bold uppercase tracking-wider flex-shrink-0">Instagram</span>
                                <span class="text-indigo-400 font-extrabold">{{ $selectedPemateri->instagram ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center gap-4">
                                <span class="text-slate-450 font-bold uppercase tracking-wider flex-shrink-0">Email</span>
                                <span class="text-slate-200 font-semibold">{{ $selectedPemateri->email ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-start gap-4">
                                <span class="text-slate-450 font-bold uppercase tracking-wider flex-shrink-0">Alamat</span>
                                <span class="text-slate-200 text-right font-medium leading-relaxed max-w-[180px]">{{ $selectedPemateri->alamat }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- COLUMN 2: Riwayat Pendidikan & Pengkaderan -->
                    <div class="lg:col-span-1 flex flex-col gap-6 overflow-hidden h-full">
                        <!-- Riwayat Pendidikan -->
                        <div class="flex-1 bg-white/5 border border-white/10 rounded-3xl p-5 flex flex-col overflow-hidden shadow-2xl backdrop-blur-xl">
                            <h3 class="text-sm font-black text-emerald-450 uppercase tracking-widest border-b border-white/10 pb-2.5 mb-3 flex items-center gap-2 flex-shrink-0">
                                🎓 Riwayat Pendidikan
                            </h3>
                            <div class="flex-grow overflow-y-auto cv-scroll space-y-3.5 pr-2">
                                @forelse($selectedPemateri->riwayatPendidikans as $edu)
                                    <div class="bg-white/5 border border-white/5 rounded-xl p-3.5 flex justify-between items-center gap-4 hover:bg-white/8 transition">
                                        <div class="space-y-0.5">
                                            <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 text-[9px] font-extrabold uppercase rounded-md tracking-wider">
                                                {{ $edu->jenjang }}
                                            </span>
                                            <h4 class="font-bold text-slate-100 text-sm mt-1">{{ $edu->nama_sekolah }}</h4>
                                        </div>
                                        <span class="text-sm font-black text-emerald-400">{{ $edu->tahun }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-500 italic text-center py-6">Belum ada data riwayat pendidikan.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Riwayat Pengkaderan -->
                        <div class="flex-1 bg-white/5 border border-white/10 rounded-3xl p-5 flex flex-col overflow-hidden shadow-2xl backdrop-blur-xl">
                            <h3 class="text-sm font-black text-indigo-455 uppercase tracking-widest border-b border-white/10 pb-2.5 mb-3 flex items-center gap-2 flex-shrink-0">
                                🎖️ Riwayat Pengkaderan
                            </h3>
                            <div class="flex-grow overflow-y-auto cv-scroll space-y-3.5 pr-2">
                                @forelse($selectedPemateri->riwayatPengkaderans as $pk)
                                    <div class="bg-white/5 border border-white/5 rounded-xl p-3.5 flex justify-between items-center gap-4 hover:bg-white/8 transition">
                                        <div class="space-y-0.5">
                                            <span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-400 text-[9px] font-extrabold uppercase rounded-md tracking-wider">
                                                {{ $pk->tingkat }}
                                            </span>
                                            <h4 class="font-bold text-slate-100 text-sm mt-1">{{ $pk->nama }}</h4>
                                        </div>
                                        <span class="text-sm font-black text-indigo-400">{{ $pk->tahun }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-500 italic text-center py-6">Belum ada data riwayat pengkaderan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- COLUMN 3: Riwayat Organisasi -->
                    <div class="lg:col-span-1 bg-white/5 border border-white/10 rounded-3xl p-5 flex flex-col overflow-hidden shadow-2xl backdrop-blur-xl">
                        <h3 class="text-sm font-black text-rose-450 uppercase tracking-widest border-b border-white/10 pb-2.5 mb-3 flex items-center gap-2 flex-shrink-0">
                            🤝 Riwayat Organisasi
                        </h3>
                        <div class="flex-grow overflow-y-auto cv-scroll space-y-3.5 pr-2">
                            @forelse($selectedPemateri->riwayatOrganisasis as $org)
                                <div class="bg-white/5 border border-white/5 rounded-xl p-4 flex flex-col justify-between gap-3 hover:bg-white/8 transition">
                                    <div>
                                        <h4 class="font-bold text-slate-100 text-sm">{{ $org->nama_organisasi }}</h4>
                                        <p class="text-xs text-rose-450 font-bold mt-0.5">{{ $org->jabatan }}</p>
                                    </div>
                                    <div class="flex justify-end">
                                        <span class="text-[10px] font-extrabold text-slate-400 bg-white/5 border border-white/10 px-2 py-1 rounded-md">
                                            {{ $org->tahun }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-500 italic text-center py-10">Belum ada data riwayat organisasi.</p>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>
        </div>
    @endif

    <script>
        /* ============================================================
           CANVAS LIVE BACKGROUND ENGINE
           – Hue-rotating gradient wash
           – Drifting glow orbs (fog clouds)
           – Floating batik ornamental rings
           – Starfield particles
        ============================================================ */
        const canvas = document.getElementById('cv-canvas');
        const ctx = canvas.getContext('2d');
        let W, H, raf;
        let tick = 0;

        function resize() {
            W = canvas.width  = canvas.offsetWidth  || window.innerWidth;
            H = canvas.height = canvas.offsetHeight || window.innerHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        /* --- Glow Orbs (moving fog) --- */
        const orbs = [
            { x: 0.15, y: 0.15, r: 0.55, hue: 240, spd: 0.00035, phase: 0 },
            { x: 0.80, y: 0.75, r: 0.50, hue: 160, spd: 0.00028, phase: 1.2 },
            { x: 0.50, y: 0.45, r: 0.45, hue: 280, spd: 0.00040, phase: 2.4 },
            { x: 0.20, y: 0.80, r: 0.38, hue: 190, spd: 0.00032, phase: 3.6 },
            { x: 0.85, y: 0.20, r: 0.42, hue: 320, spd: 0.00025, phase: 4.8 },
        ];

        /* --- Batik ornament rings --- */
        const rings = [];
        for (let i = 0; i < 10; i++) {
            rings.push({
                x:      Math.random(),
                y:      Math.random(),
                r:      60 + Math.random() * 140,
                spokes: 8 + Math.floor(Math.random() * 8),
                rot:    Math.random() * Math.PI * 2,
                rotSpd: (Math.random() - 0.5) * 0.003,
                driftX: (Math.random() - 0.5) * 0.00012,
                driftY: (Math.random() - 0.5) * 0.00012,
                hue:    Math.random() * 360,
                alpha:  0.04 + Math.random() * 0.08,
            });
        }

        /* --- Particles --- */
        const particles = [];
        for (let i = 0; i < 180; i++) {
            particles.push({
                x:   Math.random(),
                y:   Math.random(),
                r:   0.5 + Math.random() * 2,
                vx:  (Math.random() - 0.5) * 0.00025,
                vy: -0.00010 - Math.random() * 0.00015,
                hue: Math.random() * 360,
                a:   0.2 + Math.random() * 0.6,
            });
        }

        function drawOrbBackground() {
            /* Dark base */
            ctx.fillStyle = `hsl(${240 + Math.sin(tick * 0.0004) * 30}, 40%, 4%)`;
            ctx.fillRect(0, 0, W, H);

            /* Orbs */
            orbs.forEach(o => {
                const cx = (0.5 + 0.45 * Math.sin(tick * o.spd + o.phase)) * W;
                const cy = (0.5 + 0.40 * Math.cos(tick * o.spd * 0.7 + o.phase)) * H;
                const radius = o.r * Math.max(W, H) * 0.5;
                const hue = (o.hue + tick * 0.018) % 360;

                const grad = ctx.createRadialGradient(cx, cy, 0, cx, cy, radius);
                grad.addColorStop(0,   `hsla(${hue}, 90%, 60%, 0.22)`);
                grad.addColorStop(0.5, `hsla(${hue}, 80%, 50%, 0.10)`);
                grad.addColorStop(1,   `hsla(${hue}, 70%, 40%, 0)`);

                ctx.globalCompositeOperation = 'screen';
                ctx.fillStyle = grad;
                ctx.beginPath();
                ctx.ellipse(cx, cy, radius, radius * 0.75, tick * 0.0002, 0, Math.PI * 2);
                ctx.fill();
            });
            ctx.globalCompositeOperation = 'source-over';
        }

        /* Draw a single batik-style ornament ring */
        function drawBatikRing(rng) {
            const cx = rng.x * W;
            const cy = rng.y * H;
            const hue = (rng.hue + tick * 0.025) % 360;

            ctx.save();
            ctx.translate(cx, cy);
            ctx.rotate(rng.rot);
            ctx.globalAlpha = rng.alpha;
            ctx.strokeStyle = `hsl(${hue}, 80%, 75%)`;
            ctx.lineWidth = 1;

            /* Outer ring */
            ctx.beginPath();
            ctx.arc(0, 0, rng.r, 0, Math.PI * 2);
            ctx.stroke();

            /* Inner ring */
            ctx.beginPath();
            ctx.arc(0, 0, rng.r * 0.7, 0, Math.PI * 2);
            ctx.stroke();

            /* Innermost */
            ctx.beginPath();
            ctx.arc(0, 0, rng.r * 0.4, 0, Math.PI * 2);
            ctx.stroke();

            /* Spokes */
            for (let s = 0; s < rng.spokes; s++) {
                const angle = (s / rng.spokes) * Math.PI * 2;
                ctx.beginPath();
                ctx.moveTo(Math.cos(angle) * rng.r * 0.4, Math.sin(angle) * rng.r * 0.4);
                ctx.lineTo(Math.cos(angle) * rng.r, Math.sin(angle) * rng.r);
                ctx.stroke();

                /* Petal at tip */
                const px = Math.cos(angle) * rng.r * 0.85;
                const py = Math.sin(angle) * rng.r * 0.85;
                ctx.beginPath();
                ctx.arc(px, py, rng.r * 0.09, 0, Math.PI * 2);
                ctx.stroke();
            }

            /* Diamond accents at cardinal points */
            for (let d = 0; d < 4; d++) {
                const ang = (d / 4) * Math.PI * 2;
                const dx = Math.cos(ang) * rng.r * 0.55;
                const dy = Math.sin(ang) * rng.r * 0.55;
                ctx.beginPath();
                ctx.save();
                ctx.translate(dx, dy);
                ctx.rotate(ang + Math.PI / 4);
                const ds = rng.r * 0.08;
                ctx.rect(-ds, -ds, ds * 2, ds * 2);
                ctx.restore();
                ctx.stroke();
            }

            ctx.restore();
        }

        function drawParticles() {
            particles.forEach(p => {
                const hue = (p.hue + tick * 0.04) % 360;
                ctx.globalAlpha = p.a * (0.5 + 0.5 * Math.sin(tick * 0.004 + p.hue));
                ctx.fillStyle = `hsl(${hue}, 90%, 80%)`;
                ctx.beginPath();
                ctx.arc(p.x * W, p.y * H, p.r, 0, Math.PI * 2);
                ctx.fill();
            });
            ctx.globalAlpha = 1;
        }

        function update() {
            tick++;

            /* Update rings */
            rings.forEach(rng => {
                rng.rot  += rng.rotSpd;
                rng.x    += rng.driftX;
                rng.y    += rng.driftY;
                if (rng.x < -0.1) rng.x = 1.1;
                if (rng.x > 1.1)  rng.x = -0.1;
                if (rng.y < -0.1) rng.y = 1.1;
                if (rng.y > 1.1)  rng.y = -0.1;
            });

            /* Update particles */
            particles.forEach(p => {
                p.x += p.vx;
                p.y += p.vy;
                if (p.y < -0.02) { p.y = 1.02; p.x = Math.random(); }
                if (p.x < -0.02) p.x = 1.02;
                if (p.x > 1.02)  p.x = -0.02;
            });
        }

        function draw() {
            resize();
            drawOrbBackground();
            rings.forEach(rng => drawBatikRing(rng));
            drawParticles();
            update();
            raf = requestAnimationFrame(draw);
        }

        let animRunning = false;

        function startPresentation() {
            const container = document.getElementById('slideContainer');
            if (container) {
                container.classList.remove('hidden');
                if (!animRunning) {
                    animRunning = true;
                    resize();
                    draw();
                }
                if (container.requestFullscreen) {
                    container.requestFullscreen();
                } else if (container.webkitRequestFullscreen) {
                    container.webkitRequestFullscreen();
                } else if (container.msRequestFullscreen) {
                    container.msRequestFullscreen();
                }
            }
        }

        function exitPresentation() {
            cancelAnimationFrame(raf);
            animRunning = false;
            const container = document.getElementById('slideContainer');
            if (container) {
                container.classList.add('hidden');
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        }

        document.addEventListener('fullscreenchange', () => {
            const container = document.getElementById('slideContainer');
            if (!document.fullscreenElement && container) {
                cancelAnimationFrame(raf);
                animRunning = false;
                container.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
