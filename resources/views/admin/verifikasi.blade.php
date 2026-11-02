{{-- Letak file: resources/views/admin/verifikasi.blade.php --}}
@extends('layouts.admin')
@section('title', 'Verifikasi')

@section('content')
    @php
        $kolom = [
            ['judul' => 'Menunggu',  'nada' => 'wait', 'ikon' => 'clock', 'data' => $menunggu,  'kosong' => 'Tidak ada pengajuan yang menunggu.', 'sub' => 'Semua pengajuan sudah diproses.'],
            ['judul' => 'Disetujui', 'nada' => 'ok',   'ikon' => 'check', 'data' => $disetujui, 'kosong' => 'Belum ada yang disetujui.',           'sub' => 'Pengajuan yang kamu setujui tampil di sini.'],
            ['judul' => 'Ditolak',   'nada' => 'no',   'ikon' => 'x',     'data' => $ditolak,   'kosong' => 'Belum ada yang ditolak.',             'sub' => 'Pengajuan yang ditolak tampil di sini.'],
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
            <div class="stat tone-{{ $k['nada'] }} {{ $k['nada'] === 'wait' && $k['data']->count() > 0 ? 'is-alert' : '' }} rise" style="--i: {{ $loop->index + 2 }}">
                <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-{{ $k['ikon'] }}"/></svg></span>
                <div class="stat-label">{{ $k['judul'] }}</div>
                <div class="stat-num" data-count="{{ $k['data']->count() }}">{{ $k['data']->count() }}</div>
            </div>
        @endforeach
    </div>

    @if ($errors->any())
        <div class="alert mb-6 rise" role="alert">
            <svg class="ico" aria-hidden="true"><use href="#i-alert"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="cols-3">
        @foreach ($kolom as $k)
            <section class="tone-{{ $k['nada'] }} rise" style="--i: {{ $loop->index + 2 }}">
                <div class="col-head">
                    <span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-{{ $k['ikon'] }}"/></svg></span>
                    <h2 class="t-sec">{{ $k['judul'] }}</h2>
                    <span class="count">{{ $k['data']->count() }}</span>
                </div>

                <div class="space-y-4">
                    @forelse ($k['data'] as $p)
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
                            @else
                                <div class="mt-4"><x-status :nilai="$p->status" /></div>
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
            <li><svg class="ico" aria-hidden="true"><use href="#i-check"/></svg><span>Pengajuan yang ditolak tetap terlihat oleh peminjam di riwayatnya.</span></li>
        </ul>
    </section>
@endsection