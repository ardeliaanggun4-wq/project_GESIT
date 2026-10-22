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
            'total_alat' => Alat::sum('stok') > 0 ? Alat::count() : Alat::count(),
            'menunggu' => Peminjaman::where('status', 'menunggu')->count(),
            'sedang_dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'rusak_kosong' => Alat::whereIn('kondisi', ['rusak', 'stok_habis'])->count(),
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

        return view('admin.ringkasan', compact('stats', 'grafik', 'kategori', 'maxKategori', 'aktivitas'));
    }
}