<?php

namespace App\Models;

// Letak file: app/Models/Alat.php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alat';

    protected $fillable = [
        'kode', 'nama', 'kategori', 'lokasi', 'stok', 'kondisi',
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function kategoriLabel(): string
    {
        return match ($this->kategori) {
            'praktik_lab' => 'Alat Praktik Lab',
            'olahraga' => 'Alat Olahraga',
            'kesenian' => 'Alat Kesenian',
            'multimedia' => 'Multimedia',
            default => ucfirst($this->kategori),
        };
    }

    public function kondisiLabel(): string
    {
        return match ($this->kondisi) {
            'baik' => 'Baik',
            'perlu_cek' => 'Perlu Cek',
            'rusak' => 'Rusak',
            'stok_habis' => 'Stok Habis',
            default => ucfirst($this->kondisi),
        };
    }
}