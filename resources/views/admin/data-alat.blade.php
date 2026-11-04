{{-- Letak file: resources/views/admin/data-alat.blade.php --}}
@extends('layouts.admin')
@section('title', 'Data Alat')

@section('content')
    @php
        $kategoriOpsi = ['komputer' => 'Komputer', 'elektronika' => 'Elektronika', 'perkakas' => 'Perkakas', 'multimedia' => 'Multimedia'];
        $kondisiOpsi  = ['baik' => 'Baik', 'stok_habis' => 'Stok Habis'];
        $adaFilter    = request()->filled('cari') || request()->filled('kategori') || request()->filled('kondisi');
        $urlKategori  = fn ($nilai) => route('admin.data-alat', array_filter(array_merge(request()->except(['page', 'kategori']), ['kategori' => $nilai])));
    @endphp

    <section class="banner tone-primary rise mb-6">
        <div class="hero-dots" aria-hidden="true"></div>
        <div class="banner-body">
            <p class="eyebrow">Inventaris</p>
            <h1 class="display mt-3">
                <span class="line"><span style="--i:0">Data <span class="hl">Alat</span></span></span>
            </h1>
            <p class="page-lead rise" style="--i:2">
                <span class="typed" data-type='["Kelola inventaris alat komputer, elektronika, perkakas, dan multimedia.","Cari alat lewat nama atau kodenya.","Perbarui stok dan kondisi setelah alat dikembalikan."]'>Kelola inventaris alat komputer, elektronika, perkakas, dan multimedia.</span>
            </p>
            <div class="hero-actions rise" style="--i:3">
                <button type="button" data-open="dlg-tambah" data-reset class="btn btn-primary">
                    <svg class="ico" aria-hidden="true"><use href="#i-plus"/></svg>Tambah alat
                </button>
            </div>
        </div>
        <x-banner-art ikon="grid" :mini="['box', 'flask', 'ball']" />
    </section>

    <div class="stats stats-3 mb-6">
        <div class="stat tone-primary rise" style="--i:2">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-box"/></svg></span>
            <div class="stat-label">Jenis alat</div>
            <div class="stat-num" data-count="{{ $totalSemua }}">{{ $totalSemua }}</div>
        </div>
        <div class="stat tone-ok rise" style="--i:3">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-layers"/></svg></span>
            <div class="stat-label">Total unit tersedia</div>
            <div class="stat-num" data-count="{{ $totalStok }}">{{ $totalStok }}</div>
        </div>
        <a href="{{ route('admin.data-alat', ['kondisi' => 'stok_habis']) }}" class="stat tone-no rise" style="--i:4">
            <svg class="ico stat-go" aria-hidden="true"><use href="#i-arrow"/></svg>
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-minus"/></svg></span>
            <div class="stat-label">Stok habis</div>
            <div class="stat-num" data-count="{{ $kondisiHitung->get('stok_habis', 0) }}">{{ $kondisiHitung->get('stok_habis', 0) }}</div>
        </a>
    </div>

    @if ($errors->any())
        <div class="alert mb-5 rise" role="alert">
            <svg class="ico" aria-hidden="true"><use href="#i-alert"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    {{-- Ringkasan kategori: sekaligus filter cepat --}}
    <div class="filters mb-4 rise" style="--i:3" role="group" aria-label="Saring menurut kategori">
        <a href="{{ route('admin.data-alat', array_filter(request()->except(['page', 'kategori']))) }}"
           class="filter-chip {{ request('kategori') ? '' : 'is-on' }}">
            <svg class="ico ico-sm" aria-hidden="true"><use href="#i-layers"/></svg>Semua <span class="n">{{ $totalSemua }}</span>
        </a>
        @foreach ($kategoriOpsi as $val => $label)
            <a href="{{ $urlKategori($val) }}" data-kat="{{ $val }}"
               class="filter-chip {{ request('kategori') === $val ? 'is-on' : '' }}">
                <i class="dot" aria-hidden="true"></i>{{ $label }} <span class="n">{{ $hitung->get($val, 0) }}</span>
            </a>
        @endforeach
    </div>

    {{-- Pencarian --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-5 rise" style="--i:4">
        @if (request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
        <div class="search flex-1" style="min-width:14rem">
            <svg class="ico" aria-hidden="true"><use href="#i-search"/></svg>
            <input type="search" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau kode alat…" class="field" aria-label="Cari alat">
        </div>
        <select name="kondisi" class="field" style="width:auto" aria-label="Saring menurut kondisi">
            <option value="">Semua kondisi</option>
            @foreach ($kondisiOpsi as $val => $label)
                <option value="{{ $val }}" @selected(request('kondisi') === $val)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary">Terapkan</button>
        @if ($adaFilter)
            <a href="{{ route('admin.data-alat') }}" class="btn btn-ghost">Atur ulang</a>
        @endif
    </form>

    {{-- Tabel --}}
    <div class="table-wrap rise" style="--i:5">
        @if ($alat->count())
            <table class="tbl tbl-alat">
                <thead>
                    <tr>
                        <th class="c-kode">Kode</th>
                        <th>Nama alat</th>
                        <th class="c-kat">Kategori</th>
                        <th class="c-stok">Stok</th>
                        <th class="c-kond">Kondisi</th>
                        <th class="c-aksi r">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($alat as $a)
                        <tr class="has-kat" data-kat="{{ $a->kategori }}">
                            <td><span class="kode">{{ $a->kode }}</span></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <x-kat-ico :nilai="$a->kategori" />
                                    <div>
                                        <div class="alat-nama">{{ $a->nama }}</div>
                                        @if ($a->lokasi)
                                            <div class="alat-lok"><svg class="ico" aria-hidden="true"><use href="#i-pin"/></svg>{{ $a->lokasi }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td><x-kategori :nilai="$a->kategori" /></td>
                            <td>
                                <div class="stok-cell">
                                    <span class="stok {{ $a->stok === 0 ? 'stok-low' : '' }}">{{ $a->stok }}</span>
                                    <span class="meter" aria-hidden="true"><i style="width: {{ min(100, ($a->stok / 20) * 100) }}%"></i></span>
                                </div>
                            </td>
                            <td><x-status :nilai="$a->kondisi" /></td>
                            <td class="r">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" class="btn btn-ghost btn-sm btn-icon" aria-label="Ubah {{ $a->nama }}" title="Ubah"
                                            data-open="dlg-ubah"
                                            data-form-action="{{ route('admin.data-alat.update', $a) }}"
                                            data-fill="{{ json_encode([
                                                '#u-kode' => $a->kode,
                                                '#u-nama' => $a->nama,
                                                '#u-kategori' => $a->kategori,
                                                '#u-lokasi' => $a->lokasi ?? '',
                                                '#u-stok' => $a->stok,
                                                '#u-kondisi' => $a->kondisi,
                                            ]) }}">
                                        <svg class="ico" aria-hidden="true"><use href="#i-edit"/></svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.data-alat.destroy', $a) }}"
                                          data-confirm data-confirm-title="Hapus alat ini?"
                                          data-confirm-text="{{ $a->nama }} ({{ $a->kode }}) akan dihapus dari inventaris."
                                          data-confirm-ok="Ya, hapus">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost is-danger btn-sm btn-icon" aria-label="Hapus {{ $a->nama }}" title="Hapus">
                                            <svg class="ico" aria-hidden="true"><use href="#i-trash"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="table-foot">
                <span>Menampilkan {{ $alat->firstItem() }}–{{ $alat->lastItem() }} dari {{ $alat->total() }} alat</span>
                <div>{{ $alat->links() }}</div>
            </div>
        @else
            <div class="empty">
                <span class="empty-ico"><svg class="ico" aria-hidden="true"><use href="#i-search"/></svg></span>
                <h3>Tidak ada alat yang cocok</h3>
                <p>Coba kata kunci lain, atau atur ulang saringan kategori dan kondisi.</p>
                <a href="{{ route('admin.data-alat') }}" class="btn btn-secondary" style="margin-top:.6rem">Atur ulang saringan</a>
            </div>
        @endif
    </div>

    {{-- Dialog tambah alat --}}
    <dialog id="dlg-tambah" class="dlg" aria-labelledby="tambah-judul">
        <form method="POST" action="{{ route('admin.data-alat.store') }}" class="flex flex-col flex-1 min-h-0">
            @csrf
            <div class="dlg-head">
                <div>
                    <p class="t-xs t-muted">Inventaris baru</p>
                    <h2 id="tambah-judul" class="t-sec">Tambah alat</h2>
                </div>
                <button type="button" class="btn btn-ghost btn-icon" data-close aria-label="Tutup"><svg class="ico" aria-hidden="true"><use href="#i-x"/></svg></button>
            </div>
            <div class="dlg-body space-y-4">
                <div>
                    <label class="label" for="t-kode">Kode alat</label>
                    <input id="t-kode" name="kode" placeholder="Contoh: ALT-XXX-000" class="field" required>
                </div>
                <div>
                    <label class="label" for="t-nama">Nama alat</label>
                    <input id="t-nama" name="nama" placeholder="Nama alat" class="field" required>
                </div>
                <div>
                    <label class="label" for="t-kategori">Kategori</label>
                    <select id="t-kategori" name="kategori" class="field">
                        @foreach ($kategoriOpsi as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="t-lokasi">Lokasi</label>
                    <input id="t-lokasi" name="lokasi" placeholder="Contoh: Lab RPL" class="field">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label" for="t-stok">Stok</label>
                        <input id="t-stok" type="number" name="stok" min="0" value="0" class="field">
                    </div>
                    <div>
                        <label class="label" for="t-kondisi">Kondisi</label>
                        <select id="t-kondisi" name="kondisi" class="field">
                            @foreach ($kondisiOpsi as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="dlg-foot">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </dialog>

    {{-- Dialog ubah alat (satu dialog dipakai semua baris; isinya diisi lewat data-fill) --}}
    <dialog id="dlg-ubah" class="dlg" aria-labelledby="ubah-judul">
        <form method="POST" action="#" class="flex flex-col flex-1 min-h-0">
            @csrf @method('PUT')
            <div class="dlg-head">
                <div>
                    <p class="t-xs t-muted">Ubah alat</p>
                    <h2 id="ubah-judul" class="t-sec"><span id="u-kode"></span></h2>
                </div>
                <button type="button" class="btn btn-ghost btn-icon" data-close aria-label="Tutup"><svg class="ico" aria-hidden="true"><use href="#i-x"/></svg></button>
            </div>
            <div class="dlg-body space-y-4">
                <div>
                    <label class="label" for="u-nama">Nama alat</label>
                    <input id="u-nama" name="nama" class="field" required>
                </div>
                <div>
                    <label class="label" for="u-kategori">Kategori</label>
                    <select id="u-kategori" name="kategori" class="field">
                        @foreach ($kategoriOpsi as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="u-lokasi">Lokasi</label>
                    <input id="u-lokasi" name="lokasi" class="field" placeholder="Lokasi">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label" for="u-stok">Stok</label>
                        <input id="u-stok" type="number" name="stok" min="0" class="field">
                    </div>
                    <div>
                        <label class="label" for="u-kondisi">Kondisi</label>
                        <select id="u-kondisi" name="kondisi" class="field">
                            @foreach ($kondisiOpsi as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="dlg-foot">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-primary">Simpan perubahan</button>
            </div>
        </form>
    </dialog>

    <script>
        // Stok dan kondisi saling mengikuti: pilih "Stok Habis" -> stok jadi 0; stok diisi 0 -> kondisi jadi "Stok Habis"
        document.querySelectorAll('select[name="kondisi"][id$="-kondisi"]').forEach(function (kond) {
            var stok = document.getElementById(kond.id.replace('kondisi', 'stok'));
            if (!stok) return;
            kond.addEventListener('change', function () { if (kond.value === 'stok_habis') stok.value = 0; });
            stok.addEventListener('input', function () {
                if (stok.value !== '' && parseInt(stok.value, 10) === 0) kond.value = 'stok_habis';
                else if (kond.value === 'stok_habis' && parseInt(stok.value, 10) > 0) kond.value = 'baik';
            });
        });
    </script>
@endsection