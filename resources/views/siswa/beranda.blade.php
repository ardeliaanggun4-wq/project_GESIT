{{-- Letak file: resources/views/siswa/beranda.blade.php --}}
@extends('layouts.siswa')
@section('title', 'Beranda')

@section('content')
    @php
        $nadaStatus = ['green' => 'ok', 'yellow' => 'wait', 'red' => 'no'];
        $namaDepan = explode(' ', trim($user->name))[0];
    @endphp

    {{-- Kotak sambutan --}}
    <section class="hero rise mb-6">
        <div class="hero-dots" aria-hidden="true"></div>
        @include('partials.hero art')
        <div class="hero-body">
            <p class="eyebrow">{{ now()->translatedFormat('l, j F Y') }}</p>
            <h1 class="display mt-3">
                <span class="line"><span style="--i:0"><span data-greet>Halo</span>,</span></span>
                <span class="line"><span style="--i:1" class="hl">{{ $namaDepan }}.</span></span>
            </h1>
            <p class="page-lead rise" style="--i:3">
                <span class="typed" data-type='["Berikut status peminjaman alat kamu saat ini.","Butuh alat praktik? Ajukan dari HP dalam semenit.","Ambil alat di ruang Sarpras setelah disetujui."]'>Berikut status peminjaman alat kamu saat ini.</span>
            </p>
            <div class="hero-actions rise" style="--i:4">
                <a href="{{ route('siswa.ajukan') }}" class="btn btn-primary">
                    <svg class="ico" aria-hidden="true"><use href="#i-plus"/></svg>Ajukan peminjaman
                </a>
            </div>
        </div>
    </section>

    @if ($stats['jatuh_tempo_hari_ini'] > 0)
        <div class="alert alert-info mb-6 rise" style="--i:2" role="status">
            <svg class="ico" aria-hidden="true"><use href="#i-bell"/></svg>
            <span><b>{{ $stats['jatuh_tempo_hari_ini'] }} alat jatuh tempo hari ini.</b> Kembalikan ke ruang Sarpras sebelum jam pulang sekolah.</span>
        </div>
    @endif

    {{-- Angka utama --}}
    <div class="stats stats-3 mb-8">
        <div class="stat tone-ok rise" style="--i:2">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-loan"/></svg></span>
            <div class="stat-label">Sedang dipinjam</div>
            <div class="stat-num" data-count="{{ $stats['sedang_dipinjam'] }}">{{ $stats['sedang_dipinjam'] }}</div>
        </div>
        <div class="stat tone-wait rise" style="--i:3">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-clock"/></svg></span>
            <div class="stat-label">Menunggu verifikasi</div>
            <div class="stat-num" data-count="{{ $stats['menunggu'] }}">{{ $stats['menunggu'] }}</div>
        </div>
        <div class="stat tone-no {{ $stats['jatuh_tempo_hari_ini'] > 0 ? 'is-alert' : '' }} rise" style="--i:4">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-bell"/></svg></span>
            <div class="stat-label">Jatuh tempo hari ini</div>
            <div class="stat-num" data-count="{{ $stats['jatuh_tempo_hari_ini'] }}">{{ $stats['jatuh_tempo_hari_ini'] }}</div>
        </div>
    </div>

    {{-- Jelajahi kategori --}}
    @php
        $tiles = ['komputer' => 'Komputer', 'elektronika' => 'Elektronika', 'perkakas' => 'Perkakas', 'multimedia' => 'Multimedia'];
    @endphp
    <section class="mb-8">
        <div class="sec-head">
            <h2 class="t-sec">Jelajahi kategori alat</h2>
            <a href="{{ route('siswa.ajukan') }}" class="link t-small">Ajukan pinjam</a>
        </div>
        <div class="kat-grid">
            @foreach ($tiles as $kode => $nama)
                @php $info = $kategoriAlat->get($kode); @endphp
                <a href="{{ route('siswa.ajukan', ['kategori' => $kode]) }}" class="kat-tile rise" data-kat="{{ $kode }}" style="--i: {{ $loop->index + 2 }}">
                    <x-kat-ico :nilai="$kode" class="lg" />
                    <svg class="ico arrow" aria-hidden="true"><use href="#i-chev-right"/></svg>
                    <b>{{ $nama }}</b>
                    <small>{{ $info?->jenis ?? 0 }} jenis · {{ $info?->unit ?? 0 }} unit</small>
                </a>
            @endforeach
        </div>
    </section>

    <div class="tipbar rise mb-8" style="--i:6">
        <svg class="ico" aria-hidden="true"><use href="#i-bulb"/></svg>
        <span><b>Tips:</b> <span class="tip-rot" data-rotate='["Ajukan pinjam langsung dari HP, tidak perlu antre.","Pantau status pengajuanmu di riwayat peminjaman.","Sebutkan keperluan agar pengajuan cepat diverifikasi.","Kembalikan alat sebelum tanggal rencana kembali."]'>Ajukan pinjam langsung dari HP, tidak perlu antre.</span></span>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 items-start">
        {{-- Riwayat --}}
        <section class="lg:col-span-2">
            <div class="sec-head">
                <h2 class="t-sec">Riwayat peminjaman</h2>
                <a href="{{ route('siswa.riwayat') }}" class="link t-small">Lihat semua</a>
            </div>
            <div class="card overflow-hidden rise" style="--i:5">
                @forelse ($riwayat as $item)
                    <div class="list-row tone-{{ $nadaStatus[$item->statusColor()] ?? 'idle' }}" style="--i: {{ $loop->index }}">
                        <div class="date-tile" aria-hidden="true">
                            <b>{{ $item->tanggal_pinjam->translatedFormat('d') }}</b>
                            <small>{{ $item->tanggal_pinjam->translatedFormat('M') }}</small>
                        </div>
                        <div class="list-main">
                            <div class="list-title">{{ $item->alat->nama }}</div>
                            <div class="list-sub"><span class="kode">{{ $item->alat->kode }}</span> · {{ $item->jumlah }} unit @if ($item->keperluan) · {{ $item->keperluan }} @endif @if ($item->status === 'ditolak' && $item->catatan_admin) · Alasan: {{ $item->catatan_admin }} @endif</div>
                        </div>
                        <x-status :nilai="$item->status" />
                    </div>
                @empty
                    <div class="empty">
                        <span class="empty-ico"><svg class="ico" aria-hidden="true"><use href="#i-book"/></svg></span>
                        <h3>Belum ada riwayat peminjaman</h3>
                        <p>Pengajuan pertamamu akan muncul di sini lengkap dengan statusnya.</p>
                        <a href="{{ route('siswa.ajukan') }}" class="btn btn-primary" style="margin-top:.6rem">Ajukan sekarang</a>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Alur pinjam --}}
        <aside class="card card-pad card-accent rise" style="--i:6">
            <div class="sec-head"><h2 class="t-sec">Alur peminjaman</h2></div>
            <ol class="steps mt-4">
                <li><div><b>Ajukan</b><span>Pilih alat, tanggal, dan isi keperluan.</span></div></li>
                <li><div><b>Tunggu verifikasi</b><span>Petugas GESIT memeriksa stok dan pengajuanmu.</span></div></li>
                <li><div><b>Ambil alat</b><span>Datang ke ruang Sarpras setelah disetujui.</span></div></li>
                <li><div><b>Kembalikan tepat waktu</b><span>Pastikan alat utuh sebelum tanggal rencana kembali.</span></div></li>
            </ol>
        </aside>
    </div>
@endsection