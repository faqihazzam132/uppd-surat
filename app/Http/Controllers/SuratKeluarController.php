<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Notifications\StatusSuratNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\SuratLog;

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
            'kategori' => 'required',
            'tanggal_surat' => 'required|date',
            'file_draft' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Upload Draft
        $path = $request->file('file_draft')->store('surat-keluar/draft', 'public');

        // Generate No Surat Otomatis (Contoh: SK-2023/001)
        $count = SuratKeluar::count() + 1;
        $no_surat = 'SK-' . date('Y') . '/' . str_pad($count, 3, '0', STR_PAD_LEFT);

        $surat = SuratKeluar::create([
            'no_surat' => $no_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'tujuan' => $request->tujuan,
            'perihal' => $request->perihal,
            'kategori' => $request->kategori,
            'file_draft' => $path,
            'status' => 'draft', // Status awal
            'user_id' => Auth::id(),
        ]);

        // Log Creation
        SuratLog::create([
            'surat_type' => SuratKeluar::class,
            'surat_id' => $surat->id,
            'user_id' => Auth::id(),
            'action' => 'Dibuat',
            'description' => 'Surat keluar dibuat dengan status Draft.',
        ]);

        // Notifikasi ke Kasubbag & Kepala Unit
        $approvers = User::whereIn('role', ['kasubbag', 'kepala_unit'])->get();
        Notification::send($approvers, new StatusSuratNotification(
            "Surat Keluar baru ($no_surat) menunggu verifikasi.",
            route('surat-keluar.show', $surat->id),
            $no_surat
        ));

        return redirect()->route('surat-keluar.index')->with('success', 'Draft Surat Keluar berhasil dibuat!');
    }

    // 4. Edit Surat Keluar (Jika status draft/revisi)
    public function edit($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $user = Auth::user();
        
        // Cek Hak Akses Edit
        $canEdit = false;
        if ($user->role == 'admin') {
            $canEdit = true;
        } elseif ($user->role == 'staff' && in_array($surat->status, ['draft', 'revisi'])) {
            $canEdit = true;
        } elseif ($user->role == 'kasubbag' && $surat->status == 'revisi') {
            $canEdit = true;
        }

        if (!$canEdit) {
            return redirect()->route('surat-keluar.show', $id)->with('error', 'Anda tidak memiliki izin untuk mengedit surat ini saat ini.');
        }

        return view('surat-keluar.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $user = Auth::user();

        // Cek Hak Akses Update (Sama dengan Edit)
        $canEdit = false;
        if ($user->role == 'admin') {
            $canEdit = true;
        } elseif ($user->role == 'staff' && in_array($surat->status, ['draft', 'revisi'])) {
            $canEdit = true;
        } elseif ($user->role == 'kasubbag' && $surat->status == 'revisi') {
            $canEdit = true;
        }

        if (!$canEdit) {
            return redirect()->route('surat-keluar.show', $id)->with('error', 'Surat tidak dapat diedit.');
        }

        $request->validate([
            'tujuan' => 'required',
            'perihal' => 'required',
            'kategori' => 'required',
            'tanggal_surat' => 'required|date',
            'file_draft' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $data = [
            'tujuan' => $request->tujuan,
            'perihal' => $request->perihal,
            'kategori' => $request->kategori,
            'tanggal_surat' => $request->tanggal_surat,
        ];

        if ($request->hasFile('file_draft')) {
            // Hapus file lama jika ada
            if ($surat->file_draft) {
                Storage::disk('public')->delete($surat->file_draft);
            }
            $data['file_draft'] = $request->file('file_draft')->store('surat-keluar/draft', 'public');
        }
        
        // Logika Perubahan Status setelah Edit
        if ($surat->status == 'revisi') {
            // Jika yang edit Staff -> Status jadi 'verifikasi' (Kirim ke Kasubbag)
            if ($user->role == 'staff') {
                $data['status'] = 'verifikasi';
                
                // Notifikasi ke Kasubbag
                $kasubbags = User::where('role', 'kasubbag')->get();
                Notification::send($kasubbags, new StatusSuratNotification(
                    "Revisi surat ($surat->no_surat) telah diperbaiki oleh Staff.",
                    route('surat-keluar.show', $surat->id),
                    $surat->no_surat
                ));
            } 
            elseif ($user->role == 'kasubbag') {
                 $data['status'] = 'verifikasi';

                 // Notifikasi ke Kepala Unit
                 $kepalaUnit = User::where('role', 'kepala_unit')->get();
                 Notification::send($kepalaUnit, new StatusSuratNotification(
                    "Revisi surat ($surat->no_surat) telah diperbaiki oleh Kasubbag.",
                    route('surat-keluar.show', $surat->id),
                    $surat->no_surat
                ));
            }
        }

        $surat->update($data);

        // Log Update
        SuratLog::create([
            'surat_type' => SuratKeluar::class,
            'surat_id' => $surat->id,
            'user_id' => Auth::id(),
            'action' => 'Diedit',
            'description' => 'Data surat diperbarui oleh ' . Auth::user()->name,
        ]);

        return redirect()->route('surat-keluar.show', $id)->with('success', 'Surat berhasil diperbarui.');
    }

    // 5. Tampilkan Detail Surat Keluar
    public function show($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        return view('surat-keluar.show', compact('surat'));
    }

    // 6. Update Status (Verifikasi oleh Kasubbag/Kepala Unit)
    public function updateStatus(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:verifikasi,disetujui,revisi,terkirim',
            'tujuan_revisi' => 'required_if:status,revisi',
            'catatan_revisi' => 'required_if:status,revisi',
        ]);

        $surat->update(['status' => $request->status]);

        // Log Status Change
        $logDesc = "Status diubah menjadi " . ucfirst($request->status);
        if ($request->status == 'revisi') {
            $logDesc .= ". Catatan: " . $request->catatan_revisi . " (Tujuan: " . ucfirst($request->tujuan_revisi) . ")";
        }

        SuratLog::create([
            'surat_type' => SuratKeluar::class,
            'surat_id' => $surat->id,
            'user_id' => Auth::id(),
            'action' => 'Update Status',
            'description' => $logDesc,
        ]);

        if ($request->status == 'revisi') {
            $catatan = $request->catatan_revisi;
            $targetRole = $request->tujuan_revisi;

            if ($targetRole == 'staff') {
                // Notifikasi ke Staff (Pembuat)
                $surat->user->notify(new StatusSuratNotification(
                    "Surat dikembalikan untuk REVISI. Catatan: " . $catatan,
                    route('surat-keluar.show', $surat->id),
                    $surat->no_surat
                ));
            } elseif ($targetRole == 'kasubbag') {
                // Notifikasi ke Kasubbag
                $kasubbags = User::where('role', 'kasubbag')->get();
                Notification::send($kasubbags, new StatusSuratNotification(
                    "Surat dikembalikan oleh Kepala Unit untuk REVISI. Catatan: " . $catatan,
                    route('surat-keluar.show', $surat->id),
                    $surat->no_surat
                ));
            }
        } else {
            // Notifikasi standar untuk status lain (Verifikasi, Disetujui)
            
            $surat->user->notify(new StatusSuratNotification(
                "Status surat keluar ($surat->no_surat) diperbarui menjadi: " . ucfirst($request->status),
                route('surat-keluar.show', $surat->id),
                $surat->no_surat
            ));
        }

        return redirect()->back()->with('success', 'Status surat berhasil diperbarui.');
    }

    // 7. Upload File Final (Setelah TTD Basah/Elektronik)
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

        // Log Upload Final
        SuratLog::create([
            'surat_type' => SuratKeluar::class,
            'surat_id' => $surat->id,
            'user_id' => Auth::id(),
            'action' => 'Upload Final',
            'description' => 'File final diupload dan status berubah menjadi Terkirim.',
        ]);

        // Notifikasi ke Semua (Staff & Pimpinan) bahwa surat sudah terkirim
        $users = User::whereIn('role', ['kasubbag', 'kepala_unit', 'staff'])->get();
        Notification::send($users, new StatusSuratNotification(
            "Surat Keluar ($surat->no_surat) telah dikirim/diupload final.",
            route('surat-keluar.show', $surat->id),
            $surat->no_surat
        ));

        return redirect()->back()->with('success', 'File final berhasil diunggah!');
    }
}