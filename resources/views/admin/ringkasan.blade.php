{{-- Letak file: resources/views/admin/ringkasan.blade.php (timpa file lama) --}}
@extends('layouts.admin')
@section('title', 'Ringkasan')

@section('content')
    @php $statusMap = ['green' => 'ok', 'yellow' => 'wait', 'red' => 'no']; @endphp

    <div class="mb-8">
        <p class="mono-label rise">Halaman 01 · Ringkasan</p>
        <h1 class="display mt-3">
            <span class="line"><span style="--i:0">Ringkasan</span></span>
            <span class="line"><span style="--i:1">Sarana &amp; Prasarana</span></span>
        </h1>
        <p class="soft text-sm mt-4 rise" style="--i:3">Pantau kondisi dan aktivitas peminjaman alat hari ini.</p>
    </div>

    {{-- Angka utama --}}
    <div class="ledger grid-cols-2 md:grid-cols-4 mb-10 rise" style="--i:2">
        <div>
            <div class="mono-label flex items-center gap-2"><i class="dot" style="background:var(--ink)"></i>Total alat terdaftar</div>
            <div class="num mt-4" data-count="{{ $stats['total_alat'] }}">{{ $stats['total_alat'] }}</div>
        </div>
        <a href="{{ route('admin.verifikasi') }}" class="block">
            <span class="arrow">→</span>
            <div class="mono-label flex items-center gap-2"><i class="dot" style="background:var(--mustard)"></i>Menunggu verifikasi</div>
            <div class="num mt-4" data-count="{{ $stats['menunggu'] }}">{{ $stats['menunggu'] }}</div>
        </a>
        <div>
            <div class="mono-label flex items-center gap-2"><i class="dot" style="background:var(--olive)"></i>Sedang dipinjam</div>
            <div class="num mt-4" data-count="{{ $stats['sedang_dipinjam'] }}">{{ $stats['sedang_dipinjam'] }}</div>
        </div>
        <div>
            <div class="mono-label flex items-center gap-2"><i class="dot" style="background:var(--brick)"></i>Rusak / stok kosong</div>
            <div class="num mt-4" data-count="{{ $stats['rusak_kosong'] }}">{{ $stats['rusak_kosong'] }}</div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mb-10">
        {{-- Grafik 7 hari --}}
        <section class="card p-6 lg:col-span-2 rise" style="--i:3">
            <h2 class="h-sec mb-8">Peminjaman 7 hari terakhir</h2>
            @php
                $puncak = $grafik->max('total');
                $max = max(1, $puncak);
            @endphp
            <div class="flex items-stretch justify-between gap-3 h-44">
                @foreach ($grafik as $g)
                    <div class="flex-1 flex flex-col h-full">
                        <div class="flex-1 flex items-end">
                            <div class="bar {{ $g['total'] > 0 && $g['total'] == $puncak ? 'is-peak' : '' }}"
                                 data-v="{{ $g['total'] }}"
                                 style="height: {{ max(6, ($g['total'] / $max) * 100) }}%; --i: {{ $loop->index }}"></div>
                        </div>
                        <div class="mono-label mt-2 text-center">{{ $g['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Kategori --}}
        <section class="card p-6 rise" style="--i:4">
            <h2 class="h-sec mb-6">Kategori paling sering dipinjam</h2>
            <div class="space-y-5">
                @forelse ($kategori as $k)
                    <div>
                        <div class="flex items-baseline justify-between text-sm mb-1.5">
                            <span>{{ \App\Models\Alat::make(['kategori' => $k->kategori])->kategoriLabel() }}</span>
                            <span class="kode muted">{{ $k->total }}</span>
                        </div>
                        <div class="track">
                            <div class="fill" style="width: {{ ($k->total / $maxKategori) * 100 }}%; --i: {{ $loop->index }}"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm muted">Belum ada data peminjaman.</p>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Aktivitas --}}
    <h2 class="h-sec mb-3">Aktivitas terbaru</h2>
    <div class="card overflow-hidden rise" style="--i:5">
        @forelse ($aktivitas as $a)
            <div class="list-row">
                <div>
                    <div class="font-semibold">{{ $a->user->name }} @if($a->user->kelas_jabatan) — {{ $a->user->kelas_jabatan }} @endif</div>
                    <div class="text-xs soft mt-0.5">
                        @if($a->status === 'menunggu') Mengajukan peminjaman {{ $a->alat->nama }}
                        @elseif($a->status === 'ditolak') Pengajuan {{ $a->alat->nama }} ditolak — {{ $a->catatan_admin }}
                        @else Pengajuan {{ $a->alat->nama }} disetujui oleh Petugas Sarpras @endif
                        · {{ $a->updated_at->diffForHumans() }}
                    </div>
                </div>
                <span class="stamp stamp-{{ $statusMap[$a->statusColor()] ?? 'idle' }}" style="--i: {{ $loop->index }}">
                    {{ $a->statusLabel() }}
                </span>
            </div>
        @empty
            <div class="empty">Belum ada aktivitas.</div>
        @endforelse
    </div>
@endsection