<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Ditutup – LAKMUD V</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            background: #04040a;
            overflow: hidden;
            color: #fff;
        }

        canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .card {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 480px;
            padding: 56px 48px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 28px;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            box-shadow: 0 32px 80px rgba(0,0,0,0.5);
            animation: fadeUp 0.8s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .icon-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(239,68,68,0.12);
            border: 2px solid rgba(239,68,68,0.25);
            margin-bottom: 24px;
        }

        .icon-wrap svg {
            width: 34px;
            height: 34px;
            color: #f87171;
        }

        .badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #f87171;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 999px;
            padding: 4px 14px;
            margin-bottom: 16px;
        }

        h1 {
            font-size: 28px;
            font-weight: 900;
            line-height: 1.2;
            background: linear-gradient(135deg, #fff 30%, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 12px;
        }

        p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.06);
            margin: 24px 0;
        }

        .org {
            font-size: 11px;
            font-weight: 600;
            color: #475569;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .login-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #818cf8;
            text-decoration: none;
            border: 1px solid rgba(129,140,248,0.25);
            border-radius: 12px;
            padding: 10px 22px;
            transition: background 0.2s, color 0.2s;
        }
        .login-link:hover {
            background: rgba(129,140,248,0.12);
            color: #a5b4fc;
        }
    </style>
</head>
<body>
    <canvas id="bg-canvas"></canvas>

    <div class="card">
        <div class="icon-wrap">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
        </div>

        <div class="badge">Status Pendaftaran</div>

        <h1>Pendaftaran<br>Sudah Ditutup</h1>

        <p>
            Masa pendaftaran LAKMUD V telah berakhir.<br>
            Terima kasih atas antusiasme dan minat Anda<br>
            untuk bergabung bersama kami.
        </p>

        <a href="/login" class="login-link">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Masuk ke Sistem
        </a>

        <hr class="divider">
        <p class="org">PAC IPNU IPPNU Kauman &bull; LAKMUD V</p>
    </div>

    <script>
        const canvas = document.getElementById('bg-canvas');
        const ctx = canvas.getContext('2d');
        let W, H, tick = 0, raf;

        function resize() {
            W = canvas.width  = window.innerWidth;
            H = canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        const orbs = [
            { hue: 0,   spd: 0.00030, phase: 0 },
            { hue: 240, spd: 0.00022, phase: 2.1 },
            { hue: 280, spd: 0.00038, phase: 4.2 },
        ];

        const rings = [];
        for (let i = 0; i < 8; i++) {
            rings.push({
                x: Math.random(), y: Math.random(),
                r: 50 + Math.random() * 120,
                spokes: 8 + Math.floor(Math.random() * 8),
                rot: Math.random() * Math.PI * 2,
                rotSpd: (Math.random() - 0.5) * 0.003,
                driftX: (Math.random() - 0.5) * 0.00012,
                driftY: (Math.random() - 0.5) * 0.00012,
                hue: Math.random() * 360,
                alpha: 0.04 + Math.random() * 0.07,
            });
        }

        const particles = [];
        for (let i = 0; i < 120; i++) {
            particles.push({
                x: Math.random(), y: Math.random(),
                r: 0.5 + Math.random() * 1.5,
                vx: (Math.random() - 0.5) * 0.00020,
                vy: -0.00008 - Math.random() * 0.00013,
                hue: Math.random() * 360,
                a: 0.15 + Math.random() * 0.5,
            });
        }

        function draw() {
            tick++;
            ctx.fillStyle = `hsl(${240 + Math.sin(tick*0.0003)*20}, 30%, 4%)`;
            ctx.fillRect(0, 0, W, H);

            orbs.forEach(o => {
                const cx = (0.5 + 0.45 * Math.sin(tick * o.spd + o.phase)) * W;
                const cy = (0.5 + 0.40 * Math.cos(tick * o.spd * 0.7 + o.phase)) * H;
                const radius = 0.5 * Math.max(W, H);
                const hue = (o.hue + tick * 0.015) % 360;
                const g = ctx.createRadialGradient(cx, cy, 0, cx, cy, radius);
                g.addColorStop(0, `hsla(${hue},90%,55%,0.18)`);
                g.addColorStop(1, `hsla(${hue},70%,40%,0)`);
                ctx.globalCompositeOperation = 'screen';
                ctx.fillStyle = g;
                ctx.beginPath();
                ctx.ellipse(cx, cy, radius, radius * 0.7, tick * 0.0002, 0, Math.PI * 2);
                ctx.fill();
            });
            ctx.globalCompositeOperation = 'source-over';

            rings.forEach(rng => {
                rng.rot += rng.rotSpd;
                rng.x += rng.driftX; rng.y += rng.driftY;
                if (rng.x < -0.1) rng.x = 1.1;
                if (rng.x > 1.1)  rng.x = -0.1;
                if (rng.y < -0.1) rng.y = 1.1;
                if (rng.y > 1.1)  rng.y = -0.1;

                const hue = (rng.hue + tick * 0.02) % 360;
                ctx.save();
                ctx.translate(rng.x * W, rng.y * H);
                ctx.rotate(rng.rot);
                ctx.globalAlpha = rng.alpha;
                ctx.strokeStyle = `hsl(${hue}, 80%, 75%)`;
                ctx.lineWidth = 1;
                [1, 0.7, 0.4].forEach(s => {
                    ctx.beginPath(); ctx.arc(0, 0, rng.r * s, 0, Math.PI * 2); ctx.stroke();
                });
                for (let s = 0; s < rng.spokes; s++) {
                    const a = (s / rng.spokes) * Math.PI * 2;
                    ctx.beginPath();
                    ctx.moveTo(Math.cos(a)*rng.r*0.4, Math.sin(a)*rng.r*0.4);
                    ctx.lineTo(Math.cos(a)*rng.r,     Math.sin(a)*rng.r);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.arc(Math.cos(a)*rng.r*0.85, Math.sin(a)*rng.r*0.85, rng.r*0.08, 0, Math.PI*2);
                    ctx.stroke();
                }
                ctx.restore();
            });

            particles.forEach(p => {
                p.x += p.vx; p.y += p.vy;
                if (p.y < -0.02) { p.y = 1.02; p.x = Math.random(); }
                if (p.x < -0.02) p.x = 1.02;
                if (p.x > 1.02)  p.x = -0.02;
                const hue = (p.hue + tick * 0.04) % 360;
                ctx.globalAlpha = p.a * (0.5 + 0.5 * Math.sin(tick * 0.004 + p.hue));
                ctx.fillStyle = `hsl(${hue}, 90%, 80%)`;
                ctx.beginPath();
                ctx.arc(p.x * W, p.y * H, p.r, 0, Math.PI * 2);
                ctx.fill();
            });
            ctx.globalAlpha = 1;
            requestAnimationFrame(draw);
        }
        draw();
    </script>
</body>
</html>
