<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|min:3',
        ]);

        $keyword = $request->keyword;

        // Cari di Surat Masuk berdasarkan No. Agenda
        $surat = SuratMasuk::where('no_agenda', $keyword)->first();

        // Jika tidak ketemu, coba cari di Pengajuan berdasarkan No. Registrasi (Bonus functionality)
        if (!$surat) {
            $pengajuan = Pengajuan::where('no_registrasi', $keyword)->first();
            if ($pengajuan) {
                return view('tracking.result', [
                    'data' => $pengajuan,
                    'type' => 'pengajuan',
                    'keyword' => $keyword
                ]);
            }
        } else {
            return view('tracking.result', [
                'data' => $surat,
                'type' => 'surat',
                'keyword' => $keyword
            ]);
        }

        return redirect()->back()->with('error', 'Data dengan Nomor Agenda / Registrasi tersebut tidak ditemukan.');
    }
}
