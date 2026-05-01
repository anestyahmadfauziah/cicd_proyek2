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
      Schema::create('wishlist', function (Blueprint $table) {
    $table->id('id_wishlist');

    // ✅ UBAH KE UUID
    $table->uuid('id_user');

    // destinasi tetap bigint (karena id_destinasi = bigint)
    $table->unsignedBigInteger('id_destinasi');

    // ✅ RELASI USERS (UUID)
    $table->foreign('id_user')
          ->references('id')
          ->on('users')
          ->onDelete('cascade');

    // ✅ RELASI DESTINASI (BIGINT)
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
        Schema::dropIfExists('wishlist');
    }
};
