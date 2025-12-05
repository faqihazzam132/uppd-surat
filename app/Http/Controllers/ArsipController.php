<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
    // 1. Tampilkan Daftar Arsip
    public function index()
    {
        $arsips = Arsip::with('surat')->latest()->get();
        return view('arsip.index', compact('arsips'));
    }

    // 2. Form Arsipkan Surat (Dari tombol di detail surat)
    public function create($type, $id)
    {
        // Validasi tipe surat
        if ($type == 'surat-masuk') {
            $surat = SuratMasuk::findOrFail($id);
            $model_type = SuratMasuk::class;
        } elseif ($type == 'surat-keluar') {
            $surat = SuratKeluar::findOrFail($id);
            $model_type = SuratKeluar::class;
        } else {
            abort(404);
        }

        // Cek jika sudah diarsipkan
        if ($surat->arsip) {
            return redirect()->route('arsip.show', $surat->arsip->id)
                ->with('warning', 'Surat ini sudah diarsipkan.');
        }

        return view('arsip.create', compact('surat', 'type', 'id'));
    }

    // 3. Simpan Arsip
    public function store(Request $request)
    {
        $request->validate([
            'kode_klasifikasi' => 'required',
            'lokasi_arsip' => 'required',
            'tanggal_arsip' => 'required|date',
            'file_arsip' => 'nullable|file|mimes:pdf|max:2048',
            'surat_type' => 'required',
            'surat_id' => 'required',
        ]);

        $data = $request->all();

        // Mapping tipe string ke Model Class
        if ($request->surat_type == 'surat-masuk') {
            $data['surat_type'] = SuratMasuk::class;
        } elseif ($request->surat_type == 'surat-keluar') {
            $data['surat_type'] = SuratKeluar::class;
        }

        // Upload File Arsip (Jika ada)
        if ($request->hasFile('file_arsip')) {
            $data['file_arsip'] = $request->file('file_arsip')->store('arsip', 'public');
        }

        Arsip::create($data);

        return redirect()->route('arsip.index')->with('success', 'Surat berhasil diarsipkan!');
    }

    // 4. Detail Arsip
    public function show($id)
    {
        $arsip = Arsip::with('surat')->findOrFail($id);
        return view('arsip.show', compact('arsip'));
    }

    // 5. Hapus Arsip
    public function destroy($id)
    {
        $arsip = Arsip::findOrFail($id);
        
        if ($arsip->file_arsip) {
            Storage::disk('public')->delete($arsip->file_arsip);
        }

        $arsip->delete();

        return redirect()->route('arsip.index')->with('success', 'Data arsip berhasil dihapus.');
    }
}
