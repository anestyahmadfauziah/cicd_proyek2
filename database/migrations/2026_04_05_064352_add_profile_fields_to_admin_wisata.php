<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToAdminWisata extends Migration
{
    public function up(): void
    {
        Schema::table('admin_wisata', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->string('location')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('admin_wisata', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'phone', 'bio', 'location']);
        });
    }
}