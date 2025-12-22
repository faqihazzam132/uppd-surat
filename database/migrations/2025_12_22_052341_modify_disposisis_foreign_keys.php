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
        Schema::table('disposisis', function (Blueprint $table) {
            // 1. Drop existing foreign keys
            $table->dropForeign(['pengirim_id']);
            $table->dropForeign(['penerima_id']);

            // 2. Make columns nullable
            $table->unsignedBigInteger('pengirim_id')->nullable()->change();
            $table->unsignedBigInteger('penerima_id')->nullable()->change();

            // 3. Add new foreign keys with ON DELETE SET NULL
            $table->foreign('pengirim_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->foreign('penerima_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposisis', function (Blueprint $table) {
            $table->dropForeign(['pengirim_id']);
            $table->dropForeign(['penerima_id']);

            // Revert changes (Warning: this might fail if data is null)
            // Ideally we shouldn't force it back to not null without data cleanup, 
            // but for 'down' method we try to restore structure.
            $table->unsignedBigInteger('pengirim_id')->nullable(false)->change();
            $table->unsignedBigInteger('penerima_id')->nullable(false)->change();

            $table->foreign('pengirim_id')->references('id')->on('users');
            $table->foreign('penerima_id')->references('id')->on('users');
        });
    }
};
