<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Letak file: database/migrations/2026_11_04_000003_isi_data_alat.php
// Mengisi data alat sesuai daftar terbaru, supaya cukup dengan `php artisan migrate`.
return new class extends Migration
{
    // PRAGMA foreign_keys (SQLite) tidak bisa diubah di dalam transaksi
    public $withinTransaction = false;

    public function up(): void
    {
        $this->bukaBatasanKategori();

        $lokasi = 'Gudang Lab RPL 4';
        $daftar = [
            ['ALT-LP', 'Laptop HP',        'komputer',    3],
            ['ALT-FD', 'Flashdisk',        'komputer',    10],
            ['ALT-MS', 'Mouse',            'komputer',    20],
            ['ALT-KY', 'Keyboard',         'komputer',    10],
            ['ALT-PR', 'Proyektor',        'multimedia',  3],
            ['ALT-MN', 'Monitor',          'komputer',    3],
            ['ALT-OB', 'Obeng Mini (set)', 'elektronika', 1],
            ['ALT-AU', 'Arduino Uno',      'elektronika', 30],
            ['ALT-SL', 'Solder',           'elektronika', 1],
            ['ALT-BT', 'Bor Tembok',       'perkakas',    1],
            ['ALT-BM', 'Bor Mini Listrik', 'perkakas',    1],
            ['ALT-SP', 'Spiker',           'multimedia',  1],
            ['ALT-MC', 'Mic + Sound',      'multimedia',  1],
        ];

        foreach ($daftar as [$kode, $nama, $kategori, $stok]) {
            $data = [
                'nama' => $nama,
                'kategori' => $kategori,
                'lokasi' => $lokasi,
                'stok' => $stok,
                'kondisi' => 'baik',
                'updated_at' => now(),
            ];

            if (DB::table('alat')->where('kode', $kode)->exists()) {
                DB::table('alat')->where('kode', $kode)->update($data);
            } else {
                DB::table('alat')->insert($data + ['kode' => $kode, 'created_at' => now()]);
            }
        }

        // Alat yang tidak ada di daftar (mis. CPU lama) dihapus
        DB::table('alat')->whereNotIn('kode', array_column($daftar, 0))->delete();
    }

    public function down(): void
    {
        // Tidak ada yang perlu dikembalikan
    }

    // Kolom kategori lama dibatasi enum (di SQLite berupa CHECK) yang menolak nilai baru.
    private function bukaBatasanKategori(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('alat', fn ($table) => $table->string('kategori')->change());

            return;
        }

        $tabel = DB::selectOne("select sql from sqlite_master where type = 'table' and name = 'alat'");
        if (! $tabel || ! preg_match('/"kategori"[^,]*check/i', $tabel->sql)) {
            return; // sudah tanpa batasan
        }

        $indeks = collect(DB::select("select sql from sqlite_master where type = 'index' and tbl_name = 'alat' and sql is not null"))->pluck('sql');

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