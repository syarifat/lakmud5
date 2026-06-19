<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    public function index()
    {
        // Mengambil kelompok beserta nama pendamping dan pesertanya
        $kelompoks = Kelompok::with(['pendamping', 'pesertas'])->get();
        return view('admin.kelompok.index', compact('kelompoks'));
    }

    public function create()
    {
        // Hanya ambil user yang rolenya pendamping untuk dipilih sebagai fasilitator kelompok
        $pendampings = User::where('role', 'pendamping')->get();
        // Ambil peserta yang belum memiliki kelompok
        $pesertas = User::where('role', 'peserta')->whereDoesntHave('kelompoks')->get();
        return view('admin.kelompok.create', compact('pendampings', 'pesertas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'pendamping_id' => 'required|exists:users,id',
            'peserta_ids' => 'nullable|array',
            'peserta_ids.*' => 'exists:users,id',
        ]);

        $kelompok = Kelompok::create([
            'nama_kelompok' => $request->nama_kelompok,
            'pendamping_id' => $request->pendamping_id,
        ]);

        if ($request->has('peserta_ids')) {
            $kelompok->pesertas()->sync($request->peserta_ids);
        }

        return redirect()->route('admin.kelompok.index')->with('status', 'Kelompok berhasil dibentuk.');
    }

    public function edit(Kelompok $kelompok)
    {
        $pendampings = User::where('role', 'pendamping')->get();
        // Ambil peserta yang belum punya kelompok ATAU yang sudah tergabung di kelompok ini
        $pesertas = User::where('role', 'peserta')
            ->where(function($query) use ($kelompok) {
                $query->whereDoesntHave('kelompoks')
                      ->orWhereHas('kelompoks', function($q) use ($kelompok) {
                          $q->where('kelompoks.id', $kelompok->id);
                      });
            })
            ->get();

        return view('admin.kelompok.edit', compact('kelompok', 'pendampings', 'pesertas'));
    }

    public function update(Request $request, Kelompok $kelompok)
    {
        $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'pendamping_id' => 'required|exists:users,id',
            'peserta_ids' => 'nullable|array',
            'peserta_ids.*' => 'exists:users,id',
        ]);

        $kelompok->update([
            'nama_kelompok' => $request->nama_kelompok,
            'pendamping_id' => $request->pendamping_id,
        ]);

        $pesertaIds = $request->input('peserta_ids', []);
        $kelompok->pesertas()->sync($pesertaIds);

        return redirect()->route('admin.kelompok.index')->with('status', 'Data kelompok diperbarui.');
    }

    public function destroy(Kelompok $kelompok)
    {
        $kelompok->delete();
        return redirect()->route('admin.kelompok.index')->with('status', 'Kelompok dibubarkan.');
    }
}