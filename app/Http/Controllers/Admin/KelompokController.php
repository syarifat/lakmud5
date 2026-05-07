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
        // Mengambil kelompok beserta nama pendampingnya
        $kelompoks = Kelompok::with('pendamping')->get();
        return view('admin.kelompok.index', compact('kelompoks'));
    }

    public function create()
    {
        // Hanya ambil user yang rolenya pendamping untuk dipilih sebagai fasilitator kelompok
        $pendampings = User::where('role', 'pendamping')->get();
        return view('admin.kelompok.create', compact('pendampings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'pendamping_id' => 'required|exists:users,id',
        ]);

        Kelompok::create($request->all());

        return redirect()->route('admin.kelompok.index')->with('status', 'Kelompok berhasil dibentuk.');
    }

    public function edit(Kelompok $kelompok)
    {
        $pendampings = User::where('role', 'pendamping')->get();
        return view('admin.kelompok.edit', compact('kelompok', 'pendampings'));
    }

    public function update(Request $request, Kelompok $kelompok)
    {
        $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'pendamping_id' => 'required|exists:users,id',
        ]);

        $kelompok->update($request->all());

        return redirect()->route('admin.kelompok.index')->with('status', 'Data kelompok diperbarui.');
    }

    public function destroy(Kelompok $kelompok)
    {
        $kelompok->delete();
        return redirect()->route('admin.kelompok.index')->with('status', 'Kelompok dibubarkan.');
    }
}