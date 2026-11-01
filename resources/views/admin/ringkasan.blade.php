{{-- Letak file: resources/views/admin/ringkasan.blade.php --}}
@extends('layouts.admin')
@section('title', 'Ringkasan')

@section('content')
    @php
        $nadaStatus = ['green' => 'ok', 'yellow' => 'wait', 'red' => 'no'];
        $totalGrafik = $grafik->sum('total');
        $puncak = $grafik->max('total');
        $maks = max(1, $puncak);
        $totalKondisi = max(1, $kondisi->sum());
        $segmen = [
            ['baik', 'Baik', '#2F8A52'],
            ['perlu_cek', 'Perlu cek', '#C8961E'],
            ['stok_habis', 'Stok habis', '#8A857B'],
            ['rusak', 'Rusak', 'var(--danger)'],
        ];
    @endphp

    {{-- Kotak sambutan --}}
    <section class="hero rise mb-6">
        <div class="hero-dots" aria-hidden="true"></div>
        @include('partials.hero art')
        <div class="hero-body">
            <p class="eyebrow">{{ now()->translatedFormat('l, j F Y') }}</p>
            <h1 class="display mt-3">
                <span class="line"><span style="--i:0"><span data-greet>Selamat datang</span>,</span></span>
                <span class="line"><span style="--i:1" class="hl">Petugas Sarpras.</span></span>
            </h1>
            <p class="page-lead rise" style="--i:3">
                <span class="typed" data-type='["Pantau kondisi dan aktivitas peminjaman alat hari ini.","Ada {{ $stats['menunggu'] }} pengajuan menunggu keputusanmu.","Alat rusak dan stok kosong tampil di panel perhatian."]'>Pantau kondisi dan aktivitas peminjaman alat hari ini.</span>
            </p>
            <div class="hero-actions rise" style="--i:4">
                <a href="{{ route('admin.verifikasi') }}" class="btn btn-primary">
                    <svg class="ico" aria-hidden="true"><use href="#i-verif"/></svg>Verifikasi pengajuan
                </a>
                <a href="{{ route('admin.data-alat') }}" class="btn btn-secondary">
                    <svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg>Kelola data alat
                </a>
            </div>
        </div>
    </section>

    {{-- Angka utama --}}
    <div class="stats stats-4 mb-8">
        <div class="stat tone-primary rise" style="--i:2">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-box"/></svg></span>
            <div class="stat-label">Total alat terdaftar</div>
            <div class="stat-num" data-count="{{ $stats['total_alat'] }}">{{ $stats['total_alat'] }}</div>
            <div class="stat-note">Semua kategori</div>
        </div>
        <a href="{{ route('admin.verifikasi') }}" class="stat tone-wait {{ $stats['menunggu'] > 0 ? 'is-alert' : '' }} rise" style="--i:3">
            <svg class="ico stat-go" aria-hidden="true"><use href="#i-arrow"/></svg>
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-clock"/></svg></span>
            <div class="stat-label">Menunggu verifikasi</div>
            <div class="stat-num" data-count="{{ $stats['menunggu'] }}">{{ $stats['menunggu'] }}</div>
            <div class="stat-note">{{ $stats['menunggu'] > 0 ? 'Perlu keputusanmu' : 'Antrean kosong' }}</div>
        </a>
        <div class="stat tone-ok rise" style="--i:4">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-loan"/></svg></span>
            <div class="stat-label">Sedang dipinjam</div>
            <div class="stat-num" data-count="{{ $stats['sedang_dipinjam'] }}">{{ $stats['sedang_dipinjam'] }}</div>
            <div class="stat-note">Belum dikembalikan</div>
        </div>
        <a href="{{ route('admin.data-alat', ['kondisi' => 'rusak']) }}" class="stat tone-no {{ $stats['rusak_kosong'] > 0 ? 'is-alert' : '' }} rise" style="--i:5">
            <svg class="ico stat-go" aria-hidden="true"><use href="#i-arrow"/></svg>
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-wrench"/></svg></span>
            <div class="stat-label">Rusak / stok kosong</div>
            <div class="stat-num" data-count="{{ $stats['rusak_kosong'] }}">{{ $stats['rusak_kosong'] }}</div>
            <div class="stat-note">Perlu ditindaklanjuti</div>
        </a>
    </div>

    {{-- Grafik & kategori --}}
    <div class="grid lg:grid-cols-3 gap-6 mb-8">
        <section class="card card-pad lg:col-span-2 rise" style="--i:5">
            <div class="sec-head">
                <h2 class="t-sec">Peminjaman 7 hari terakhir</h2>
            </div>
            <div class="chart-sum">
                <span><b class="num">{{ $totalGrafik }}</b> pengajuan</span>
                <span>rata-rata <b class="num">{{ number_format($totalGrafik / 7, 1, ',', '.') }}</b> per hari</span>
            </div>
            <div class="bars" role="img" aria-label="Grafik batang jumlah pengajuan tujuh hari terakhir">
                @foreach ($grafik as $g)
                    <div class="bar-col">
                        <div class="bar-area">
                            <div class="bar {{ $g['total'] > 0 && $g['total'] == $puncak ? 'is-peak' : '' }}"
                                 style="height: {{ max(6, ($g['total'] / $maks) * 100) }}%; --i: {{ $loop->index }}">
                                <span class="bar-v">{{ $g['total'] }}</span>
                            </div>
                        </div>
                        <div class="bar-l">{{ $g['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="card card-pad rise" style="--i:6">
            <div class="sec-head"><h2 class="t-sec">Kategori paling sering dipinjam</h2></div>
            <div class="space-y-5 mt-4">
                @forelse ($kategori as $k)
                    <div data-kat="{{ $k->kategori }}">
                        <div class="flex items-center justify-between gap-3 text-sm mb-1.5">
                            <x-kategori :nilai="$k->kategori" />
                            <span class="num font-semibold">{{ $k->total }}x</span>
                        </div>
                        <div class="track">
                            <div class="fill" style="width: {{ ($k->total / $maxKategori) * 100 }}%; --i: {{ $loop->index }}"></div>
                        </div>
                    </div>
                @empty
                    <div class="empty">
                        <span class="empty-ico"><svg class="ico" aria-hidden="true"><use href="#i-chart"/></svg></span>
                        <p>Belum ada data peminjaman.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Kondisi alat & perhatian --}}
    <div class="grid lg:grid-cols-2 gap-6 mb-8">
        <section class="card card-pad rise" style="--i:6">
            <div class="sec-head"><h2 class="t-sec">Kondisi seluruh alat</h2></div>
            <div class="stackbar mt-4" role="img" aria-label="Perbandingan kondisi alat">
                @foreach ($segmen as [$kunci, $nama, $warna])
                    @if ($kondisi->get($kunci, 0) > 0)
                        <i style="width: {{ ($kondisi->get($kunci, 0) / $totalKondisi) * 100 }}%; background: {{ $warna }}; --i: {{ $loop->index }}"></i>
                    @endif
                @endforeach
            </div>
            <ul class="legend">
                @foreach ($segmen as [$kunci, $nama, $warna])
                    <li><span class="sw" style="background: {{ $warna }}"></span>{{ $nama }}<b>{{ $kondisi->get($kunci, 0) }}</b></li>
                @endforeach
            </ul>
        </section>

        <section class="card overflow-hidden rise" style="--i:7">
            <div class="sec-head px-5 pt-5 mb-0">
                <h2 class="t-sec">Perlu perhatian</h2>
                @if ($totalPerhatian > 5)
                    <a href="{{ route('admin.data-alat') }}" class="link t-small">Lihat semua ({{ $totalPerhatian }})</a>
                @endif
            </div>
            <div class="mt-3">
                @forelse ($perhatian as $a)
                    @php $nada = in_array($a->kondisi, ['rusak', 'stok_habis']) ? 'no' : 'wait'; @endphp
                    <div class="list-row tone-{{ $nada }}" style="--i: {{ $loop->index }}">
                        <span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-{{ $a->kondisi === 'rusak' ? 'wrench' : ($a->kondisi === 'stok_habis' ? 'minus' : 'alert') }}"/></svg></span>
                        <div class="list-main">
                            <div class="list-title">{{ $a->nama }}</div>
                            <div class="list-sub"><span class="kode">{{ $a->kode }}</span> · sisa {{ $a->stok }} unit</div>
                        </div>
                        <x-status :nilai="$a->kondisi" />
                    </div>
                @empty
                    <div class="empty">
                        <span class="empty-ico tone-ok" style="background:var(--t-bg);color:var(--t-fg)"><svg class="ico" aria-hidden="true"><use href="#i-check"/></svg></span>
                        <h3>Semua alat dalam kondisi baik</h3>
                        <p>Tidak ada alat rusak, habis, atau perlu dicek.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Aktivitas --}}
    <div class="sec-head"><h2 class="t-sec">Aktivitas terbaru</h2></div>
    <div class="card overflow-hidden rise" style="--i:7">
        @forelse ($aktivitas as $a)
            @php $warna = $a->statusColor(); @endphp
            <div class="list-row tone-{{ $nadaStatus[$warna] ?? 'idle' }}" style="--i: {{ $loop->index }}">
                <span class="avatar" aria-hidden="true">{{ $a->user->initials() }}</span>
                <div class="list-main">
                    <div class="list-title">{{ $a->user->name }}@if($a->user->kelas_jabatan) <span class="t-muted" style="font-weight:400">· {{ $a->user->kelas_jabatan }}</span>@endif</div>
                    <div class="list-sub">
                        @if($a->status === 'menunggu') Mengajukan peminjaman {{ $a->alat->nama }}
                        @elseif($a->status === 'ditolak') Pengajuan {{ $a->alat->nama }} ditolak @if($a->catatan_admin) — {{ $a->catatan_admin }} @endif
                        @else Pengajuan {{ $a->alat->nama }} disetujui oleh Petugas Sarpras @endif
                        · {{ $a->updated_at->diffForHumans() }}
                    </div>
                </div>
                <x-status :nilai="$a->status" />
            </div>
        @empty
            <div class="empty">
                <span class="empty-ico"><svg class="ico" aria-hidden="true"><use href="#i-clock"/></svg></span>
                <h3>Belum ada aktivitas</h3>
                <p>Pengajuan pinjam dari siswa dan guru akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
@endsection