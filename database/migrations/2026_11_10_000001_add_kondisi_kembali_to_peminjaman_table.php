<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Letak file: database/migrations/2026_11_10_000001_add_kondisi_kembali_to_peminjaman_table.php
// Saat alat dikembalikan, petugas mencatat kondisinya (baik / rusak / hilang) dan berapa unit yang bermasalah.
// Unit rusak atau hilang tidak dikembalikan ke stok.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->string('kondisi_kembali', 10)->nullable()->after('catatan_pengembalian');
            $table->unsignedInteger('unit_bermasalah')->default(0)->after('kondisi_kembali');
            $table->string('catatan_kembali')->nullable()->after('unit_bermasalah');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['kondisi_kembali', 'unit_bermasalah', 'catatan_kembali']);
        });
    }
};