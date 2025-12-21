<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // FR-R-02 & FR-R-03: Halaman Filter Laporan
    public function index()
    {
        return view('reports.index');
    }

    // Generate Laporan (Preview / Export)
    public function generate(Request $request)
    {
        $request->validate([
            'jenis_laporan' => 'required|in:surat_masuk,surat_keluar',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'action' => 'required|in:preview,pdf'
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $jenis = $request->jenis_laporan;

        if ($jenis == 'surat_masuk') {
            $data = SuratMasuk::whereBetween('tanggal_diterima', [$startDate, $endDate])
                ->orderBy('tanggal_diterima', 'asc')
                ->get();
            $title = "Laporan Rekapitulasi Surat Masuk";
        } else {
            $data = SuratKeluar::whereBetween('tanggal_surat', [$startDate, $endDate])
                ->orderBy('tanggal_surat', 'asc')
                ->get();
            $title = "Laporan Rekapitulasi Surat Keluar";
        }

        // Jika Action PDF (FR-R-04)
        if ($request->action == 'pdf') {
            $pdf = Pdf::loadView('reports.pdf', compact('data', 'jenis', 'startDate', 'endDate', 'title'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('laporan_' . $jenis . '_' . $startDate . '_to_' . $endDate . '.pdf');
        }

        // Jika Preview
        return view('reports.result', compact('data', 'jenis', 'startDate', 'endDate', 'title'));
    }
}
