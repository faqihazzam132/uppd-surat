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
        Schema::create('arsips', function (Blueprint $table) {
            $table->id();
            $table->string('kode_klasifikasi');
            $table->string('lokasi_arsip');
            $table->date('tanggal_arsip');
            $table->string('file_arsip')->nullable(); // Bisa null jika fisik saja, tapi biasanya ada scan
            
            // Polymorphic Relationship (bisa SuratMasuk atau SuratKeluar)
            $table->string('surat_type');
            $table->unsignedBigInteger('surat_id');
            $table->index(['surat_type', 'surat_id']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};
