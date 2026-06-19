<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemateri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PemateriController extends Controller
{
    public function index()
    {
        $pemateris = Pemateri::with('materi')->latest()->get();
        return view('admin.pemateri.index', compact('pemateris'));
    }

    public function create()
    {
        $materis = \App\Models\Materi::orderBy('nama_materi')->get();
        return view('admin.pemateri.create', compact('materis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'materi_id' => 'nullable|exists:materis,id',
            'jabatan' => 'nullable|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'hobi' => 'required|string|max:255',
            'motto' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'pekerjaan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pemateri', 'public');
        }

        Pemateri::create($data);

        return redirect()->route('admin.pemateri.index')->with('status', 'Data Pemateri berhasil ditambahkan.');
    }

    public function edit(Pemateri $pemateri)
    {
        $materis = \App\Models\Materi::orderBy('nama_materi')->get();
        return view('admin.pemateri.edit', compact('pemateri', 'materis'));
    }

    public function update(Request $request, Pemateri $pemateri)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'materi_id' => 'nullable|exists:materis,id',
            'jabatan' => 'nullable|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'hobi' => 'required|string|max:255',
            'motto' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'pekerjaan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($pemateri->foto) {
                Storage::disk('public')->delete($pemateri->foto);
            }
            $data['foto'] = $request->file('foto')->store('pemateri', 'public');
        }

        $pemateri->update($data);

        return redirect()->route('admin.pemateri.index')->with('status', 'Data Pemateri berhasil diperbarui.');
    }

    public function destroy(Pemateri $pemateri)
    {
        if ($pemateri->foto) {
            Storage::disk('public')->delete($pemateri->foto);
        }
        $pemateri->delete();
        return redirect()->route('admin.pemateri.index')->with('status', 'Data Pemateri berhasil dihapus.');
    }
}