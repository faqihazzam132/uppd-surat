<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SuratMasukController extends Controller
{
    // 1. Data Surat Masuk (Hanya yang Selesai)
    public function index()
    {
        // "hanya surat yang statusnya udah selesai"
        $surats = SuratMasuk::where('status', 'selesai')->latest()->get();
        return view('surat-masuk.data', compact('surats'));
    }

    // 2. Validasi Surat Masuk (Surat Aktif / Belum Selesai)
    public function validasi()
    {
        $user = Auth::user();
        
        // Filter Strict: Hanya tampilkan surat yang posisinya sedang di user tersebut
        // Filter Strict: Admin & Staff bisa memantau semua surat aktif
        if (in_array($user->role, ['admin', 'staff'])) {
            $surats = SuratMasuk::where('status', '!=', 'selesai')->latest()->get();
        } else {
            // User lain (Leader) hanya melihat yang ada di meja mereka
            $surats = SuratMasuk::where('posisi', $user->role)
                                ->where('status', '!=', 'selesai')
                                ->latest()->get();
        }
        
        return view('surat-masuk.validasi', compact('surats'));
    }

    public function create()
    {
        return view('surat-masuk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_surat' => 'required',
            'pengirim' => 'required',
            'perihal' => 'required',
            'file_surat' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Upload File
        $path = $request->file('file_surat')->store('surat-masuk', 'public');

        // Generate No Agenda Otomatis (Contoh: SM-2023001)
        $count = SuratMasuk::count() + 1;
        $no_agenda = 'SM-' . date('Y') . str_pad($count, 3, '0', STR_PAD_LEFT);

        SuratMasuk::create([
            'no_agenda' => $no_agenda,
            'no_surat' => $request->no_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_diterima' => now(),
            'pengirim' => $request->pengirim,
            'perihal' => $request->perihal,
            'sifat' => $request->sifat,
            'file_path' => $path,
            'user_id' => Auth::id(),
            'posisi' => 'staff', // Default position
            'status' => 'baru', // Initial status
        ]);

        return redirect()->route('surat-masuk.validasi')->with('success', 'Surat Masuk berhasil dicatat!');
    }

    public function show(SuratMasuk $suratMasuk)
    {
        return view('surat-masuk.show', compact('suratMasuk'));
    }

    // Fitur 2: Validasi / Teruskan (Staff)
    public function forward(Request $request, $id)
    {
        $request->validate([
            'tujuan_role' => 'required|in:kepala_unit,kasubbag',
        ]);

        $surat = SuratMasuk::findOrFail($id);
        
        // Update posisi surat ke role yang dipilih
        $surat->update([
            'posisi' => $request->tujuan_role,
            'status' => 'menunggu_disposisi' // Status update indicates ready for leader
        ]);

        return redirect()->back()->with('success', 'Surat berhasil diteruskan ke ' . ucwords(str_replace('_', ' ', $request->tujuan_role)));
    }

    // Fitur 2: Selesai (Leader)
    public function selesai($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        
        // Pastikan hanya leader yang memegang surat ini yang bisa menyelesaikan
        if (Auth::user()->role != $surat->posisi && Auth::user()->role != 'admin') {
             abort(403, 'Anda tidak memiliki akses untuk menyelesaikan surat ini.');
        }

        $surat->update([
            'status' => 'selesai',
            // Posisi could remain or be cleared. Let's keep it for history or clear it to remove from active list if strict.
            // Requirement says "staff bisa melihat semua daftar". Leaders see "what is chosen for them".
            // If finished, maybe it shouldn't be in Leader's "Actionable" list anymore?
            // Let's keep 'posisi' but filter by status in Index if needed? 
            // The existing filter is just `where('posisi', $role)`. 
            // Let's assume Finished items stay visible to them until Archived?
            // Or maybe 'posisi' should change to 'arsip' or null?
            // For now, let's keep it simpler: Mark as finished.
        ]);

        return redirect()->back()->with('success', 'Surat ditandai sebagai Selesai.');
    }

    public function viewFile($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        
        // Ensure file exists
        if (!$surat->file_path || !Storage::disk('public')->exists($surat->file_path)) {
             abort(404, 'File tidak ditemukan');
        }
        
        // Return file inline (for browser viewing)
        return response()->file(storage_path('app/public/' . $surat->file_path));
    }

    public function download($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        if (!$surat->file_path || !Storage::disk('public')->exists($surat->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }
        return Storage::disk('public')->download($surat->file_path);
    }
}