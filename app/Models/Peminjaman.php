<?php

namespace App\Models;

// Letak file: app/Models/Peminjaman.php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'kode_pengajuan', 'user_id', 'alat_id', 'jumlah',
        'tanggal_pinjam', 'tanggal_kembali_rencana', 'tanggal_kembali_aktual',
        'keperluan', 'status', 'catatan_admin',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_kembali_rencana' => 'date',
            'tanggal_kembali_aktual' => 'date',
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