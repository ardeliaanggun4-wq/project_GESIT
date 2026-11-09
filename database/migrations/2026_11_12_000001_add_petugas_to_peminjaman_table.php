<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Letak file: database/migrations/2026_11_12_000001_add_petugas_to_peminjaman_table.php
// Mencatat petugas (admin) yang memproses pengajuan (setujui/tolak) dan yang menerima alat kembali.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->foreignId('diproses_oleh')->nullable()->after('catatan_admin')->constrained('users')->nullOnDelete();
            $table->timestamp('diproses_at')->nullable()->after('diproses_oleh');
            $table->foreignId('diterima_oleh')->nullable()->after('diproses_at')->constrained('users')->nullOnDelete();
            $table->timestamp('diterima_at')->nullable()->after('diterima_oleh');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropConstrainedForeignId('diproses_oleh');
            $table->dropConstrainedForeignId('diterima_oleh');
            $table->dropColumn(['diproses_at', 'diterima_at']);
        });
    }
};