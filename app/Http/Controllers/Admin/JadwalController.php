<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Materi;
use App\Models\Pemateri;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with(['materi', 'pemateri'])->latest('id')->get();
        $materis = Materi::orderBy('nama_materi')->get();
        $pemateris = Pemateri::orderBy('nama')->get();

        return view('admin.jadwal.index', compact('jadwals', 'materis', 'pemateris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'materi_id' => 'required|exists:materis,id',
            'pemateri_id' => 'required|exists:pemateris,id',
        ]);

        // Auto-fill waktu_mulai and waktu_selesai to current time to fulfill database non-null constraints
        Jadwal::create([
            'materi_id' => $request->materi_id,
            'pemateri_id' => $request->pemateri_id,
            'waktu_mulai' => now(),
            'waktu_selesai' => now(),
        ]);

        return redirect()->route('admin.jadwal.index')->with('status', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')->with('status', 'Jadwal berhasil dihapus.');
    }
}
