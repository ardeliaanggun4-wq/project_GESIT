<?php

namespace App\Http\Controllers\Auth;

// Letak file: app/Http/Controllers/Auth/RegisterController.php (timpa file yang lama)

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.daftar');
    }

    public function store(Request $request)
    {
        // Pendaftaran publik hanya untuk siswa dan guru. Akun admin dibuat oleh sekolah (seeder).
        // Siswa mendaftar dengan NIPD, guru dengan NIP. Nomor harus ada di data induk sekolah.
        $request->merge([
            'nisn_nip' => preg_replace('/\D+/', '', (string) $request->input('nisn_nip')),
        ]);

        $data = $request->validate([
            'role'     => ['required', Rule::in(['siswa', 'guru'])],
            'nisn_nip' => ['required', 'regex:/^[0-9]{5,20}$/', 'unique:users,nisn_nip'],
            'email'    => ['required', 'email:rfc', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'role.in'             => 'Pilih daftar sebagai Siswa atau Guru.',
            'nisn_nip.required'   => 'NIPD / NIP wajib diisi.',
            'nisn_nip.regex'      => 'NIPD / NIP hanya boleh berisi angka.',
            'nisn_nip.unique'     => 'NIPD / NIP ini sudah terdaftar. Silakan masuk.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email belum benar.',
            'email.unique'        => 'Email ini sudah dipakai akun lain.',
            'password.required'   => 'Kata sandi wajib diisi.',
            'password.min'        => 'Kata sandi minimal 8 karakter.',
            'password.confirmed'  => 'Konfirmasi kata sandi tidak sama.',
        ]);

        // Cocokkan dengan data induk sesuai peran yang dipilih
        if ($data['role'] === 'siswa') {
            $induk = DB::table('data_siswa')->where('nipd', $data['nisn_nip'])->first();
            $kelasJabatan = $induk->kelas ?? null;
            $pesan = 'NIPD tidak ditemukan di data siswa. Periksa kembali, atau hubungi petugas GESIT.';
        } else {
            $induk = DB::table('data_guru')->where('nip', $data['nisn_nip'])->first();
            $kelasJabatan = $induk ? ($induk->mata_pelajaran ? 'Guru ' . $induk->mata_pelajaran : 'Guru') : null;
            $pesan = 'NIP tidak ditemukan di data guru. Periksa kembali, atau hubungi petugas GESIT.';
        }

        if (! $induk) {
            throw ValidationException::withMessages(['nisn_nip' => $pesan]);
        }

        $user = User::create([
            'name'          => trim($induk->nama),
            'role'          => $data['role'],
            'nisn_nip'      => $data['nisn_nip'],
            'kelas_jabatan' => $kelasJabatan,
            'email'         => mb_strtolower($data['email']),
            'password'      => $data['password'], // di-hash otomatis oleh cast "hashed" pada model User
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('siswa.beranda')
            ->with('status', 'Akun berhasil dibuat. Selamat datang di GESIT, ' . $user->name . '!');
    }
}