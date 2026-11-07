{{-- Letak file: resources/views/siswa/ajukan.blade.php --}}
@extends('layouts.siswa')
@section('title', 'Ajukan Pinjam')

@section('content')
    @include('partials.foto-alat-style')
    @php
        $kategoriLabel = ['komputer' => 'Komputer', 'elektronika' => 'Elektronika', 'perkakas' => 'Perkakas', 'multimedia' => 'Multimedia'];
    @endphp

    <section class="banner tone-ok rise mb-6">
        <div class="hero-dots" aria-hidden="true"></div>
        <div class="banner-body">
            <p class="eyebrow">Ajukan pinjam</p>
            <h1 class="display mt-3">
                <span class="line"><span style="--i:0">Formulir pengajuan</span></span>
                <span class="line"><span style="--i:1" class="hl">peminjaman</span></span>
            </h1>
            <p class="page-lead rise" style="--i:3">
                <span class="typed" data-type='["Isi selengkap mungkin agar petugas Sarpras bisa memverifikasi lebih cepat.","Pilih alat dulu, lalu cek stok yang tersedia.","Sebutkan keperluan agar pengajuanmu cepat disetujui."]'>Isi selengkap mungkin agar petugas Sarpras bisa memverifikasi lebih cepat.</span>
            </p>
        </div>
        <x-banner-art ikon="send" :mini="['calendar', 'box', 'check']" />
    </section>

    <div class="alert alert-info mb-6" id="info-kat" role="status" hidden>
        <svg class="ico" aria-hidden="true"><use href="#i-info"/></svg>
        <span>Menampilkan alat kategori <b id="info-kat-teks"></b>. <a href="{{ route('siswa.ajukan') }}" class="link">Tampilkan semua alat</a></span>
    </div>

    @if ($errors->any())
        <div class="alert mb-6 rise" role="alert">
            <svg class="ico" aria-hidden="true"><use href="#i-alert"/></svg>
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-grid">
        <form method="POST" action="{{ route('siswa.ajukan.store') }}" class="card card-accent rise" style="--i:2">
            @csrf
            <div class="form-head">
                <span class="row-ico tone-primary"><svg class="ico" aria-hidden="true"><use href="#i-send"/></svg></span>
                <div>
                    <div class="t-card">Peminjaman alat</div>
                    <div class="t-xs t-muted">Semua kolom bertanda nomor wajib diisi</div>
                </div>
                <div style="margin-left:auto;text-align:right">
                    <div class="t-xs t-muted"><b id="fp-n" class="num" style="color:var(--ink)">0</b>/5 terisi</div>
                    <div class="track" style="width:6rem;margin-top:.35rem"><div class="fill" id="fp-bar" style="width:0;animation:none;transition:width .3s var(--ease)"></div></div>
                </div>
            </div>

            <div class="p-6 md:p-8 space-y-6">
                <div>
                    <label class="label" for="alat_id"><span class="label-no">1</span>Nama alat / inventaris</label>
                    <select id="alat_id" name="alat_id" class="field" required>
                        <option value="">Pilih alat</option>
                        @foreach ($daftarAlat as $alat)
                            <option value="{{ $alat->id }}"
                                    data-nama="{{ $alat->nama }}" data-kode="{{ $alat->kode }}" data-lokasi="{{ $alat->lokasi }}"
                                    data-stok="{{ $alat->stok }}" data-kategori="{{ $alat->kategori }}"
                                    data-foto="{{ $alat->fotoUrl() }}" data-deskripsi="{{ $alat->deskripsi }}"
                                    data-kategori-label="{{ $kategoriLabel[$alat->kategori] ?? $alat->kategori }}"
                                    {{ old('alat_id') == $alat->id ? 'selected' : '' }}>
                                {{ $alat->nama }} — {{ $alat->lokasi }} ({{ $alat->stok }} unit tersedia)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label class="label" for="tgl_pinjam"><span class="label-no">2</span>Tanggal pinjam</label>
                        <input id="tgl_pinjam" type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}" class="field" required>
                    </div>
                    <div>
                        <label class="label" for="tgl_kembali"><span class="label-no">3</span>Rencana kembali</label>
                        <input id="tgl_kembali" type="date" name="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana') }}" class="field" required>
                        <p class="hint">Maksimal {{ \App\Models\Peminjaman::MAKS_HARI_PINJAM }} hari dari tanggal pinjam.</p>
                    </div>
                </div>

                <div>
                    <label class="label" for="jumlah"><span class="label-no">4</span>Jumlah alat</label>
                    <div class="stepper">
                        <button type="button" id="kurang" aria-label="Kurangi jumlah">−</button>
                        <input id="jumlah" type="number" min="1" name="jumlah" value="{{ old('jumlah', 1) }}" required inputmode="numeric">
                        <button type="button" id="tambah" aria-label="Tambah jumlah">+</button>
                    </div>
                    <p class="hint" id="hint-jumlah">Pilih alat untuk melihat stok yang tersedia.</p>
                </div>

                <div>
                    <label class="label" for="keperluan"><span class="label-no">5</span>Keperluan penggunaan</label>
                    <textarea id="keperluan" name="keperluan" rows="3" maxlength="500"
                              placeholder="Contoh: praktik mata pelajaran Pemrograman Web, kelas XII RPL B"
                              class="field">{{ old('keperluan') }}</textarea>
                </div>

                <div class="pt-1">
                    <button type="submit" class="btn btn-primary">
                        <svg class="ico" aria-hidden="true"><use href="#i-send"/></svg>Kirim pengajuan
                    </button>
                </div>
            </div>
        </form>

        {{-- Panel samping: info alat terpilih & alur --}}
        <aside class="space-y-5">
            <section class="rise" style="--i:3" aria-live="polite">
                <div class="pick-empty card" id="pick-kosong" style="padding:var(--s-5)">
                    <span class="row-ico tone-primary" style="width:3rem;height:3rem"><svg class="ico" aria-hidden="true"><use href="#i-box"/></svg></span>
                    <b>Belum ada alat dipilih</b>
                    <span class="t-small">Detail stok dan lokasi alat akan tampil di sini.</span>
                </div>
                <div class="pick" id="pick" hidden>
                    <img id="pick-foto" class="pick-foto" alt="" hidden>
                    <div id="pick-kat" class="chip-kat"><i class="dot" aria-hidden="true"></i><span id="pick-kat-teks"></span></div>
                    <div class="pick-name" id="pick-nama" style="margin-top:.7rem"></div>
                    <dl>
                        <div><dt>Kode</dt><dd><span class="kode" id="pick-kode"></span></dd></div>
                        <div><dt>Lokasi</dt><dd id="pick-lokasi"></dd></div>
                        <div><dt>Stok tersedia</dt><dd id="pick-stok"></dd></div>
                    </dl>
                    <p class="pick-desk" id="pick-desk" hidden></p>
                </div>
            </section>

            @php
                $grup = $daftarAlat->groupBy('kategori');
                $maksUnit = max(1, $grup->map(fn ($g) => $g->sum('stok'))->max() ?? 0);
            @endphp
            <section class="card card-pad rise" style="--i:4">
                <div class="attn" style="margin-bottom:var(--s-4)">
                    <span class="row-ico tone-ok"><svg class="ico" aria-hidden="true"><use href="#i-layers"/></svg></span>
                    <h2 class="t-card">Ketersediaan alat</h2>
                </div>
                <div class="space-y-4">
                    @foreach ($kategoriLabel as $kode => $nama)
                        @php $g = $grup->get($kode, collect()); $unit = $g->sum('stok'); @endphp
                        <div data-kat="{{ $kode }}">
                            <div class="flex items-center justify-between gap-3 text-sm mb-1.5">
                                <x-kategori :nilai="$kode" />
                                <span class="num t-small t-muted"><b style="color:var(--ink)">{{ $unit }}</b> unit · {{ $g->count() }} jenis</span>
                            </div>
                            <div class="track"><div class="fill" style="width: {{ ($unit / $maksUnit) * 100 }}%; --i: {{ $loop->index }}"></div></div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="card card-pad card-accent rise" style="--i:5">
                <div class="attn" style="margin-bottom:var(--s-4)">
                    <span class="row-ico tone-wait"><svg class="ico" aria-hidden="true"><use href="#i-bulb"/></svg></span>
                    <h2 class="t-card">Setelah mengirim</h2>
                </div>
                <ol class="steps">
                    <li><div><b>Menunggu verifikasi</b><span>Status bisa kamu pantau di Beranda.</span></div></li>
                    <li><div><b>Disetujui</b><span>Ambil alat di ruang Sarpras.</span></div></li>
                    <li><div><b>Kembalikan</b><span>Sesuai tanggal rencana kembali. Terlambat maksimal {{ \App\Models\Peminjaman::MAKS_HARI_TELAT }} hari.</span></div></li>
                </ol>
            </section>
        </aside>
    </div>

    {{-- Daftar alat yang bisa dipinjam --}}
    @php
        $tersedia = $daftarAlat->where('stok', '>', 0)->count();
    @endphp
    <section class="mt-8 rise" style="--i:5" aria-labelledby="daftar-alat-judul">
        <div class="sec-head">
            <h2 class="t-sec" id="daftar-alat-judul">Daftar alat</h2>
            <span class="t-small t-muted">{{ $tersedia }} dari {{ $daftarAlat->count() }} jenis alat tersedia · tekan alat untuk melihat foto dan deskripsinya</span>
        </div>
        <div class="card" style="overflow:hidden">
            @forelse ($daftarAlat as $alat)
                @php $habis = $alat->stok <= 0; @endphp
                <button type="button" class="list-row alat-pilih {{ $habis ? 'is-habis' : '' }}" data-pilih="{{ $alat->id }}"
                        @disabled($habis) style="width:100%;text-align:left;background:transparent;border-left:0;border-right:0;border-top:0;{{ $habis ? 'opacity:.55;cursor:not-allowed' : 'cursor:pointer' }}">
                    @if ($alat->fotoUrl())
                        <img src="{{ $alat->fotoUrl() }}" alt="" class="alat-thumb" loading="lazy">
                    @else
                        <x-kat-ico :nilai="$alat->kategori" />
                    @endif
                    <div class="list-main">
                        <div class="list-title">{{ $alat->nama }}</div>
                        <div class="list-sub"><span class="kode">{{ $alat->kode }}</span> · {{ $alat->lokasi ?: 'Lokasi belum diisi' }}</div>
                    </div>
                    <x-kategori :nilai="$alat->kategori" class="hidden sm:inline-flex" />
                    @if ($habis)
                        <x-status nilai="stok_habis" />
                    @else
                        <span class="num" style="font-weight:600;white-space:nowrap">{{ $alat->stok }} <span class="t-small t-muted" style="font-weight:400">unit</span></span>
                    @endif
                </button>
            @empty
                <div class="empty">
                    <span class="empty-ico"><svg class="ico" aria-hidden="true"><use href="#i-box"/></svg></span>
                    <h3>Belum ada alat terdaftar</h3>
                    <p class="t-small">Hubungi petugas Sarpras.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Detail alat: foto + deskripsi, muncul saat alat di daftar ditekan --}}
    <dialog id="dlg-alat" class="dlg" aria-labelledby="alat-judul">
        <div class="flex flex-col flex-1 min-h-0">
            <div class="dlg-head">
                <div>
                    <p class="t-xs t-muted"><span class="kode" id="da-kode"></span></p>
                    <h2 id="alat-judul" class="t-sec"><span id="da-nama"></span></h2>
                </div>
                <button type="button" class="btn btn-ghost btn-icon" data-close aria-label="Tutup"><svg class="ico" aria-hidden="true"><use href="#i-x"/></svg></button>
            </div>
            <div class="dlg-body space-y-4">
                <img id="da-foto" class="pick-foto" alt="" hidden>
                <div id="da-kat" class="chip-kat"><i class="dot" aria-hidden="true"></i><span id="da-kat-teks"></span></div>
                <div>
                    <div class="t-xs t-muted" style="margin-bottom:.25rem">Deskripsi</div>
                    <p class="pick-desk" id="da-desk" style="margin-top:0"></p>
                </div>
                <div class="t-small">
                    <div><span class="t-muted">Lokasi:</span> <b id="da-lokasi"></b></div>
                    <div style="margin-top:.2rem"><span class="t-muted">Stok tersedia:</span> <b id="da-stok"></b></div>
                </div>
            </div>
            <div class="dlg-foot">
                <button type="button" class="btn btn-secondary" data-close>Tutup</button>
                <button type="button" class="btn btn-primary" id="da-pilih">
                    <svg class="ico" aria-hidden="true"><use href="#i-check"/></svg>Pilih alat ini
                </button>
            </div>
        </div>
    </dialog>
@endsection

@push('scripts')
<script>
(() => {
    const pilih = document.getElementById('alat_id');
    const jumlah = document.getElementById('jumlah');
    const hint = document.getElementById('hint-jumlah');
    const pinjam = document.getElementById('tgl_pinjam');
    const kembali = document.getElementById('tgl_kembali');
    const kosong = document.getElementById('pick-kosong');
    const kartu = document.getElementById('pick');
    const $ = (id) => document.getElementById(id);

    // Tanggal: tidak boleh sebelum hari ini, dan tanggal kembali tidak boleh sebelum tanggal pinjam
    const h = new Date();
    const hariIni = h.getFullYear() + '-' + String(h.getMonth() + 1).padStart(2, '0') + '-' + String(h.getDate()).padStart(2, '0');
    pinjam.min = hariIni;
    const selaraskan = () => {
        kembali.min = pinjam.value || hariIni;
        if (kembali.value && kembali.value < kembali.min) kembali.value = kembali.min;
        // Batas maksimal lama pinjam
        const mulai = new Date((pinjam.value || hariIni) + 'T00:00:00');
        mulai.setDate(mulai.getDate() + {{ \App\Models\Peminjaman::MAKS_HARI_PINJAM }});
        kembali.max = mulai.getFullYear() + '-' + String(mulai.getMonth() + 1).padStart(2, '0') + '-' + String(mulai.getDate()).padStart(2, '0');
        if (kembali.value && kembali.value > kembali.max) kembali.value = kembali.max;
    };
    // Klik alat di daftar -> buka detail (foto + deskripsi); tombol "Pilih alat ini" memilihnya di formulir
    const dlgAlat = $('dlg-alat');
    let alatDilihat = null;
    document.querySelectorAll('[data-pilih]').forEach((tombol) => {
        tombol.addEventListener('click', () => {
            const opt = pilih.querySelector('option[value="' + tombol.dataset.pilih + '"]');
            if (!opt) return;
            alatDilihat = opt.value;
            const stok = parseInt(opt.dataset.stok, 10);
            $('da-kode').textContent = opt.dataset.kode;
            $('da-nama').textContent = opt.dataset.nama;
            $('da-kat').dataset.kat = opt.dataset.kategori;
            $('da-kat-teks').textContent = opt.dataset.kategoriLabel;
            $('da-lokasi').textContent = opt.dataset.lokasi || '-';
            $('da-stok').textContent = stok > 0 ? stok + ' unit' : 'Habis';
            const desk = $('da-desk');
            desk.textContent = opt.dataset.deskripsi || 'Belum ada deskripsi untuk alat ini.';
            const foto = $('da-foto');
            if (opt.dataset.foto) { foto.src = opt.dataset.foto; foto.alt = 'Foto ' + opt.dataset.nama; foto.hidden = false; }
            else { foto.removeAttribute('src'); foto.hidden = true; }
            $('da-pilih').disabled = stok <= 0;
            dlgAlat.showModal();
            document.body.classList.add('dialog-open');
        });
    });
    $('da-pilih').addEventListener('click', () => {
        if (!alatDilihat) return;
        dlgAlat.close();
        pilih.value = alatDilihat;
        pilih.dispatchEvent(new Event('change', { bubbles: true }));
        pilih.scrollIntoView({ behavior: 'smooth', block: 'center' });
        pilih.focus({ preventScroll: true });
    });

    pinjam.addEventListener('change', selaraskan);
    selaraskan();

    // Info alat terpilih + batas jumlah mengikuti stok
    const perbarui = () => {
        const opt = pilih.selectedOptions[0];
        if (!opt || !opt.value) {
            kartu.hidden = true; kosong.hidden = false;
            jumlah.removeAttribute('max');
            hint.textContent = 'Pilih alat untuk melihat stok yang tersedia.';
            return;
        }
        const stok = parseInt(opt.dataset.stok, 10);
        kosong.hidden = true; kartu.hidden = false;
        $('pick-kat').dataset.kat = opt.dataset.kategori;
        $('pick-kat-teks').textContent = opt.dataset.kategoriLabel;
        $('pick-nama').textContent = opt.dataset.nama;
        $('pick-kode').textContent = opt.dataset.kode;
        $('pick-lokasi').textContent = opt.dataset.lokasi || '-';
        // Foto dan deskripsi alat (bila sudah diisi admin)
        const foto = $('pick-foto'), desk = $('pick-desk');
        if (opt.dataset.foto) { foto.src = opt.dataset.foto; foto.alt = 'Foto ' + opt.dataset.nama; foto.hidden = false; }
        else { foto.removeAttribute('src'); foto.hidden = true; }
        desk.textContent = opt.dataset.deskripsi || '';
        desk.hidden = !opt.dataset.deskripsi;
        $('pick-stok').textContent = stok > 0 ? stok + ' unit' : 'Habis';
        $('pick-stok').style.color = stok > 0 ? '' : 'var(--danger)';
        jumlah.max = Math.max(stok, 1);
        if (parseInt(jumlah.value, 10) > stok && stok > 0) jumlah.value = stok;
        hint.textContent = stok > 0 ? 'Maksimal ' + stok + ' unit.' : 'Stok sedang habis; pengajuan bisa ditolak petugas.';
    };
    pilih.addEventListener('change', perbarui);
    perbarui();

    // Kategori dari kartu di Beranda (?kategori=...): tampilkan hanya alat kategori itu
    const kat = new URLSearchParams(location.search).get('kategori');
    if (kat) {
        const cocok = [...pilih.options].filter((o) => o.value && o.dataset.kategori === kat);
        if (cocok.length) {
            [...pilih.options].forEach((o) => { if (o.value && o.dataset.kategori !== kat) { o.hidden = true; o.disabled = true; } });
            $('info-kat-teks').textContent = cocok[0].dataset.kategoriLabel;
            $('info-kat').hidden = false;
        }
    }

    // Penunjuk kelengkapan formulir
    const kolom = ['alat_id', 'tgl_pinjam', 'tgl_kembali', 'jumlah', 'keperluan'];
    const hitungIsi = () => {
        const n = kolom.filter((id) => String($(id).value).trim() !== '').length;
        $('fp-n').textContent = n;
        $('fp-bar').style.width = (n / kolom.length * 100) + '%';
    };
    kolom.forEach((id) => ['input', 'change'].forEach((ev) => $(id).addEventListener(ev, hitungIsi)));
    hitungIsi();

    // Tombol − dan +
    const ubah = (d) => {
        const maks = parseInt(jumlah.max, 10) || Infinity;
        jumlah.value = Math.min(maks, Math.max(1, (parseInt(jumlah.value, 10) || 1) + d));
    };
    $('kurang').addEventListener('click', () => ubah(-1));
    $('tambah').addEventListener('click', () => ubah(1));
})();
</script>
@endpush