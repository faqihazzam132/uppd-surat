<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->string('no_registrasi')->unique(); // Tiket untuk tracking
            $table->foreignId('user_id')->constrained('users'); // Pemohon
            $table->string('jenis_surat'); // Mutasi, PBB, dll
            $table->text('keterangan');
            $table->string('file_syarat')->nullable(); // KTP/Dokumen pendukung
            $table->string('status')->default('menunggu_verifikasi'); // menunggu, diterima, ditolak, selesai
            $table->text('catatan_petugas')->nullable(); // Kalau ditolak kenapa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
