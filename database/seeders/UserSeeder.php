<?php

namespace Database\Seeders;

// Letak file: database/seeders/UserSeeder.php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Petugas Sarpras',
            'email' => 'admin@gesit.sch.id',
            'nisn_nip' => '0011234567',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'kelas_jabatan' => 'Admin',
        ]);

        User::create([
            'name' => 'Bpk. Suryanto',
            'email' => 'suryanto@gesit.sch.id',
            'nisn_nip' => '0021234567',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'kelas_jabatan' => 'Guru Olahraga',
        ]);

        User::create([
            'name' => 'Ardelia Anggun Saputri',
            'email' => 'ardelia@gesit.sch.id',
            'nisn_nip' => '0091234567',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'kelas_jabatan' => 'XII RPL B',
        ]);
    }
}