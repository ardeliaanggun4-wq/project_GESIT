<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Letak file: database/migrations/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alat', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // contoh: ALT-OSC-012
            $table->string('nama');
            $table->enum('kategori', ['praktik_lab', 'olahraga', 'kesenian', 'multimedia']);
            $table->string('lokasi')->nullable(); // contoh: "Lab RPL", "Gudang Olahraga"
            $table->unsignedInteger('stok')->default(0);
            $table->enum('kondisi', ['baik', 'perlu_cek', 'rusak', 'stok_habis'])->default('baik');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};