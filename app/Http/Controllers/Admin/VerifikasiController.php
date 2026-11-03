<?php

namespace App\Http\Controllers\Admin;

// Letak file: app/Http/Controllers/Admin/VerifikasiController.php

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    // Tandai alat sudah dikembalikan: stok bertambah lagi dan kondisi alat diperbarui
    public function kembali(Request $request, Peminjaman $peminjaman)
    {
        if (! in_array($peminjaman->status, ['dipinjam', 'disetujui'], true)) {
            return back()->withErrors(['kembali' => 'Hanya peminjaman yang sedang berjalan yang bisa ditandai dikembalikan.']);
        }

        $validated = $request->validate([
            'kondisi_kembali' => ['required', 'in:baik,perlu_cek,rusak'],
        ]);
        $kondisi = $validated['kondisi_kembali'];

        DB::transaction(function () use ($peminjaman, $kondisi) {
            $alat = $peminjaman->alat;

            if ($kondisi === 'rusak') {
                // Unit yang rusak tidak dikembalikan ke stok, alat ditandai rusak agar ditindaklanjuti
                $alat->update(['kondisi' => 'rusak']);
            } else {
                $alat->increment('stok', $peminjaman->jumlah);
                if ($kondisi === 'perlu_cek') {
                    $alat->update(['kondisi' => 'perlu_cek']);
                } elseif ($alat->kondisi === 'stok_habis') {
                    $alat->update(['kondisi' => 'baik']);
                }
            }

            $peminjaman->update([
                'status' => 'selesai',
                'tanggal_kembali_aktual' => now()->toDateString(),
                'catatan_admin' => match ($kondisi) {
                    'perlu_cek' => 'Dikembalikan, alat perlu dicek.',
                    'rusak' => 'Dikembalikan dalam kondisi rusak.',
                    default => $peminjaman->catatan_admin,
                },
            ]);
        });

        return back()->with('status', "Pengajuan {$peminjaman->kode_pengajuan} ditandai sudah dikembalikan.");
    }
}