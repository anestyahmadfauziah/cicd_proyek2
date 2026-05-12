<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinasi_media', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_destinasi')->unsigned();
            $table->enum('type', ['image', 'video']);
            $table->text('url');
            $table->text('thumbnail')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('id_destinasi')
                  ->references('id_destinasi')
                  ->on('destinasi')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinasi_media');
    }
};