<?php

namespace App\Http\Controllers\Siswa;

// Letak file: app/Http/Controllers/Siswa/PinjamController.php

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinjamController extends Controller
{
    public function create()
    {
        $daftarAlat = Alat::orderBy('nama')->get();

        return view('siswa.ajukan', compact('daftarAlat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alat_id' => ['required', 'exists:alat,id'],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_kembali_rencana' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keperluan' => ['nullable', 'string', 'max:500'],
        ]);

        Peminjaman::create([
            'kode_pengajuan' => Peminjaman::generateKode(),
            'user_id' => Auth::id(),
            'alat_id' => $validated['alat_id'],
            'jumlah' => $validated['jumlah'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
            'keperluan' => $validated['keperluan'] ?? null,
            'status' => 'menunggu',
        ]);

        return redirect()->route('siswa.beranda')->with('status', 'Pengajuan peminjaman berhasil dikirim, menunggu verifikasi petugas Sarpras.');
    }
}