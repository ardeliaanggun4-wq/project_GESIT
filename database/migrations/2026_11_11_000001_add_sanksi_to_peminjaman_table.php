<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Letak file: database/migrations/2026_11_11_000001_add_sanksi_to_peminjaman_table.php
// Mencatat ketentuan yang dikenakan saat alat dikembalikan: hari terlambat, ringkasan sanksi,
// dan kapan kewajiban memperbaiki/mengganti alat (rusak/hilang) diselesaikan.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->unsignedSmallInteger('hari_terlambat')->default(0)->after('catatan_kembali');
            $table->string('sanksi')->nullable()->after('hari_terlambat');
            $table->timestamp('kewajiban_selesai_at')->nullable()->after('sanksi');
        });

        // Data lama: hitung keterlambatan yang sudah terjadi, dan anggap kewajiban lama sudah beres
        // supaya peminjam lama tidak mendadak terblokir oleh aturan baru.
        foreach (DB::table('peminjaman')->where('status', 'selesai')->get() as $r) {
            $telat = 0;
            if ($r->tanggal_kembali_aktual && $r->tanggal_kembali_rencana) {
                $telat = max(0, (int) Carbon::parse($r->tanggal_kembali_rencana)->diffInDays(Carbon::parse($r->tanggal_kembali_aktual), false));
            }
            DB::table('peminjaman')->where('id', $r->id)->update([
                'hari_terlambat' => $telat,
                'kewajiban_selesai_at' => in_array($r->kondisi_kembali, ['rusak', 'hilang'], true) ? now() : null,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['hari_terlambat', 'sanksi', 'kewajiban_selesai_at']);
        });
    }
};