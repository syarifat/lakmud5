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
    public function index(Request $request)
    {
        $pemateris = Pemateri::orderBy('nama')->get();
        $jadwals = Jadwal::with(['materi', 'pemateri'])->latest()->get();
        $kelompoks = Kelompok::orderBy('nama_kelompok')->get();
        $pesertas = User::where('role', 'peserta')->orderBy('name')->get();
        $materis = Materi::orderBy('nama_materi')->get();

        $type = $request->query('type');
        
        // Data for selected report
        $selectedPemateri = null;
        $selectedJadwal = null;
        $daftarHadirPesertas = collect();
        $penilaianPesertas = collect();
        
        $selectedKelompok = null;
        $observasiPesertas = collect();
        $observasis = collect();
        
        $selectedPeserta = null;
        $nilaiPemateriRatings = collect();
        
        $nilaiInspelRatings = collect();
        $inspels = collect();
        
        $evaluasiRefleksi = null;
        
        $jawabanUjians = collect();

        if ($type) {
            switch ($type) {
                case 'cv_pemateri':
                    if ($request->pemateri_id) {
                        $selectedPemateri = Pemateri::with(['riwayatPendidikans', 'riwayatOrganisasis', 'riwayatPengkaderans'])->find($request->pemateri_id);
                    }
                    break;
                    
                case 'daftar_hadir':
                    if ($request->jadwal_id) {
                        $selectedJadwal = Jadwal::with(['materi', 'pemateri'])->find($request->jadwal_id);
                        if ($selectedJadwal) {
                            $daftarHadirPesertas = User::where('role', 'peserta')
                                ->with(['pendaftaran', 'absensis' => function ($q) use ($selectedJadwal) {
                                    $q->where('jadwal_id', $selectedJadwal->id);
                                }])
                                ->orderBy('name')
                                ->get();
                        }
                    }
                    break;
                    
                case 'penilaian_peserta':
                    if ($request->jadwal_id) {
                        $selectedJadwal = Jadwal::with(['materi', 'pemateri'])->find($request->jadwal_id);
                        if ($selectedJadwal) {
                            $penilaianPesertas = User::where('role', 'peserta')
                                ->with(['pendaftaran', 'penilaianPesertas' => function ($q) use ($selectedJadwal) {
                                    $q->where('jadwal_id', $selectedJadwal->id);
                                }])
                                ->orderBy('name')
                                ->get();
                        }
                    }
                    break;
                    
                case 'observasi_harian':
                    if ($request->kelompok_id && $request->hari_ke) {
                        $selectedKelompok = Kelompok::with('pendamping')->find($request->kelompok_id);
                        if ($selectedKelompok) {
                            $observasiPesertas = $selectedKelompok->pesertas()->orderBy('name')->get();
                            $observasis = ObservasiHarian::where('hari_ke', $request->hari_ke)
                                ->whereIn('peserta_id', $observasiPesertas->pluck('id'))
                                ->get()
                                ->keyBy('peserta_id');
                        }
                    }
                    break;
                    
                case 'nilai_pemateri':
                    if ($request->peserta_id) {
                        $selectedPeserta = User::with('pendaftaran')->find($request->peserta_id);
                        if ($selectedPeserta) {
                            $nilaiPemateriRatings = NilaiPemateri::where('peserta_id', $selectedPeserta->id)
                                ->get()
                                ->keyBy('jadwal_id');
                        }
                    }
                    break;
                    
                case 'nilai_inspel':
                    if ($request->peserta_id) {
                        $selectedPeserta = User::with('pendaftaran')->find($request->peserta_id);
                        if ($selectedPeserta) {
                            $inspels = User::where('role', 'inspel')->orderBy('name')->get();
                            $nilaiInspelRatings = NilaiInspel::where('peserta_id', $selectedPeserta->id)
                                ->get()
                                ->keyBy('inspel_id');
                        }
                    }
                    break;
                    
                case 'evaluasi_refleksi':
                    if ($request->peserta_id && $request->hari_ke) {
                        $selectedPeserta = User::with('pendaftaran')->find($request->peserta_id);
                        if ($selectedPeserta) {
                            $evaluasiRefleksi = EvaluasiRefleksi::where('peserta_id', $selectedPeserta->id)
                                ->where('hari_ke', $request->hari_ke)
                                ->first();
                        }
                    }
                    break;
                    
                case 'berkas_jawaban':
                    $query = \App\Models\JawabanTes::with(['peserta.pendaftaran', 'bankSoal.materi'])
                        ->where('jawaban', 'like', 'uploads/jawaban_ujian/%');
                    if ($request->materi_id && $request->materi_id !== 'all') {
                        $query->whereHas('bankSoal', function($q) use ($request) {
                            $q->where('materi_id', $request->materi_id);
                        });
                    }
                    if ($request->tipe_ujian && $request->tipe_ujian !== 'all') {
                        $query->whereHas('bankSoal', function($q) use ($request) {
                            $q->where('tipe', $request->tipe_ujian);
                        });
                    }
                    $jawabanUjians = $query->latest()->get();
                    break;
            }
        }

        return view('admin.laporan.index', compact(
            'pemateris', 'jadwals', 'kelompoks', 'pesertas', 'materis', 'type',
            'selectedPemateri', 'selectedJadwal', 'daftarHadirPesertas', 'penilaianPesertas',
            'selectedKelompok', 'observasiPesertas', 'observasis', 'selectedPeserta', 
            'nilaiPemateriRatings', 'nilaiInspelRatings', 'inspels', 'evaluasiRefleksi', 'jawabanUjians'
        ));
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
                $pemateri = Pemateri::with(['riwayatPendidikans', 'riwayatOrganisasis', 'riwayatPengkaderans'])->findOrFail($request->pemateri_id);
                
                $pdf = Pdf::loadView('admin.laporan.pdf.cv_pemateri', compact('pemateri'));
                return $this->downloadPdf($pdf, '01_CV_Pemateri_' . $pemateri->nama . '.pdf');

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
                return $this->downloadPdf($pdf, '02_Daftar_Hadir_' . $jadwal->materi->nama_materi . '.pdf');

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
                return $this->downloadPdf($pdf, '03_Penilaian_Peserta_' . $jadwal->materi->nama_materi . '.pdf');

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
                return $this->downloadPdf($pdf, '04_Observasi_Harian_Kelompok_' . $kelompok->nama_kelompok . '_Hari_' . $hari_ke . '.pdf');

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
                return $this->downloadPdf($pdf, '05_Penilaian_Pemateri_Oleh_' . $peserta->name . '.pdf');

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
                return $this->downloadPdf($pdf, '06_Penilaian_Inspel_Oleh_' . $peserta->name . '.pdf');

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
                return $this->downloadPdf($pdf, '07_Evaluasi_Refleksi_Peserta_' . $peserta->name . '_Hari_' . $hari_ke . '.pdf');

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
                return $this->downloadPdf($pdf, '08_Soal_Pretest_Posttest.pdf');

            default:
                return abort(404, 'Jenis laporan tidak valid.');
        }
    }

    private function downloadPdf($pdf, $filename)
    {
        $cleanFilename = preg_replace('/[^A-Za-z0-9_\-\.]/', '', str_replace(' ', '_', $filename));
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $cleanFilename . '"',
        ]);
    }

    public function downloadJawaban($id)
    {
        $jawaban = \App\Models\JawabanTes::with(['peserta', 'bankSoal.materi'])->findOrFail($id);
        
        $filePath = public_path($jawaban->jawaban);
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Berkas jawaban tidak ditemukan di server.');
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        
        // Clean material name and participant name for filename compatibility
        $materiName = str_replace([' ', '/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $jawaban->bankSoal->materi->nama_materi);
        $pesertaName = str_replace([' ', '/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $jawaban->peserta->name);
        
        // Format: materi_nama peserta.ext
        $downloadName = $materiName . '_' . $pesertaName . '.' . $extension;

        return response()->download($filePath, $downloadName);
    }

    public function slideCv(Request $request)
    {
        $pemateris = \App\Models\Pemateri::orderBy('nama')->get();
        $selectedPemateri = null;
        if ($request->pemateri_id) {
            $selectedPemateri = \App\Models\Pemateri::with(['riwayatPendidikans', 'riwayatOrganisasis', 'riwayatPengkaderans'])->find($request->pemateri_id);
        }
        return view('admin.laporan.slide_cv', compact('pemateris', 'selectedPemateri'));
    }
}
