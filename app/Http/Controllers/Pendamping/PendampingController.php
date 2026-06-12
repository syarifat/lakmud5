<?php

namespace App\Http\Controllers\Pendamping;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelompok;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\ObservasiHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendampingController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Ambil semua kelompok yang didampingi oleh pendamping ini beserta anggotanya
        $kelompoks = Kelompok::where('pendamping_id', $user->id)
            ->with(['pesertas' => function($q) {
                $q->orderBy('name');
            }])
            ->get();

        // Cari tahu catatan observasi terakhir untuk tiap peserta
        foreach ($kelompoks as $kel) {
            foreach ($kel->pesertas as $peserta) {
                $peserta->latest_observasi = ObservasiHarian::where('peserta_id', $peserta->id)
                    ->orderBy('hari_ke', 'desc')
                    ->first();
            }
        }

        return view('pendamping.dashboard', compact('kelompoks'));
    }

    public function absensi(Request $request)
    {
        $user = Auth::user();
        
        // Ambil semua kelompok pendamping
        $kelompoks = Kelompok::where('pendamping_id', $user->id)->get();
        $memberIds = [];
        foreach ($kelompoks as $kel) {
            $memberIds = array_merge($memberIds, $kel->pesertas()->pluck('users.id')->toArray());
        }
        $memberIds = array_unique($memberIds);

        // Ambil jadwal
        $jadwals = Jadwal::with(['materi', 'pemateri'])->orderBy('waktu_mulai')->get();
        
        $selectedJadwal = null;
        $pesertaAbsen = [];

        if ($request->has('jadwal_id')) {
            $selectedJadwal = Jadwal::with(['materi', 'pemateri'])->findOrFail($request->jadwal_id);
            
            // Ambil anggota kelompok pendamping saja
            $pesertas = User::whereIn('id', $memberIds)->orderBy('name')->get();
            
            foreach ($pesertas as $p) {
                $absen = Absensi::where('jadwal_id', $selectedJadwal->id)
                    ->where('peserta_id', $p->id)
                    ->first();
                $p->is_hadir = !is_null($absen);
                $p->waktu_tap = $absen ? $absen->waktu_tap : null;
                $pesertaAbsen[] = $p;
            }
        }

        return view('pendamping.absensi', compact('jadwals', 'selectedJadwal', 'pesertaAbsen'));
    }

    public function observasiIndex(Request $request)
    {
        $user = Auth::user();
        
        // Ambil anggota kelompok pendamping
        $kelompoks = Kelompok::where('pendamping_id', $user->id)->get();
        $memberIds = [];
        foreach ($kelompoks as $kel) {
            $memberIds = array_merge($memberIds, $kel->pesertas()->pluck('users.id')->toArray());
        }
        $memberIds = array_unique($memberIds);
        
        $pesertas = User::whereIn('id', $memberIds)->orderBy('name')->get();
        
        $selectedDay = $request->query('hari_ke', 1);
        if ($selectedDay < 1 || $selectedDay > 4) {
            $selectedDay = 1;
        }

        // Ambil data observasi hari terpilih
        $observasiExist = ObservasiHarian::where('hari_ke', $selectedDay)
            ->whereIn('peserta_id', $memberIds)
            ->get()
            ->keyBy('peserta_id');

        return view('pendamping.observasi', compact('pesertas', 'selectedDay', 'observasiExist'));
    }

    public function observasiStore(Request $request)
    {
        $request->validate([
            'hari_ke' => 'required|integer|between:1,4',
            'kedisiplinan' => 'required|array',
            'kedisiplinan.*' => 'required|integer|between:1,5',
            'kemampuan' => 'required|array',
            'kemampuan.*' => 'required|integer|between:1,5',
            'keaktifan' => 'required|array',
            'keaktifan.*' => 'required|integer|between:1,5',
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string',
        ]);

        $pendampingId = Auth::id();

        foreach ($request->kedisiplinan as $pesertaId => $kedisiplinan) {
            $kemampuan = $request->kemampuan[$pesertaId] ?? 1;
            $keaktifan = $request->keaktifan[$pesertaId] ?? 1;
            $catatan = $request->catatan[$pesertaId] ?? null;
            $nilaiAngka = ($kedisiplinan + $kemampuan + $keaktifan) / 3;

            ObservasiHarian::updateOrCreate(
                [
                    'peserta_id' => $pesertaId,
                    'hari_ke' => $request->hari_ke,
                ],
                [
                    'pendamping_id' => $pendampingId,
                    'kedisiplinan' => $kedisiplinan,
                    'kemampuan' => $kemampuan,
                    'keaktifan' => $keaktifan,
                    'nilai_angka' => $nilaiAngka,
                    'catatan' => $catatan,
                ]
            );
        }

        return redirect()->route('pendamping.observasi', ['hari_ke' => $request->hari_ke])
            ->with('status', 'Lembar observasi harian kelompok berhasil disimpan.');
    }
}
