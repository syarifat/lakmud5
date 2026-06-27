<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\NilaiPemateri;
use App\Models\NilaiInspel;
use App\Models\EvaluasiRefleksi;
use App\Models\Materi;
use App\Models\BankSoal;
use App\Models\JawabanTes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Ambil kelompok peserta (jika diploting)
        $kelompok = $user->kelompoks()->with('pendamping')->first();

        // Hitung persentase kehadiran
        $totalJadwal = Jadwal::count();
        $totalHadir = Absensi::where('peserta_id', $user->id)->count();
        $kehadiranPersen = $totalJadwal > 0 ? round(($totalHadir / $totalJadwal) * 100) : 0;

        // Hitung pengisian evaluasi & feedback
        $refleksiSelesaiCount = EvaluasiRefleksi::where('peserta_id', $user->id)->count();
        
        // Jadwal hari ini
        $today = now()->toDateString();
        $jadwalHariIni = Jadwal::with(['materi', 'pemateri'])
            ->whereDate('waktu_mulai', $today)
            ->orderBy('waktu_mulai')
            ->get();

        // Cari tahu status kehadiran hari ini
        foreach ($jadwalHariIni as $j) {
            $j->is_hadir = Absensi::where('jadwal_id', $j->id)
                ->where('peserta_id', $user->id)
                ->exists();
        }

        // Statistik umum
        $stats = [
            'kehadiran_persen' => $kehadiranPersen,
            'refleksi_selesai' => $refleksiSelesaiCount,
            'nilai_pemateri' => NilaiPemateri::where('peserta_id', $user->id)->count(),
            'nilai_inspel' => NilaiInspel::where('peserta_id', $user->id)->count(),
        ];

        return view('peserta.dashboard', compact('kelompok', 'jadwalHariIni', 'stats'));
    }

    public function absensi()
    {
        $user = Auth::user();
        $jadwals = Jadwal::with(['materi', 'pemateri'])->orderBy('waktu_mulai')->get();

        // Tandai status absen untuk masing-masing jadwal
        foreach ($jadwals as $j) {
            $absensi = Absensi::where('jadwal_id', $j->id)
                ->where('peserta_id', $user->id)
                ->first();
            $j->is_hadir = !is_null($absensi);
            $j->waktu_absen = $absensi ? $absensi->waktu_tap : null;
        }

        return view('peserta.absensi', compact('jadwals'));
    }


    public function nilaiPemateriIndex()
    {
        $user = Auth::user();

        // Ambil semua jadwal yang pernah ada
        $jadwals = Jadwal::with(['materi', 'pemateri'])->orderBy('waktu_mulai')->get();

        // Ambil nilai pemateri yang sudah diberikan oleh peserta ini
        $nilaiPemateri = NilaiPemateri::where('peserta_id', $user->id)
            ->get()
            ->keyBy('jadwal_id');

        return view('peserta.nilai_pemateri', compact('jadwals', 'nilaiPemateri'));
    }

    public function nilaiPemateriStore(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'nilai' => 'required|integer|in:50,60,70,80,90',
            'catatan_khusus' => 'nullable|string',
        ]);

        $user = Auth::user();

        NilaiPemateri::updateOrCreate(
            [
                'jadwal_id' => $request->jadwal_id,
                'peserta_id' => $user->id,
            ],
            [
                'nilai' => $request->nilai,
                'catatan_khusus' => $request->catatan_khusus,
            ]
        );

        return redirect()->back()->with('status', 'Penilaian pemateri berhasil disimpan.');
    }

    public function nilaiInspelIndex()
    {
        $user = Auth::user();

        // Ambil semua inspel (role: inspel)
        $inspels = User::where('role', 'inspel')->get();

        // Ambil nilai inspel yang sudah diberikan oleh peserta ini
        $nilaiInspel = NilaiInspel::where('peserta_id', $user->id)
            ->get()
            ->keyBy('inspel_id');

        return view('peserta.nilai_inspel', compact('inspels', 'nilaiInspel'));
    }

    public function nilaiInspelStore(Request $request)
    {
        $request->validate([
            'inspel_id' => 'required|exists:users,id',
            'nilai' => 'required|integer|in:50,60,70,80,90',
            'catatan_khusus' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Pastikan target memang inspel
        $targetUser = User::findOrFail($request->inspel_id);
        if ($targetUser->role !== 'inspel') {
            return redirect()->back()->with('error', 'User yang dinilai bukan merupakan Inspel.');
        }

        NilaiInspel::updateOrCreate(
            [
                'inspel_id' => $request->inspel_id,
                'peserta_id' => $user->id,
            ],
            [
                'nilai' => $request->nilai,
                'catatan_khusus' => $request->catatan_khusus,
            ]
        );

        return redirect()->back()->with('status', 'Penilaian Inspel berhasil disimpan.');
    }

    public function refleksiIndex()
    {
        $user = Auth::user();

        // Ambil semua refleksi yang sudah diisi oleh peserta
        $refleksis = EvaluasiRefleksi::where('peserta_id', $user->id)
            ->get()
            ->keyBy('hari_ke');

        return view('peserta.refleksi', compact('refleksis'));
    }

    public function refleksiStore(Request $request)
    {
        $request->validate([
            'hari_ke' => 'required|integer|between:1,4',
            'tanggal' => 'required|date',
            'q1_pengalaman' => 'required|string',
            'q2_partisipasi' => 'required|string',
            'q3_hambatan_dorongan' => 'required|string',
            'q4_kesempatan_pendapat' => 'required|string',
            'q5_pengetahuan_didapat' => 'required|string',
            'q6_hambatan_diri_sendiri' => 'required|string',
        ]);

        $user = Auth::user();

        EvaluasiRefleksi::updateOrCreate(
            [
                'hari_ke' => $request->hari_ke,
                'peserta_id' => $user->id,
            ],
            [
                'tanggal' => $request->tanggal,
                'q1_pengalaman' => $request->q1_pengalaman,
                'q2_partisipasi' => $request->q2_partisipasi,
                'q3_hambatan_dorongan' => $request->q3_hambatan_dorongan,
                'q4_kesempatan_pendapat' => $request->q4_kesempatan_pendapat,
                'q5_pengetahuan_didapat' => $request->q5_pengetahuan_didapat,
                'q6_hambatan_diri_sendiri' => $request->q6_hambatan_diri_sendiri,
            ]
        );

        return redirect()->back()->with('status', 'Lembar evaluasi dan refleksi harian berhasil disimpan.');
    }

    public function ujianIndex()
    {
        $user = Auth::user();

        // Ambil semua materi beserta bank soalnya
        $materis = Materi::with(['bankSoals' => function($q) {
            $q->select('id', 'materi_id', 'tipe');
        }])->get();

        // Susun daftar materi dan ketersediaan tes
        foreach ($materis as $m) {
            $m->has_pretest = true;
            $m->has_posttest = true;

            // Cek apakah user sudah mengunggah Pre-Test
            $pretestJawaban = JawabanTes::where('peserta_id', $user->id)
                ->whereHas('bankSoal', function ($q) use ($m) {
                    $q->where('materi_id', $m->id)->where('tipe', 'pretest');
                })
                ->first();
            $m->pretest_done = !is_null($pretestJawaban);
            $m->pretest_file = $pretestJawaban ? $pretestJawaban->jawaban : null;

            // Cek apakah user sudah mengunggah Post-Test
            $posttestJawaban = JawabanTes::where('peserta_id', $user->id)
                ->whereHas('bankSoal', function ($q) use ($m) {
                    $q->where('materi_id', $m->id)->where('tipe', 'posttest');
                })
                ->first();
            $m->posttest_done = !is_null($posttestJawaban);
            $m->posttest_file = $posttestJawaban ? $posttestJawaban->jawaban : null;
        }

        return view('peserta.ujian.index', compact('materis'));
    }

    public function ujianMulai($materi_id, Request $request)
    {
        $tipe = $request->query('tipe');
        if (!in_array($tipe, ['pretest', 'posttest'])) {
            return redirect()->route('peserta.ujian')->with('error', 'Tipe tes tidak valid.');
        }

        $user = Auth::user();
        $materi = Materi::findOrFail($materi_id);

        // Ambil atau buat BankSoal record untuk tipe ini agar foreign key valid
        $firstSoal = BankSoal::where('materi_id', $materi_id)
            ->where('tipe', $tipe)
            ->first();

        if (!$firstSoal) {
            $firstSoal = BankSoal::create([
                'materi_id' => $materi_id,
                'tipe' => $tipe,
                'pertanyaan' => 'Berkas Jawaban ' . ucfirst($tipe) . ' ' . $materi->nama_materi,
            ]);
        }

        // Ambil jawaban yang sudah pernah diunggah jika ada
        $existingJawaban = JawabanTes::where('peserta_id', $user->id)
            ->where('bank_soal_id', $firstSoal->id)
            ->first();

        $soals = collect([$firstSoal]);

        return view('peserta.ujian.mulai', compact('materi', 'soals', 'tipe', 'existingJawaban'));
    }

    public function ujianStore($materi_id, Request $request)
    {
        $tipe = $request->input('tipe');
        if (!in_array($tipe, ['pretest', 'posttest'])) {
            return redirect()->route('peserta.ujian')->with('error', 'Tipe tes tidak valid.');
        }

        $request->validate([
            'foto_jawaban' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240',
        ]);

        $user = Auth::user();
        $materi = Materi::findOrFail($materi_id);

        // Ambil atau buat BankSoal record untuk tipe ini agar foreign key valid
        $firstSoal = BankSoal::where('materi_id', $materi_id)
            ->where('tipe', $tipe)
            ->first();

        if (!$firstSoal) {
            $firstSoal = BankSoal::create([
                'materi_id' => $materi_id,
                'tipe' => $tipe,
                'pertanyaan' => 'Berkas Jawaban ' . ucfirst($tipe) . ' ' . $materi->nama_materi,
            ]);
        }

        // Simpan file
        if ($request->hasFile('foto_jawaban')) {
            $file = $request->file('foto_jawaban');
            $destinationPath = public_path('uploads/jawaban_ujian');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            // Clean material and participant name for disk filename
            $cleanMateri = str_replace([' ', '/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $materi->nama_materi);
            $cleanName = str_replace([' ', '/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $user->name);
            
            // Format: materi_nama peserta_timestamp.ext
            $filename = $cleanMateri . '_' . $cleanName . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $filename);
            $filePath = 'uploads/jawaban_ujian/' . $filename;
            
            // Hapus file lama jika ada untuk mencegah sampah berkas
            $oldJawaban = JawabanTes::where('peserta_id', $user->id)
                ->where('bank_soal_id', $firstSoal->id)
                ->first();
                
            if ($oldJawaban) {
                if (file_exists(public_path($oldJawaban->jawaban))) {
                    @unlink(public_path($oldJawaban->jawaban));
                }
                $oldJawaban->update([
                    'jawaban' => $filePath,
                ]);
            } else {
                JawabanTes::create([
                    'bank_soal_id' => $firstSoal->id,
                    'peserta_id' => $user->id,
                    'jawaban' => $filePath,
                    'nilai' => null,
                ]);
            }
        }

        return redirect()->route('peserta.ujian')->with('status', 'Foto lembar jawaban berhasil diunggah.');
    }
}
