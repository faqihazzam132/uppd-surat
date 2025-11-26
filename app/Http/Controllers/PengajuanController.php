<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

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

    // 4. Detail Pengajuan (Hanya milik user sendiri)
    public function show($id)
    {
        $pengajuan = Pengajuan::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('pengajuan.show', compact('pengajuan'));
    }

    // 5. Lihat berkas syarat (hanya untuk pemilik pengajuan)
    public function viewFile($id)
    {
        $pengajuan = Pengajuan::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $filePath = storage_path('app/public/'.$pengajuan->file_syarat);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath);
    }

    // --- UNTUK STAFF (Verifikator) ---

    // 4. Laporan / Daftar Pengajuan (Untuk Admin/Staff)
    public function indexAdmin(Request $request)
    {
        $query = Pengajuan::with('user')->orderByDesc('created_at');

        // Filter tanggal
        if ($request->filled('tanggal_from')) {
            $query->whereDate('created_at', '>=', $request->tanggal_from);
        }
        if ($request->filled('tanggal_to')) {
            $query->whereDate('created_at', '<=', $request->tanggal_to);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter jenis surat
        if ($request->filled('jenis_surat')) {
            $query->where('jenis_surat', $request->jenis_surat);
        }

        $pengajuans = $query->get();

        // Data untuk opsi filter (distinct)
        $jenisSuratList = Pengajuan::select('jenis_surat')->distinct()->pluck('jenis_surat');

        return view('pengajuan.admin_index', [
            'pengajuans' => $pengajuans,
            'jenisSuratList' => $jenisSuratList,
            'filters' => $request->only(['tanggal_from', 'tanggal_to', 'status', 'jenis_surat']),
        ]);
    }

    // Export laporan pengajuan ke PDF dengan filter yang sama
    public function exportPdf(Request $request)
    {
        $query = Pengajuan::with('user')->orderByDesc('created_at');

        if ($request->filled('tanggal_from')) {
            $query->whereDate('created_at', '>=', $request->tanggal_from);
        }
        if ($request->filled('tanggal_to')) {
            $query->whereDate('created_at', '<=', $request->tanggal_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis_surat')) {
            $query->where('jenis_surat', $request->jenis_surat);
        }

        $pengajuans = $query->get();

        $pdf = Pdf::loadView('pengajuan.admin_report_pdf', [
            'pengajuans' => $pengajuans,
            'filters' => $request->only(['tanggal_from', 'tanggal_to', 'status', 'jenis_surat']),
        ])->setPaper('a4', 'landscape');

        $filename = 'laporan-pengajuan-' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
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