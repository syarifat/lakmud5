<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - LAKMUD V</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .bg-animated {
            background: linear-gradient(-45deg, #064e3b, #047857, #0f766e, #115e59);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="bg-animated min-h-screen flex items-center justify-center p-4 antialiased text-gray-800">

    <div class="glass-panel w-full max-w-md rounded-3xl p-8 sm:p-10 transform transition-all hover:scale-[1.01] duration-300 relative overflow-hidden">
        
        <!-- Decorative subtle element -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 rounded-full bg-emerald-500 opacity-10 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 rounded-full bg-teal-500 opacity-10 blur-2xl"></div>

        <div class="text-center mb-8 relative z-10">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-white mb-4 shadow-sm p-2">
                <img src="{{ asset('logo.png') }}" alt="Logo LAKMUD V" class="w-full h-full object-contain">
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selamat Datang</h1>
            <p class="text-sm text-gray-500 mt-2 font-medium">Masuk ke sistem pendaftaran LAKMUD V</p>
        </div>

        <x-auth-session-status class="mb-4 relative z-10" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6 relative z-10">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white/60 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none shadow-sm" 
                       placeholder="nama@email.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs" />
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white/60 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none shadow-sm"
                       placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs" />
            </div>

            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-emerald-600 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 focus:ring-2 cursor-pointer transition-colors">
                <label for="remember_me" class="ml-2 text-sm font-medium text-gray-600 cursor-pointer select-none">Ingat saya</label>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 transform transition-all hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 text-[15px]">
                Log in ke Dashboard
            </button>
        </form>
        
        <div class="mt-8 text-center relative z-10 space-y-2">
            <p class="text-xs font-medium text-gray-400">
                Lupa password? Silakan hubungi panitia.
            </p>
            <p class="text-sm font-medium text-gray-500 pt-2 border-t border-gray-200">
                Belum mendaftar? <a href="/daftar-lakmud" class="font-bold text-emerald-600 hover:text-emerald-700 transition-colors hover:underline underline-offset-2">Daftar sekarang</a>
            </p>
        </div>
    </div>

</body>
</html>
