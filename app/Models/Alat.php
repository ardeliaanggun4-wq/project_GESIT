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

    // Stok dan kondisi selalu sejalan, apa pun cara datanya disimpan:
    // kondisi "stok_habis" -> stok 0, dan stok 0 -> kondisi "stok_habis"
    protected static function booted(): void
    {
        static::saving(function (Alat $alat) {
            if ($alat->kondisi === 'stok_habis' || (int) $alat->stok <= 0) {
                $alat->stok = 0;
                $alat->kondisi = 'stok_habis';
            }
        });
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function kategoriLabel(): string
    {
        return match ($this->kategori) {
            'komputer' => 'Komputer',
            'elektronika' => 'Elektronika',
            'perkakas' => 'Perkakas',
            'multimedia' => 'Multimedia',
            default => ucfirst($this->kategori),
        };
    }

    public function kondisiLabel(): string
    {
        return match ($this->kondisi) {
            'baik' => 'Baik',
            'stok_habis' => 'Stok Habis',
            default => ucfirst($this->kondisi),
        };
    }
}