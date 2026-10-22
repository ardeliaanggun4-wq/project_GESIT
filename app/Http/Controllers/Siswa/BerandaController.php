<?php

namespace App\Http\Controllers\Siswa;

// Letak file: app/Http/Controllers/Siswa/BerandaController.php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $riwayat = $user->peminjaman()->with('alat')->latest()->take(10)->get();

        $stats = [
            'sedang_dipinjam' => $user->peminjaman()->where('status', 'dipinjam')->count(),
            'menunggu' => $user->peminjaman()->where('status', 'menunggu')->count(),
            'jatuh_tempo_hari_ini' => $user->peminjaman()
                ->where('status', 'dipinjam')
                ->whereDate('tanggal_kembali_rencana', now()->toDateString())
                ->count(),
        ];

        return view('siswa.beranda', compact('riwayat', 'stats', 'user'));
    }
}