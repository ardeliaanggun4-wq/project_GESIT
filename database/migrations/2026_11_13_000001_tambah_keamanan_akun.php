<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Letak file: database/migrations/2026_11_13_000001_tambah_keamanan_akun.php (FILE BARU)
// - tanggal_lahir di data induk: dipakai untuk memastikan pendaftar dan pelupa sandi adalah orang yang benar
// - status_akun di users: "aktif" atau "menunggu" (menunggu persetujuan petugas)
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_siswa', function (Blueprint $table) {
            $table->date('tanggal_lahir')->nullable();
        });

        Schema::table('data_guru', function (Blueprint $table) {
            $table->date('tanggal_lahir')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('status_akun', 10)->default('aktif'); // akun yang sudah ada tetap aktif
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status_akun');
        });

        Schema::table('data_guru', function (Blueprint $table) {
            $table->dropColumn('tanggal_lahir');
        });

        Schema::table('data_siswa', function (Blueprint $table) {
            $table->dropColumn('tanggal_lahir');
        });
    }
};