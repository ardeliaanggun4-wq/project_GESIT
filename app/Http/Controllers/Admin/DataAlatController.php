<?php

namespace App\Http\Controllers\Admin;

// Letak file: app/Http/Controllers/Admin/DataAlatController.php

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // maks 2 MB
            'stok' => ['required', 'integer', 'min:0'],
            'maks_hari_pinjam' => ['required', 'integer', 'min:1', 'max:30'],
            'kondisi' => ['required', 'in:baik,stok_habis'],
        ], $this->pesanValidasi());

        unset($validated['foto']);
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto-alat', 'public');
        }

        Alat::create($this->samakanStokKondisi($validated));

        return back()->with('status', 'Alat baru berhasil ditambahkan.');
    }

    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:komputer,elektronika,perkakas,multimedia'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // maks 2 MB
            'stok' => ['required', 'integer', 'min:0'],
            'maks_hari_pinjam' => ['required', 'integer', 'min:1', 'max:30'],
            'kondisi' => ['required', 'in:baik,stok_habis'],
        ], $this->pesanValidasi());

        unset($validated['foto']);

        // Foto baru menggantikan yang lama; centang "hapus foto" menghapusnya tanpa pengganti
        if ($request->hasFile('foto')) {
            $this->hapusFoto($alat);
            $validated['foto'] = $request->file('foto')->store('foto-alat', 'public');
        } elseif ($request->boolean('hapus_foto')) {
            $this->hapusFoto($alat);
            $validated['foto'] = null;
        }

        $alat->update($this->samakanStokKondisi($validated));

        return back()->with('status', 'Data alat berhasil diperbarui.');
    }

    public function destroy(Alat $alat)
    {
        $this->hapusFoto($alat);
        $alat->delete();

        return back()->with('status', 'Alat berhasil dihapus.');
    }

    // Hapus file foto lama dari penyimpanan agar tidak menumpuk
    private function hapusFoto(Alat $alat): void
    {
        if ($alat->foto) {
            Storage::disk('public')->delete($alat->foto);
        }
    }

    private function pesanValidasi(): array
    {
        return [
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
            'maks_hari_pinjam.required' => 'Batas lama pinjam wajib diisi.',
            'maks_hari_pinjam.min' => 'Batas lama pinjam minimal 1 hari.',
            'maks_hari_pinjam.max' => 'Batas lama pinjam maksimal 30 hari.',
        ];
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