<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisposisiController extends Controller
{
    // Menampilkan surat yang PERLU didisposisi (Untuk Kepala Unit/Kasubbag)
    public function index()
    {
        $user = Auth::user();
        
        // Jika user adalah penerima disposisi
        $disposisiMasuk = Disposisi::where('penerima_id', $user->id)->get();
        
        return view('disposisi.index', compact('disposisiMasuk'));
    }

    // Form buat disposisi baru
    public function create($surat_id)
    {
        $surat = SuratMasuk::findOrFail($surat_id);
        // Ambil user bawahan (Contoh: Kepala Unit cuma bisa milih Kasubbag)
        $tujuan = User::where('role', '!=', 'pemohon')->get(); 
        return view('disposisi.create', compact('surat', 'tujuan'));
    }

    // Simpan Disposisi
    public function store(Request $request)
    {
        $request->validate([
            'surat_masuk_id' => 'required',
            'penerima_id' => 'required',
            'instruksi' => 'required',
        ]);

        Disposisi::create([
            'surat_masuk_id' => $request->surat_masuk_id,
            'pengirim_id' => Auth::id(),
            'penerima_id' => $request->penerima_id,
            'instruksi' => $request->instruksi,
            'batas_waktu' => $request->batas_waktu,
            'status' => 'belum_dibaca',
        ]);

        // Update status surat utama
        $surat = SuratMasuk::find($request->surat_masuk_id);
        $surat->update(['status' => 'disposisi']);

        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil dikirim!');
    }
}