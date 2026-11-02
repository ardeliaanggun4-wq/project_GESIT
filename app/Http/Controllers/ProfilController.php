<?php

namespace App\Http\Controllers;

// Letak file: app/Http/Controllers/ProfilController.php
// Dipakai bersama oleh admin, guru, dan siswa (semua yang sudah login).

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
}