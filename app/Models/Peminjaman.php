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
        'hari_terlambat', 'sanksi', 'kewajiban_selesai_at',
        'diproses_oleh', 'diproses_at', 'diterima_oleh', 'diterima_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_kembali_rencana' => 'date',
            'tanggal_kembali_aktual' => 'date',
            'pengembalian_diajukan_at' => 'datetime',
            'kewajiban_selesai_at' => 'datetime',
            'diproses_at' => 'datetime',
            'diterima_at' => 'datetime',
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

    // Petugas yang menyetujui/menolak pengajuan
    public function petugas()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    // Petugas yang menerima alat saat dikembalikan
    public function penerima()
    {
        return $this->belongsTo(User::class, 'diterima_oleh');
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

    // Ketentuan pengembalian. Ubah teksnya di sini bila aturan sekolah berubah;
    // Panduan, halaman Ajukan, dan bukti pinjam PDF ikut berubah.
    // Isi tiap baris: [ikon, judul, isi]
    public static function ketentuan(): array
    {
        $t = self::MAKS_HARI_TELAT;

        return [
            ['clock', 'Terlambat', "Terlambat 1 sampai {$t} hari: dicatat dan diberi peringatan. Terlambat lebih dari {$t} hari: belum bisa mengajukan peminjaman baru sampai alat dikembalikan, dan tercatat sebagai pelanggaran."],
            ['wrench', 'Alat rusak', 'Peminjam wajib memperbaiki atau mengganti unit yang rusak. Unit rusak tidak masuk stok.'],
            ['alert', 'Alat hilang', 'Peminjam wajib mengganti dengan alat yang sama atau setara. Unit hilang tidak masuk stok.'],
            ['shield', 'Kewajiban belum selesai', 'Selama kewajiban memperbaiki atau mengganti belum ditandai selesai oleh petugas GESIT, peminjam belum bisa mengajukan peminjaman baru.'],
        ];
    }

    // Ringkasan ketentuan yang dikenakan saat alat dikembalikan (null bila tidak ada)
    public static function susunSanksi(string $kondisi, int $unitBermasalah, int $hariTerlambat): ?string
    {
        $bagian = [];

        if ($kondisi === 'rusak' && $unitBermasalah > 0) {
            $bagian[] = "{$unitBermasalah} unit rusak: wajib diperbaiki atau diganti.";
        } elseif ($kondisi === 'hilang' && $unitBermasalah > 0) {
            $bagian[] = "{$unitBermasalah} unit hilang: wajib diganti dengan alat yang sama atau setara.";
        }

        if ($hariTerlambat > 0) {
            $bagian[] = $hariTerlambat > self::MAKS_HARI_TELAT
                ? "Terlambat {$hariTerlambat} hari: melewati batas toleransi, tercatat sebagai pelanggaran."
                : "Terlambat {$hariTerlambat} hari: diberi peringatan.";
        }

        return $bagian ? implode(' ', $bagian) : null;
    }

    // Peminjaman selesai yang kewajiban ganti/perbaikannya belum ditandai beres oleh petugas
    public function scopeKewajibanBelumSelesai($query)
    {
        return $query->where('status', 'selesai')
            ->whereIn('kondisi_kembali', ['rusak', 'hilang'])
            ->whereNull('kewajiban_selesai_at');
    }

    public function punyaKewajiban(): bool
    {
        return $this->status === 'selesai'
            && in_array($this->kondisi_kembali, ['rusak', 'hilang'], true)
            && $this->kewajiban_selesai_at === null;
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