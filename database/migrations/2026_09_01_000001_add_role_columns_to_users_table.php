<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Letak file: database/migrations/
// Menambahkan kolom yang dibutuhkan GESIT ke tabel users bawaan Laravel.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nisn_nip')->unique()->after('id');
            $table->enum('role', ['siswa', 'guru', 'admin'])->default('siswa')->after('nisn_nip');
            $table->string('kelas_jabatan')->nullable()->after('role'); // contoh: "XII RPL B" / "Guru Olahraga"
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nisn_nip', 'role', 'kelas_jabatan']);
        });
    }
};