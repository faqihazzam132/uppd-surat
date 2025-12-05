<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
    // 1. Tampilkan Daftar Arsip (Dengan Filter & Pencarian)
    public function index(Request $request)
    {
        $query = Arsip::with('surat');

        // Filter Jenis Surat
        if ($request->filled('jenis_surat')) {
            if ($request->jenis_surat == 'masuk') {
                $query->where('surat_type', SuratMasuk::class);
            } elseif ($request->jenis_surat == 'keluar') {
                $query->where('surat_type', SuratKeluar::class);
            }
        }

        // Filter Rentang Tanggal Arsip
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_arsip', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        // Pencarian Global (No Surat, Perihal, Pengirim/Tujuan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHasMorph('surat', [SuratMasuk::class, SuratKeluar::class], function ($q, $type) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
                
                if ($type === SuratMasuk::class) {
                    $q->orWhere('pengirim', 'like', "%{$search}%");
                } elseif ($type === SuratKeluar::class) {
                    $q->orWhere('tujuan', 'like', "%{$search}%");
                }
            });
        }

        $arsips = $query->latest()->get();
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
        $arsip->delete();

        return redirect()->route('arsip.index')->with('success', 'Data arsip berhasil dihapus.');
    }
}
