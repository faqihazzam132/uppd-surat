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
            $pengajuans = Pengajuan::where('user_id', $user->id)->latest()->take(5)->get(); // Limit 5 for dashboard
            
            $stats = [
                'total' => Pengajuan::where('user_id', $user->id)->count(),
                'menunggu' => Pengajuan::where('user_id', $user->id)->where('status', 'menunggu_verifikasi')->count(),
                'diterima' => Pengajuan::where('user_id', $user->id)->where('status', 'diterima')->count(),
                'ditolak' => Pengajuan::where('user_id', $user->id)->where('status', 'ditolak')->count(),
            ];

            return view('dashboard.pemohon', compact('pengajuans', 'stats'));
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