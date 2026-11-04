<?php

// Letak file: routes/web.php (timpa file bawaan)

use App\Http\Controllers\Admin\DataAlatController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\RingkasanController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LupaSandiController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Siswa\BerandaController;
use App\Http\Controllers\Siswa\PinjamController;
use App\Http\Controllers\Siswa\RiwayatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Siswa\PanduanController;
use App\Http\Controllers\ProfilController;

Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil');
    Route::post('/profil/foto', [ProfilController::class, 'updateFoto'])->name('profil.foto');
    Route::delete('/profil/foto', [ProfilController::class, 'hapusFoto'])->name('profil.foto.hapus');
});

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.ringkasan')
            : redirect()->route('siswa.beranda');
    }
    return view('landing');
})->name('beranda');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    // Daftar akun baru (siswa / guru)
    Route::get('/daftar', [RegisterController::class, 'create'])->name('daftar');
    Route::post('/daftar', [RegisterController::class, 'store'])->middleware('throttle:5,1')->name('daftar.simpan');

    // Lupa kata sandi: ganti kata sandi dengan NISN/NIP + email akun
    Route::get('/lupa-sandi', [LupaSandiController::class, 'create'])->name('sandi.lupa');
    Route::post('/lupa-sandi', [LupaSandiController::class, 'store'])->middleware('throttle:5,1')->name('sandi.simpan');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// MENU SISWA / GURU
Route::middleware(['auth', 'role:siswa,guru'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
    Route::get('/ajukan', [PinjamController::class, 'create'])->name('ajukan');
    Route::post('/ajukan', [PinjamController::class, 'store'])->name('ajukan.store');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');
    Route::delete('/riwayat/{peminjaman}', [RiwayatController::class, 'batal'])->name('batal');
    Route::get('/panduan', [PanduanController::class, 'index'])->name('panduan');
});

// MENU ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/ringkasan', [RingkasanController::class, 'index'])->name('ringkasan');

    Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi');
    Route::post('/verifikasi/{peminjaman}/setujui', [VerifikasiController::class, 'approve'])->name('verifikasi.approve');
    Route::post('/verifikasi/{peminjaman}/tolak', [VerifikasiController::class, 'reject'])->name('verifikasi.reject');
    Route::post('/verifikasi/{peminjaman}/kembali', [VerifikasiController::class, 'kembali'])->name('verifikasi.kembali');

    Route::get('/data-alat', [DataAlatController::class, 'index'])->name('data-alat');
    Route::post('/data-alat', [DataAlatController::class, 'store'])->name('data-alat.store');
    Route::put('/data-alat/{alat}', [DataAlatController::class, 'update'])->name('data-alat.update');
    Route::delete('/data-alat/{alat}', [DataAlatController::class, 'destroy'])->name('data-alat.destroy');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/unduh', [LaporanController::class, 'unduh'])->name('laporan.unduh');
});