<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{
    // --- UNTUK PEMOHON (Masyarakat) ---

    // 1. Halaman Dashboard Pemohon (Daftar Pengajuan Saya)
    public function index()
    {
        $pengajuans = Pengajuan::where('user_id', Auth::id())->latest()->get();
        return view('pengajuan.index', compact('pengajuans'));
    }

    // 2. Form Pengajuan Baru
    public function create()
    {
        return view('pengajuan.create');
    }

    // 3. Simpan Pengajuan Baru
    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required',
            'keterangan' => 'required',
            'file_syarat' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        // Upload file syarat
        $path = $request->file('file_syarat')->store('syarat-pengajuan', 'public');

        // Generate No Registrasi Unik (Contoh: REG-20231025-X7Z)
        $no_registrasi = 'REG-' . date('Ymd') . '-' . strtoupper(Str::random(3));

        Pengajuan::create([
            'no_registrasi' => $no_registrasi,
            'user_id' => Auth::id(),
            'jenis_surat' => $request->jenis_surat,
            'keterangan' => $request->keterangan,
            'file_syarat' => $path,
            'status' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Permohonan berhasil diajukan! Nomor Registrasi: ' . $no_registrasi);
    }

    // --- UNTUK STAFF (Verifikator) ---

    // 4. Halaman Daftar Masuk (Khusus Staff)
    public function indexAdmin()
    {
        // Menampilkan yang statusnya 'menunggu_verifikasi'
        $pengajuans = Pengajuan::where('status', 'menunggu_verifikasi')->latest()->get();
        return view('pengajuan.admin_index', compact('pengajuans'));
    }

    // 5. Proses Verifikasi (Terima/Tolak)
    public function verify(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'catatan_petugas' => 'nullable|string',
        ]);

        $pengajuan->update([
            'status' => $request->status,
            'catatan_petugas' => $request->catatan_petugas,
        ]);

        // Jika diterima, mungkin bisa otomatis masuk ke tabel SuratMasuk (Opsional)
        // Logic tambahan bisa ditaruh di sini.

        return redirect()->back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}