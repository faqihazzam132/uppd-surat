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

            return view('dashboard.admin', compact('totalMasuk', 'totalKeluar', 'totalDisposisi', 'pengajuanBaru'));
        }
    }
}