<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Letak file: database/migrations/2026_11_08_000002_isi_deskripsi_alat.php
// Mengisi deskripsi semua alat. Foto ikut terpasang otomatis bila file-nya sudah ada di
// storage/app/public/foto-alat/ dengan nama sesuai kolom ke-3 (contoh: mouse.jpg, bor-tembok.png).
// Jalankan SETELAH migration 2026_11_08_000001_add_foto_deskripsi_to_alat_table.
return new class extends Migration
{
    public function up(): void
    {
        $daftar = [
            // kode, nama file foto (tanpa ekstensi), deskripsi
            ['ALT-LP', 'laptop-hp', 'Komputer jinjing (laptop) merek HP untuk praktik pemrograman, desain, dan presentasi. Mudah dibawa ke kelas atau lab.'],
            ['ALT-FD', 'flashdisk', 'Penyimpan data portabel berkonektor USB untuk memindahkan file tugas dan praktik antar komputer.'],
            ['ALT-MS', 'mouse', 'Alat penunjuk untuk menggerakkan kursor, memilih menu, dan mengklik di komputer atau laptop.'],
            ['ALT-KY', 'keyboard', 'Papan ketik untuk mengetik teks dan menulis kode program saat praktik di komputer.'],
            ['ALT-PR', 'proyektor', 'Alat yang memproyeksikan tampilan layar komputer atau laptop ke dinding atau layar besar, dipakai untuk presentasi dan pembelajaran.'],
            ['ALT-MN', 'monitor', 'Layar komputer untuk menampilkan hasil keluaran dari CPU atau laptop.'],
            ['ALT-OB', 'obeng-mini', 'Satu set obeng berukuran kecil untuk membuka dan memasang sekrup pada laptop, komputer, dan perangkat elektronik.'],
            ['ALT-AU', 'arduino-uno', 'Papan mikrokontroler untuk belajar elektronika dan pemrograman perangkat, misalnya mengendalikan lampu LED, sensor, dan motor.'],
            ['ALT-SL', 'solder', 'Alat pemanas untuk melelehkan timah agar komponen elektronika tersambung ke papan rangkaian. Ujungnya sangat panas, gunakan dengan hati-hati.'],
            ['ALT-BT', 'bor-tembok', 'Bor listrik bertenaga besar dengan mata bor khusus untuk melubangi tembok atau beton, misalnya saat memasang kabel atau instalasi. Gunakan dengan pengawasan petugas.'],
            ['ALT-BM', 'bor-mini', 'Bor listrik berukuran kecil untuk melubangi papan rangkaian (PCB), kayu tipis, atau plastik pada pekerjaan yang butuh ketelitian.'],
            ['ALT-SP', 'spiker', 'Pengeras suara untuk memperdengarkan audio dari komputer atau perangkat lain saat pembelajaran dan acara.'],
            ['ALT-MC', 'mic-sound', 'Mikrofon beserta perangkat pengeras suaranya untuk kegiatan yang butuh suara lantang, seperti presentasi, acara, atau rekaman.'],
        ];

        foreach ($daftar as [$kode, $berkas, $deskripsi]) {
            $alat = DB::table('alat')->where('kode', $kode)->first();
            if (! $alat) {
                continue;
            }

            $data = ['deskripsi' => $deskripsi, 'updated_at' => now()];

            // Foto yang sudah terpasang dan filenya ada tidak ditimpa; selain itu cari file bernama sesuai alat
            $fotoAda = $alat->foto && Storage::disk('public')->exists($alat->foto);
            if (! $fotoAda) {
                $data['foto'] = null;
                foreach (['jpg', 'jpeg', 'png', 'webp'] as $ekstensi) {
                    if (Storage::disk('public')->exists("foto-alat/{$berkas}.{$ekstensi}")) {
                        $data['foto'] = "foto-alat/{$berkas}.{$ekstensi}";
                        break;
                    }
                }
            }

            DB::table('alat')->where('kode', $kode)->update($data);
        }
    }

    public function down(): void
    {
        DB::table('alat')->update(['deskripsi' => null]);
    }
};