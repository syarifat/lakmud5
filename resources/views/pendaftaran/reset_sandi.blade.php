<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Sandi – LAKMUD V</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #022c22 100%);
            overflow: hidden;
            color: #fff;
            padding: 20px;
            position: relative;
        }

        /* Abstract glowing blobs for a modern look */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.15;
            z-index: 1;
            pointer-events: none;
        }
        .blob-1 {
            width: 400px;
            height: 400px;
            background: #10b981;
            top: -100px;
            left: -100px;
        }
        .blob-2 {
            width: 350px;
            height: 350px;
            background: #0ea5e9;
            bottom: -50px;
            right: -50px;
        }

        .card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            margin-bottom: 24px;
            padding: 10px;
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-contain: contain;
        }

        h1 {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: #94a3b8;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .email-display {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: #cbd5e1;
        }

        input[type="password"] {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            font-size: 14px;
            color: #ffffff;
            font-family: inherit;
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s, background-color 0.25s;
        }

        input[type="password"]:focus, input[type="text"]:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }

        input[type="text"] {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            font-size: 14px;
            color: #ffffff;
            font-family: inherit;
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s, background-color 0.25s;
        }

        .input-container {
            position: relative;
            width: 100%;
        }

        .eye-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            outline: none;
            transition: color 0.2s;
        }

        .eye-toggle:hover {
            color: #10b981;
        }

        .eye-toggle svg {
            width: 20px;
            height: 20px;
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 15px;
            background: #10b981;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s, box-shadow 0.2s;
            margin-top: 28px;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.25);
        }

        .btn-submit:hover {
            background-color: #059669;
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 24px;
            text-align: left;
            line-height: 1.5;
        }

        .alert-error ul {
            margin-left: 16px;
        }

        footer {
            margin-top: 32px;
            font-size: 11px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="card">
        <center>
            <div class="logo-wrap">
                <img src="{{ asset('logo.png') }}" alt="Logo LAKMUD V">
            </div>
            <h1>Atur Ulang Sandi</h1>
            <p class="subtitle">Buat sandi baru untuk mengamankan akun LAKMUD V Anda.</p>
        </center>

        <!-- Display validation errors if any -->
        @if ($errors->any())
            <div class="alert-error">
                <strong>Gagal memperbarui sandi:</strong>
                <ul style="margin-top: 4px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ request()->fullUrl() }}" method="POST">
            @csrf

            <!-- Email Display (Readonly) -->
            <div class="form-group">
                <label>Email Anda</label>
                <div class="email-display">{{ $user->email }}</div>
            </div>

            <!-- Password Baru -->
            <div class="form-group">
                <label for="password">Sandi Baru</label>
                <div class="input-container">
                    <input type="password" id="password" name="password" required minlength="8" placeholder="Minimal 8 karakter..." style="padding-right: 48px;">
                    <button type="button" class="eye-toggle" data-target="password">
                        <!-- Eye Open Icon (Show) -->
                        <svg class="eye-show" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Eye Closed Icon (Hide) -->
                        <svg class="eye-hide" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Konfirmasi Password Baru -->
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Sandi Baru</label>
                <div class="input-container">
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" placeholder="Ulangi sandi baru..." style="padding-right: 48px;">
                    <button type="button" class="eye-toggle" data-target="password_confirmation">
                        <!-- Eye Open Icon (Show) -->
                        <svg class="eye-show" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Eye Closed Icon (Hide) -->
                        <svg class="eye-hide" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Simpan Sandi Baru
            </button>
        </form>

        <center>
            <footer>PAC IPNU IPPNU Kauman &bull; LAKMUD V</footer>
        </center>
    </div>

    <script>
        document.querySelectorAll('.eye-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const showIcon = this.querySelector('.eye-show');
                const hideIcon = this.querySelector('.eye-hide');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    showIcon.style.display = 'block';
                    hideIcon.style.display = 'none';
                } else {
                    input.type = 'password';
                    showIcon.style.display = 'none';
                    hideIcon.style.display = 'block';
                }
            });
        });
    </script>
</body>
</html>
