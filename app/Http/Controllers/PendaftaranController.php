<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    public function index()
    {
        return view('pendaftaran.form');
    }

    public function store(Request $request)
    {
        // 1. Validasi Data Akun & Biodata
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', // Password untuk login nanti
            'nia' => 'nullable|string',
            'delegasi' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'jabatan' => 'required|string',
            'no_hp' => 'required|string',
            'username_ig' => 'required|string',
            'ukuran_kaos' => 'required|string',
            'ttd_data' => 'required|string',
            'file_sertifikat' => 'required|file|mimes:jpg,png,jpeg,pdf|max:2048',
            'file_rekom' => 'required|file|mimes:jpg,png,jpeg,pdf|max:2048',
            'file_foto' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'file_identitas' => 'required|file|mimes:jpg,png,jpeg,pdf|max:2048',
            'file_bukti_ig' => 'required|file|mimes:jpg,png,jpeg,pdf|max:2048',
            'file_bukti_ig_kaderisasi' => 'required|file|mimes:jpg,png,jpeg,pdf|max:2048',
        ]);

        // 2. Buat Akun User Baru (Role Otomatis 'pendaftar')
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pendaftar',
        ]);

        // 3. Simpan Tanda Tangan SVG
        $ttd_name = 'ttd_' . time() . '_' . $user->id . '.svg';
        Storage::disk('public')->put('pendaftaran/ttd/' . $ttd_name, $request->ttd_data);

        // 4. Upload Berkas Lainnya
        $upload = function($file) {
            return $file->store('pendaftaran/berkas', 'public');
        };

        // 5. Simpan Biodata Relasi ke User
        Pendaftaran::create([
            'user_id' => $user->id,
            'nia' => $request->nia,
            'delegasi' => $request->delegasi,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'username_ig' => $request->username_ig,
            'ukuran_kaos' => $request->ukuran_kaos,
            'file_ttd' => 'pendaftaran/ttd/' . $ttd_name,
            'file_sertifikat' => $upload($request->file('file_sertifikat')),
            'file_rekom' => $upload($request->file('file_rekom')),
            'file_foto' => $upload($request->file('file_foto')),
            'file_identitas' => $upload($request->file('file_identitas')),
            'file_bukti_ig' => $upload($request->file('file_bukti_ig')),
            'file_bukti_ig_kaderisasi' => $upload($request->file('file_bukti_ig_kaderisasi')),
            'status_lulus' => false,
        ]);

        // Arahkan ke halaman login dengan pesan sukses (ingat, middleware login kita sebelumnya sudah memblokir role pendaftar yang belum lulus)
        return redirect()->route('login')->with('status', 'Pendaftaran berhasil! Akun Anda telah dibuat. Silakan tunggu admin memvalidasi berkas Anda sebelum bisa Login.');
    }

    public function showResetPasswordForm(Request $request, User $user)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Link reset sandi tidak valid atau telah kedaluwarsa.');
        }
        return view('pendaftaran.reset_sandi', compact('user'));
    }

    public function updatePasswordCustom(Request $request, User $user)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Link reset sandi tidak valid atau telah kedaluwarsa.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('status', 'Sandi Anda berhasil diperbarui. Silakan masuk menggunakan sandi baru.');
    }
}