<?php

namespace App\Http\Controllers;

// Letak file: app/Http/Controllers/ProfilController.php
// Dipakai bersama oleh admin, guru, dan siswa (semua yang sudah login).

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfilController extends Controller
{
    public function show()
    {
        return view('profil', ['user' => Auth::user()]);
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // maks 2 MB
        ], [
            'foto.required' => 'Pilih foto terlebih dahulu.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user = Auth::user();

        // Hapus foto lama agar penyimpanan tidak menumpuk
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        $path = $request->file('foto')->store('foto-profil', 'public');
        $user->forceFill(['foto' => $path])->save();

        return back()->with('status', 'Foto profil berhasil diperbarui.');
    }

    public function hapusFoto()
    {
        $user = Auth::user();

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
            $user->forceFill(['foto' => null])->save();
        }

        return back()->with('status', 'Foto profil dihapus.');
    }

    // Ganti kata sandi saat sudah login. Berlaku untuk admin, guru, dan siswa.
    // Admin tidak bisa memakai "Lupa sandi" publik, jadi di sinilah admin mengganti
    // kata sandi awal yang ditentukan pihak sekolah/pengembang.
    public function updateSandi(Request $request)
    {
        $request->validateWithBag('sandi', [
            'password_lama' => ['required', 'current_password'],
            'password'      => ['required', 'string', 'min:8', 'confirmed', 'different:password_lama'],
        ], [
            'password_lama.required'         => 'Kata sandi saat ini wajib diisi.',
            'password_lama.current_password' => 'Kata sandi saat ini salah.',
            'password.required'              => 'Kata sandi baru wajib diisi.',
            'password.min'                   => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed'             => 'Konfirmasi kata sandi baru tidak sama.',
            'password.different'             => 'Kata sandi baru harus berbeda dari yang lama.',
        ]);

        $request->user()->forceFill([
            'password' => $request->input('password'), // di-hash otomatis oleh cast "hashed" pada model User
            'remember_token' => Str::random(60),
        ])->save();

        return back()->with('status', 'Kata sandi berhasil diganti.');
    }
}