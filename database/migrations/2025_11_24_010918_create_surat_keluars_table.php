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
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->unique(); // Generate otomatis
            $table->date('tanggal_surat');
            $table->string('tujuan');
            $table->string('perihal');
            $table->string('file_draft')->nullable();
            $table->string('file_final')->nullable(); // Setelah TTD
            $table->string('status')->default('draft'); // draft, verifikasi, disetujui, terkirim, revisi
            $table->foreignId('user_id')->constrained('users'); // Pembuat (Staff)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
