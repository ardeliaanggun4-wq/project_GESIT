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

        return view('admin.data-alat', compact('alat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'unique:alat,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:praktik_lab,olahraga,kesenian,multimedia'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'in:baik,perlu_cek,rusak,stok_habis'],
        ]);

        Alat::create($validated);

        return back()->with('status', 'Alat baru berhasil ditambahkan.');
    }

    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:praktik_lab,olahraga,kesenian,multimedia'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'in:baik,perlu_cek,rusak,stok_habis'],
        ]);

        $alat->update($validated);

        return back()->with('status', 'Data alat berhasil diperbarui.');
    }

    public function destroy(Alat $alat)
    {
        $alat->delete();

        return back()->with('status', 'Alat berhasil dihapus.');
    }
}