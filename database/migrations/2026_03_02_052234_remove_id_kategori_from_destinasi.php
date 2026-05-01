<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('destinasi', 'id_kategori')) {
            Schema::table('destinasi', function (Blueprint $table) {
                $table->dropColumn('id_kategori');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('destinasi', 'id_kategori')) {
            Schema::table('destinasi', function (Blueprint $table) {
                $table->unsignedBigInteger('id_kategori')->nullable();
            });
        }
    }
};