<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DisposisiController extends Controller
{
    // Menampilkan daftar disposisi sesuai peran
    public function index()
    {
        $user = Auth::user();

        // Data untuk view
        $suratBelumDisposisi = collect();
        $disposisiMasuk = collect();
        $disposisiTerkirim = collect();

        // Fetch Disposisi Masuk for everyone (including Kepala Unit)
        $disposisiMasuk = Disposisi::where('penerima_id', $user->id)
            ->with(['suratMasuk', 'pengirim'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($user->role === 'kepala_unit') {
            // FR-D-01: Kepala Unit melihat surat masuk "Menunggu Disposisi"
            $suratBelumDisposisi = SuratMasuk::where('status', 'menunggu_disposisi')->latest()->get();

            // Log disposisi yang sudah dikirim
            $disposisiTerkirim = Disposisi::where('pengirim_id', $user->id)
                ->with(['suratMasuk', 'penerima'])
                ->orderBy('created_at', 'desc')
                ->get();

        } elseif ($user->role === 'kasubbag') {
            // Kasubbag juga bisa melihat yang dia teruskan
            $disposisiTerkirim = Disposisi::where('pengirim_id', $user->id)
                ->with(['suratMasuk', 'penerima'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('disposisi.index', compact('suratBelumDisposisi', 'disposisiMasuk', 'disposisiTerkirim'));
    }

    // Form buat disposisi baru (untuk Kepala Unit) atau Meneruskan (Kasubbag)
    public function create($surat_id) // surat_id bisa merujuk ke id surat_masuk
    {
        $user = Auth::user();
        $surat = SuratMasuk::findOrFail($surat_id);

        // Tentukan siapa tujuan disposisi berdasarkan role pengirim
        $tujuan = collect();

        if ($user->role === 'kepala_unit') {
            // Kepala Unit -> Kasubbag
            $tujuan = User::where('role', 'kasubbag')->get();
        } elseif ($user->role === 'kasubbag') {
            // Kasubbag -> Staff atau Kepala Unit (jika perlu koordinasi)
            $tujuan = User::whereIn('role', ['staff', 'kepala_unit'])->get();
        } elseif ($user->role === 'staff') {
            // Staff -> Kasubbag (Lapor/Naikkan)
            $tujuan = User::where('role', 'kasubbag')->get();
        }

        return view('disposisi.create', compact('surat', 'tujuan'));
    }

    // Simpan Disposisi Baru
    public function store(Request $request)
    {
        $request->validate([
            'surat_masuk_id' => 'required|exists:surat_masuks,id',
            'penerima_id' => 'required|exists:users,id',
            'instruksi' => 'required|string',
            'batas_waktu' => 'nullable|date',
            'catatan_tambahan' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Simpan Disposisi
        $disposisi = Disposisi::create([
            'surat_masuk_id' => $request->surat_masuk_id,
            'pengirim_id' => $user->id,
            'penerima_id' => $request->penerima_id,
            'instruksi' => $request->instruksi,
            'catatan_tambahan' => $request->catatan_tambahan,
            'batas_waktu' => $request->batas_waktu,
            'status' => 'belum_dibaca',
        ]);

        // Update status surat utama jika ini dari Kepala Unit
        $surat = SuratMasuk::find($request->surat_masuk_id);
        if ($user->role === 'kepala_unit') {
            $surat->update(['status' => 'disposisi']);
        }

        // Kirim Notifikasi
        $penerima = User::find($request->penerima_id);
        if ($penerima) {
            $penerima->notify(new \App\Notifications\NewDisposisiNotification($disposisi));
        }

        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil dikirim!');
    }

    // Lihat Detail Disposisi (Untuk Penerima: Baca / Proses)
    public function show($id)
    {
        $disposisi = Disposisi::with(['suratMasuk', 'pengirim', 'penerima'])->findOrFail($id);

        // Update status menjadi 'diproses' jika masih 'belum_dibaca' dan yang buka adalah penerima
        if (Auth::id() === $disposisi->penerima_id && $disposisi->status === 'belum_dibaca') {
            $disposisi->update(['status' => 'diproses']);
        }

        // Ambil riwayat disposisi lain untuk surat ini (FR-D-08)
        $riwayat = Disposisi::where('surat_masuk_id', $disposisi->surat_masuk_id)
            ->with(['pengirim', 'penerima'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('disposisi.show', compact('disposisi', 'riwayat'));
    }

    // FR-D-06: Staff update laporan / selesaikan
    public function update(Request $request, $id)
    {
        $disposisi = Disposisi::findOrFail($id);

        // Validasi user
        if (Auth::id() !== $disposisi->penerima_id) {
            abort(403);
        }

        $request->validate([
            'laporan_penyelesaian' => 'required|string',
            'status' => 'required|in:diproses,selesai',
            'file_penyelesaian' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ]);

        $data = [
            'laporan_penyelesaian' => $request->laporan_penyelesaian,
            'status' => $request->status,
        ];

        if ($request->hasFile('file_penyelesaian')) {
            $path = $request->file('file_penyelesaian')->store('laporan-disposisi', 'public');
            $data['file_penyelesaian'] = $path;
        }

        $disposisi->update($data);

        // Jika selesai, update status surat utama jadi 'selesai'
        if ($request->status === 'selesai') {
            $disposisi->suratMasuk->update(['status' => 'selesai']);
        }

        return redirect()->back()->with('success', 'Laporan berhasil disimpan.');
    }
}