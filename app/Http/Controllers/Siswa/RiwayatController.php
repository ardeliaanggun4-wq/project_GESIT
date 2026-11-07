<?php

namespace App\Http\Controllers\Siswa;

// Letak file: app/Http/Controllers/Siswa/RiwayatController.php

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $filter = request('status');
        $filterValid = ['menunggu', 'dipinjam', 'ditolak', 'selesai'];

        $query = $user->peminjaman()->with('alat')->latest();
        if (in_array($filter, $filterValid, true)) {
            // "Dipinjam" mencakup status "disetujui" supaya tidak ada yang terlewat
            $query->whereIn('status', $filter === 'dipinjam' ? ['dipinjam', 'disetujui'] : [$filter]);
        } else {
            $filter = null;
        }

        $daftar = $query->take(50)->get();

        // Pengajuan yang berubah status sejak terakhir Riwayat dibuka, ditandai "Baru"
        $baruIds = $user->peminjaman()
            ->where('status', '!=', 'menunggu')
            ->when(
                $user->notif_dilihat_at,
                fn ($q, $t) => $q->where('updated_at', '>', $t),
                fn ($q) => $q->where('updated_at', '>=', now()->subDays(7))
            )
            ->pluck('id')
            ->all();

        $jumlah = [
            'semua' => $user->peminjaman()->count(),
            'menunggu' => $user->peminjaman()->where('status', 'menunggu')->count(),
            'dipinjam' => $user->peminjaman()->whereIn('status', ['dipinjam', 'disetujui'])->count(),
            'ditolak' => $user->peminjaman()->where('status', 'ditolak')->count(),
            'selesai' => $user->peminjaman()->where('status', 'selesai')->count(),
        ];

        $user->forceFill(['notif_dilihat_at' => now()])->save();

        return view('siswa.riwayat', compact('daftar', 'filter', 'baruIds', 'jumlah'));
    }

    // Batalkan pengajuan milik sendiri selama masih "menunggu"
    public function batal(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->user_id === Auth::id(), 403);

        if ($peminjaman->status !== 'menunggu') {
            return back()->withErrors(['batal' => 'Pengajuan yang sudah diproses tidak bisa dibatalkan. Hubungi petugas GESIT.']);
        }

        $kode = $peminjaman->kode_pengajuan;
        $peminjaman->delete();

        return redirect()->route('siswa.riwayat')->with('status', "Pengajuan {$kode} dibatalkan.");
    }

    // Ajukan pengembalian alat yang sedang dipinjam. Stok baru bertambah setelah petugas GESIT mengonfirmasi alat diterima.
    public function kembalikan(Request $request, Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->user_id === Auth::id(), 403);

        if (! in_array($peminjaman->status, ['dipinjam', 'disetujui'], true)) {
            return back()->withErrors(['kembali' => 'Hanya alat yang sedang dipinjam yang bisa diajukan pengembaliannya.']);
        }

        if ($peminjaman->pengembalian_diajukan_at) {
            return back()->withErrors(['kembali' => 'Pengembalian untuk peminjaman ini sudah diajukan. Serahkan alat ke ruang Sarpras.']);
        }

        $validated = $request->validate([
            'catatan_pengembalian' => ['nullable', 'string', 'max:255'],
        ]);

        $peminjaman->update([
            'pengembalian_diajukan_at' => now(),
            'catatan_pengembalian' => $validated['catatan_pengembalian'] ?? null,
        ]);

        return back()->with('status', "Pengembalian {$peminjaman->kode_pengajuan} diajukan. Serahkan alat ke ruang Sarpras untuk dicek petugas.");
    }
}