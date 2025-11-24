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
        Schema::create('disposisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->constrained('surat_masuks')->onDelete('cascade');
            $table->foreignId('pengirim_id')->constrained('users'); // Kepala Unit / Kasubbag
            $table->foreignId('penerima_id')->constrained('users'); // Kasubbag / Staff
            $table->text('instruksi');
            $table->text('catatan_tambahan')->nullable();
            $table->date('batas_waktu')->nullable();
            $table->string('status')->default('belum_dibaca'); // belum_dibaca, diproses, selesai
            $table->text('laporan_penyelesaian')->nullable(); // Diisi staff pas selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisis');
    }
};
