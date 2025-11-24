<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SuratMasukController extends Controller
{
    public function index()
    {
        $surats = SuratMasuk::latest()->get();
        return view('surat-masuk.index', compact('surats'));
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
            'file_surat' => 'required|file|mimes:pdf,jpg,png|max:2048',
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
        ]);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat Masuk berhasil dicatat!');
    }

    public function show(SuratMasuk $suratMasuk)
    {
        return view('surat-masuk.show', compact('suratMasuk'));
    }
}