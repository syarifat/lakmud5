<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_pendaftar' => Pendaftaran::count(),
            'lolos' => Pendaftaran::where('status_lulus', true)->count(),
            'pending' => Pendaftaran::where('status_lulus', false)->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }

    public function pendaftarIndex()
    {
        $pendaftar = Pendaftaran::with('user')->latest()->get();
        return view('admin.pendaftar.index', compact('pendaftar'));
    }

    public function pendaftarShow($id)
    {
        $data = Pendaftaran::with('user')->findOrFail($id);
        return view('admin.pendaftar.show', compact('data'));
    }

    public function verifikasi(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $user = User::findOrFail($pendaftaran->user_id);

        if ($request->status == 'lolos') {
            $pendaftaran->update(['status_lulus' => true]);
            $user->update(['role' => 'peserta']); // Ubah role jadi peserta agar bisa login penuh
            $pesan = 'Pendaftar berhasil diloloskan sebagai Peserta.';
        } else {
            $pendaftaran->update(['status_lulus' => false]);
            $user->update(['role' => 'pendaftar']);
            $pesan = 'Status pendaftar dibatalkan/ditolak.';
        }

        return redirect()->route('admin.pendaftar.index')->with('status', $pesan);
    }
}