<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('id_pemesanan');
            $table->date('tanggal_pemesanan');
            $table->integer('jumlah_tiket');
            $table->integer('total_harga');
            $table->string('status');

            // ✅ FIX: pakai UUID + naming standar
            $table->uuid('user_id');

            $table->unsignedBigInteger('id_destinasi');

            // ✅ Relasi ke users (UUID)
            $table->foreign('user_id')
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

    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};