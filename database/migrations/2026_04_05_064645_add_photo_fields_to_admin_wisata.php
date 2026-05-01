<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_wisata', function (Blueprint $table) {
            if (!Schema::hasColumn('admin_wisata', 'first_name')) {
                $table->string('first_name')->nullable();
            }
            if (!Schema::hasColumn('admin_wisata', 'last_name')) {
                $table->string('last_name')->nullable();
            }
            if (!Schema::hasColumn('admin_wisata', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('admin_wisata', 'bio')) {
                $table->text('bio')->nullable();
            }
            if (!Schema::hasColumn('admin_wisata', 'location')) {
                $table->string('location')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('admin_wisata', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'phone', 'bio', 'location']);
        });
    }
};