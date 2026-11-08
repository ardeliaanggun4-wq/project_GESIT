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
            ['stok_habis', 'Stok habis', '#8A857B'],
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
                <span class="line"><span style="--i:1" class="hl">Petugas GESIT.</span></span>
            </h1>
            <p class="page-lead rise" style="--i:3">
                <span class="typed" data-type='["Pantau kondisi dan aktivitas peminjaman alat hari ini.","Ada {{ $stats['menunggu'] }} pengajuan menunggu keputusanmu.","Alat yang stoknya habis tampil di panel perhatian."]'>Pantau kondisi dan aktivitas peminjaman alat hari ini.</span>
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
        <a href="{{ route('admin.data-alat', ['kondisi' => 'stok_habis']) }}" class="stat tone-no {{ $stats['stok_habis'] > 0 ? 'is-alert' : '' }} rise" style="--i:5">
            <svg class="ico stat-go" aria-hidden="true"><use href="#i-arrow"/></svg>
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-wrench"/></svg></span>
            <div class="stat-label">Stok habis</div>
            <div class="stat-num" data-count="{{ $stats['stok_habis'] }}">{{ $stats['stok_habis'] }}</div>
            <div class="stat-note">Perlu ditindaklanjuti</div>
        </a>
    </div>

    {{-- Aksi cepat & tip --}}
    <div class="sec-head"><h2 class="t-sec">Aksi cepat</h2></div>
    <div class="qa-grid mb-6">
        <a href="{{ route('admin.verifikasi') }}" class="qa tone-wait rise" style="--i:6">
            <span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-verif"/></svg></span>
            <div><b>Verifikasi pengajuan</b><small>{{ $stats['menunggu'] > 0 ? $stats['menunggu'] . ' menunggu keputusan' : 'Antrean kosong' }}</small></div>
            <svg class="ico arrow" aria-hidden="true"><use href="#i-chev-right"/></svg>
        </a>
        <a href="{{ route('admin.data-alat') }}" class="qa tone-primary rise" style="--i:7">
            <span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg></span>
            <div><b>Kelola data alat</b><small>{{ $stats['total_alat'] }} alat terdaftar</small></div>
            <svg class="ico arrow" aria-hidden="true"><use href="#i-chev-right"/></svg>
        </a>
        <a href="{{ route('admin.data-alat', ['kondisi' => 'stok_habis']) }}" class="qa tone-no rise" style="--i:8">
            <span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-wrench"/></svg></span>
            <div><b>Cek alat stok habis</b><small>{{ $stats['stok_habis'] }} alat stok habis</small></div>
            <svg class="ico arrow" aria-hidden="true"><use href="#i-chev-right"/></svg>
        </a>
    </div>

    <div class="tipbar rise mb-8" style="--i:8">
        <svg class="ico" aria-hidden="true"><use href="#i-bulb"/></svg>
        <span><b>Tips:</b> <span class="tip-rot" data-rotate='["Kartu \"Menunggu verifikasi\" langsung membuka antrean pengajuan.","Stok alat otomatis berkurang saat pengajuan disetujui.","Perbarui kondisi alat setelah dikembalikan agar data tetap akurat.","Cari alat lewat nama atau kodenya di halaman Data Alat."]'>Kartu "Menunggu verifikasi" langsung membuka antrean pengajuan.</span></span>
    </div>

    {{-- Grafik & kategori --}}
    <div class="grid lg:grid-cols-3 gap-6 mb-8">
        <section class="card card-pad card-accent lg:col-span-2 rise" style="--i:5">
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

        <section class="card card-pad card-accent rise" style="--i:6">
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
        <section class="card card-pad card-accent rise" style="--i:6">
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
                    @php $nada = 'no'; @endphp
                    <div class="list-row tone-{{ $nada }}" style="--i: {{ $loop->index }}">
                        <span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-minus"/></svg></span>
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
                        <p>Tidak ada alat yang stoknya habis.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Terlambat dikembalikan --}}
    <div class="sec-head">
        <h2 class="t-sec">Terlambat dikembalikan</h2>
        @if ($totalTerlambat > 5)
            <a href="{{ route('admin.verifikasi', ['status' => 'disetujui']) }}" class="link t-small">Lihat semua ({{ $totalTerlambat }})</a>
        @endif
    </div>
    <div class="card overflow-hidden rise mb-8" style="--i:7">
        @forelse ($terlambat as $t)
            <div class="list-row tone-no" style="--i: {{ $loop->index }}">
                <x-avatar :user="$t->user" />
                <div class="list-main">
                    <div class="list-title">{{ $t->user->name }}@if($t->user->kelas_jabatan) <span class="t-muted" style="font-weight:400">· {{ $t->user->kelas_jabatan }}</span>@endif</div>
                    <div class="list-sub">
                        {{ $t->alat->nama }} · {{ $t->jumlah }} unit · terlambat <b>{{ $t->hariTerlambat() }} hari</b>
                        @if ($t->melewatiBatasTelat()) · melewati batas {{ \App\Models\Peminjaman::MAKS_HARI_TELAT }} hari @endif
                    </div>
                </div>
                <a href="{{ route('admin.scan', $t) }}" class="btn btn-secondary btn-sm">Proses</a>
            </div>
        @empty
            <div class="empty">
                <span class="empty-ico tone-ok" style="background:var(--t-bg);color:var(--t-fg)"><svg class="ico" aria-hidden="true"><use href="#i-check"/></svg></span>
                <h3>Tidak ada yang terlambat</h3>
                <p>Semua alat yang dipinjam masih dalam batas waktu.</p>
            </div>
        @endforelse
    </div>

    {{-- Aktivitas --}}
    <div class="sec-head"><h2 class="t-sec">Aktivitas terbaru</h2></div>
    <div class="card overflow-hidden rise" style="--i:7">
        @forelse ($aktivitas as $a)
            @php $warna = $a->statusColor(); @endphp
            <div class="list-row tone-{{ $nadaStatus[$warna] ?? 'idle' }}" style="--i: {{ $loop->index }}">
                <x-avatar :user="$a->user" />
                <div class="list-main">
                    <div class="list-title">{{ $a->user->name }}@if($a->user->kelas_jabatan) <span class="t-muted" style="font-weight:400">· {{ $a->user->kelas_jabatan }}</span>@endif</div>
                    <div class="list-sub">
                        @if($a->status === 'menunggu') Mengajukan peminjaman {{ $a->alat->nama }}
                        @elseif($a->status === 'ditolak') Pengajuan {{ $a->alat->nama }} ditolak @if($a->catatan_admin) — {{ $a->catatan_admin }} @endif
                        @else Pengajuan {{ $a->alat->nama }} disetujui oleh Petugas GESIT @endif
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