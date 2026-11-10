<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Letak file: database/migrations/
// Data induk sekolah: daftar NIS siswa dan NIP guru yang boleh mendaftar akun GESIT.
// Sumber: database/data/data_siswa.csv dan database/data/data_guru.csv
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nipd', 20)->unique();
            $table->string('nama');
            $table->string('kelas', 60)->nullable();
        });

        Schema::create('data_guru', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 20)->unique(); // 18 digit tanpa spasi
            $table->string('nama');
            $table->string('mata_pelajaran', 100)->nullable();
        });

        $this->impor(database_path('data/data_siswa.csv'), 'data_siswa', ['nipd', 'nama', 'kelas']);
        $this->impor(database_path('data/data_guru.csv'), 'data_guru', ['nip', 'nama', 'mata_pelajaran']);
    }

    public function down(): void
    {
        Schema::dropIfExists('data_guru');
        Schema::dropIfExists('data_siswa');
    }

    private function impor(string $berkas, string $tabel, array $kolom): void
    {
        if (! is_file($berkas) || ($h = fopen($berkas, 'r')) === false) {
            return;
        }

        fgetcsv($h); // lewati baris judul
        $batch = [];
        while (($r = fgetcsv($h)) !== false) {
            if (! isset($r[0]) || trim($r[0]) === '') {
                continue;
            }
            $batch[] = array_combine($kolom, [trim($r[0]), trim($r[1] ?? ''), trim($r[2] ?? '') ?: null]);
            if (count($batch) === 100) {
                DB::table($tabel)->insert($batch);
                $batch = [];
            }
        }
        if ($batch) {
            DB::table($tabel)->insert($batch);
        }
        fclose($h);
    }
};