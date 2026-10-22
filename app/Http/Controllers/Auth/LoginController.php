<?php

namespace App\Http\Controllers\Auth;

// Letak file: app/Http/Controllers/Auth/LoginController.php (timpa file yang lama)

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'nisn_nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $roleHint = $request->input('role_hint', 'siswa'); // dari tab yang diklik di halaman login

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'nisn_nip' => 'NISN / NIP atau kata sandi salah.',
            ])->onlyInput('nisn_nip');
        }

        $user = Auth::user();

        // Validasi ramah: kalau user login di tab yang salah, kasih tahu (tetap berhasil login, cuma diberi info)
        $cocok = $roleHint === 'admin' ? $user->role === 'admin' : in_array($user->role, ['siswa', 'guru']);

        if (! $cocok) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'nisn_nip' => $roleHint === 'admin'
                    ? 'Akun ini bukan akun Admin/Petugas. Silakan pilih tab "Siswa / Guru".'
                    : 'Akun ini adalah akun Admin/Petugas. Silakan pilih tab "Admin / Petugas".',
            ])->onlyInput('nisn_nip');
        }

        $request->session()->regenerate();

        return $user->isAdmin()
            ? redirect()->intended(route('admin.ringkasan'))
            : redirect()->intended(route('siswa.beranda'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}