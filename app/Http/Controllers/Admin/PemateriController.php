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
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'motto' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->except(['pendidikan', 'organisasi', 'pengkaderan']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pemateri', 'public');
        }

        $pemateri = Pemateri::create($data);

        // Sync Riwayat Pendidikan
        if ($request->pendidikan) {
            foreach ($request->pendidikan as $tingkat => $p) {
                if (!empty($p['sekolah']) && !empty($p['tahun'])) {
                    $pemateri->riwayatPendidikans()->create([
                        'jenjang' => $tingkat,
                        'nama_sekolah' => $p['sekolah'],
                        'tahun' => $p['tahun'],
                    ]);
                }
            }
        }

        // Sync Riwayat Organisasi
        if ($request->organisasi) {
            foreach ($request->organisasi as $org) {
                if (!empty($org['nama']) && !empty($org['jabatan']) && !empty($org['tahun'])) {
                    $pemateri->riwayatOrganisasis()->create([
                        'nama_organisasi' => $org['nama'],
                        'jabatan' => $org['jabatan'],
                        'tahun' => $org['tahun'],
                    ]);
                }
            }
        }

        // Sync Riwayat Pengkaderan
        if ($request->pengkaderan) {
            foreach ($request->pengkaderan as $pk) {
                if (!empty($pk['tingkat']) && !empty($pk['nama']) && !empty($pk['tahun'])) {
                    $pemateri->riwayatPengkaderans()->create([
                        'tingkat' => $pk['tingkat'],
                        'nama' => $pk['nama'],
                        'tahun' => $pk['tahun'],
                    ]);
                }
            }
        }

        if ($pemateri->materi_id) {
            \App\Models\Jadwal::updateOrCreate(
                ['pemateri_id' => $pemateri->id],
                [
                    'materi_id' => $pemateri->materi_id,
                    'waktu_mulai' => now(),
                    'waktu_selesai' => now(),
                ]
            );
        }

        return redirect()->route('admin.pemateri.index')->with('status', 'Data Pemateri berhasil ditambahkan.');
    }

    public function edit(Pemateri $pemateri)
    {
        $materis = \App\Models\Materi::orderBy('nama_materi')->get();
        // Load relationships
        $pemateri->load(['riwayatPendidikans', 'riwayatOrganisasis', 'riwayatPengkaderans']);
        return view('admin.pemateri.edit', compact('pemateri', 'materis'));
    }

    public function update(Request $request, Pemateri $pemateri)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'materi_id' => 'nullable|exists:materis,id',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'motto' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->except(['pendidikan', 'organisasi', 'pengkaderan']);

        if ($request->hasFile('foto')) {
            if ($pemateri->foto) {
                Storage::disk('public')->delete($pemateri->foto);
            }
            $data['foto'] = $request->file('foto')->store('pemateri', 'public');
        }

        $pemateri->update($data);

        // Sync Riwayat Pendidikan (re-create)
        $pemateri->riwayatPendidikans()->delete();
        if ($request->pendidikan) {
            foreach ($request->pendidikan as $tingkat => $p) {
                if (!empty($p['sekolah']) && !empty($p['tahun'])) {
                    $pemateri->riwayatPendidikans()->create([
                        'jenjang' => $tingkat,
                        'nama_sekolah' => $p['sekolah'],
                        'tahun' => $p['tahun'],
                    ]);
                }
            }
        }

        // Sync Riwayat Organisasi (re-create)
        $pemateri->riwayatOrganisasis()->delete();
        if ($request->organisasi) {
            foreach ($request->organisasi as $org) {
                if (!empty($org['nama']) && !empty($org['jabatan']) && !empty($org['tahun'])) {
                    $pemateri->riwayatOrganisasis()->create([
                        'nama_organisasi' => $org['nama'],
                        'jabatan' => $org['jabatan'],
                        'tahun' => $org['tahun'],
                    ]);
                }
            }
        }

        // Sync Riwayat Pengkaderan (re-create)
        $pemateri->riwayatPengkaderans()->delete();
        if ($request->pengkaderan) {
            foreach ($request->pengkaderan as $pk) {
                if (!empty($pk['tingkat']) && !empty($pk['nama']) && !empty($pk['tahun'])) {
                    $pemateri->riwayatPengkaderans()->create([
                        'tingkat' => $pk['tingkat'],
                        'nama' => $pk['nama'],
                        'tahun' => $pk['tahun'],
                    ]);
                }
            }
        }

        if ($pemateri->materi_id) {
            \App\Models\Jadwal::updateOrCreate(
                ['pemateri_id' => $pemateri->id],
                [
                    'materi_id' => $pemateri->materi_id,
                    'waktu_mulai' => now(),
                    'waktu_selesai' => now(),
                ]
            );
        } else {
            \App\Models\Jadwal::where('pemateri_id', $pemateri->id)->delete();
        }

        return redirect()->route('admin.pemateri.index')->with('status', 'Data Pemateri berhasil diperbarui.');
    }

    public function destroy(Pemateri $pemateri)
    {
        if ($pemateri->foto) {
            Storage::disk('public')->delete($pemateri->foto);
        }

        \App\Models\Jadwal::where('pemateri_id', $pemateri->id)->delete();

        $pemateri->delete();
        return redirect()->route('admin.pemateri.index')->with('status', 'Data Pemateri berhasil dihapus.');
    }
}