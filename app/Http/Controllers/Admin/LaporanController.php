<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Materi;
use App\Models\Pemateri;
use App\Models\Kelompok;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\PenilaianPeserta;
use App\Models\ObservasiHarian;
use App\Models\NilaiPemateri;
use App\Models\NilaiInspel;
use App\Models\EvaluasiRefleksi;
use App\Models\BankSoal;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $pemateris = Pemateri::orderBy('nama')->get();
        $jadwals = Jadwal::with(['materi', 'pemateri'])->latest()->get();
        $kelompoks = Kelompok::orderBy('nama_kelompok')->get();
        $pesertas = User::where('role', 'peserta')->orderBy('name')->get();
        $materis = Materi::orderBy('nama_materi')->get();

        return view('admin.laporan.index', compact('pemateris', 'jadwals', 'kelompoks', 'pesertas', 'materis'));
    }

    public function download(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
        ]);

        $type = $request->type;

        switch ($type) {
            case 'cv_pemateri':
                $request->validate(['pemateri_id' => 'required|exists:pemateris,id']);
                $pemateri = Pemateri::with(['riwayatPendidikans', 'riwayatOrganisasis'])->findOrFail($request->pemateri_id);
                
                $pdf = Pdf::loadView('admin.laporan.pdf.cv_pemateri', compact('pemateri'));
                return $pdf->download('01_CV_Pemateri_' . str_replace(' ', '_', $pemateri->nama) . '.pdf');

            case 'daftar_hadir':
                $request->validate(['jadwal_id' => 'required|exists:jadwals,id']);
                $jadwal = Jadwal::with(['materi', 'pemateri'])->findOrFail($request->jadwal_id);
                
                // Get all users who are verified participants (role: peserta)
                $pesertas = User::where('role', 'peserta')
                    ->with(['pendaftaran', 'absensis' => function ($q) use ($jadwal) {
                        $q->where('jadwal_id', $jadwal->id);
                    }])
                    ->orderBy('name')
                    ->get();

                $pdf = Pdf::loadView('admin.laporan.pdf.daftar_hadir', compact('jadwal', 'pesertas'));
                return $pdf->download('02_Daftar_Hadir_' . str_replace(' ', '_', $jadwal->materi->nama_materi) . '.pdf');

            case 'penilaian_peserta':
                $request->validate(['jadwal_id' => 'required|exists:jadwals,id']);
                $jadwal = Jadwal::with(['materi', 'pemateri'])->findOrFail($request->jadwal_id);
                
                $pesertas = User::where('role', 'peserta')
                    ->with(['pendaftaran', 'penilaianPesertas' => function ($q) use ($jadwal) {
                        $q->where('jadwal_id', $jadwal->id);
                    }])
                    ->orderBy('name')
                    ->get();

                $pdf = Pdf::loadView('admin.laporan.pdf.penilaian_peserta', compact('jadwal', 'pesertas'));
                return $pdf->download('03_Penilaian_Peserta_' . str_replace(' ', '_', $jadwal->materi->nama_materi) . '.pdf');

            case 'observasi_harian':
                $request->validate([
                    'kelompok_id' => 'required|exists:kelompoks,id',
                    'hari_ke' => 'required|integer|min:1|max:4',
                ]);
                $kelompok = Kelompok::with('pendamping')->findOrFail($request->kelompok_id);
                $pesertas = $kelompok->pesertas()->orderBy('name')->get();
                $hari_ke = $request->hari_ke;

                $observasis = ObservasiHarian::where('hari_ke', $hari_ke)
                    ->whereIn('peserta_id', $pesertas->pluck('id'))
                    ->get()
                    ->keyBy('peserta_id');

                $pdf = Pdf::loadView('admin.laporan.pdf.observasi_harian', compact('kelompok', 'pesertas', 'hari_ke', 'observasis'));
                return $pdf->download('04_Observasi_Harian_Kelompok_' . str_replace(' ', '_', $kelompok->nama_kelompok) . '_Hari_' . $hari_ke . '.pdf');

            case 'nilai_pemateri':
                $request->validate(['peserta_id' => 'required|exists:users,id']);
                $peserta = User::with('pendaftaran')->findOrFail($request->peserta_id);
                
                // Get all schedules
                $jadwals = Jadwal::with(['materi', 'pemateri'])->get();
                
                // Get evaluations by this participant
                $ratings = NilaiPemateri::where('peserta_id', $peserta->id)
                    ->get()
                    ->keyBy('jadwal_id');

                $pdf = Pdf::loadView('admin.laporan.pdf.penilaian_pemateri_oleh_peserta', compact('peserta', 'jadwals', 'ratings'));
                return $pdf->download('05_Penilaian_Pemateri_Oleh_' . str_replace(' ', '_', $peserta->name) . '.pdf');

            case 'nilai_inspel':
                $request->validate(['peserta_id' => 'required|exists:users,id']);
                $peserta = User::with('pendaftaran')->findOrFail($request->peserta_id);
                
                // Get all inspel users
                $inspels = User::where('role', 'inspel')->orderBy('name')->get();
                
                // Get evaluations by this participant
                $ratings = NilaiInspel::where('peserta_id', $peserta->id)
                    ->get()
                    ->keyBy('inspel_id');

                $pdf = Pdf::loadView('admin.laporan.pdf.penilaian_inspel_oleh_peserta', compact('peserta', 'inspels', 'ratings'));
                return $pdf->download('06_Penilaian_Inspel_Oleh_' . str_replace(' ', '_', $peserta->name) . '.pdf');

            case 'evaluasi_refleksi':
                $request->validate([
                    'peserta_id' => 'required|exists:users,id',
                    'hari_ke' => 'required|integer|min:1|max:4',
                ]);
                $peserta = User::with('pendaftaran')->findOrFail($request->peserta_id);
                $hari_ke = $request->hari_ke;

                $evaluasi = EvaluasiRefleksi::where('peserta_id', $peserta->id)
                    ->where('hari_ke', $hari_ke)
                    ->first();

                $pdf = Pdf::loadView('admin.laporan.pdf.evaluasi_refleksi_peserta', compact('peserta', 'hari_ke', 'evaluasi'));
                return $pdf->download('07_Evaluasi_Refleksi_Peserta_' . str_replace(' ', '_', $peserta->name) . '_Hari_' . $hari_ke . '.pdf');

            case 'soal_prepost':
                $materi_id = $request->materi_id;
                
                if ($materi_id && $materi_id !== 'all') {
                    $request->validate(['materi_id' => 'exists:materis,id']);
                    $materi = Materi::findOrFail($materi_id);
                    $materis = collect([$materi]);
                } else {
                    $materis = Materi::orderBy('nama_materi')->get();
                }

                // Get all questions grouped by materi and type
                $questions = BankSoal::whereIn('materi_id', $materis->pluck('id'))->get()->groupBy('materi_id');

                $pdf = Pdf::loadView('admin.laporan.pdf.soal_prepost', compact('materis', 'questions'));
                return $pdf->download('08_Soal_Pretest_Posttest.pdf');

            default:
                return abort(404, 'Jenis laporan tidak valid.');
        }
    }
}
