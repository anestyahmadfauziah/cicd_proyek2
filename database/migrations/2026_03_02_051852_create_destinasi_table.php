<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinasi', function (Blueprint $table) {
            $table->id('id_destinasi');
            $table->string('nama');
            $table->text('deskripsi');
            $table->string('lokasi');
            $table->text('alamat_lengkap');
            $table->string('weekday'); // jam buka weekday
            $table->string('weekend'); // jam buka weekend
            $table->integer('harga_tiket_weekday')->default(0);
            $table->integer('harga_tiket_weekend')->default(0);
            $table->string('foto')->nullable();
            $table->unsignedBigInteger('id_kategori');
            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('kategori')
                  ->onDelete('cascade');
            $table->timestamps();
            $table->uuid('created_by_id');
        $table->string('created_by_role')->nullable();

        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('destinasi');
    }
};