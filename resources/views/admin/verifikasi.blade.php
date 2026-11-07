{{-- Letak file: resources/views/admin/verifikasi.blade.php --}}
@extends('layouts.admin')
@section('title', 'Verifikasi')

@section('content')
    @php
        $kolom = [
            ['kunci' => 'menunggu',  'judul' => 'Menunggu',  'nada' => 'wait', 'ikon' => 'clock', 'kosong' => 'Tidak ada pengajuan yang menunggu.', 'sub' => 'Semua pengajuan sudah diproses.'],
            ['kunci' => 'disetujui', 'judul' => 'Disetujui', 'nada' => 'ok',   'ikon' => 'check', 'kosong' => 'Belum ada yang disetujui.',           'sub' => 'Pengajuan yang kamu setujui tampil di sini.'],
            ['kunci' => 'ditolak',   'judul' => 'Ditolak',   'nada' => 'no',   'ikon' => 'x',     'kosong' => 'Belum ada yang ditolak.',             'sub' => 'Pengajuan yang ditolak tampil di sini.'],
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

    <div class="stats stats-3 mb-8">
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
                                        <input type="hidden" name="catatan_admin" value="Ditolak oleh petugas Sarpras">
                                        <button type="submit" class="btn btn-secondary btn-sm"><svg class="ico ico-sm" aria-hidden="true"><use href="#i-x"/></svg>Tolak</button>
                                    </form>
                                </div>
                            @elseif (in_array($p->status, ['dipinjam', 'disetujui'], true))
                                <div class="mt-4"><x-status :nilai="$p->status" /></div>
                                @if ($p->pengembalian_diajukan_at)
                                    <p class="pj-note">
                                        <b>Siswa mengajukan pengembalian</b> {{ $p->pengembalian_diajukan_at->translatedFormat('j M, H:i') }}.
                                        Cek fisik alat sebelum menandai dikembalikan.
                                        @if ($p->catatan_pengembalian)<br>Catatan siswa: {{ $p->catatan_pengembalian }}@endif
                                    </p>
                                @endif
                                <form method="POST" action="{{ route('admin.verifikasi.kembali', $p) }}"
                                      class="pj-act"
                                      data-confirm data-confirm-tone="primary"
                                      data-confirm-title="Tandai sudah dikembalikan?"
                                      data-confirm-text="{{ $p->user->name }} mengembalikan {{ $p->jumlah }} unit {{ $p->alat->nama }}. Stok alat akan bertambah lagi."
                                      data-confirm-ok="Ya, sudah kembali">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm"><svg class="ico ico-sm" aria-hidden="true"><use href="#i-check"/></svg>Dikembalikan</button>
                                </form>
                            @else
                                <div class="mt-4"><x-status :nilai="$p->status" /></div>
                            @endif
                            @if (in_array($p->status, ['disetujui', 'dipinjam'], true))
                                <div class="pj-act">
                                    <a href="{{ route('bukti-pinjam', $p) }}" target="_blank" rel="noopener" class="btn btn-secondary btn-sm">
                                        <svg class="ico ico-sm" aria-hidden="true"><use href="#i-book"/></svg>Bukti pinjam (PDF)
                                    </a>
                                </div>
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