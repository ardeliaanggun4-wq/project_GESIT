<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Letak file: database/migrations/2026_11_04_000002_ganti_kategori_alat.php
// Kategori alat diganti: komputer, elektronika, perkakas, multimedia.
return new class extends Migration
{
    // PRAGMA foreign_keys (SQLite) tidak bisa diubah di dalam transaksi
    public $withinTransaction = false;

    public function up(): void
    {
        $this->bukaBatasanKategori();

        // Nilai lama -> nilai baru
        DB::table('alat')->where('kategori', 'praktik_lab')->update(['kategori' => 'komputer']);
        DB::table('alat')->where('kategori', 'olahraga')->update(['kategori' => 'elektronika']);
        DB::table('alat')->where('kategori', 'kesenian')->update(['kategori' => 'perkakas']);

        // Kelompokkan alat yang sudah ada menurut kodenya
        $peta = [
            'komputer' => ['ALT-LP', 'ALT-FD', 'ALT-MS', 'ALT-KY', 'ALT-MN'],
            'elektronika' => ['ALT-OB', 'ALT-AU', 'ALT-SL'],
            'perkakas' => ['ALT-BT', 'ALT-BM'],
            'multimedia' => ['ALT-PR', 'ALT-SP', 'ALT-MC'],
        ];
        foreach ($peta as $kategori => $kode) {
            DB::table('alat')->whereIn('kode', $kode)->update(['kategori' => $kategori]);
        }
    }

    public function down(): void
    {
        // Tidak ada yang perlu dikembalikan
    }

    // Kolom kategori lama dibatasi enum (di SQLite berupa CHECK) yang menolak nilai baru.
    private function bukaBatasanKategori(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('alat', function (Blueprint $table) {
                $table->string('kategori')->change();
            });

            return;
        }

        $tabel = DB::selectOne("select sql from sqlite_master where type = 'table' and name = 'alat'");
        if (! $tabel || ! preg_match('/"kategori"[^,]*check/i', $tabel->sql)) {
            return; // sudah tanpa batasan
        }

        $indeks = collect(DB::select("select sql from sqlite_master where type = 'index' and tbl_name = 'alat' and sql is not null"))->pluck('sql');

        // Buat ulang tabel tanpa CHECK pada kolom kategori (cara yang dianjurkan SQLite)
        $sqlBaru = preg_replace('/\s*check\s*\(\s*"kategori"\s+in\s*\([^)]*\)\s*\)/i', '', $tabel->sql);
        $sqlBaru = preg_replace('/^create table "alat"/i', 'create table "alat_baru"', $sqlBaru);

        DB::statement('PRAGMA foreign_keys = OFF');
        DB::beginTransaction();
        try {
            DB::statement($sqlBaru);
            DB::statement('insert into "alat_baru" select * from "alat"');
            DB::statement('drop table "alat"');
            DB::statement('alter table "alat_baru" rename to "alat"');
            foreach ($indeks as $sql) {
                DB::statement($sql);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        } finally {
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }
};