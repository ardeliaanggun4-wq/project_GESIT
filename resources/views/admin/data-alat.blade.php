{{-- Letak file: resources/views/admin/data-alat.blade.php --}}
@extends('layouts.admin')
@section('title', 'Data Alat')

@section('content')
    @include('partials.foto-alat-style')
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
                                {{-- Klik gambar atau nama/deskripsi untuk membuka detail alat --}}
                                <div class="flex items-center gap-3 alat-klik" role="button" tabindex="0"
                                     title="Klik untuk melihat detail" aria-label="Lihat detail {{ $a->nama }}"
                                     data-open="dlg-detail"
                                     data-foto-url="{{ $a->fotoUrl() }}"
                                     data-hapus-action="{{ route('admin.data-alat.destroy', $a) }}"
                                     data-nama="{{ $a->nama }}" data-kode="{{ $a->kode }}"
                                     data-fill="{{ json_encode([
                                         '#d-kode' => $a->kode,
                                         '#d-nama' => $a->nama,
                                         '#d-kategori' => $a->kategoriLabel(),
                                         '#d-kondisi' => $a->kondisiLabel(),
                                         '#d-lokasi' => $a->lokasi ?: '-',
                                         '#d-stok' => $a->stok . ' unit',
                                         '#d-maks' => $a->batasHariPinjam() . ' hari',
                                         '#d-deskripsi' => $a->deskripsi ?: 'Belum ada deskripsi.',
                                     ]) }}">
                                    @if ($a->fotoUrl())
                                        <img src="{{ $a->fotoUrl() }}" alt="Foto {{ $a->nama }}" class="alat-thumb" loading="lazy">
                                    @else
                                        <x-kat-ico :nilai="$a->kategori" />
                                    @endif
                                    <div>
                                        <div class="alat-nama">{{ $a->nama }}</div>
                                        @if ($a->lokasi)
                                            <div class="alat-lok"><svg class="ico" aria-hidden="true"><use href="#i-pin"/></svg>{{ $a->lokasi }}</div>
                                        @endif
                                        <div class="alat-lok">Maks. pinjam {{ $a->batasHariPinjam() }} hari</div>
                                        @if ($a->deskripsi)
                                            <div class="alat-lok" style="max-width:22rem">{{ \Illuminate\Support\Str::limit($a->deskripsi, 70) }}</div>
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
                                            data-foto-url="{{ $a->fotoUrl() }}"
                                            data-form-action="{{ route('admin.data-alat.update', $a) }}"
                                            data-fill="{{ json_encode([
                                                '#u-kode' => $a->kode,
                                                '#u-nama' => $a->nama,
                                                '#u-kategori' => $a->kategori,
                                                '#u-lokasi' => $a->lokasi ?? '',
                                                '#u-deskripsi' => $a->deskripsi ?? '',
                                                '#u-stok' => $a->stok,
                                                '#u-maks' => $a->batasHariPinjam(),
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
        <form method="POST" action="{{ route('admin.data-alat.store') }}" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0">
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
                <div>
                    <label class="label" for="t-deskripsi">Deskripsi alat</label>
                    <textarea id="t-deskripsi" name="deskripsi" rows="3" maxlength="1000" class="field" placeholder="Contoh: Mouse optik USB, dipakai untuk praktik di Lab RPL."></textarea>
                </div>
                <div>
                    <label class="label" for="t-foto">Foto alat</label>
                    <input id="t-foto" type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="field" data-prev="t-prev">
                    <p class="hint">JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
                    <img id="t-prev" class="foto-prev" alt="Pratinjau foto alat" hidden>
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
                <div>
                    <label class="label" for="t-maks">Batas lama pinjam (hari)</label>
                    <input id="t-maks" type="number" name="maks_hari_pinjam" min="1" max="30" value="4" class="field" required>
                    <p class="hint">Alat yang rawan rusak bila lama dipinjam diberi batas pendek, misalnya proyektor 1 hari.</p>
                </div>
            </div>
            <div class="dlg-foot">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </dialog>

    {{-- Dialog detail alat: terbuka saat gambar atau deskripsi alat di tabel diklik --}}
    <style>
        .alat-klik{cursor:pointer;border-radius:10px}
        .alat-klik:hover .alat-nama{color:var(--primary);text-decoration:underline}
        .alat-klik:focus-visible{outline:2px solid var(--primary);outline-offset:3px}
        .detail-foto{display:block;width:100%;max-height:16rem;object-fit:contain;border-radius:12px;border:1px solid var(--line);background:var(--surface-2)}
        .detail-foto-kosong{display:grid;place-items:center;height:9rem;border-radius:12px;border:1px dashed var(--line-strong);background:var(--surface-2);color:var(--muted);font-size:.875rem}
        .detail-grid{display:grid;grid-template-columns:8.5rem minmax(0,1fr);gap:.6rem 1rem;margin:1.1rem 0 0}
        .detail-grid dt{color:var(--muted);font-size:.875rem}
        .detail-grid dd{margin:0;font-weight:600;overflow-wrap:anywhere}
        .detail-grid dd.desk{font-weight:400;white-space:pre-line}
        .dlg-foot .btn-hapus-detail{margin-right:auto}
    </style>
    <dialog id="dlg-detail" class="dlg" aria-labelledby="d-nama">
        <div class="dlg-head">
            <div>
                <p class="t-xs t-muted">Detail alat · <span id="d-kode"></span></p>
                <h2 id="d-nama" class="t-sec"></h2>
            </div>
            <button type="button" class="btn btn-ghost btn-icon" data-close aria-label="Tutup"><svg class="ico" aria-hidden="true"><use href="#i-x"/></svg></button>
        </div>
        <div class="dlg-body">
            <img id="d-foto" class="detail-foto" alt="Foto alat" hidden>
            <div id="d-foto-kosong" class="detail-foto-kosong">Belum ada foto</div>
            <dl class="detail-grid">
                <dt>Kategori</dt><dd id="d-kategori"></dd>
                <dt>Kondisi</dt><dd id="d-kondisi"></dd>
                <dt>Stok</dt><dd id="d-stok"></dd>
                <dt>Lokasi</dt><dd id="d-lokasi"></dd>
                <dt>Batas lama pinjam</dt><dd id="d-maks"></dd>
                <dt>Deskripsi</dt><dd id="d-deskripsi" class="desk"></dd>
            </dl>
        </div>
        <div class="dlg-foot">
            <form id="d-hapus-form" method="POST" action="#" class="btn-hapus-detail"
                  data-confirm data-confirm-title="Hapus alat ini?" data-confirm-text="" data-confirm-ok="Ya, hapus">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-ghost is-danger"><svg class="ico" aria-hidden="true"><use href="#i-trash"/></svg>Hapus</button>
            </form>
            <button type="button" class="btn btn-secondary" data-close>Tutup</button>
            <button type="button" class="btn btn-primary" id="d-ubah"><svg class="ico" aria-hidden="true"><use href="#i-edit"/></svg>Ubah</button>
        </div>
    </dialog>

    <script>
        (function () {
            var detail = document.getElementById('dlg-detail');
            var foto = document.getElementById('d-foto');
            var fotoKosong = document.getElementById('d-foto-kosong');
            var formHapus = document.getElementById('d-hapus-form');
            var baris = null; // baris alat yang sedang dilihat

            document.querySelectorAll('.alat-klik').forEach(function (el) {
                // Isi teks detail ditangani handler dialog umum (data-fill); di sini foto dan form hapus
                el.addEventListener('click', function () {
                    baris = el.closest('tr');
                    var url = el.dataset.fotoUrl || '';
                    if (url) { foto.src = url; foto.alt = 'Foto ' + el.dataset.nama; foto.hidden = false; fotoKosong.hidden = true; }
                    else { foto.removeAttribute('src'); foto.hidden = true; fotoKosong.hidden = false; }
                    formHapus.action = el.dataset.hapusAction;
                    formHapus.dataset.confirmText = el.dataset.nama + ' (' + el.dataset.kode + ') akan dihapus dari inventaris.';
                    delete formHapus.dataset.dikonfirmasi;
                    delete formHapus.dataset.busy;
                });
                // Bisa dibuka dengan keyboard (Enter atau Spasi)
                el.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); el.click(); }
                });
            });

            // Tombol Ubah: tutup detail, lalu buka dialog Ubah milik baris yang sama
            document.getElementById('d-ubah').addEventListener('click', function () {
                var tombol = baris ? baris.querySelector('[data-open="dlg-ubah"]') : null;
                detail.close();
                if (tombol) tombol.click();
            });
        })();
    </script>

    {{-- Dialog ubah alat (satu dialog dipakai semua baris; isinya diisi lewat data-fill) --}}
    <dialog id="dlg-ubah" class="dlg" aria-labelledby="ubah-judul">
        <form method="POST" action="#" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0">
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
                <div>
                    <label class="label" for="u-deskripsi">Deskripsi alat</label>
                    <textarea id="u-deskripsi" name="deskripsi" rows="3" maxlength="1000" class="field" placeholder="Deskripsi alat"></textarea>
                </div>
                <div>
                    <label class="label" for="u-foto">Foto alat</label>
                    <input id="u-foto" type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="field" data-prev="u-prev">
                    <p class="hint">Pilih file baru untuk mengganti foto. JPG, PNG, atau WEBP, maksimal 2 MB.</p>
                    <img id="u-prev" class="foto-prev" alt="Foto alat saat ini" hidden>
                    <label class="cek-hapus" id="u-hapus-wrap" hidden><input type="checkbox" name="hapus_foto" value="1" id="u-hapus"> Hapus foto ini</label>
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
                <div>
                    <label class="label" for="u-maks">Batas lama pinjam (hari)</label>
                    <input id="u-maks" type="number" name="maks_hari_pinjam" min="1" max="30" value="" class="field" required>
                    <p class="hint">Alat yang rawan rusak bila lama dipinjam diberi batas pendek, misalnya proyektor 1 hari.</p>
                </div>
            </div>
            <div class="dlg-foot">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-primary">Simpan perubahan</button>
            </div>
        </form>
    </dialog>

    <script>
        // Pratinjau foto: saat file dipilih, dan saat dialog Ubah dibuka (menampilkan foto yang sudah ada)
        (function () {
            function tampil(img, src) {
                if (!img) return;
                if (src) { img.src = src; img.hidden = false; } else { img.removeAttribute('src'); img.hidden = true; }
            }
            document.querySelectorAll('input[type="file"][data-prev]').forEach(function (input) {
                input.addEventListener('change', function () {
                    var img = document.getElementById(input.dataset.prev);
                    var f = input.files && input.files[0];
                    if (f) tampil(img, URL.createObjectURL(f));
                });
            });
            // Dialog Tambah: bersihkan pratinjau setiap dibuka (tombol memakai data-reset)
            document.querySelectorAll('[data-open="dlg-tambah"]').forEach(function (b) {
                b.addEventListener('click', function () { tampil(document.getElementById('t-prev'), null); });
            });
            // Dialog Ubah: tampilkan foto saat ini, kosongkan pilihan file dan centang hapus
            document.querySelectorAll('[data-open="dlg-ubah"]').forEach(function (b) {
                b.addEventListener('click', function () {
                    var url = b.dataset.fotoUrl || '';
                    tampil(document.getElementById('u-prev'), url);
                    document.getElementById('u-foto').value = '';
                    document.getElementById('u-hapus').checked = false;
                    document.getElementById('u-hapus-wrap').hidden = !url;
                });
            });
        })();
    </script>

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