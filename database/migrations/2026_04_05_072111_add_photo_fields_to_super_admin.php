<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('super_admin', function (Blueprint $table) {
            if (!Schema::hasColumn('super_admin', 'first_name')) {
                $table->string('super_admin')->nullable();
            }
            if (!Schema::hasColumn('super_admin', 'last_name')) {
                $table->string('last_name')->nullable();
            }
            if (!Schema::hasColumn('super_admin', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('super_admin', 'bio')) {
                $table->text('bio')->nullable();
            }
            if (!Schema::hasColumn('super_admin', 'location')) {
                $table->string('location')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('super_admin', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'phone', 'bio', 'location']);
        });
    }
};