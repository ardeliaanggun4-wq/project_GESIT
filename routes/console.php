<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

// Letak file: routes/console.php (timpa file lama)

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Ganti kata sandi akun lewat terminal, termasuk akun admin dan akun demo.
// Contoh: php artisan gesit:atur-sandi 0011234567        (dibuatkan kata sandi acak)
//         php artisan gesit:atur-sandi 0011234567 SandiBaru2026
Artisan::command('gesit:atur-sandi {nomor : NIS/NIP/nomor akun} {sandi? : Kata sandi baru, kosongkan agar dibuatkan acak}', function (string $nomor, ?string $sandi = null) {
    $user = User::where('nisn_nip', preg_replace('/\D+/', '', $nomor))->first();
    if (! $user) {
        $this->error('Akun dengan nomor itu tidak ditemukan.');

        return 1;
    }

    if ($sandi === null) {
        $huruf = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $sandi = '';
        for ($i = 0; $i < 12; $i++) {
            $sandi .= $huruf[random_int(0, strlen($huruf) - 1)];
        }
    }

    if (strlen($sandi) < 8) {
        $this->error('Kata sandi minimal 8 karakter.');

        return 1;
    }

    $user->forceFill(['password' => $sandi, 'remember_token' => Str::random(60)])->save();
    $this->info("Kata sandi {$user->name} ({$user->role}) sudah diganti: {$sandi}");

    return 0;
})->purpose('Ganti kata sandi akun (termasuk admin dan akun demo)');