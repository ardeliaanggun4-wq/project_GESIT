<?php

namespace App\Http\Controllers\Admin;

// Letak file: app/Http/Controllers/Admin/VerifikasiController.php

use App\Http\Controllers\Controller;
use App\Models\Alat;
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

        // Mode fokus: dibuka dari QR bukti pinjam atau dari daftar terlambat, hanya menampilkan satu pengajuan
        $kode = trim((string) $request->query('kode', ''));
        $fokus = $kode !== '' ? Peminjaman::where('kode_pengajuan', $kode)->first() : null;
        if ($fokus) {
            $filter = match ($fokus->status) {
                'menunggu' => 'menunggu',
                'ditolak' => 'ditolak',
                default => 'disetujui',
            };
        }

        $jumlah = [];
        foreach ($statusFilter as $kunci => $daftarStatus) {
            $jumlah[$kunci] = Peminjaman::whereIn('status', $daftarStatus)->count();
        }

        // Peminjaman aktif yang siswanya sudah mengajukan pengembalian
        $jumlah['minta_kembali'] = Peminjaman::whereIn('status', ['dipinjam', 'disetujui'])
            ->whereNotNull('pengembalian_diajukan_at')
            ->count();

        $daftar = Peminjaman::with(['user', 'alat'])
            ->when(
                $fokus,
                fn ($q) => $q->whereKey($fokus->id),
                fn ($q) => $q->whereIn('status', $statusFilter[$filter])
            )
            ->orderByRaw('pengembalian_diajukan_at IS NULL')
            ->latest()
            ->take(50)
            ->get();

        return view('admin.verifikasi', compact('filter', 'jumlah', 'daftar', 'fokus'));
    }

    // Tujuan QR bukti pinjam dan tautan di daftar terlambat: langsung membuka kartu satu pengajuan
    public function scan(Peminjaman $peminjaman)
    {
        return redirect()->route('admin.verifikasi', ['kode' => $peminjaman->kode_pengajuan]);
    }

    public function approve(Peminjaman $peminjaman)
    {
        // Transaksi + kunci baris: dua petugas yang menyetujui bersamaan (atau klik ganda)
        // tidak bisa mengurangi stok dua kali atau membuat stok minus.
        $hasil = DB::transaction(function () use ($peminjaman) {
            $p = Peminjaman::lockForUpdate()->findOrFail($peminjaman->id);

            if ($p->status !== 'menunggu') {
                return 'sudah';
            }

            $alat = Alat::lockForUpdate()->findOrFail($p->alat_id);

            if ($alat->stok < $p->jumlah) {
                return 'stok';
            }

            $alat->decrement('stok', $p->jumlah);
            $alat->refresh();
            if ($alat->stok === 0) {
                $alat->update(['kondisi' => 'stok_habis']);
            }

            $p->update(['status' => 'dipinjam']);

            return 'ok';
        });

        if ($hasil === 'sudah') {
            return back()->withErrors(['proses' => 'Pengajuan ini sudah diproses sebelumnya.']);
        }
        if ($hasil === 'stok') {
            return back()->withErrors(['stok' => 'Stok alat tidak mencukupi.']);
        }

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

    // Tandai alat sudah dikembalikan. Petugas mencatat kondisinya: unit yang baik kembali ke stok,
    // unit yang rusak atau hilang dicatat di peminjaman dan tidak menambah stok.
    public function kembali(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'kondisi_kembali' => ['required', 'in:baik,rusak,hilang'],
            'unit_bermasalah' => ['nullable', 'integer', 'min:1', 'max:' . $peminjaman->jumlah],
            'catatan_kembali' => ['nullable', 'string', 'max:255'],
        ]);

        $kondisi = $validated['kondisi_kembali'];
        $bermasalah = $kondisi === 'baik' ? 0 : (int) ($validated['unit_bermasalah'] ?? 0);

        if ($kondisi !== 'baik' && $bermasalah < 1) {
            return back()->withErrors(['kembali' => 'Isi jumlah unit yang rusak atau hilang (minimal 1).']);
        }

        $berhasil = DB::transaction(function () use ($peminjaman, $kondisi, $bermasalah, $validated) {
            $p = Peminjaman::lockForUpdate()->findOrFail($peminjaman->id);

            if (! in_array($p->status, ['dipinjam', 'disetujui'], true)) {
                return false;
            }

            $alat = Alat::lockForUpdate()->findOrFail($p->alat_id);

            $kembaliKeStok = $p->jumlah - $bermasalah;
            if ($kembaliKeStok > 0) {
                $alat->increment('stok', $kembaliKeStok);
                $alat->refresh();
            }

            // Kondisi alat kembali "baik" bila stok sudah ada lagi
            if ($alat->kondisi === 'stok_habis' && $alat->stok > 0) {
                $alat->update(['kondisi' => 'baik']);
            }

            $p->update([
                'status' => 'selesai',
                'tanggal_kembali_aktual' => now()->toDateString(),
                'kondisi_kembali' => $kondisi,
                'unit_bermasalah' => $bermasalah,
                'catatan_kembali' => $validated['catatan_kembali'] ?? null,
            ]);

            return true;
        });

        if (! $berhasil) {
            return back()->withErrors(['kembali' => 'Hanya peminjaman yang sedang berjalan yang bisa ditandai dikembalikan.']);
        }

        $pesan = "Pengajuan {$peminjaman->kode_pengajuan} ditandai sudah dikembalikan.";
        if ($bermasalah > 0) {
            $pesan .= " {$bermasalah} unit tercatat {$kondisi} dan tidak masuk stok.";
        }

        return back()->with('status', $pesan);
    }
}