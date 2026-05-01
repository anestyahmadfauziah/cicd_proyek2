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
        Schema::create('pembayaran', function (Blueprint $table) {
    $table->id('id_pembayaran');
    $table->string('metode_bayar');
    $table->date('tanggal_bayar');
    $table->string('status_pembayaran');
    $table->integer('total_bayar');

    $table->unsignedBigInteger('id_pemesanan');
    $table->foreign('id_pemesanan')
          ->references('id_pemesanan')
          ->on('pemesanan')
          ->onDelete('cascade');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
