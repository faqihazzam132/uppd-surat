<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    // 1. Tampilkan Daftar Surat Keluar
    public function index()
    {
        $surats = SuratKeluar::latest()->get();
        return view('surat-keluar.index', compact('surats'));
    }

    // 2. Form Buat Surat Keluar Baru
    public function create()
    {
        return view('surat-keluar.create');
    }

    // 3. Simpan Draft Surat Keluar
    public function store(Request $request)
    {
        $request->validate([
            'tujuan' => 'required',
            'perihal' => 'required',
            'tanggal_surat' => 'required|date',
            'file_draft' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Upload Draft
        $path = $request->file('file_draft')->store('surat-keluar/draft', 'public');

        // Generate No Surat Otomatis (Contoh: SK-2023/001)
        $count = SuratKeluar::count() + 1;
        $no_surat = 'SK-' . date('Y') . '/' . str_pad($count, 3, '0', STR_PAD_LEFT);

        SuratKeluar::create([
            'no_surat' => $no_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'tujuan' => $request->tujuan,
            'perihal' => $request->perihal,
            'file_draft' => $path,
            'status' => 'draft', // Status awal
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('surat-keluar.index')->with('success', 'Draft Surat Keluar berhasil dibuat!');
    }

    // 4. Tampilkan Detail Surat Keluar
    public function show($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        return view('surat-keluar.show', compact('surat'));
    }

    // 5. Update Status (Verifikasi oleh Kasubbag/Kepala Unit)
    // Bisa buat tombol "Setujui" atau "Revisi"
    public function updateStatus(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:verifikasi,disetujui,revisi,terkirim',
        ]);

        $surat->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status surat berhasil diperbarui.');
    }

    // 6. Upload File Final (Setelah TTD Basah/Elektronik)
    public function uploadFinal(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);

        $request->validate([
            'file_final' => 'required|file|mimes:pdf|max:2048',
        ]);

        $path = $request->file('file_final')->store('surat-keluar/final', 'public');

        $surat->update([
            'file_final' => $path,
            'status' => 'terkirim', // Atau 'arsip'
        ]);

        return redirect()->back()->with('success', 'File final berhasil diunggah!');
    }
}