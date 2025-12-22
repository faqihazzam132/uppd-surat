<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Disposisi;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role == 'pemohon') {
            // Dashboard Pemohon: Lihat pengajuan sendiri
            $pengajuans = Pengajuan::where('user_id', $user->id)->get();
            return view('dashboard.pemohon', compact('pengajuans'));
        } else {
            // Dashboard Admin/Staff: Statistik
            $totalMasuk = SuratMasuk::count();
            $totalKeluar = SuratKeluar::count();
            $totalDisposisi = Disposisi::where('status', 'belum_dibaca')->count();
            $pengajuanBaru = Pengajuan::where('status', 'menunggu_verifikasi')->count();

            // Statistik Bulanan (Tahun Ini)
            $masukPerBulan = [];
            $keluarPerBulan = [];
            
            for ($m = 1; $m <= 12; $m++) {
                $masukPerBulan[] = SuratMasuk::whereYear('tanggal_diterima', date('Y'))
                                            ->whereMonth('tanggal_diterima', $m)
                                            ->count();
                                            
                $keluarPerBulan[] = SuratKeluar::whereYear('tanggal_surat', date('Y'))
                                            ->whereMonth('tanggal_surat', $m)
                                            ->count();
            }

            return view('dashboard.admin', compact('totalMasuk', 'totalKeluar', 'totalDisposisi', 'pengajuanBaru', 'masukPerBulan', 'keluarPerBulan'));
        }
    }
}