{{-- Letak file: resources/views/admin/verifikasi.blade.php (timpa file lama) --}}
@extends('layouts.admin')
@section('title', 'Verifikasi')

@section('content')
    <div class="mb-8">
        <p class="mono-label rise">Halaman 02 · Verifikasi</p>
        <h1 class="display mt-3">
            <span class="line"><span style="--i:0">Verifikasi pengajuan</span></span>
        </h1>
        <p class="soft text-sm mt-4 rise" style="--i:2">Setujui atau tolak permintaan pinjam dari siswa dan guru.</p>
    </div>

    @if ($errors->any())
        <div class="alert mb-6">{{ $errors->first() }}</div>
    @endif

    <div class="grid md:grid-cols-3 gap-6 items-start">

        {{-- Menunggu --}}
        <section class="rise" style="--i:1">
            <div class="col-head">
                <i class="dot" style="background:var(--mustard)"></i>
                <h2 class="h-sec">Menunggu</h2>
                <span class="count" data-count="{{ $menunggu->count() }}">{{ $menunggu->count() }}</span>
            </div>
            <div class="space-y-4">
                @forelse ($menunggu as $p)
                    <article class="card tag-card card-lift p-4 rise" style="--i: {{ $loop->index + 2 }}">
                        <div class="kode muted mb-1">FR-04 · #{{ $p->kode_pengajuan }}</div>
                        <div class="font-semibold">{{ $p->user->name }} @if($p->user->kelas_jabatan) — {{ $p->user->kelas_jabatan }} @endif</div>
                        <div class="text-sm soft mb-4">{{ $p->alat->nama }} · {{ $p->jumlah }} unit</div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.verifikasi.approve', $p) }}">
                                @csrf
                                <button class="btn btn-ok btn-sm">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('admin.verifikasi.reject', $p) }}">
                                @csrf
                                <input type="hidden" name="catatan_admin" value="Ditolak oleh petugas Sarpras">
                                <button class="btn btn-no btn-sm">Tolak</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="empty card">Tidak ada pengajuan yang menunggu.</div>
                @endforelse
            </div>
        </section>

        {{-- Disetujui --}}
        <section class="rise" style="--i:2">
            <div class="col-head">
                <i class="dot" style="background:var(--olive)"></i>
                <h2 class="h-sec">Disetujui</h2>
                <span class="count" data-count="{{ $disetujui->count() }}">{{ $disetujui->count() }}</span>
            </div>
            <div class="space-y-4">
                @forelse ($disetujui as $p)
                    <article class="card tag-card ok card-lift p-4 rise" style="--i: {{ $loop->index + 3 }}">
                        <div class="kode muted mb-1">FR-04 · #{{ $p->kode_pengajuan }}</div>
                        <div class="font-semibold">{{ $p->user->name }} @if($p->user->kelas_jabatan) — {{ $p->user->kelas_jabatan }} @endif</div>
                        <div class="text-sm soft mb-4">{{ $p->alat->nama }} · {{ $p->jumlah }} unit</div>
                        <span class="stamp stamp-ok" style="--i: {{ $loop->index }}">Dipinjam</span>
                    </article>
                @empty
                    <div class="empty card">Belum ada yang disetujui.</div>
                @endforelse
            </div>
        </section>

        {{-- Ditolak --}}
        <section class="rise" style="--i:3">
            <div class="col-head">
                <i class="dot" style="background:var(--brick)"></i>
                <h2 class="h-sec">Ditolak</h2>
                <span class="count" data-count="{{ $ditolak->count() }}">{{ $ditolak->count() }}</span>
            </div>
            <div class="space-y-4">
                @forelse ($ditolak as $p)
                    <article class="card tag-card no card-lift p-4 rise" style="--i: {{ $loop->index + 4 }}">
                        <div class="kode muted mb-1">FR-04 · #{{ $p->kode_pengajuan }}</div>
                        <div class="font-semibold">{{ $p->user->name }} @if($p->user->kelas_jabatan) — {{ $p->user->kelas_jabatan }} @endif</div>
                        <div class="text-sm soft mb-4">{{ $p->alat->nama }} · {{ $p->catatan_admin }}</div>
                        <span class="stamp stamp-no" style="--i: {{ $loop->index }}">Ditolak</span>
                    </article>
                @empty
                    <div class="empty card">Belum ada yang ditolak.</div>
                @endforelse
            </div>
        </section>
    </div>
@endsection