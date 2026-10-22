<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Letak file: database/migrations/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan')->unique(); // contoh: PJM-0231
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('alat_id')->constrained('alat')->cascadeOnDelete();
            $table->unsignedInteger('jumlah')->default(1);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->text('keperluan')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dipinjam', 'selesai'])->default('menunggu');
            $table->string('catatan_admin')->nullable(); // contoh alasan tolak: "stok kosong"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};