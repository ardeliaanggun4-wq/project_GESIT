<?php

namespace App\Http\Controllers\Admin;

// Letak file: app/Http/Controllers/Admin/VerifikasiController.php

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiController extends Controller
{
    public function index(Request $request)
    {
        // Filter status: default "menunggu" supaya pengajuan yang perlu diproses selalu tampil paling atas
        $filter = in_array($request->query('status'), ['menunggu', 'disetujui', 'ditolak'], true)
            ? $request->query('status')
            : 'menunggu';

        $statusFilter = [
            'menunggu' => ['menunggu'],
            'disetujui' => ['disetujui', 'dipinjam'],
            'ditolak' => ['ditolak'],
        ];

        $jumlah = [];
        foreach ($statusFilter as $kunci => $daftarStatus) {
            $jumlah[$kunci] = Peminjaman::whereIn('status', $daftarStatus)->count();
        }

        // Peminjaman aktif yang siswanya sudah mengajukan pengembalian
        $jumlah['minta_kembali'] = Peminjaman::whereIn('status', ['dipinjam', 'disetujui'])
            ->whereNotNull('pengembalian_diajukan_at')
            ->count();

        $daftar = Peminjaman::with(['user', 'alat'])
            ->whereIn('status', $statusFilter[$filter])
            ->orderByRaw('pengembalian_diajukan_at IS NULL')
            ->latest()
            ->take(50)
            ->get();

        return view('admin.verifikasi', compact('filter', 'jumlah', 'daftar'));
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
            'catatan_admin' => $validated['catatan_admin'] ?? 'Ditolak oleh petugas GESIT',
        ]);

        return back()->with('status', "Pengajuan {$peminjaman->kode_pengajuan} ditolak.");
    }

    // Tandai alat sudah dikembalikan: stok bertambah lagi dan kondisi alat kembali "baik" bila sebelumnya habis
    public function kembali(Request $request, Peminjaman $peminjaman)
    {
        if (! in_array($peminjaman->status, ['dipinjam', 'disetujui'], true)) {
            return back()->withErrors(['kembali' => 'Hanya peminjaman yang sedang berjalan yang bisa ditandai dikembalikan.']);
        }

        DB::transaction(function () use ($peminjaman) {
            $alat = $peminjaman->alat;

            $alat->increment('stok', $peminjaman->jumlah);
            if ($alat->kondisi === 'stok_habis') {
                $alat->update(['kondisi' => 'baik']);
            }

            $peminjaman->update([
                'status' => 'selesai',
                'tanggal_kembali_aktual' => now()->toDateString(),
            ]);
        });

        return back()->with('status', "Pengajuan {$peminjaman->kode_pengajuan} ditandai sudah dikembalikan.");
    }
}