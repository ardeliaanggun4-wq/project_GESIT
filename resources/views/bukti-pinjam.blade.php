{{-- Letak file: resources/views/bukti-pinjam.blade.php (FILE BARU) --}}
{{-- Bukti peminjaman untuk PDF. Tabel sederhana karena DomPDF tidak mendukung flex/grid. --}}
@php
    $kategoriOpsi = ['komputer' => 'Komputer', 'elektronika' => 'Elektronika', 'perkakas' => 'Perkakas', 'multimedia' => 'Multimedia'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Bukti Pinjam {{ $p->kode_pengajuan }}</title>
    <style>
        @page { margin: 36px 42px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1F2933; }
        h1 { margin: 0; font-size: 18px; color: #34507A; }
        .muted { color: #6B7280; }
        .kop { border-bottom: 2px solid #34507A; padding-bottom: 10px; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; }
        .kode { text-align: right; font-size: 15px; font-weight: bold; }
        h2 { margin: 16px 0 6px; font-size: 11.5px; color: #34507A; text-transform: uppercase; letter-spacing: .5px; }
        .data td { padding: 5px 0; border-bottom: 1px solid #E7E1D7; vertical-align: top; }
        .data td.k { width: 32%; color: #6B7280; }
        .foto { width: 230px; border: 1px solid #CFC8BA; }
        .foto-kosong { width: 230px; height: 150px; border: 1px dashed #CFC8BA; background: #FAF8F3; text-align: center; color: #6B7280; font-size: 10px; padding-top: 60px; }
        .cap { width: 230px; margin-top: 4px; font-size: 9px; color: #6B7280; text-align: center; }
        .box { border: 1px solid #E7E1D7; background: #FAF8F3; padding: 8px 10px; }
        .ttd td { width: 50%; text-align: center; padding-top: 6px; }
        .ttd .garis { height: 64px; }
        .foot { margin-top: 18px; font-size: 9px; color: #6B7280; }
    </style>
</head>
<body>
    <div class="kop">
        <table>
            <tr>
                <td>
                    <h1>Bukti Peminjaman Alat</h1>
                    <div class="muted">GESIT · Inventaris Sarpras Sekolah</div>
                </td>
                <td class="kode">{{ $p->kode_pengajuan }}<br><span class="muted" style="font-size:10px;font-weight:normal">Status: {{ $p->statusLabel() }}</span></td>
            </tr>
        </table>
    </div>

    <h2>Peminjam</h2>
    <table class="data">
        <tr><td class="k">Nama</td><td>{{ $p->user->name }}</td></tr>
        <tr><td class="k">NIPD / NIP</td><td>{{ $p->user->nisn_nip ?: '-' }}</td></tr>
        <tr><td class="k">Kelas / Jabatan</td><td>{{ $p->user->kelas_jabatan ?: '-' }}</td></tr>
    </table>

    <h2>Alat yang dipinjam</h2>
    <table>
        <tr>
            <td style="width:250px;vertical-align:top">
                @if ($foto)
                    <img src="{{ $foto }}" class="foto" alt="">
                    <div class="cap">Foto alat: {{ $p->alat->nama }}</div>
                @else
                    <div class="foto-kosong">Foto alat belum tersedia</div>
                @endif
            </td>
            <td style="vertical-align:top">
                <table class="data">
                    <tr><td class="k">Nama alat</td><td><b>{{ $p->alat->nama }}</b></td></tr>
                    <tr><td class="k">Kode alat</td><td>{{ $p->alat->kode }}</td></tr>
                    <tr><td class="k">Kategori</td><td>{{ $kategoriOpsi[$p->alat->kategori] ?? ucfirst($p->alat->kategori) }}</td></tr>
                    <tr><td class="k">Lokasi</td><td>{{ $p->alat->lokasi ?: '-' }}</td></tr>
                    <tr><td class="k">Jumlah dipinjam</td><td>{{ $p->jumlah }} unit</td></tr>
                </table>
            </td>
        </tr>
    </table>
    <div class="box" style="margin-top:8px">
        <b>Validasi alat:</b> alat yang dipinjam adalah alat seperti pada foto di atas. Peminjam wajib mengembalikannya
        dalam kondisi yang sama dan lengkap.
    </div>
    @if ($p->alat->deskripsi)
        <div class="box" style="margin-top:8px"><b>Deskripsi alat:</b> {{ $p->alat->deskripsi }}</div>
    @endif

    <h2>Waktu peminjaman</h2>
    <table class="data">
        <tr><td class="k">Tanggal pinjam</td><td>{{ $p->tanggal_pinjam->translatedFormat('l, j F Y') }}</td></tr>
        <tr><td class="k">Rencana kembali</td><td>{{ $p->tanggal_kembali_rencana->translatedFormat('l, j F Y') }}</td></tr>
        @if ($p->tanggal_kembali_aktual)
            <tr><td class="k">Dikembalikan</td><td>{{ $p->tanggal_kembali_aktual->translatedFormat('l, j F Y') }}</td></tr>
        @endif
        @if ($p->kondisi_kembali)
            <tr><td class="k">Kondisi saat kembali</td><td>{{ $p->kondisiKembaliLabel() }}@if ($p->catatan_kembali) · {{ $p->catatan_kembali }}@endif</td></tr>
        @endif
        @if ($p->sanksi)
            <tr><td class="k">Ketentuan dikenakan</td><td>{{ $p->sanksi }}</td></tr>
        @endif
        <tr><td class="k">Keperluan</td><td>{{ $p->keperluan ?: '-' }}</td></tr>
    </table>

    <h2>Ketentuan</h2>
    <div class="box">
        Alat dikembalikan ke ruang Sarpras paling lambat pada tanggal rencana kembali.
        @foreach (\App\Models\Peminjaman::ketentuan() as [$ikon, $judul, $isi])
            <br>&bull; <b>{{ $judul }}:</b> {{ $isi }}
        @endforeach
    </div>

    <table class="ttd" style="margin-top:22px">
        <tr>
            <td>Peminjam<div class="garis"></div><b>{{ $p->user->name }}</b></td>
            <td>Petugas GESIT<div class="garis"></div>( ............................ )</td>
        </tr>
    </table>

    <div class="foot">Dicetak {{ $dicetak->translatedFormat('l, j F Y H:i') }} dari sistem GESIT.</div>
</body>
</html>