{{-- Letak file: resources/views/siswa/riwayat.blade.php --}}
@extends('layouts.siswa')
@section('title', 'Riwayat')

@section('content')
    @php
        $nadaStatus = ['green' => 'ok', 'yellow' => 'wait', 'red' => 'no', 'gray' => 'idle'];
        $tab = [
            [null, 'Semua', $jumlah['semua']],
            ['menunggu', 'Menunggu', $jumlah['menunggu']],
            ['dipinjam', 'Dipinjam', $jumlah['dipinjam']],
            ['ditolak', 'Ditolak', $jumlah['ditolak']],
            ['selesai', 'Selesai', $jumlah['selesai']],
        ];
        $kosong = [
            'menunggu' => 'Tidak ada pengajuan yang menunggu.',
            'dipinjam' => 'Tidak ada alat yang sedang kamu pinjam.',
            'ditolak' => 'Tidak ada pengajuan yang ditolak.',
            'selesai' => 'Belum ada peminjaman yang selesai.',
        ];
    @endphp

    <div class="mb-8">
        <p class="eyebrow rise">Riwayat</p>
        <h1 class="display mt-3">
            <span class="line"><span style="--i:0">Riwayat <span class="hl">peminjaman</span></span></span>
        </h1>
        <p class="page-lead rise" style="--i:2">Semua pengajuanmu beserta statusnya. Perubahan terbaru ditandai “Baru”.</p>
    </div>

    @if ($errors->any())
        <div class="alert mb-6 rise" role="alert">
            <svg class="ico" aria-hidden="true"><use href="#i-alert"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <nav class="mb-6 rise" style="--i:2" aria-label="Saring berdasarkan status">
        <div class="filters">
            @foreach ($tab as [$nilai, $nama, $n])
                <a href="{{ $nilai ? route('siswa.riwayat', ['status' => $nilai]) : route('siswa.riwayat') }}"
                   class="filter-chip {{ $filter === $nilai ? 'is-on' : '' }}"
                   @if ($filter === $nilai) aria-current="page" @endif>
                    {{ $nama }} <span class="n">{{ $n }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    <div class="pj-grid">
        @forelse ($daftar as $p)
            @php
                $selesai = $p->status === 'selesai';
                $aktif = in_array($p->status, ['dipinjam', 'disetujui'], true);
                $terlambat = $aktif && $p->tanggal_kembali_rencana->isBefore(today());
            @endphp
            <article class="pj tone-{{ $nadaStatus[$p->statusColor()] ?? 'idle' }} rise" style="--i: {{ min($loop->index, 6) }}">
                <div class="pj-top">
                    <div class="date-tile" aria-hidden="true">
                        <b>{{ $p->tanggal_pinjam->translatedFormat('d') }}</b>
                        <small>{{ $p->tanggal_pinjam->translatedFormat('M') }}</small>
                    </div>
                    <div class="list-main">
                        <div class="list-title">
                            {{ $p->alat->nama }}
                            @if (in_array($p->id, $baruIds, true))
                                <span class="status" data-status="menunggu" style="margin-left:.35rem;font-size:.75rem;padding:1px 8px"><svg class="ico" aria-hidden="true"><use href="#i-spark"/></svg>Baru</span>
                            @endif
                        </div>
                        <div class="list-sub"><span class="kode">#{{ $p->kode_pengajuan }}</span> · {{ $p->jumlah }} unit</div>
                    </div>
                    <x-status :nilai="$p->status" />
                </div>

                <div class="pj-meta">
                    <span><svg class="ico" aria-hidden="true"><use href="#i-calendar"/></svg>{{ $p->tanggal_pinjam->translatedFormat('j M Y') }} – {{ $p->tanggal_kembali_rencana->translatedFormat('j M Y') }}</span>
                    @if ($selesai && $p->tanggal_kembali_aktual)
                        <span><svg class="ico" aria-hidden="true"><use href="#i-check"/></svg>Dikembalikan {{ $p->tanggal_kembali_aktual->translatedFormat('j M Y') }}</span>
                    @endif
                </div>

                @if ($p->keperluan)
                    <p class="pj-note">{{ $p->keperluan }}</p>
                @endif

                @if ($p->status === 'ditolak')
                    <p class="pj-note"><b>Alasan:</b> {{ $p->catatan_admin ?: 'Ditolak oleh petugas Sarpras.' }}</p>
                @elseif ($aktif)
                    <p class="pj-note">
                        @if ($terlambat)
                            <b style="color:var(--danger)">Terlambat {{ $p->hariTerlambat() }} hari.</b>
                            @if ($p->melewatiBatasTelat())
                                Melewati batas toleransi {{ \App\Models\Peminjaman::MAKS_HARI_TELAT }} hari. Segera kembalikan ke ruang Sarpras, kamu belum bisa mengajukan peminjaman baru.
                            @else
                                Batas toleransi {{ \App\Models\Peminjaman::MAKS_HARI_TELAT }} hari. Segera kembalikan ke ruang Sarpras.
                            @endif
                        @else
                            Ambil di ruang Sarpras, kembalikan sebelum <b>{{ $p->tanggal_kembali_rencana->translatedFormat('l, j F') }}</b>.
                        @endif
                    </p>
                @elseif ($selesai && $p->catatan_admin)
                    <p class="pj-note">{{ $p->catatan_admin }}</p>
                @endif

                @if ($p->status === 'menunggu')
                    <div class="pj-act">
                        <form method="POST" action="{{ route('siswa.batal', $p) }}"
                              data-confirm
                              data-confirm-title="Batalkan pengajuan ini?"
                              data-confirm-text="Pengajuan {{ $p->kode_pengajuan }} ({{ $p->alat->nama }}) akan dihapus. Kamu bisa mengajukan lagi kapan saja."
                              data-confirm-ok="Ya, batalkan">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary btn-sm">
                                <svg class="ico ico-sm" aria-hidden="true"><use href="#i-x"/></svg>Batalkan pengajuan
                            </button>
                        </form>
                    </div>
                @endif
            </article>
        @empty
            <div class="empty card">
                <span class="empty-ico"><svg class="ico" aria-hidden="true"><use href="#i-book"/></svg></span>
                <h3>{{ $filter ? $kosong[$filter] : 'Belum ada riwayat peminjaman' }}</h3>
                <p class="t-small">{{ $filter ? 'Coba pilih filter lain.' : 'Pengajuan pertamamu akan muncul di sini lengkap dengan statusnya.' }}</p>
                @unless ($filter)
                    <a href="{{ route('siswa.ajukan') }}" class="btn btn-primary" style="margin-top:.6rem">Ajukan sekarang</a>
                @endunless
            </div>
        @endforelse
    </div>

    @if ($daftar->count() >= 50)
        <p class="t-small t-muted mt-6">Menampilkan 50 pengajuan terbaru.</p>
    @endif
@endsection