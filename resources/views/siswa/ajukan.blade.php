{{-- Letak file: resources/views/siswa/ajukan.blade.php --}}
@extends('layouts.siswa')
@section('title', 'Ajukan Pinjam')

@section('content')
    @php
        $kategoriLabel = ['praktik_lab' => 'Alat Praktik Lab', 'olahraga' => 'Alat Olahraga', 'kesenian' => 'Alat Kesenian', 'multimedia' => 'Multimedia'];
    @endphp

    <div class="mb-8">
        <p class="eyebrow rise">Ajukan pinjam</p>
        <h1 class="display mt-3">
            <span class="line"><span style="--i:0">Formulir pengajuan</span></span>
            <span class="line"><span style="--i:1" class="hl">peminjaman</span></span>
        </h1>
        <p class="page-lead rise" style="--i:3">
            <span class="typed" data-type='["Isi selengkap mungkin agar petugas Sarpras bisa memverifikasi lebih cepat.","Pilih alat dulu, lalu cek stok yang tersedia.","Sebutkan keperluan agar pengajuanmu cepat disetujui."]'>Isi selengkap mungkin agar petugas Sarpras bisa memverifikasi lebih cepat.</span>
        </p>
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
        <form method="POST" action="{{ route('siswa.ajukan.store') }}" class="card rise" style="--i:2">
            @csrf
            <div class="form-head">
                <span class="row-ico tone-primary"><svg class="ico" aria-hidden="true"><use href="#i-send"/></svg></span>
                <div>
                    <div class="t-card">Peminjaman alat</div>
                    <div class="t-xs t-muted">Semua kolom bertanda nomor wajib diisi</div>
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

            <section class="card card-pad rise" style="--i:4">
                <div class="attn" style="margin-bottom:var(--s-4)">
                    <span class="row-ico tone-wait"><svg class="ico" aria-hidden="true"><use href="#i-bulb"/></svg></span>
                    <h2 class="t-card">Setelah mengirim</h2>
                </div>
                <ol class="steps">
                    <li><div><b>Menunggu verifikasi</b><span>Status bisa kamu pantau di Beranda.</span></div></li>
                    <li><div><b>Disetujui</b><span>Ambil alat di ruang Sarpras.</span></div></li>
                    <li><div><b>Kembalikan</b><span>Sesuai tanggal rencana kembali.</span></div></li>
                </ol>
            </section>
        </aside>
    </div>
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
    };
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