<?php

namespace App\Http\Controllers\Admin;

// Letak file: app/Http/Controllers/Admin/LaporanController.php (FILE BARU)

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    private const STATUS = ['menunggu', 'disetujui', 'dipinjam', 'selesai', 'ditolak'];
    private const KATEGORI = ['komputer', 'elektronika', 'perkakas', 'multimedia'];
    // Status yang dihitung sebagai alat benar-benar keluar dari inventaris
    private const TERPAKAI = ['disetujui', 'dipinjam', 'selesai'];

    public function index(Request $request)
    {
        $f = $this->saringan($request);

        [$stats, $statusHitung, $populer, $maxPopuler] = $this->ringkasan($f);

        $daftar = $this->dasar($f)->with(['user', 'alat'])
            ->orderByDesc('peminjaman.tanggal_pinjam')->orderByDesc('peminjaman.id');

        // Mode cetak: semua baris dalam satu halaman, lalu dialog cetak terbuka otomatis
        $cetak = $request->boolean('cetak');
        $data = $cetak ? $daftar->get() : $daftar->paginate(10)->withQueryString();

        return view('admin.laporan', compact('f', 'stats', 'statusHitung', 'populer', 'maxPopuler', 'data', 'cetak'));
    }

    /** Unduh laporan ringkas dalam bentuk PDF (A4). */
    public function unduh(Request $request)
    {
        $f = $this->saringan($request);
        [$stats, $statusHitung, $populer] = $this->ringkasan($f);

        $baris = $this->dasar($f)->with(['user', 'alat'])
            ->orderByDesc('peminjaman.tanggal_pinjam')->orderByDesc('peminjaman.id')->get();

        $nama = 'laporan-peminjaman-' . $f['dari']->format('Ymd') . '-' . $f['sampai']->format('Ymd') . '.pdf';

        return Pdf::loadView('admin.laporan-pdf', [
            'f' => $f,
            'stats' => $stats,
            'statusHitung' => $statusHitung,
            'populer' => $populer,
            'baris' => $baris,
            'petugas' => $request->user()->name,
        ])->setPaper('a4', 'portrait')->download($nama);
    }

    /** Angka ringkas, komposisi status, dan alat terpopuler (dipakai halaman dan PDF). */
    private function ringkasan(array $f): array
    {
        $statusHitung = $this->dasar($f)
            ->selectRaw('peminjaman.status as status, count(*) as total')
            ->groupBy('peminjaman.status')
            ->pluck('total', 'status');

        $stats = [
            'total' => (int) $statusHitung->sum(),
            'unit' => (int) $this->dasar($f)->whereIn('peminjaman.status', self::TERPAKAI)->sum('peminjaman.jumlah'),
            'terlambat' => $this->dasar($f)->where('peminjaman.status', 'dipinjam')
                ->whereDate('peminjaman.tanggal_kembali_rencana', '<', Carbon::today())->count(),
            'ditolak' => (int) $statusHitung->get('ditolak', 0),
        ];

        $populer = $this->dasar($f)
            ->whereIn('peminjaman.status', self::TERPAKAI)
            ->join('alat', 'alat.id', '=', 'peminjaman.alat_id')
            ->selectRaw('alat.nama as nama, alat.kode as kode, alat.kategori as kategori, sum(peminjaman.jumlah) as unit, count(*) as kali')
            ->groupBy('alat.id', 'alat.nama', 'alat.kode', 'alat.kategori')
            ->orderByDesc('unit')
            ->take(5)
            ->get();
        $maxPopuler = max(1, (int) ($populer->first()->unit ?? 1));

        return [$stats, $statusHitung, $populer, $maxPopuler];
    }

    /** Baca dan validasi saringan dari query string. Bawaan: 30 hari terakhir. */
    private function saringan(Request $request): array
    {
        $v = $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date'],
            'status' => ['nullable', 'in:' . implode(',', self::STATUS)],
            'kategori' => ['nullable', 'in:' . implode(',', self::KATEGORI)],
            'cari' => ['nullable', 'string', 'max:100'],
        ]);

        $sampai = isset($v['sampai']) ? Carbon::parse($v['sampai'])->startOfDay() : Carbon::today();
        $dari = isset($v['dari']) ? Carbon::parse($v['dari'])->startOfDay() : $sampai->copy()->subDays(29);
        if ($dari->gt($sampai)) {
            [$dari, $sampai] = [$sampai, $dari];
        }

        return [
            'dari' => $dari,
            'sampai' => $sampai,
            'status' => $v['status'] ?? null,
            'kategori' => $v['kategori'] ?? null,
            'cari' => $v['cari'] ?? null,
        ];
    }

    private function dasar(array $f): Builder
    {
        $q = Peminjaman::query()->whereBetween('peminjaman.tanggal_pinjam', [
            $f['dari']->toDateString(), $f['sampai']->toDateString(),
        ]);

        if ($f['status']) {
            $q->where('peminjaman.status', $f['status']);
        }
        if ($f['kategori']) {
            $q->whereHas('alat', fn ($a) => $a->where('kategori', $f['kategori']));
        }
        if ($f['cari']) {
            $c = $f['cari'];
            $q->where(function ($w) use ($c) {
                $w->where('peminjaman.kode_pengajuan', 'like', "%{$c}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$c}%")->orWhere('nisn_nip', 'like', "%{$c}%"))
                    ->orWhereHas('alat', fn ($a) => $a->where('nama', 'like', "%{$c}%")->orWhere('kode', 'like', "%{$c}%"));
            });
        }

        return $q;
    }
}