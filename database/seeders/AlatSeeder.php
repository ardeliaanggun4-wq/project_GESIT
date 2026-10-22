<?php

namespace Database\Seeders;

// Letak file: database/seeders/AlatSeeder.php

use App\Models\Alat;
use Illuminate\Database\Seeder;

class AlatSeeder extends Seeder
{
    public function run(): void
    {
        $daftar = [
            ['kode' => 'ALT-OSC-012', 'nama' => 'Osiloskop Digital', 'kategori' => 'praktik_lab', 'lokasi' => 'Lab RPL', 'stok' => 5, 'kondisi' => 'baik'],
            ['kode' => 'ALT-FLS-012', 'nama' => 'FlashDisk', 'kategori' => 'praktik_lab', 'lokasi' => 'Lab RPL', 'stok' => 8, 'kondisi' => 'baik'],
            ['kode' => 'ALT-MS-030', 'nama' => 'Mouse', 'kategori' => 'praktik_lab', 'lokasi' => 'Lab RPL', 'stok' => 18, 'kondisi' => 'baik'],
            ['kode' => 'ALT-PRJ-004', 'nama' => 'Proyektor Multimedia', 'kategori' => 'multimedia', 'lokasi' => 'Ruang AV', 'stok' => 3, 'kondisi' => 'baik'],
            ['kode' => 'ALT-BOL-021', 'nama' => 'Bola Voli (set)', 'kategori' => 'olahraga', 'lokasi' => 'Gudang Olahraga', 'stok' => 0, 'kondisi' => 'stok_habis'],
            ['kode' => 'ALT-BSK-014', 'nama' => 'Bola Basket', 'kategori' => 'olahraga', 'lokasi' => 'Gudang Olahraga', 'stok' => 4, 'kondisi' => 'perlu_cek'],
        ];

        foreach ($daftar as $item) {
            Alat::create($item);
        }

        // Sisanya diisi acak agar total mendekati 147 alat seperti pada mockup.
        $kategoriList = ['praktik_lab', 'olahraga', 'kesenian', 'multimedia'];
        for ($i = 1; $i <= 141; $i++) {
            $kategori = $kategoriList[array_rand($kategoriList)];
            Alat::create([
                'kode' => 'ALT-GEN-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'nama' => 'Alat Inventaris ' . $i,
                'kategori' => $kategori,
                'lokasi' => 'Gudang Utama',
                'stok' => rand(0, 20),
                'kondisi' => collect(['baik', 'baik', 'baik', 'perlu_cek', 'rusak'])->random(),
            ]);
        }
    }
}