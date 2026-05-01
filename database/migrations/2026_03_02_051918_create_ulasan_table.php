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
        Schema::create('ulasan', function (Blueprint $table) {
    $table->id('id_ulasan');
    $table->float('rating');
    $table->text('komentar');
    $table->date('tanggal_ulasan');

    // ✅ HARUS UUID
    $table->uuid('id_user');

    // destinasi tetap BIGINT (aman)
    $table->unsignedBigInteger('id_destinasi');

    // Relasi ke users (UUID)
    $table->foreign('id_user')
          ->references('id')
          ->on('users')
          ->onDelete('cascade');

    // Relasi ke destinasi
    $table->foreign('id_destinasi')
          ->references('id_destinasi')
          ->on('destinasi')
          ->onDelete('cascade');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
