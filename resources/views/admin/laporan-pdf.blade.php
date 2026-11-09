{{-- Letak file: resources/views/admin/laporan-pdf.blade.php (FILE BARU) --}}
{{-- Laporan ringkas untuk PDF. Dibuat dengan tabel sederhana karena DomPDF tidak mendukung flex/grid. --}}
@php
    $statusOpsi   = ['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'dipinjam' => 'Dipinjam', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'];
    $kategoriOpsi = ['komputer' => 'Komputer', 'elektronika' => 'Elektronika', 'perkakas' => 'Perkakas', 'multimedia' => 'Multimedia'];
    $hariIni      = \Illuminate\Support\Carbon::today();
    $periode      = $f['dari']->translatedFormat('j M Y') . ' – ' . $f['sampai']->translatedFormat('j M Y');
    $saringan     = collect([
        $f['status'] ? 'Status: ' . $statusOpsi[$f['status']] : null,
        $f['kategori'] ? 'Kategori: ' . $kategoriOpsi[$f['kategori']] : null,
        $f['cari'] ? 'Kata kunci: ' . $f['cari'] : null,
    ])->filter()->implode(' · ');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman Alat</title>
    <style>
        @page { margin: 28px 32px 36px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1F2933; }
        h1 { margin: 0; font-size: 15px; }
        .muted { color: #6B7280; }
        .kop { border-bottom: 2px solid #34507A; padding-bottom: 8px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        .ringkas td { width: 25%; padding: 7px 9px; border: 1px solid #E3DED3; background: #FAF8F3; }
        .ringkas b { display: block; font-size: 15px; margin-top: 2px; }
        h2 { margin: 14px 0 5px; font-size: 10.5px; color: #34507A; }
        .rincian th { background: #34507A; color: #fff; text-align: left; padding: 5px 6px; font-size: 8.5px; }
        .rincian td { padding: 4px 6px; border-bottom: 1px solid #E3DED3; vertical-align: top; }
        .rincian tr:nth-child(even) td { background: #FAF8F3; }
        .kecil { font-size: 8px; color: #6B7280; }
        .telat { color: #C0392B; font-weight: bold; }
        .kanan { text-align: right; }
        .tengah { text-align: center; }
        .foot { margin-top: 10px; font-size: 8px; color: #6B7280; }
    </style>
</head>
<body>
    <div class="kop">
        <h1>Laporan Peminjaman Alat · GESIT</h1>
        <div class="muted">Periode {{ $periode }} (tanggal pinjam)@if ($saringan) · {{ $saringan }}@endif</div>
    </div>

    <table class="ringkas">
        <tr>
            <td>Total pengajuan<b>{{ $stats['total'] }}</b></td>
            <td>Unit dipinjam<b>{{ $stats['unit'] }}</b></td>
            <td>Terlambat kembali<b>{{ $stats['terlambat'] }}</b></td>
            <td>Ditolak<b>{{ $stats['ditolak'] }}</b></td>
        </tr>
    </table>
    <table class="ringkas" style="margin-top:6px">
        <tr>
            <td>Dikembalikan terlambat<b>{{ $stats['telat_kembali'] }}</b></td>
            <td>Unit rusak<b>{{ $stats['unit_rusak'] }}</b></td>
            <td>Unit hilang<b>{{ $stats['unit_hilang'] }}</b></td>
            <td>Kewajiban tertunda<b>{{ $stats['kewajiban'] }}</b></td>
        </tr>
    </table>

    <p style="margin:8px 0 0">
        <b>Menurut status:</b>
        @foreach ($statusOpsi as $kunci => $nama)
            {{ $nama }} {{ $statusHitung->get($kunci, 0) }}@if (! $loop->last) · @endif
        @endforeach
    </p>
    @if ($populer->isNotEmpty())
        <p style="margin:3px 0 0">
            <b>Alat paling sering dipinjam:</b>
            @foreach ($populer as $a)
                {{ $a->nama }} ({{ $a->unit }} unit)@if (! $loop->last), @endif
            @endforeach
        </p>
    @endif

    <h2>Rincian peminjaman ({{ $baris->count() }})</h2>
    @if ($baris->isEmpty())
        <p class="muted">Tidak ada data pada periode ini.</p>
    @else
        <table class="rincian">
            <thead>
                <tr>
                    <th style="width:4%">No</th>
                    <th style="width:11%">Kode</th>
                    <th style="width:20%">Peminjam</th>
                    <th style="width:19%">Alat</th>
                    <th class="tengah" style="width:6%">Jml</th>
                    <th style="width:18%">Tanggal</th>
                    <th style="width:22%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($baris as $p)
                    @php
                        $telat = $p->status === 'dipinjam' && $p->tanggal_kembali_rencana->lt($hariIni);
                        $hariTelat = $telat ? (int) abs($p->tanggal_kembali_rencana->diffInDays($hariIni)) : 0;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->kode_pengajuan }}</td>
                        <td>{{ $p->user->name }}@if ($p->user->kelas_jabatan)<br><span class="kecil">{{ $p->user->kelas_jabatan }}</span>@endif</td>
                        <td>{{ $p->alat->nama }}</td>
                        <td class="tengah">{{ $p->jumlah }}</td>
                        <td>
                            {{ $p->tanggal_pinjam->translatedFormat('j M Y') }}<br>
                            <span class="kecil">
                                @if ($p->tanggal_kembali_aktual)
                                    Kembali {{ $p->tanggal_kembali_aktual->translatedFormat('j M Y') }}
                                @else
                                    Rencana {{ $p->tanggal_kembali_rencana->translatedFormat('j M Y') }}
                                @endif
                            </span>
                        </td>
                        <td>
                            {{ $p->statusLabel() }}
                            @if ($telat)<br><span class="telat">Telat {{ $hariTelat }} hari</span>@endif
                            @if ($p->status === 'selesai' && $p->hari_terlambat > 0)<br><span class="telat">Kembali telat {{ $p->hari_terlambat }} hari</span>@endif
                            @if ($p->kondisi_kembali && $p->kondisi_kembali !== 'baik')<br><span class="telat">{{ $p->kondisiKembaliLabel() }}</span>@if ($p->punyaKewajiban()) <span class="kecil">(kewajiban belum selesai)</span>@endif @endif
                            @if ($p->petugas)<br><span class="kecil">Diproses {{ $p->petugas->name }}</span>@endif
                            @if ($p->penerima)<br><span class="kecil">Diterima {{ $p->penerima->name }}</span>@endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="foot">Dicetak {{ now()->translatedFormat('l, j F Y H:i') }} oleh {{ $petugas }}</div>
</body>
</html>