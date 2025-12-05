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
        Schema::create('surat_logs', function (Blueprint $table) {
            $table->id();
            $table->string('surat_type');
            $table->unsignedBigInteger('surat_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // e.g., 'created', 'updated', 'status_changed'
            $table->text('description')->nullable(); // Detail log
            $table->timestamps();

            $table->index(['surat_type', 'surat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_logs');
    }
};
