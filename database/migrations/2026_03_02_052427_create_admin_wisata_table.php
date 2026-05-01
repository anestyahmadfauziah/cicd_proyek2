<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_wisata', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'superadmin']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_wisata');
    }
};