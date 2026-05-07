<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::latest()->get();
        return view('admin.materi.index', compact('materis'));
    }

    public function create()
    {
        return view('admin.materi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_materi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Materi::create($request->all());

        return redirect()->route('admin.materi.index')->with('status', 'Materi berhasil ditambahkan.');
    }

    public function edit(Materi $materi)
    {
        return view('admin.materi.edit', compact('materi'));
    }

    public function update(Request $request, Materi $materi)
    {
        $request->validate([
            'nama_materi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $materi->update($request->all());

        return redirect()->route('admin.materi.index')->with('status', 'Materi berhasil diperbarui.');
    }

    public function destroy(Materi $materi)
    {
        $materi->delete();
        return redirect()->route('admin.materi.index')->with('status', 'Materi berhasil dihapus.');
    }
}