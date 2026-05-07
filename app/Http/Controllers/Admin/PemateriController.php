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
        $pemateris = Pemateri::latest()->get();
        return view('admin.pemateri.index', compact('pemateris'));
    }

    public function create()
    {
        return view('admin.pemateri.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
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
        return view('admin.pemateri.edit', compact('pemateri'));
    }

    public function update(Request $request, Pemateri $pemateri)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
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