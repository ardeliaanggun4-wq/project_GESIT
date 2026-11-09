<?php

namespace Database\Seeders;

// Letak file: database/seeders/UserSeeder.php (timpa file lama)
// Kata sandi tidak lagi "password". Isi ADMIN_PASSWORD dan DEMO_PASSWORD di file .env bila ingin
// menentukan sendiri; bila kosong, kata sandi dibuat acak dan ditampilkan sekali di layar saat seeding.

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $sandiAdmin = env('ADMIN_PASSWORD') ?: $this->acak();
        $sandiDemo  = env('DEMO_PASSWORD') ?: $this->acak();

        User::create([
            'name' => 'Petugas GESIT',
            'email' => 'admin@gesit.sch.id',
            'nisn_nip' => '0011234567',
            'password' => $sandiAdmin, // di-hash otomatis oleh cast "hashed" pada model User
            'role' => 'admin',
            'kelas_jabatan' => 'Admin',
        ]);

        User::create([
            'name' => 'Bpk. Suryanto',
            'email' => 'suryanto@gesit.sch.id',
            'nisn_nip' => '0021234567',
            'password' => $sandiDemo,
            'role' => 'guru',
            'kelas_jabatan' => 'Guru Olahraga',
        ]);

        User::create([
            'name' => 'Ardelia Anggun Saputri',
            'email' => 'ardelia@gesit.sch.id',
            'nisn_nip' => '0091234567',
            'password' => $sandiDemo,
            'role' => 'siswa',
            'kelas_jabatan' => 'XII RPL B',
        ]);

        if (! env('ADMIN_PASSWORD')) {
            $this->command?->warn("Kata sandi admin (0011234567): {$sandiAdmin}  <- catat sekarang");
        }
        if (! env('DEMO_PASSWORD')) {
            $this->command?->warn("Kata sandi akun demo guru/siswa (0021234567, 0091234567): {$sandiDemo}  <- catat sekarang");
        }
    }

    // 12 karakter acak tanpa karakter yang mirip (l, 1, o, 0, i)
    private function acak(): string
    {
        $huruf = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $hasil = '';
        for ($i = 0; $i < 12; $i++) {
            $hasil .= $huruf[random_int(0, strlen($huruf) - 1)];
        }

        return $hasil;
    }
}