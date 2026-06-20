<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class IdCardController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'peserta')->with('pendaftaran');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('pendaftaran', function($qp) use ($search) {
                      $qp->where('delegasi', 'like', "%{$search}%");
                  });
            });
        }

        $pesertas = $query->latest()->get();

        return view('admin.idcard.index', compact('pesertas'));
    }

    public function download($id)
    {
        $peserta = User::where('role', 'peserta')->with('pendaftaran')->findOrFail($id);

        $pdf = Pdf::loadView('admin.idcard.pdf', compact('peserta'));
        
        // Exact aspect ratio of 1276 x 2022 (approx. 398pt x 632pt)
        $pdf->setPaper([0, 0, 398, 632]);

        return $pdf->stream('ID_Card_Peserta_' . str_replace(' ', '_', $peserta->name) . '.pdf');
    }

    public function downloadAll(Request $request)
    {
        $pesertas = User::where('role', 'peserta')->with('pendaftaran')->latest()->get();

        if ($pesertas->isEmpty()) {
            return redirect()->back()->with('status', 'Tidak ada data peserta untuk diexport.');
        }

        $pdf = Pdf::loadView('admin.idcard.pdf_all', compact('pesertas'));
        $pdf->setPaper([0, 0, 398, 632]);

        return $pdf->download('ID_Card_Peserta_Semua.pdf');
    }
}
