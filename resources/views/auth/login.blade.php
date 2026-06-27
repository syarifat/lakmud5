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

            <div x-data="{ show: false }">
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                </div>
                <div class="relative">
                    <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                           class="w-full pl-4 pr-12 py-3 rounded-xl border border-gray-200 bg-white/60 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none shadow-sm"
                           placeholder="••••••••">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-emerald-600 focus:outline-none transition-colors">
                        <!-- Eye Open Icon (Show) -->
                        <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Eye Closed Icon (Hide) -->
                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
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
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-500 bg-red-50 border border-red-100 rounded-full px-3 py-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    Pendaftaran sudah ditutup
                </span>
            </p>
        </div>
    </div>

</body>
</html>
