{{-- Letak file: resources/views/siswa/beranda.blade.php (timpa file lama) --}}
@extends('layouts.siswa')
@section('title', 'Beranda')

@section('content')
    @php $statusMap = ['green' => 'ok', 'yellow' => 'wait', 'red' => 'no']; @endphp

    <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
        <div>
            <p class="mono-label rise">Beranda</p>
            <h1 class="display mt-3">
                <span class="line"><span style="--i:0">Halo, <span class="hl">{{ explode(' ', $user->name)[0] }}</span>.</span></span>
            </h1>
            <p class="soft text-sm mt-4 rise" style="--i:3">Berikut status peminjaman alat kamu saat ini.</p>
        </div>
        <a href="{{ route('siswa.ajukan') }}" class="btn btn-primary rise" style="--i:2">+ Ajukan peminjaman</a>
    </div>

    {{-- Angka utama --}}
    <div class="ledger grid-cols-1 sm:grid-cols-3 mb-10 rise" style="--i:2">
        <div>
            <div class="mono-label flex items-center gap-2"><i class="dot" style="background:var(--olive)"></i>Sedang dipinjam</div>
            <div class="num mt-4" data-count="{{ $stats['sedang_dipinjam'] }}">{{ $stats['sedang_dipinjam'] }}</div>
        </div>
        <div>
            <div class="mono-label flex items-center gap-2"><i class="dot" style="background:var(--mustard)"></i>Menunggu verifikasi</div>
            <div class="num mt-4" data-count="{{ $stats['menunggu'] }}">{{ $stats['menunggu'] }}</div>
        </div>
        <div>
            <div class="mono-label flex items-center gap-2"><i class="dot" style="background:var(--signal)"></i>Jatuh tempo hari ini</div>
            <div class="num mt-4" data-count="{{ $stats['jatuh_tempo_hari_ini'] }}">{{ $stats['jatuh_tempo_hari_ini'] }}</div>
        </div>
    </div>

    <h2 class="h-sec mb-3">Riwayat peminjaman</h2>
    <div class="card overflow-hidden rise" style="--i:3">
        @forelse ($riwayat as $item)
            <div class="list-row">
                <div class="flex items-center gap-4">
                    <div class="date-tile">
                        <b>{{ $item->tanggal_pinjam->translatedFormat('d') }}</b>
                        <small>{{ $item->tanggal_pinjam->translatedFormat('M') }}</small>
                    </div>
                    <div>
                        <div class="font-semibold">{{ $item->alat->nama }}</div>
                        <div class="text-xs soft mt-0.5"><span class="kode">{{ $item->alat->kode }}</span> · {{ $item->keperluan }}</div>
                    </div>
                </div>
                <span class="stamp stamp-{{ $statusMap[$item->statusColor()] ?? 'idle' }}" style="--i: {{ $loop->index }}">
                    {{ $item->statusLabel() }}
                </span>
            </div>
        @empty
            <div class="empty">Belum ada riwayat peminjaman.</div>
        @endforelse
    </div>
@endsection