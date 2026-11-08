<?php

namespace App\Models;

// Letak file: app/Models/Peminjaman.php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    // Aturan peminjaman
    public const MAKS_HARI_PINJAM = 4;  // lama pinjam maksimal (selisih tanggal pinjam ke rencana kembali)
    public const MAKS_HARI_TELAT = 2;   // toleransi terlambat maksimal dari tanggal rencana kembali

    protected $fillable = [
        'kode_pengajuan', 'user_id', 'alat_id', 'jumlah',
        'tanggal_pinjam', 'tanggal_kembali_rencana', 'tanggal_kembali_aktual',
        'keperluan', 'status', 'catatan_admin',
        'pengembalian_diajukan_at', 'catatan_pengembalian',
        'kondisi_kembali', 'unit_bermasalah', 'catatan_kembali',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_kembali_rencana' => 'date',
            'tanggal_kembali_aktual' => 'date',
            'pengembalian_diajukan_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }

    // Teks kondisi alat saat dikembalikan (null bila belum dikembalikan)
    public function kondisiKembaliLabel(): ?string
    {
        return match ($this->kondisi_kembali) {
            'baik' => 'Kembali dalam kondisi baik',
            'rusak' => $this->unit_bermasalah . ' unit rusak',
            'hilang' => $this->unit_bermasalah . ' unit hilang',
            default => null,
        };
    }

    // Jumlah hari terlambat (0 bila belum jatuh tempo atau alat sudah kembali)
    public function hariTerlambat(): int
    {
        if (! in_array($this->status, ['dipinjam', 'disetujui'], true) || ! $this->tanggal_kembali_rencana) {
            return 0;
        }

        return $this->tanggal_kembali_rencana->lt(today())
            ? (int) abs($this->tanggal_kembali_rencana->diffInDays(today()))
            : 0;
    }

    public function melewatiBatasTelat(): bool
    {
        return $this->hariTerlambat() > self::MAKS_HARI_TELAT;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'dipinjam' => 'Dipinjam',
            'selesai' => 'Selesai',
            default => ucfirst($this->status),
        };
    }

    // Warna badge status dipakai di Blade lewat class Tailwind, lihat resources/views/components jika dibuat.
    public function statusColor(): string
    {
        return match ($this->status) {
            'menunggu' => 'yellow',
            'disetujui', 'dipinjam' => 'green',
            'ditolak' => 'red',
            'selesai' => 'gray',
            default => 'gray',
        };
    }

    public static function generateKode(): string
    {
        $last = static::orderByDesc('id')->first();
        $next = $last ? ((int) substr($last->kode_pengajuan, -4)) + 1 : 227;
        return 'PJM-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}