{{-- Letak file: resources/views/siswa/ajukan.blade.php --}}
@extends('layouts.siswa')
@section('title', 'Ajukan Pinjam')

@section('content')
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
                    <div id="pick-kat" class="chip-kat"><i class="dot" aria-hidden="true"></i><span id="pick-kat-teks"></span></div>
                    <div class="pick-name" id="pick-nama" style="margin-top:.7rem"></div>
                    <dl>
                        <div><dt>Kode</dt><dd><span class="kode" id="pick-kode"></span></dd></div>
                        <div><dt>Lokasi</dt><dd id="pick-lokasi"></dd></div>
                        <div><dt>Stok tersedia</dt><dd id="pick-stok"></dd></div>
                    </dl>
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
            <span class="t-small t-muted">{{ $tersedia }} dari {{ $daftarAlat->count() }} jenis alat tersedia · tekan alat untuk memilihnya</span>
        </div>
        <div class="card" style="overflow:hidden">
            @forelse ($daftarAlat as $alat)
                @php $habis = $alat->stok <= 0; @endphp
                <button type="button" class="list-row alat-pilih {{ $habis ? 'is-habis' : '' }}" data-pilih="{{ $alat->id }}"
                        @disabled($habis) style="width:100%;text-align:left;background:transparent;border-left:0;border-right:0;border-top:0;{{ $habis ? 'opacity:.55;cursor:not-allowed' : 'cursor:pointer' }}">
                    <x-kat-ico :nilai="$alat->kategori" />
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
    // Klik alat di daftar -> terpilih di formulir
    document.querySelectorAll('[data-pilih]').forEach((tombol) => {
        tombol.addEventListener('click', () => {
            pilih.value = tombol.dataset.pilih;
            pilih.dispatchEvent(new Event('change', { bubbles: true }));
            pilih.scrollIntoView({ behavior: 'smooth', block: 'center' });
            pilih.focus({ preventScroll: true });
        });
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