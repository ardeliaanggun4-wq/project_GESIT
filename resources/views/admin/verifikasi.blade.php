{{-- Letak file: resources/views/admin/verifikasi.blade.php --}}
@extends('layouts.admin')
@section('title', 'Verifikasi')

@section('content')
    @php
        $kolom = [
            ['kunci' => 'menunggu',  'judul' => 'Menunggu',  'nada' => 'wait', 'ikon' => 'clock', 'kosong' => 'Tidak ada pengajuan yang menunggu.', 'sub' => 'Semua pengajuan sudah diproses.'],
            ['kunci' => 'disetujui', 'judul' => 'Disetujui', 'nada' => 'ok',   'ikon' => 'check', 'kosong' => 'Belum ada yang disetujui.',           'sub' => 'Pengajuan yang kamu setujui tampil di sini.'],
            ['kunci' => 'ditolak',   'judul' => 'Ditolak',   'nada' => 'no',   'ikon' => 'x',     'kosong' => 'Belum ada yang ditolak.',             'sub' => 'Pengajuan yang ditolak tampil di sini.'],
            ['kunci' => 'kewajiban', 'judul' => 'Kewajiban', 'nada' => 'no',   'ikon' => 'alert', 'kosong' => 'Tidak ada kewajiban yang tertunda.',  'sub' => 'Alat rusak atau hilang yang belum diganti tampil di sini.'],
        ];
    @endphp

    <section class="banner tone-wait rise mb-6">
        <div class="hero-dots" aria-hidden="true"></div>
        <div class="banner-body">
            <p class="eyebrow">Verifikasi</p>
            <h1 class="display mt-3">
                <span class="line"><span style="--i:0">Verifikasi <span class="hl">pengajuan</span></span></span>
            </h1>
            <p class="page-lead rise" style="--i:2">
                <span class="typed" data-type='["Setujui atau tolak permintaan pinjam dari siswa dan guru.","Cek stok alat sebelum menyetujui.","Pengajuan terbaru selalu ada di paling atas."]'>Setujui atau tolak permintaan pinjam dari siswa dan guru.</span>
            </p>
        </div>
        <x-banner-art ikon="verif" :mini="['clock', 'check', 'x']" />
    </section>

    <div class="stats stats-4 mb-8">
        @foreach ($kolom as $k)
            <div class="stat tone-{{ $k['nada'] }} {{ $k['nada'] === 'wait' && $jumlah['menunggu'] > 0 ? 'is-alert' : '' }} rise" style="--i: {{ $loop->index + 2 }}">
                <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-{{ $k['ikon'] }}"/></svg></span>
                <div class="stat-label">{{ $k['judul'] }}</div>
                <div class="stat-num" data-count="{{ $jumlah[$k['kunci']] }}">{{ $jumlah[$k['kunci']] }}</div>
            </div>
        @endforeach
    </div>

    @if ($errors->any())
        <div class="alert mb-6 rise" role="alert">
            <svg class="ico" aria-hidden="true"><use href="#i-alert"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    @if ($fokus)
        <div class="alert alert-info mb-6 rise" role="status">
            <svg class="ico" aria-hidden="true"><use href="#i-check"/></svg>
            <span>Menampilkan satu pengajuan: <b>{{ $fokus->kode_pengajuan }}</b>.
                <a href="{{ route('admin.verifikasi') }}" class="link">Tampilkan semua pengajuan</a></span>
        </div>
    @endif

    <nav class="mb-6 rise" style="--i:2" aria-label="Saring berdasarkan status">
        <div class="filters">
            @foreach ($kolom as $k)
                <a href="{{ route('admin.verifikasi', ['status' => $k['kunci']]) }}"
                   class="filter-chip {{ $filter === $k['kunci'] ? 'is-on' : '' }}"
                   @if ($filter === $k['kunci']) aria-current="page" @endif>
                    {{ $k['judul'] }} <span class="n">{{ $jumlah[$k['kunci']] }}</span>
                    @if ($k['kunci'] === 'disetujui' && $jumlah['minta_kembali'] > 0)
                        <span class="n" title="Siswa mengajukan pengembalian">{{ $jumlah['minta_kembali'] }} minta kembali</span>
                    @endif
                </a>
            @endforeach
        </div>
    </nav>

    <div>
        @foreach ($kolom as $k)
            @continue($k['kunci'] !== $filter)
            <section class="tone-{{ $k['nada'] }} rise" style="--i: 2">
                <div class="col-head">
                    <span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-{{ $k['ikon'] }}"/></svg></span>
                    <h2 class="t-sec">{{ $k['judul'] }}</h2>
                    <span class="count">{{ $jumlah[$k['kunci']] }}</span>
                </div>

                <div class="pj-grid">
                    @forelse ($daftar as $p)
                        <article class="pj" style="animation: riseIn .5s var(--ease) both; animation-delay: {{ 0.3 + $loop->index * 0.07 }}s; opacity: 0">
                            <div class="pj-top">
                                <span class="avatar" aria-hidden="true">{{ $p->user->initials() }}</span>
                                <div class="list-main">
                                    <div class="list-title">{{ $p->user->name }}</div>
                                    <div class="list-sub">{{ $p->user->kelas_jabatan ?: 'Peminjam' }}</div>
                                </div>
                                <span class="kode">#{{ $p->kode_pengajuan }}</span>
                            </div>

                            <div class="pj-meta">
                                <span><svg class="ico" aria-hidden="true"><use href="#i-box"/></svg>{{ $p->alat->nama }}</span>
                                <span><svg class="ico" aria-hidden="true"><use href="#i-layers"/></svg>{{ $p->jumlah }} unit</span>
                                @if ($p->tanggal_pinjam)
                                    <span><svg class="ico" aria-hidden="true"><use href="#i-calendar"/></svg>{{ $p->tanggal_pinjam->translatedFormat('j M') }}@if ($p->tanggal_kembali_rencana) – {{ $p->tanggal_kembali_rencana->translatedFormat('j M') }}@endif</span>
                                @endif
                                @if (in_array($p->status, ['dipinjam', 'disetujui'], true) && $p->tanggal_kembali_rencana->isBefore(today()))
                                    <span style="color:var(--danger);font-weight:600"><svg class="ico" aria-hidden="true"><use href="#i-alert"/></svg>Terlambat {{ $p->hariTerlambat() }} hari @if ($p->melewatiBatasTelat()) · melewati batas {{ \App\Models\Peminjaman::MAKS_HARI_TELAT }} hari @endif</span>
                                @endif
                            </div>

                            @if ($p->keperluan && $p->status === 'menunggu')
                                <p class="pj-note">{{ $p->keperluan }}</p>
                            @elseif ($p->status === 'ditolak' && $p->catatan_admin)
                                <p class="pj-note">{{ $p->catatan_admin }}</p>
                            @endif

                            @if ($p->status === 'menunggu')
                                <div class="pj-act">
                                    <form method="POST" action="{{ route('admin.verifikasi.approve', $p) }}"
                                          data-confirm data-confirm-tone="primary"
                                          data-confirm-title="Setujui pengajuan ini?"
                                          data-confirm-text="{{ $p->user->name }} meminjam {{ $p->jumlah }} unit {{ $p->alat->nama }}. Stok akan berkurang."
                                          data-confirm-ok="Ya, setujui">
                                        @csrf
                                        <button type="submit" class="btn btn-ok btn-sm"><svg class="ico ico-sm" aria-hidden="true"><use href="#i-check"/></svg>Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.verifikasi.reject', $p) }}"
                                          data-confirm
                                          data-confirm-title="Tolak pengajuan ini?"
                                          data-confirm-text="Pengajuan {{ $p->kode_pengajuan }} dari {{ $p->user->name }} akan ditolak."
                                          data-confirm-ok="Ya, tolak">
                                        @csrf
                                        <input type="hidden" name="catatan_admin" value="Ditolak oleh petugas GESIT">
                                        <button type="submit" class="btn btn-secondary btn-sm"><svg class="ico ico-sm" aria-hidden="true"><use href="#i-x"/></svg>Tolak</button>
                                    </form>
                                </div>
                            @elseif (in_array($p->status, ['dipinjam', 'disetujui'], true))
                                <div class="mt-4"><x-status :nilai="$p->status" /></div>
                                @if ($p->pengembalian_diajukan_at)
                                    <p class="pj-note">
                                        <b>Siswa mengajukan pengembalian</b> {{ $p->pengembalian_diajukan_at->translatedFormat('j M, H:i') }}.
                                        Cek fisik alat sebelum menandai dikembalikan. Unit rusak atau hilang wajib diganti peminjam, keterlambatan dicatat otomatis.
                                        @if ($p->catatan_pengembalian)<br>Catatan siswa: {{ $p->catatan_pengembalian }}@endif
                                    </p>
                                @endif
                                <form method="POST" action="{{ route('admin.verifikasi.kembali', $p) }}"
                                      class="pj-act form-kembali"
                                      style="flex-direction:column"
                                      data-confirm data-confirm-tone="primary"
                                      data-confirm-title="Tandai sudah dikembalikan?"
                                      data-confirm-text="{{ $p->user->name }} mengembalikan {{ $p->jumlah }} unit {{ $p->alat->nama }}. Stok bertambah sesuai unit yang kembali dalam kondisi baik."
                                      data-confirm-ok="Ya, sudah kembali">
                                    @csrf
                                    <select name="kondisi_kembali" class="field" aria-label="Kondisi alat saat dikembalikan" data-kondisi>
                                        <option value="baik">Kembali dalam kondisi baik</option>
                                        <option value="rusak">Ada unit yang rusak</option>
                                        <option value="hilang">Ada unit yang hilang</option>
                                    </select>
                                    <input type="number" name="unit_bermasalah" class="field" min="1" max="{{ $p->jumlah }}"
                                           inputmode="numeric" placeholder="Berapa unit yang rusak / hilang? (maks {{ $p->jumlah }})"
                                           aria-label="Jumlah unit rusak atau hilang" data-unit style="display:none">
                                    <input type="text" name="catatan_kembali" class="field" maxlength="255"
                                           placeholder="Catatan petugas (opsional)" aria-label="Catatan petugas saat alat dikembalikan">
                                    <button type="submit" class="btn btn-primary btn-sm"><svg class="ico ico-sm" aria-hidden="true"><use href="#i-check"/></svg>Dikembalikan</button>
                                </form>
                            @else
                                <div class="mt-4"><x-status :nilai="$p->status" /></div>
                                @if ($p->kondisi_kembali)
                                    <p class="pj-note">
                                        <b>{{ $p->kondisiKembaliLabel() }}</b>@if ($p->catatan_kembali) · {{ $p->catatan_kembali }}@endif
                                    </p>
                                @endif
                                @if ($p->sanksi)
                                    <p class="pj-note"><b>Ketentuan:</b> {{ $p->sanksi }}</p>
                                @endif
                                @if ($p->punyaKewajiban())
                                    <form method="POST" action="{{ route('admin.verifikasi.kewajiban', $p) }}" class="pj-act"
                                          data-confirm data-confirm-tone="primary"
                                          data-confirm-title="Kewajiban sudah diselesaikan?"
                                          data-confirm-text="{{ $p->user->name }} sudah memperbaiki atau mengganti alat. Setelah ini ia bisa mengajukan peminjaman baru."
                                          data-confirm-ok="Ya, sudah selesai">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm"><svg class="ico ico-sm" aria-hidden="true"><use href="#i-check"/></svg>Kewajiban selesai</button>
                                    </form>
                                @elseif ($p->kewajiban_selesai_at)
                                    <p class="pj-note">Kewajiban diselesaikan {{ $p->kewajiban_selesai_at->translatedFormat('j M Y') }}.</p>
                                @endif
                            @endif
                            @if (in_array($p->status, ['disetujui', 'dipinjam'], true))
                                <div class="pj-act">
                                    <a href="{{ route('bukti-pinjam', $p) }}" target="_blank" rel="noopener" class="btn btn-secondary btn-sm">
                                        <svg class="ico ico-sm" aria-hidden="true"><use href="#i-book"/></svg>Bukti pinjam (PDF)
                                    </a>
                                </div>
                            @endif
                            @if ($p->petugas || $p->penerima)
                                <p class="pj-note">
                                    @if ($p->petugas)Diproses oleh <b>{{ $p->petugas->name }}</b>@if ($p->diproses_at), {{ $p->diproses_at->translatedFormat('j M H:i') }}@endif.@endif
                                    @if ($p->penerima) Alat diterima oleh <b>{{ $p->penerima->name }}</b>@if ($p->diterima_at), {{ $p->diterima_at->translatedFormat('j M H:i') }}@endif.@endif
                                </p>
                            @endif
                        </article>
                    @empty
                        <div class="empty card">
                            <span class="empty-ico"><svg class="ico" aria-hidden="true"><use href="#i-{{ $k['ikon'] }}"/></svg></span>
                            <h3>{{ $k['kosong'] }}</h3>
                            <p class="t-small">{{ $k['sub'] }}</p>
                        </div>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>

    <section class="card card-pad card-accent mt-8 rise" style="--i:6">
        <div class="attn">
            <span class="row-ico tone-primary"><svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg></span>
            <h2 class="t-card">Sebelum memutuskan</h2>
        </div>
        <ul class="check-list">
            <li><svg class="ico" aria-hidden="true"><use href="#i-check"/></svg><span>Periksa stok alat. Stok berkurang otomatis saat pengajuan disetujui.</span></li>
            <li><svg class="ico" aria-hidden="true"><use href="#i-check"/></svg><span>Baca keperluan dan rentang tanggal pinjam di setiap kartu.</span></li>
            <li><svg class="ico" aria-hidden="true"><use href="#i-check"/></svg><span>Pengajuan yang ditolak tetap terlihat oleh peminjam di riwayatnya, lengkap dengan alasannya.</span></li>
            <li><svg class="ico" aria-hidden="true"><use href="#i-check"/></svg><span>Setelah alat kembali, tandai lewat kartu di tab Disetujui agar stok bertambah lagi. Kartu yang siswanya sudah mengajukan pengembalian tampil paling atas.</span></li>
        </ul>
    </section>
@endsection

@push('scripts')
<script>
(function () {
    // Kolom "jumlah unit" hanya muncul bila ada yang rusak atau hilang
    document.querySelectorAll('.form-kembali').forEach(function (form) {
        var pilih = form.querySelector('[data-kondisi]');
        var unit = form.querySelector('[data-unit]');
        if (!pilih || !unit) return;
        pilih.addEventListener('change', function () {
            var bermasalah = pilih.value !== 'baik';
            unit.style.display = bermasalah ? '' : 'none';
            unit.required = bermasalah;
            if (!bermasalah) unit.value = '';
            else unit.focus();
        });
    });
})();
</script>
@endpushphp artisan migrate