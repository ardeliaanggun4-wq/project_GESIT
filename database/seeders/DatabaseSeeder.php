<?php

namespace Database\Seeders;

// Letak file: database/seeders/DatabaseSeeder.php (timpa file bawaan)

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AlatSeeder::class,
        ]);

        $ardelia = User::where('nisn_nip', '0091234567')->first();
        $suryanto = User::where('nisn_nip', '0021234567')->first();

        $osiloskop = Alat::where('kode', 'ALT-OSC-012')->first();
        $proyektor = Alat::where('kode', 'ALT-PRJ-004')->first();
        $bolaVoli = Alat::where('kode', 'ALT-BOL-021')->first();
        $bolaBasket = Alat::where('kode', 'ALT-BSK-014')->first();

        Peminjaman::create([
            'kode_pengajuan' => 'PJM-0227',
            'user_id' => $ardelia->id,
            'alat_id' => $bolaVoli->id,
            'jumlah' => 1,
            'tanggal_pinjam' => now()->subDays(19),
            'tanggal_kembali_rencana' => now()->subDays(15),
            'keperluan' => 'Ekstrakurikuler',
            'status' => 'ditolak',
            'catatan_admin' => 'stok kosong',
        ]);

        Peminjaman::create([
            'kode_pengajuan' => 'PJM-0229',
            'user_id' => $ardelia->id,
            'alat_id' => $osiloskop->id,
            'jumlah' => 1,
            'tanggal_pinjam' => now()->subDays(14),
            'tanggal_kembali_rencana' => now()->subDays(10),
            'keperluan' => 'Praktik mata pelajaran Pemrograman Web',
            'status' => 'dipinjam',
        ]);

        Peminjaman::create([
            'kode_pengajuan' => 'PJM-0230',
            'user_id' => $suryanto->id,
            'alat_id' => $bolaBasket->id,
            'jumlah' => 4,
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => now()->addDays(3),
            'keperluan' => 'Latihan tim basket sekolah',
            'status' => 'menunggu',
        ]);

        Peminjaman::create([
            'kode_pengajuan' => 'PJM-0231',
            'user_id' => $ardelia->id,
            'alat_id' => $proyektor->id,
            'jumlah' => 1,
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => now()->addDays(4),
            'keperluan' => 'Untuk kelas RPL',
            'status' => 'menunggu',
        ]);
    }
}