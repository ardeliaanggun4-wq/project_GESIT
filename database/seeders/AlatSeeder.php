<?php

namespace Database\Seeders;

// Letak file: database/seeders/AlatSeeder.php

use App\Models\Alat;
use Illuminate\Database\Seeder;

class AlatSeeder extends Seeder
{
    public function run(): void
    {
        $lab = 'Gudang Lab RPL 4';

        $daftar = [
            ['kode' => 'ALT-LP', 'nama' => 'Laptop HP',         'kategori' => 'komputer',    'stok' => 3],
            ['kode' => 'ALT-FD', 'nama' => 'Flashdisk',         'kategori' => 'komputer',    'stok' => 10],
            ['kode' => 'ALT-MS', 'nama' => 'Mouse',             'kategori' => 'komputer',    'stok' => 20],
            ['kode' => 'ALT-KY', 'nama' => 'Keyboard',          'kategori' => 'komputer',    'stok' => 10],
            ['kode' => 'ALT-PR', 'nama' => 'Proyektor',         'kategori' => 'multimedia',  'stok' => 3],
            ['kode' => 'ALT-MN', 'nama' => 'Monitor',           'kategori' => 'komputer',    'stok' => 3],
            ['kode' => 'ALT-OB', 'nama' => 'Obeng Mini (set)',  'kategori' => 'elektronika', 'stok' => 1],
            ['kode' => 'ALT-AU', 'nama' => 'Arduino Uno',       'kategori' => 'elektronika', 'stok' => 30],
            ['kode' => 'ALT-SL', 'nama' => 'Solder',            'kategori' => 'elektronika', 'stok' => 1],
            ['kode' => 'ALT-BT', 'nama' => 'Bor Tembok',        'kategori' => 'perkakas',    'stok' => 1],
            ['kode' => 'ALT-BM', 'nama' => 'Bor Mini Listrik',  'kategori' => 'perkakas',    'stok' => 1],
            ['kode' => 'ALT-SP', 'nama' => 'Spiker',            'kategori' => 'multimedia',  'stok' => 1],
            ['kode' => 'ALT-MC', 'nama' => 'Mic + Sound',       'kategori' => 'multimedia',  'stok' => 1],
        ];

        // Bisa dijalankan berulang: data dengan kode yang sama diperbarui, bukan digandakan
        foreach ($daftar as $item) {
            Alat::updateOrCreate(
                ['kode' => $item['kode']],
                $item + ['lokasi' => $lab, 'kondisi' => 'baik'],
            );
        }

        // Alat yang tidak ada di daftar ini dihapus (peminjaman terkaitnya ikut terhapus)
        Alat::whereNotIn('kode', array_column($daftar, 'kode'))->delete();
    }
}