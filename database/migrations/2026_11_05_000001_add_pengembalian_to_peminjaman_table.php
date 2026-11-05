<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Letak file: database/migrations/2026_11_05_000001_add_pengembalian_to_peminjaman_table.php
// Siswa mengajukan pengembalian lebih dulu, admin yang mengonfirmasi saat alat benar-benar diterima.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->timestamp('pengembalian_diajukan_at')->nullable()->after('tanggal_kembali_aktual');
            $table->string('catatan_pengembalian')->nullable()->after('pengembalian_diajukan_at');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['pengembalian_diajukan_at', 'catatan_pengembalian']);
        });
    }
};