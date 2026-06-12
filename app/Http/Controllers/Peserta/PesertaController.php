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

    public function tapAbsen(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);

        $user = Auth::user();

        // Cegah tap ganda
        $exists = Absensi::where('jadwal_id', $request->jadwal_id)
            ->where('peserta_id', $user->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absensi pada sesi ini.');
        }

        Absensi::create([
            'jadwal_id' => $request->jadwal_id,
            'peserta_id' => $user->id,
            'waktu_tap' => now(),
        ]);

        return redirect()->back()->with('status', 'Absensi berhasil dilakukan secara mandiri/simulasi.');
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
            $m->has_pretest = $m->bankSoals->where('tipe', 'pretest')->count() > 0;
            $m->has_posttest = $m->bankSoals->where('tipe', 'posttest')->count() > 0;

            // Cek apakah user sudah mengerjakan
            if ($m->has_pretest) {
                $pretestSoalIds = $m->bankSoals->where('tipe', 'pretest')->pluck('id');
                $m->pretest_done = JawabanTes::where('peserta_id', $user->id)
                    ->whereIn('bank_soal_id', $pretestSoalIds)
                    ->exists();
            } else {
                $m->pretest_done = false;
            }

            if ($m->has_posttest) {
                $posttestSoalIds = $m->bankSoals->where('tipe', 'posttest')->pluck('id');
                $m->posttest_done = JawabanTes::where('peserta_id', $user->id)
                    ->whereIn('bank_soal_id', $posttestSoalIds)
                    ->exists();
            } else {
                $m->posttest_done = false;
            }
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

        // Ambil pertanyaan
        $soals = BankSoal::where('materi_id', $materi_id)
            ->where('tipe', $tipe)
            ->get();

        if ($soals->isEmpty()) {
            return redirect()->route('peserta.ujian')->with('error', 'Belum ada soal untuk tes ini.');
        }

        // Cek apakah sudah pernah mengerjakan
        $alreadyAnswered = JawabanTes::where('peserta_id', $user->id)
            ->whereIn('bank_soal_id', $soals->pluck('id'))
            ->exists();

        if ($alreadyAnswered) {
            return redirect()->route('peserta.ujian')->with('error', 'Anda sudah mengerjakan tes ini sebelumnya.');
        }

        return view('peserta.ujian.mulai', compact('materi', 'soals', 'tipe'));
    }

    public function ujianStore($materi_id, Request $request)
    {
        $tipe = $request->input('tipe');
        if (!in_array($tipe, ['pretest', 'posttest'])) {
            return redirect()->route('peserta.ujian')->with('error', 'Tipe tes tidak valid.');
        }

        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*' => 'required|string',
        ]);

        $user = Auth::user();
        $soals = BankSoal::where('materi_id', $materi_id)
            ->where('tipe', $tipe)
            ->get();

        // Cek ulang apakah sudah pernah mengerjakan
        $alreadyAnswered = JawabanTes::where('peserta_id', $user->id)
            ->whereIn('bank_soal_id', $soals->pluck('id'))
            ->exists();

        if ($alreadyAnswered) {
            return redirect()->route('peserta.ujian')->with('error', 'Anda sudah mengerjakan tes ini sebelumnya.');
        }

        // Simpan jawaban
        foreach ($request->jawaban as $soal_id => $jawabanText) {
            // Pastikan bank_soal_id tersebut memang milik materi ini dan tipe ini
            $validSoal = $soals->where('id', $soal_id)->first();
            if ($validSoal) {
                JawabanTes::create([
                    'bank_soal_id' => $soal_id,
                    'peserta_id' => $user->id,
                    'jawaban' => $jawabanText,
                    'nilai' => null, // akan dinilai oleh inspel nanti
                ]);
            }
        }

        return redirect()->route('peserta.ujian')->with('status', 'Jawaban ujian Anda berhasil dikirim.');
    }
}
