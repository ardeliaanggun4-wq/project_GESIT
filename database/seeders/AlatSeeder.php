<?php

namespace Database\Seeders;

// Letak file: database/seeders/AlatSeeder.php

use App\Models\Alat;
use Illuminate\Database\Seeder;

class AlatSeeder extends Seeder
{
    public function run(): void
    {
        // Data sementara: hanya alat praktik lab.
        $daftar = [
            ['kode' => 'ALT-MS', 'nama' => 'Mouse',    'kategori' => 'praktik_lab', 'lokasi' => 'Lab RPL', 'stok' => 17, 'kondisi' => 'baik'],
            ['kode' => 'ALT-KY', 'nama' => 'Keyboard', 'kategori' => 'praktik_lab', 'lokasi' => 'Lab RPL', 'stok' => 7,  'kondisi' => 'baik'],
            ['kode' => 'ALT-CP', 'nama' => 'CPU',      'kategori' => 'praktik_lab', 'lokasi' => 'Lab RPL', 'stok' => 5,  'kondisi' => 'baik'],
        ];

        foreach ($daftar as $item) {
            Alat::create($item);
        }
    }
}