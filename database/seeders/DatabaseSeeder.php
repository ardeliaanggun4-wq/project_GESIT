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

        $mouse = Alat::where('kode', 'ALT-MS')->first();
        $keyboard = Alat::where('kode', 'ALT-KY')->first();
        $laptop = Alat::where('kode', 'ALT-LP')->first();

        Peminjaman::create([
            'kode_pengajuan' => 'PJM-0227',
            'user_id' => $ardelia->id,
            'alat_id' => $mouse->id,
            'jumlah' => 25,
            'tanggal_pinjam' => now()->subDays(19),
            'tanggal_kembali_rencana' => now()->subDays(15),
            'keperluan' => 'Praktik kelas XII RPL',
            'status' => 'ditolak',
            'catatan_admin' => 'jumlah melebihi stok',
        ]);

        Peminjaman::create([
            'kode_pengajuan' => 'PJM-0229',
            'user_id' => $ardelia->id,
            'alat_id' => $laptop->id,
            'jumlah' => 1,
            'tanggal_pinjam' => now()->subDays(14),
            'tanggal_kembali_rencana' => now()->subDays(10),
            'keperluan' => 'Praktik mata pelajaran Pemrograman Web',
            'status' => 'dipinjam',
        ]);
        $laptop->decrement('stok', 1); // 1 laptop sedang dipinjam

        Peminjaman::create([
            'kode_pengajuan' => 'PJM-0230',
            'user_id' => $suryanto->id,
            'alat_id' => $keyboard->id,
            'jumlah' => 2,
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => now()->addDays(3),
            'keperluan' => 'Praktik komputer kelas X',
            'status' => 'menunggu',
        ]);

        Peminjaman::create([
            'kode_pengajuan' => 'PJM-0231',
            'user_id' => $ardelia->id,
            'alat_id' => $mouse->id,
            'jumlah' => 3,
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => now()->addDays(4),
            'keperluan' => 'Untuk kelas RPL',
            'status' => 'menunggu',
        ]);
    }
}