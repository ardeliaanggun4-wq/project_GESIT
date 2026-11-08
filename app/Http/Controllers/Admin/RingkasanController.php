<?php

namespace App\Http\Controllers\Admin;

// Letak file: app/Http/Controllers/Admin/RingkasanController.php

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Support\Carbon;

class RingkasanController extends Controller
{
    public function index()
    {
        $stats = [
            'total_alat' => Alat::count(),
            'menunggu' => Peminjaman::where('status', 'menunggu')->count(),
            'sedang_dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'stok_habis' => Alat::where('kondisi', 'stok_habis')->count(),
        ];

        // Grafik peminjaman 7 hari terakhir
        $grafik = collect(range(6, 0))->map(function ($daysAgo) {
            $tanggal = Carbon::today()->subDays($daysAgo);
            return [
                'label' => $tanggal->translatedFormat('D'),
                'total' => Peminjaman::whereDate('created_at', $tanggal->toDateString())->count(),
            ];
        });

        // Kategori paling sering dipinjam
        $kategori = Peminjaman::query()
            ->join('alat', 'alat.id', '=', 'peminjaman.alat_id')
            ->selectRaw('alat.kategori, count(*) as total')
            ->groupBy('alat.kategori')
            ->orderByDesc('total')
            ->get();

        $maxKategori = max(1, optional($kategori->first())->total ?? 1);

        $aktivitas = Peminjaman::with(['user', 'alat'])->latest()->take(5)->get();

        // Komposisi kondisi alat (untuk bar proporsi)
        $kondisi = Alat::selectRaw('kondisi, count(*) as total')->groupBy('kondisi')->pluck('total', 'kondisi');

        // Alat yang perlu perhatian petugas
        $butuhPerhatian = Alat::where('kondisi', 'stok_habis');
        $totalPerhatian = (clone $butuhPerhatian)->count();
        $perhatian = $butuhPerhatian
            ->orderBy('nama')
            ->take(5)
            ->get();

        // Peminjaman yang melewati tanggal rencana kembali dan belum dikembalikan (terlama di atas)
        $terlambatQuery = Peminjaman::with(['user', 'alat'])
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->whereDate('tanggal_kembali_rencana', '<', Carbon::today());
        $totalTerlambat = (clone $terlambatQuery)->count();
        $terlambat = $terlambatQuery->orderBy('tanggal_kembali_rencana')->take(5)->get();

        return view('admin.ringkasan', compact(
            'stats', 'grafik', 'kategori', 'maxKategori', 'aktivitas', 'kondisi', 'perhatian', 'totalPerhatian',
            'terlambat', 'totalTerlambat'
        ));
    }
}