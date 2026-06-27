<?php

namespace App\Http\Controllers\Inspel;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Pemateri;
use App\Models\Materi;
use App\Models\PenilaianPeserta;
use App\Models\EvaluasiRefleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InspelController extends Controller
{
    public function dashboard()
    {
        // Hitung statistik rata-rata nilai pemahaman peserta per materi
        $stats = Materi::select('materis.id', 'materis.nama_materi')
            ->leftJoin('jadwals', 'materis.id', '=', 'jadwals.materi_id')
            ->leftJoin('penilaian_pesertas', 'jadwals.id', '=', 'penilaian_pesertas.jadwal_id')
            ->selectRaw('COALESCE(AVG(penilaian_pesertas.pemahaman), 0) as avg_pemahaman')
            ->groupBy('materis.id', 'materis.nama_materi')
            ->get();

        // Beberapa statistik umum
        $generalStats = [
            'total_materi' => Materi::count(),
            'total_pemateri' => Pemateri::count(),
            'total_peserta' => User::where('role', 'peserta')->count(),
            'total_refleksi' => EvaluasiRefleksi::count(),
        ];

        return view('inspel.dashboard', compact('stats', 'generalStats'));
    }

    public function pemateriIndex()
    {
        $pemateri = Pemateri::latest()->get();
        return view('inspel.pemateri.index', compact('pemateri'));
    }

    public function pemateriShow($id)
    {
        $data = Pemateri::with(['riwayatPendidikans', 'riwayatOrganisasis', 'riwayatPengkaderans'])->findOrFail($id);
        return view('inspel.pemateri.show', compact('data'));
    }

    public function absensi(Request $request)
    {
        $jadwals = Jadwal::with(['materi', 'pemateri'])->orderBy('waktu_mulai')->get();
        
        $selectedJadwal = null;
        $pesertaAbsen = [];
        
        if ($request->has('jadwal_id')) {
            $selectedJadwal = Jadwal::with(['materi', 'pemateri'])->findOrFail($request->jadwal_id);
            
            // Ambil semua peserta
            $pesertas = User::where('role', 'peserta')->orderBy('name')->get();
            
            // Cek kehadiran masing-masing
            foreach ($pesertas as $p) {
                $absen = Absensi::where('jadwal_id', $selectedJadwal->id)
                    ->where('peserta_id', $p->id)
                    ->first();
                $p->is_hadir = !is_null($absen);
                $p->waktu_tap = $absen ? $absen->waktu_tap : null;
                $pesertaAbsen[] = $p;
            }
        }

        return view('inspel.absensi', compact('jadwals', 'selectedJadwal', 'pesertaAbsen'));
    }

    public function penilaianIndex()
    {
        $jadwals = Jadwal::with(['materi', 'pemateri'])->orderBy('waktu_mulai')->get();

        // Cari tahu berapa peserta yang sudah dinilai per jadwal
        foreach ($jadwals as $j) {
            $j->peserta_dinilai_count = PenilaianPeserta::where('jadwal_id', $j->id)->count();
            $j->total_peserta = User::where('role', 'peserta')->count();
        }

        return view('inspel.penilaian.index', compact('jadwals'));
    }

    public function penilaianCreate(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);

        $jadwal = Jadwal::with(['materi', 'pemateri'])->findOrFail($request->jadwal_id);
        $pesertas = User::where('role', 'peserta')->orderBy('name')->get();
        
        // Ambil nilai yang sudah ada
        $nilaiExist = PenilaianPeserta::where('jadwal_id', $jadwal->id)
            ->get()
            ->keyBy('peserta_id');

        return view('inspel.penilaian.create', compact('jadwal', 'pesertas', 'nilaiExist'));
    }

    public function penilaianStore(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'pemahaman' => 'required|array',
            'pemahaman.*' => 'required|integer|between:70,100',
            'kedisiplinan' => 'required|array',
            'kedisiplinan.*' => 'required|integer|between:70,100',
            'keaktifan' => 'required|array',
            'keaktifan.*' => 'required|integer|between:70,100',
        ]);

        $inspelId = Auth::id();

        foreach ($request->pemahaman as $pesertaId => $pemahaman) {
            $kedisiplinan = $request->kedisiplinan[$pesertaId] ?? 0;
            $keaktifan = $request->keaktifan[$pesertaId] ?? 0;
            $rerata = ($pemahaman + $kedisiplinan + $keaktifan) / 3;

            PenilaianPeserta::updateOrCreate(
                [
                    'jadwal_id' => $request->jadwal_id,
                    'peserta_id' => $pesertaId,
                ],
                [
                    'inspel_id' => $inspelId,
                    'pemahaman' => $pemahaman,
                    'kedisiplinan' => $kedisiplinan,
                    'keaktifan' => $keaktifan,
                    'rerata' => $rerata,
                ]
            );
        }

        return redirect()->route('inspel.penilaian')->with('status', 'Penilaian akademik peserta berhasil disimpan.');
    }

    public function refleksiIndex()
    {
        $refleksis = EvaluasiRefleksi::with('peserta')->latest()->get();
        return view('inspel.refleksi.index', compact('refleksis'));
    }

    public function refleksiShow($id)
    {
        $refleksi = EvaluasiRefleksi::with('peserta')->findOrFail($id);
        return view('inspel.refleksi.show', compact('refleksi'));
    }
}
