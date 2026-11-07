<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Letak file: database/migrations/2026_11_08_000001_add_foto_deskripsi_to_alat_table.php
// Revisi guru: alat punya foto (path di disk "public") dan deskripsi.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('lokasi');
            $table->text('deskripsi')->nullable()->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->dropColumn(['foto', 'deskripsi']);
        });
    }
};