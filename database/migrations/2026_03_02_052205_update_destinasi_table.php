<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinasi', function (Blueprint $table) {
            // Rename kolom weekday/weekend jika perlu
            if (Schema::hasColumn('destinasi', 'weekday')) {
                $table->string('weekday')->change(); // tetap string untuk jam buka
            }
            if (Schema::hasColumn('destinasi', 'weekend')) {
                $table->string('weekend')->change();
            }

            // Pastikan kolom harga tiket ada dan default 0
            if (!Schema::hasColumn('destinasi', 'harga_tiket_weekday')) {
                $table->integer('harga_tiket_weekday')->default(0);
            } else {
                $table->integer('harga_tiket_weekday')->default(0)->change();
            }

            if (!Schema::hasColumn('destinasi', 'harga_tiket_weekend')) {
                $table->integer('harga_tiket_weekend')->default(0);
            } else {
                $table->integer('harga_tiket_weekend')->default(0)->change();
            }

            // Tambahkan kolom alamat_lengkap kalau belum ada
            if (!Schema::hasColumn('destinasi', 'alamat_lengkap')) {
                $table->text('alamat_lengkap')->after('lokasi');
            }

            // Rename kolom foto kalau nama field form beda
            if (!Schema::hasColumn('destinasi', 'foto')) {
                $table->string('foto')->nullable()->after('harga_tiket_weekend');
            }
        });
    }

    public function down(): void
    {
        Schema::table('destinasi', function (Blueprint $table) {
            // rollback perubahan sesuai kebutuhan
        });
    }
};