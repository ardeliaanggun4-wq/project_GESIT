<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Letak file: database/migrations/2026_11_04_000001_sederhanakan_kondisi_alat.php
// Kondisi alat disederhanakan menjadi "baik" dan "stok_habis".
return new class extends Migration
{
    public function up(): void
    {
        // Data lama: perlu_cek -> baik, rusak -> stok_habis
        DB::table('alat')->where('kondisi', 'perlu_cek')->update(['kondisi' => 'baik']);
        DB::table('alat')->where('kondisi', 'rusak')->update(['kondisi' => 'stok_habis']);

        // Stok habis selalu 0, dan stok 0 selalu berarti habis
        DB::table('alat')->where('kondisi', 'stok_habis')->update(['stok' => 0]);
        DB::table('alat')->where('stok', 0)->update(['kondisi' => 'stok_habis']);
    }

    public function down(): void
    {
        // Tidak ada yang perlu dikembalikan
    }
};