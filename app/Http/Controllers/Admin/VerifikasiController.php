<?php

namespace App\Http\Controllers\Admin;

// Letak file: app/Http/Controllers/Admin/VerifikasiController.php

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function index()
    {
        $menunggu = Peminjaman::with(['user', 'alat'])->where('status', 'menunggu')->latest()->get();
        $disetujui = Peminjaman::with(['user', 'alat'])->whereIn('status', ['disetujui', 'dipinjam'])->latest()->take(10)->get();
        $ditolak = Peminjaman::with(['user', 'alat'])->where('status', 'ditolak')->latest()->take(10)->get();

        return view('admin.verifikasi', compact('menunggu', 'disetujui', 'ditolak'));
    }

    public function approve(Peminjaman $peminjaman)
    {
        $alat = $peminjaman->alat;

        if ($alat->stok < $peminjaman->jumlah) {
            return back()->withErrors(['stok' => 'Stok alat tidak mencukupi.']);
        }

        $alat->decrement('stok', $peminjaman->jumlah);
        if ($alat->fresh()->stok === 0) {
            $alat->update(['kondisi' => 'stok_habis']);
        }

        $peminjaman->update(['status' => 'dipinjam']);

        return back()->with('status', "Pengajuan {$peminjaman->kode_pengajuan} disetujui.");
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:255'],
        ]);

        $peminjaman->update([
            'status' => 'ditolak',
            'catatan_admin' => $validated['catatan_admin'] ?? 'Ditolak oleh petugas Sarpras',
        ]);

        return back()->with('status', "Pengajuan {$peminjaman->kode_pengajuan} ditolak.");
    }
}