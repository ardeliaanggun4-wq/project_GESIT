<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Letak file: database/migrations/
// Revisi guru: batas lama pinjam tergantung rawannya alat rusak bila dipinjam lama
// (contoh: proyektor maksimal 1 hari). Nilainya bisa diubah admin di menu Data Alat.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->unsignedTinyInteger('maks_hari_pinjam')->default(4)->after('stok');
        });

        // kode alat => batas hari (selisih tanggal pinjam ke rencana kembali)
        $batas = [
            'ALT-PR' => 1, // proyektor (dari guru)
            'ALT-BT' => 1, // bor tembok
            'ALT-LP' => 2, // laptop
            'ALT-MN' => 2, // monitor
            'ALT-SL' => 2, // solder
            'ALT-BM' => 2, // bor mini
            'ALT-SP' => 2, // spiker
            'ALT-MC' => 2, // mic + sound
            'ALT-AU' => 3, // arduino uno
            'ALT-FD' => 4, // flashdisk
            'ALT-MS' => 4, // mouse
            'ALT-KY' => 4, // keyboard
            'ALT-OB' => 4, // obeng mini
        ];
        foreach ($batas as $kode => $hari) {
            DB::table('alat')->where('kode', $kode)->update(['maks_hari_pinjam' => $hari]);
        }
    }

    public function down(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->dropColumn('maks_hari_pinjam');
        });
    }
};
