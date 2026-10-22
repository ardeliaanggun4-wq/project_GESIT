{{-- Letak file: resources/views/admin/data-alat.blade.php (timpa file lama) --}}
@extends('layouts.admin')
@section('title', 'Data Alat')

@section('content')
    @php
        $kategoriOpsi = ['praktik_lab' => 'Alat Praktik Lab', 'olahraga' => 'Alat Olahraga', 'kesenian' => 'Alat Kesenian', 'multimedia' => 'Multimedia'];
        $kondisiOpsi  = ['baik' => 'Baik', 'perlu_cek' => 'Perlu Cek', 'stok_habis' => 'Stok Habis', 'rusak' => 'Rusak'];
        $kondisiGaya  = ['baik' => 'ok', 'perlu_cek' => 'wait', 'stok_habis' => 'no', 'rusak' => 'no'];
    @endphp

    <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
        <div>
            <p class="mono-label rise">Halaman 03 · Data Alat</p>
            <h1 class="display mt-3">
                <span class="line"><span style="--i:0">Data Alat</span></span>
            </h1>
            <p class="soft text-sm mt-4 rise" style="--i:2">Kelola inventaris alat praktik, olahraga, kesenian, dan multimedia.</p>
        </div>
        <button type="button" data-modal-open="modal-tambah" class="btn btn-primary rise" style="--i:2">+ Tambah alat</button>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-5 rise" style="--i:2">
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau kode alat…" class="field-box flex-1 min-w-[14rem]" style="width:auto">
        <select name="kategori" class="field-box" style="width:auto">
            <option value="">Semua kategori</option>
            @foreach ($kategoriOpsi as $val => $label)
                <option value="{{ $val }}" @selected(request('kategori') === $val)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="kondisi" class="field-box" style="width:auto">
            <option value="">Semua kondisi</option>
            @foreach ($kondisiOpsi as $val => $label)
                <option value="{{ $val }}" @selected(request('kondisi') === $val)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn btn-ghost">Terapkan</button>
    </form>

    {{-- Tabel --}}
    <div class="card overflow-hidden rise" style="--i:3">
        <div class="overflow-x-auto">
            <table class="ledger-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama alat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Kondisi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($alat as $a)
                        <tr>
                            <td class="kode muted">{{ $a->kode }}</td>
                            <td>
                                <div class="font-semibold">{{ $a->nama }}</div>
                                <div class="text-xs muted">{{ $a->lokasi }}</div>
                            </td>
                            <td>{{ $a->kategoriLabel() }}</td>
                            <td class="kode {{ $a->stok === 0 ? 'font-semibold' : '' }}" @if($a->stok === 0) style="color:var(--brick)" @endif>{{ $a->stok }} unit</td>
                            <td>
                                <span class="stamp stamp-{{ $kondisiGaya[$a->kondisi] ?? 'idle' }}" style="--i: {{ $loop->index }}">
                                    {{ $a->kondisiLabel() }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-4">
                                    <button type="button" data-modal-open="modal-edit-{{ $a->id }}" class="link-act">Ubah</button>
                                    <form method="POST" action="{{ route('admin.data-alat.destroy', $a) }}" onsubmit="return confirm('Hapus alat ini?')">
                                        @csrf @method('DELETE')
                                        <button class="link-act danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 text-sm soft" style="border-top:1.5px solid var(--ink)">
            <span>Menampilkan {{ $alat->firstItem() }}–{{ $alat->lastItem() }} dari {{ $alat->total() }} alat</span>
            <div>{{ $alat->links() }}</div>
        </div>
    </div>

    {{-- Modal ubah (di luar tabel agar HTML valid) --}}
    @foreach ($alat as $a)
        <div id="modal-edit-{{ $a->id }}" class="modal" role="dialog" aria-modal="true">
            <form method="POST" action="{{ route('admin.data-alat.update', $a) }}" class="modal-panel card p-6 w-full max-w-md space-y-4">
                @csrf @method('PUT')
                <div>
                    <div class="mono-label">Ubah alat</div>
                    <h3 class="h-sec text-xl mt-1">{{ $a->kode }}</h3>
                </div>
                <div>
                    <label class="label">Nama alat</label>
                    <input name="nama" value="{{ $a->nama }}" class="field-box" required>
                </div>
                <div>
                    <label class="label">Kategori</label>
                    <select name="kategori" class="field-box">
                        @foreach ($kategoriOpsi as $val => $label)
                            <option value="{{ $val }}" @selected($a->kategori === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Lokasi</label>
                    <input name="lokasi" value="{{ $a->lokasi }}" class="field-box" placeholder="Lokasi">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Stok</label>
                        <input type="number" name="stok" value="{{ $a->stok }}" min="0" class="field-box">
                    </div>
                    <div>
                        <label class="label">Kondisi</label>
                        <select name="kondisi" class="field-box">
                            @foreach ($kondisiOpsi as $val => $label)
                                <option value="{{ $val }}" @selected($a->kondisi === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-modal-close class="btn btn-ghost btn-sm">Batal</button>
                    <button class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    @endforeach

    {{-- Modal tambah --}}
    <div id="modal-tambah" class="modal" role="dialog" aria-modal="true">
        <form method="POST" action="{{ route('admin.data-alat.store') }}" class="modal-panel card p-6 w-full max-w-md space-y-4">
            @csrf
            <div>
                <div class="mono-label">Inventaris baru</div>
                <h3 class="h-sec text-xl mt-1">Tambah alat</h3>
            </div>
            <div>
                <label class="label">Kode alat</label>
                <input name="kode" placeholder="Contoh: ALT-XXX-000" class="field-box" required>
            </div>
            <div>
                <label class="label">Nama alat</label>
                <input name="nama" placeholder="Nama alat" class="field-box" required>
            </div>
            <div>
                <label class="label">Kategori</label>
                <select name="kategori" class="field-box">
                    @foreach ($kategoriOpsi as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Lokasi</label>
                <input name="lokasi" placeholder="Lokasi" class="field-box">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Stok</label>
                    <input type="number" name="stok" min="0" value="0" class="field-box">
                </div>
                <div>
                    <label class="label">Kondisi</label>
                    <select name="kondisi" class="field-box">
                        @foreach ($kondisiOpsi as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" data-modal-close class="btn btn-ghost btn-sm">Batal</button>
                <button class="btn btn-primary btn-sm">Simpan</button>
            </div>
        </form>
    </div>
@endsection