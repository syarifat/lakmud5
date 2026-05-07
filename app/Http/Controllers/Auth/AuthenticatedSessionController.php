<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Ambil data user yang baru login
        $user = $request->user();

        // Tentukan redirect berdasarkan role
        $url = match ($user->role) {
            'admin' => route('admin.dashboard'),
            'peserta' => route('peserta.dashboard'),
            'inspel' => route('inspel.dashboard'),
            'pendamping' => route('pendamping.dashboard'),
            'pendaftar' => route('pendaftar.biodata'),
            default => '/dashboard',
        };

        return redirect()->intended($url);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
