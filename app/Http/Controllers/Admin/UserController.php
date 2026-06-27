<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->get();
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['admin', 'peserta', 'inspel', 'pendamping', 'pendaftar'])],
            'rfid_uid' => 'nullable|string|unique:users',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'rfid_uid' => $request->rfid_uid,
        ]);

        return redirect()->route('admin.user.index')->with('status', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'peserta', 'inspel', 'pendamping', 'pendaftar'])],
            'rfid_uid' => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
        ]);

        $data = $request->only(['name', 'email', 'role', 'rfid_uid']);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')->with('status', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }
        
        $user->delete();
        return redirect()->route('admin.user.index')->with('status', 'User berhasil dihapus.');
    }

    public function resetPassword(Request $request, User $user)
    {
        // Buat signed URL kustom tanpa kedaluwarsa untuk user merubah password sendiri
        $resetUrl = \Illuminate\Support\Facades\URL::signedRoute('password.reset.custom', ['user' => $user->id]);

        // Cari nomor HP di data pendaftaran
        $no_hp = $user->pendaftaran?->no_hp;
        $waLink = null;

        if ($no_hp) {
            // Bersihkan format nomor HP (hapus karakter non-angka)
            $cleanPhone = preg_replace('/[^0-9]/', '', $no_hp);
            // Ubah format 08xxx menjadi 628xxx
            if (strpos($cleanPhone, '0') === 0) {
                $cleanPhone = '62' . substr($cleanPhone, 1);
            }

            // Teks pesan WhatsApp
            $message = "Halo " . $user->name . ",\n\nUntuk merubah sandi akun LAKMUD V Anda, silakan klik tautan berikut:\n" . $resetUrl . "\n\nTautan ini bersifat aman dan tidak memiliki masa kedaluwarsa.\n\nTerima kasih.";
            
            $waLink = "https://api.whatsapp.com/send?phone=" . $cleanPhone . "&text=" . urlencode($message);
        }

        $redirect = redirect()->route('admin.user.index')
            ->with('status', 'Link reset sandi untuk ' . $user->name . ' (' . $user->email . ') telah dibuat.');

        if ($waLink) {
            $redirect->with('wa_link', $waLink);
        } else {
            $redirect->with('error', 'User tidak memiliki nomor HP terdaftar, link reset: ' . $resetUrl);
        }

        return $redirect;
    }
}