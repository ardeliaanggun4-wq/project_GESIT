{{-- Letak file: resources/views/admin/laporan.blade.php (FILE BARU) --}}
@extends('layouts.admin')
@section('title', 'Laporan')

@section('content')
    @php
        $statusOpsi   = ['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'dipinjam' => 'Dipinjam', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'];
        $kategoriOpsi = ['komputer' => 'Komputer', 'elektronika' => 'Elektronika', 'perkakas' => 'Perkakas', 'multimedia' => 'Multimedia'];
        $warnaStatus  = ['menunggu' => '#C8961E', 'disetujui' => '#34507A', 'dipinjam' => '#1E8A7A', 'selesai' => '#8A857B', 'ditolak' => '#C0392B'];
        $adaFilter    = $f['status'] || $f['kategori'] || $f['cari'];
        $hariIni      = \Illuminate\Support\Carbon::today();
        $rentang      = [
            '7 hari'     => $hariIni->copy()->subDays(6),
            '30 hari'    => $hariIni->copy()->subDays(29),
            'Bulan ini'  => $hariIni->copy()->startOfMonth(),
        ];
        $urlRentang   = fn ($dari) => route('admin.laporan', array_filter(array_merge(
            request()->except(['page', 'dari', 'sampai', 'cetak']),
            ['dari' => $dari->toDateString(), 'sampai' => $hariIni->toDateString()]
        )));
        $saringanAktif = array_filter([
            'dari' => $f['dari']->toDateString(), 'sampai' => $f['sampai']->toDateString(),
            'status' => $f['status'], 'kategori' => $f['kategori'], 'cari' => $f['cari'],
        ]);
        $periode      = $f['dari']->translatedFormat('j M Y') . ' – ' . $f['sampai']->translatedFormat('j M Y');
        $berhalaman   = $data instanceof \Illuminate\Pagination\AbstractPaginator;
        $jumlahBaris  = $berhalaman ? $data->total() : $data->count();
        $totalStatus  = max(1, $stats['total']);
    @endphp

    {{-- Kop khusus cetak --}}
    <div class="print-only" style="margin-bottom:1rem">
        <h1 style="font:600 1.4rem var(--font-head)">Laporan Peminjaman Alat · GESIT</h1>
        <p>Periode {{ $periode }}@if ($f['status']) · Status: {{ $statusOpsi[$f['status']] }}@endif @if ($f['kategori']) · {{ $kategoriOpsi[$f['kategori']] }}@endif @if ($f['cari']) · Kata kunci: {{ $f['cari'] }}@endif</p>
        <p>Dicetak {{ now()->translatedFormat('l, j F Y H:i') }} oleh {{ auth()->user()->name }}</p>
    </div>

    <section class="banner tone-primary rise mb-6 no-print">
        <div class="hero-dots" aria-hidden="true"></div>
        <div class="banner-body">
            <p class="eyebrow">Laporan</p>
            <h1 class="display mt-3">
                <span class="line"><span style="--i:0">Laporan <span class="hl">peminjaman</span></span></span>
            </h1>
            <p class="page-lead rise" style="--i:2">
                <span class="typed" data-type='["Rekap peminjaman alat per periode.","Saring menurut status, kategori, atau nama peminjam.","Unduh laporan ringkas sebagai PDF."]'>Rekap peminjaman alat per periode.</span>
            </p>
            <div class="hero-actions rise" style="--i:3">
                <a href="{{ route('admin.laporan.unduh', $saringanAktif) }}" class="btn btn-primary">
                    <svg class="ico" aria-hidden="true"><use href="#i-book"/></svg>Unduh PDF
                </a>
            </div>
        </div>
        <x-banner-art ikon="book" :mini="['box', 'flask', 'clock']" />
    </section>

    @if ($errors->any())
        <div class="alert mb-5 rise" role="alert">
            <svg class="ico" aria-hidden="true"><use href="#i-alert"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    {{-- Periode cepat --}}
    <div class="filters mb-4 rise no-print" style="--i:2" role="group" aria-label="Pilih periode">
        @foreach ($rentang as $nama => $dari)
            <a href="{{ $urlRentang($dari) }}" class="filter-chip {{ $f['dari']->isSameDay($dari) && $f['sampai']->isSameDay($hariIni) ? 'is-on' : '' }}">
                <svg class="ico ico-sm" aria-hidden="true"><use href="#i-calendar"/></svg>{{ $nama }}
            </a>
        @endforeach
    </div>

    {{-- Saringan --}}
    <form method="GET" action="{{ route('admin.laporan') }}" class="flex flex-wrap gap-3 mb-5 rise no-print" style="--i:3">
        <label class="flex items-center gap-2 t-small t-muted">Dari
            <input type="date" name="dari" value="{{ $f['dari']->toDateString() }}" class="field" style="width:auto" aria-label="Tanggal mulai">
        </label>
        <label class="flex items-center gap-2 t-small t-muted">Sampai
            <input type="date" name="sampai" value="{{ $f['sampai']->toDateString() }}" class="field" style="width:auto" aria-label="Tanggal akhir">
        </label>
        <select name="status" class="field" style="width:auto" aria-label="Saring menurut status">
            <option value="">Semua status</option>
            @foreach ($statusOpsi as $val => $label)
                <option value="{{ $val }}" @selected($f['status'] === $val)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="kategori" class="field" style="width:auto" aria-label="Saring menurut kategori">
            <option value="">Semua kategori</option>
            @foreach ($kategoriOpsi as $val => $label)
                <option value="{{ $val }}" @selected($f['kategori'] === $val)>{{ $label }}</option>
            @endforeach
        </select>
        <div class="search flex-1" style="min-width:14rem">
            <svg class="ico" aria-hidden="true"><use href="#i-search"/></svg>
            <input type="search" name="cari" value="{{ $f['cari'] }}" placeholder="Cari peminjam, alat, atau kode…" class="field" aria-label="Cari di laporan">
        </div>
        <button type="submit" class="btn btn-secondary">Terapkan</button>
        @if ($adaFilter || request()->filled('dari') || request()->filled('sampai'))
            <a href="{{ route('admin.laporan') }}" class="btn btn-ghost">Atur ulang</a>
        @endif
    </form>

    <p class="t-small t-muted mb-4 no-print">Periode {{ $periode }} · tanggal pinjam</p>

    {{-- Angka ringkas --}}
    <div class="stats stats-4 mb-6">
        <div class="stat tone-primary rise" style="--i:2">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-loan"/></svg></span>
            <div class="stat-label">Total pengajuan</div>
            <div class="stat-num" data-count="{{ $stats['total'] }}">{{ $stats['total'] }}</div>
        </div>
        <div class="stat tone-ok rise" style="--i:3">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-layers"/></svg></span>
            <div class="stat-label">Unit dipinjam</div>
            <div class="stat-num" data-count="{{ $stats['unit'] }}">{{ $stats['unit'] }}</div>
        </div>
        <div class="stat tone-wait rise" style="--i:4">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-clock"/></svg></span>
            <div class="stat-label">Terlambat kembali</div>
            <div class="stat-num" data-count="{{ $stats['terlambat'] }}">{{ $stats['terlambat'] }}</div>
        </div>
        <div class="stat tone-no rise" style="--i:5">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-x"/></svg></span>
            <div class="stat-label">Ditolak</div>
            <div class="stat-num" data-count="{{ $stats['ditolak'] }}">{{ $stats['ditolak'] }}</div>
        </div>
    </div>

    {{-- Komposisi status & alat terpopuler --}}
    <div class="grid lg:grid-cols-2 gap-6 mb-8 no-break">
        <section class="card card-pad card-accent rise" style="--i:5">
            <div class="sec-head"><h2 class="t-sec">Pengajuan menurut status</h2></div>
            @if ($stats['total'] > 0)
                <div class="stackbar mt-4" role="img" aria-label="Perbandingan status pengajuan">
                    @foreach ($statusOpsi as $kunci => $nama)
                        @if ($statusHitung->get($kunci, 0) > 0)
                            <i style="width: {{ ($statusHitung->get($kunci, 0) / $totalStatus) * 100 }}%; background: {{ $warnaStatus[$kunci] }}; --i: {{ $loop->index }}"></i>
                        @endif
                    @endforeach
                </div>
                <ul class="legend">
                    @foreach ($statusOpsi as $kunci => $nama)
                        <li><span class="sw" style="background: {{ $warnaStatus[$kunci] }}"></span>{{ $nama }}<b>{{ $statusHitung->get($kunci, 0) }}</b></li>
                    @endforeach
                </ul>
            @else
                <div class="empty">
                    <span class="empty-ico"><svg class="ico" aria-hidden="true"><use href="#i-chart"/></svg></span>
                    <p>Belum ada pengajuan pada periode ini.</p>
                </div>
            @endif
        </section>

        <section class="card card-pad card-accent rise" style="--i:6">
            <div class="sec-head"><h2 class="t-sec">Alat paling sering dipinjam</h2></div>
            <div class="space-y-5 mt-4">
                @forelse ($populer as $a)
                    <div data-kat="{{ $a->kategori }}">
                        <div class="flex items-center justify-between gap-3 text-sm mb-1.5">
                            <span><b>{{ $a->nama }}</b> <span class="kode">{{ $a->kode }}</span></span>
                            <span class="num font-semibold">{{ $a->unit }} unit · {{ $a->kali }}x</span>
                        </div>
                        <div class="track">
                            <div class="fill" style="width: {{ ($a->unit / $maxPopuler) * 100 }}%; --i: {{ $loop->index }}"></div>
                        </div>
                    </div>
                @empty
                    <div class="empty">
                        <span class="empty-ico"><svg class="ico" aria-hidden="true"><use href="#i-box"/></svg></span>
                        <p>Belum ada alat yang dipinjam pada periode ini.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Tabel rincian --}}
    <div class="sec-head"><h2 class="t-sec">Rincian peminjaman</h2></div>
    <div class="table-wrap rise" style="--i:7">
        @if ($jumlahBaris > 0)
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $p)
                        @php
                            $telat = $p->status === 'dipinjam' && $p->tanggal_kembali_rencana->lt($hariIni);
                            $hariTelat = $telat ? (int) abs($p->tanggal_kembali_rencana->diffInDays($hariIni)) : 0;
                        @endphp
                        <tr class="has-kat" data-kat="{{ $p->alat->kategori }}">
                            <td><span class="kode">{{ $p->kode_pengajuan }}</span></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <span class="avatar" aria-hidden="true">{{ $p->user->initials() }}</span>
                                    <div>
                                        <div class="alat-nama">{{ $p->user->name }}</div>
                                        @if ($p->user->kelas_jabatan)<div class="alat-lok">{{ $p->user->kelas_jabatan }}</div>@endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="alat-nama">{{ $p->alat->nama }}</div>
                                <div class="alat-lok"><span class="kode">{{ $p->alat->kode }}</span></div>
                            </td>
                            <td><span class="stok">{{ $p->jumlah }}</span></td>
                            <td>
                                <div>{{ $p->tanggal_pinjam->translatedFormat('j M Y') }}</div>
                                <div class="alat-lok">
                                    @if ($p->tanggal_kembali_aktual)
                                        Kembali {{ $p->tanggal_kembali_aktual->translatedFormat('j M Y') }}
                                    @else
                                        Rencana kembali {{ $p->tanggal_kembali_rencana->translatedFormat('j M Y') }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                <x-status :nilai="$p->status" />
                                @if ($telat)
                                    <div class="alat-lok" style="color:var(--danger);font-weight:600">Terlambat {{ $hariTelat }} hari</div>
                                @elseif ($p->status === 'ditolak' && $p->catatan_admin)
                                    <div class="alat-lok">{{ $p->catatan_admin }}</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="table-foot">
                @if ($berhalaman)
                    <span>Menampilkan {{ $data->firstItem() }}–{{ $data->lastItem() }} dari {{ $data->total() }} pengajuan</span>
                    <div class="no-print">{{ $data->links() }}</div>
                @else
                    <span>{{ $jumlahBaris }} pengajuan</span>
                @endif
            </div>
        @else
            <div class="empty">
                <span class="empty-ico"><svg class="ico" aria-hidden="true"><use href="#i-search"/></svg></span>
                <h3>Tidak ada data pada periode ini</h3>
                <p>Coba perluas rentang tanggal, atau atur ulang saringan status dan kategori.</p>
                <a href="{{ route('admin.laporan') }}" class="btn btn-secondary no-print" style="margin-top:.6rem">Atur ulang saringan</a>
            </div>
        @endif
    </div>

    @verbatim
    <style>
        .print-only{display:none}
        @media print{
            .sidebar,.dekor,.no-print,.skip-link,.toast,footer{display:none !important}
            .shell{display:block}
            .main{max-width:none;padding:0}
            .print-only{display:block}
            body{background:#fff}
            .rise,.rise *{opacity:1 !important;transform:none !important;animation:none !important}
            .card,.table-wrap,.stat{box-shadow:none !important;break-inside:avoid}
            .table-wrap{overflow:visible}
            .tbl tr{break-inside:avoid}
        }
    </style>
    @endverbatim
@endsection

@if ($cetak)
    @push('scripts')
        <script>
            window.addEventListener('load', () => setTimeout(() => window.print(), 600));
        </script>
    @endpush
@endif