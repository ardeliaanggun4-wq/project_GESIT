<?php

namespace App\Http\Controllers\Auth;

// Letak file: app/Http/Controllers/Auth/LupaSandiController.php (FILE BARU)

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LupaSandiController extends Controller
{
    public function create()
    {
        return view('auth.lupa-sandi');
    }

    public function store(Request $request)
    {
        $request->merge(['nisn_nip' => preg_replace('/\D+/', '', (string) $request->input('nisn_nip'))]);

        $data = $request->validate([
            'nisn_nip' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nisn_nip.required' => 'NIPD / NIP wajib diisi.',
            'email.required' => 'Email akun wajib diisi.',
            'email.email' => 'Format email belum benar.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ]);

        // Hanya akun siswa/guru. Akun admin tidak bisa diatur ulang dari halaman publik.
        $user = User::where('nisn_nip', $data['nisn_nip'])
            ->whereRaw('lower(email) = ?', [mb_strtolower($data['email'])])
            ->whereIn('role', ['siswa', 'guru'])
            ->first();

        if (! $user) {
            return back()->withErrors([
                'nisn_nip' => 'NIPD / NIP dan email tidak cocok dengan akun siswa atau guru.',
            ])->onlyInput('nisn_nip', 'email');
        }

        $user->forceFill([
            'password' => $data['password'], // di-hash otomatis oleh cast "hashed" pada model User
            'remember_token' => Str::random(60),
        ])->save();

        return redirect()->route('login')
            ->with('status', 'Kata sandi berhasil diganti. Silakan masuk dengan kata sandi baru.');
    }
}