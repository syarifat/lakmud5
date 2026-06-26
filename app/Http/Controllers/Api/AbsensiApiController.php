<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Materi;
use App\Models\Jadwal;
use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiApiController extends Controller
{
    public function getMateri()
    {
        $materis = Materi::select('id', 'nama_materi')->orderBy('id')->get();
        return response()->json($materis);
    }

    public function recordAbsensi(Request $request)
    {
        $request->validate([
            'materi_id' => 'required|exists:materis,id',
            'rfid_uid' => 'required|string',
        ]);

        // Find participant by rfid_uid
        $user = User::where('rfid_uid', $request->rfid_uid)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kartu tidak terdaftar'
            ], 404);
        }

        // Find the latest schedule session for this material
        $jadwal = Jadwal::where('materi_id', $request->materi_id)
            ->latest('id')
            ->first();

        if (!$jadwal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal belum diplot'
            ], 404);
        }

        // Prevent duplicate tap-ins for this schedule session
        $exists = Absensi::where('jadwal_id', $jadwal->id)
            ->where('peserta_id', $user->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Sudah absen',
                'name' => $user->name
            ], 200);
        }

        // Record attendance
        Absensi::create([
            'jadwal_id' => $jadwal->id,
            'peserta_id' => $user->id,
            'waktu_tap' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absen berhasil',
            'name' => $user->name
        ], 201);
    }
}
