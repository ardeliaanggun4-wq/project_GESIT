<?php

namespace App\Http\Controllers\Admin;

// Letak file: app/Http/Controllers/Admin/DataAlatController.php

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;

class DataAlatController extends Controller
{
    public function index(Request $request)
    {
        $query = Alat::query();

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->cari}%")
                  ->orWhere('kode', 'like', "%{$request->cari}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $alat = $query->orderBy('nama')->paginate(6)->withQueryString();

        // Jumlah alat per kategori (untuk chip ringkasan di atas tabel)
        $hitung = Alat::selectRaw('kategori, count(*) as total')->groupBy('kategori')->pluck('total', 'kategori');
        $totalSemua = $hitung->sum();

        // Ringkasan kondisi & total unit (untuk kartu angka di atas)
        $kondisiHitung = Alat::selectRaw('kondisi, count(*) as total')->groupBy('kondisi')->pluck('total', 'kondisi');
        $totalStok = (int) Alat::sum('stok');

        return view('admin.data-alat', compact('alat', 'hitung', 'totalSemua', 'kondisiHitung', 'totalStok'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'unique:alat,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:komputer,elektronika,perkakas,multimedia'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'in:baik,stok_habis'],
        ]);

        Alat::create($this->samakanStokKondisi($validated));

        return back()->with('status', 'Alat baru berhasil ditambahkan.');
    }

    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:komputer,elektronika,perkakas,multimedia'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'in:baik,stok_habis'],
        ]);

        $alat->update($this->samakanStokKondisi($validated));

        return back()->with('status', 'Data alat berhasil diperbarui.');
    }

    public function destroy(Alat $alat)
    {
        $alat->delete();

        return back()->with('status', 'Alat berhasil dihapus.');
    }

    // Stok dan kondisi harus selalu sejalan:
    // kondisi "Stok Habis" -> stok otomatis 0, dan stok 0 -> kondisi otomatis "Stok Habis"
    private function samakanStokKondisi(array $data): array
    {
        if ($data['kondisi'] === 'stok_habis' || (int) $data['stok'] === 0) {
            $data['stok'] = 0;
            $data['kondisi'] = 'stok_habis';
        }

        return $data;
    }
}