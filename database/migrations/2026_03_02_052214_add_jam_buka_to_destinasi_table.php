<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('destinasi', function (Blueprint $table) {

        if (!Schema::hasColumn('destinasi', 'weekday')) {
            $table->string('weekday')->nullable();
        }

        if (!Schema::hasColumn('destinasi', 'weekend')) {
            $table->string('weekend')->nullable();
        }

    });
}

    public function down(): void
    {
        Schema::table('destinasi', function (Blueprint $table) {
            $table->dropColumn('weekday');
            $table->dropColumn('weekend');
        });
    }
};