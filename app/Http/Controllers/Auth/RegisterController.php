<?php

namespace App\Http\Controllers\Auth;

// Letak file: app/Http/Controllers/Auth/RegisterController.php (FILE BARU)

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.daftar');
    }

    public function store(Request $request)
    {
        // Pendaftaran publik hanya untuk siswa dan guru. Akun admin dibuat oleh sekolah (seeder).
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'role'          => ['required', Rule::in(['siswa', 'guru'])],
            'nisn_nip'      => ['required', 'regex:/^[0-9]{5,20}$/', 'unique:users,nisn_nip'],
            'kelas_jabatan' => ['required', 'string', 'max:60'],
            'email'         => ['required', 'email:rfc', 'max:150', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'          => 'Nama lengkap wajib diisi.',
            'role.in'                => 'Pilih daftar sebagai Siswa atau Guru.',
            'nisn_nip.required'      => 'NISN / NIP wajib diisi.',
            'nisn_nip.regex'         => 'NISN / NIP hanya boleh berisi angka (5-20 digit).',
            'nisn_nip.unique'        => 'NISN / NIP ini sudah terdaftar. Silakan masuk.',
            'kelas_jabatan.required' => 'Kelas atau jabatan wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email belum benar.',
            'email.unique'           => 'Email ini sudah dipakai akun lain.',
            'password.required'      => 'Kata sandi wajib diisi.',
            'password.min'           => 'Kata sandi minimal 8 karakter.',
            'password.confirmed'     => 'Konfirmasi kata sandi tidak sama.',
        ]);

        $user = User::create([
            'name'          => trim($data['name']),
            'role'          => $data['role'],
            'nisn_nip'      => $data['nisn_nip'],
            'kelas_jabatan' => trim($data['kelas_jabatan']),
            'email'         => mb_strtolower($data['email']),
            'password'      => $data['password'], // di-hash otomatis oleh cast "hashed" pada model User
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('siswa.beranda')
            ->with('status', 'Akun berhasil dibuat. Selamat datang di GESIT!');
    }
}