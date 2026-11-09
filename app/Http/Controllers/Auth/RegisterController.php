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
            'tanggal_lahir' => ['nullable', 'date_format:Y-m-d'],
            'email'    => ['required', 'email:rfc', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'role.in'             => 'Pilih daftar sebagai Siswa atau Guru.',
            'nisn_nip.required'   => 'NIPD / NIP wajib diisi.',
            'nisn_nip.regex'      => 'NIPD / NIP hanya boleh berisi angka.',
            'nisn_nip.unique'     => 'NIPD / NIP ini sudah terdaftar. Silakan masuk.',
            'tanggal_lahir.date_format' => 'Format tanggal lahir belum benar.',
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

        // Pastikan pendaftar benar-benar orang yang punya nomor ini: bila data sekolah sudah punya
        // tanggal lahirnya, tanggal lahir yang diisi harus sama. Bila belum punya, akun dibuat
        // dengan status "menunggu" dan baru aktif setelah petugas GESIT menyetujuinya.
        $lahirInduk = $induk->tanggal_lahir ? substr((string) $induk->tanggal_lahir, 0, 10) : null;
        if ($lahirInduk) {
            if (empty($data['tanggal_lahir'])) {
                throw ValidationException::withMessages(['tanggal_lahir' => 'Tanggal lahir wajib diisi.']);
            }
            if ($data['tanggal_lahir'] !== $lahirInduk) {
                throw ValidationException::withMessages(['tanggal_lahir' => 'Tanggal lahir tidak cocok dengan data sekolah.']);
            }
        }
        $aktif = (bool) $lahirInduk;

        $user = User::create([
            'name'          => trim($induk->nama),
            'role'          => $data['role'],
            'nisn_nip'      => $data['nisn_nip'],
            'kelas_jabatan' => $kelasJabatan,
            'email'         => mb_strtolower($data['email']),
            'password'      => $data['password'], // di-hash otomatis oleh cast "hashed" pada model User
            'status_akun'   => $aktif ? 'aktif' : 'menunggu',
        ]);

        if (! $aktif) {
            return redirect()->route('login')
                ->with('status', 'Pendaftaran terkirim. Akunmu bisa dipakai setelah disetujui petugas GESIT.');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('siswa.beranda')
            ->with('status', 'Akun berhasil dibuat. Selamat datang di GESIT, ' . $user->name . '!');
    }
}