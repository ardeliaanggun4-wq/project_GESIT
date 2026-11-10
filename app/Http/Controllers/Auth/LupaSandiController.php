<?php

namespace App\Http\Controllers\Auth;

// Letak file: app/Http/Controllers/Auth/LupaSandiController.php (FILE BARU)

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'tanggal_lahir' => ['required', 'date_format:Y-m-d'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nisn_nip.required' => 'NIS / NIP wajib diisi.',
            'email.required' => 'Email akun wajib diisi.',
            'email.email' => 'Format email belum benar.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date_format' => 'Format tanggal lahir belum benar.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ]);

        // Hanya akun siswa/guru. Akun admin tidak bisa diatur ulang dari halaman publik.
        $user = User::where('nisn_nip', $data['nisn_nip'])
            ->whereRaw('lower(email) = ?', [mb_strtolower($data['email'])])
            ->whereIn('role', ['siswa', 'guru'])
            ->first();

        // Tanggal lahir harus cocok dengan data sekolah. Bila data sekolah belum punya tanggal lahir
        // orang ini, kata sandi tidak bisa diganti sendiri: minta petugas GESIT mengatur ulang.
        $induk = null;
        if ($user) {
            $induk = $user->role === 'siswa'
                ? DB::table('data_siswa')->where('nipd', $user->nisn_nip)->first()
                : DB::table('data_guru')->where('nip', $user->nisn_nip)->first();
        }
        $lahirInduk = $induk && $induk->tanggal_lahir ? substr((string) $induk->tanggal_lahir, 0, 10) : null;

        if (! $user || $user->menunggu() || ! $lahirInduk || $lahirInduk !== $data['tanggal_lahir']) {
            return back()->withErrors([
                'nisn_nip' => 'Data tidak cocok, atau akun ini belum bisa diganti kata sandinya sendiri. Silakan hubungi petugas GESIT.',
            ])->onlyInput('nisn_nip', 'email', 'tanggal_lahir');
        }

        $user->forceFill([
            'password' => $data['password'], // di-hash otomatis oleh cast "hashed" pada model User
            'remember_token' => Str::random(60),
        ])->save();

        return redirect()->route('login')
            ->with('status', 'Kata sandi berhasil diganti. Silakan masuk dengan kata sandi baru.');
    }
}