{{-- Letak file: resources/views/siswa/panduan.blade.php --}}
@extends('layouts.siswa')
@section('title', 'Panduan')

@section('content')
    @php
        // Isi halaman dipisah dari markup agar mudah diubah tanpa menyentuh tampilan.
        $alur = [
            ['Masuk ke akun', 'Gunakan NISN (siswa) atau NIP (guru) dan kata sandimu.'],
            ['Ajukan peminjaman', 'Pilih alat, tentukan tanggal, jumlah, dan isi keperluan.'],
            ['Tunggu verifikasi', 'Petugas Sarpras memeriksa stok dan pengajuanmu. Pantau statusnya di Beranda.'],
            ['Ambil alat', 'Setelah disetujui, datang ke ruang Sarpras untuk mengambil alat.'],
            ['Kembalikan tepat waktu', 'Serahkan alat dalam keadaan utuh sebelum tanggal rencana kembali.'],
        ];

        $formulir = [
            ['Nama alat / inventaris', 'Pilih dari daftar. Setiap pilihan menampilkan lokasi dan jumlah unit yang tersedia. Detail kode, kategori, dan stok muncul di panel samping.'],
            ['Tanggal pinjam', 'Hari kamu mulai memakai alat. Tidak bisa memilih tanggal yang sudah lewat.'],
            ['Rencana kembali', 'Hari kamu akan mengembalikan alat. Tidak boleh lebih awal dari tanggal pinjam.'],
            ['Jumlah alat', 'Atur dengan tombol − dan +. Batas maksimalnya mengikuti stok alat yang kamu pilih.'],
            ['Keperluan penggunaan', 'Tuliskan untuk apa alat dipakai, misalnya mata pelajaran dan kelas (maksimal 500 karakter). Kolom ini boleh dikosongkan, tetapi pengajuan yang jelas lebih mudah diverifikasi.'],
        ];

        $status = [
            ['menunggu', 'Pengajuanmu sudah terkirim dan sedang diperiksa petugas Sarpras. Alat belum bisa diambil.'],
            ['disetujui', 'Pengajuanmu diterima. Ambil alat di ruang Sarpras.'],
            ['dipinjam', 'Alat sudah menjadi tanggung jawabmu. Kembalikan sebelum tanggal rencana kembali.'],
            ['ditolak', 'Pengajuan tidak disetujui, misalnya karena stok tidak mencukupi. Tanyakan alasannya ke petugas Sarpras, lalu ajukan ulang bila perlu.'],
            ['selesai', 'Alat sudah dikembalikan dan peminjaman ditutup.'],
        ];

        $aturan = [
            ['check', 'Satu pengajuan untuk satu jenis alat. Butuh beberapa jenis alat? Kirim pengajuan terpisah.'],
            ['calendar', 'Cek tanggal sebelum mengirim. Pengajuan yang sudah terkirim belum bisa diubah sendiri.'],
            ['bell', 'Beranda memberi pengingat saat ada alat yang jatuh tempo hari ini. Kembalikan sebelum jam pulang sekolah.'],
            ['wrench', 'Jaga alat tetap utuh. Bila ada kerusakan atau kehilangan, segera lapor ke petugas Sarpras.'],
        ];

        $tanya = [
            ['Stok alat yang kubutuhkan habis. Apa yang bisa dilakukan?',
             'Pengajuan untuk alat dengan stok habis bisa ditolak petugas. Pilih alat serupa yang masih tersedia, atau tanyakan ke petugas Sarpras kapan alat itu kembali ada.'],
            ['Berapa lama pengajuanku diverifikasi?',
             'Waktunya tergantung petugas Sarpras. Selama belum diproses, statusmu tetap Menunggu. Cek Beranda secara berkala.'],
            ['Aku salah mengisi pengajuan atau ingin membatalkannya.',
             'Saat ini belum ada tombol ubah atau batal. Hubungi petugas Sarpras secepatnya sebelum pengajuan diproses, atau kirim pengajuan baru dengan data yang benar.'],
            ['Pengajuanku ditolak. Apakah bisa mengajukan lagi?',
             'Bisa. Tanyakan dulu alasannya ke petugas Sarpras, lalu kirim pengajuan baru dengan alat, jumlah, atau tanggal yang sesuai.'],
            ['Aku terlambat mengembalikan alat.',
             'Segera temui petugas Sarpras dan serahkan alatnya. Semakin cepat dilaporkan, semakin mudah diselesaikan.'],
            ['Aku tidak bisa masuk ke akun.',
             'Pastikan kamu memilih tab “Siswa / Guru”, lalu periksa NISN atau NIP dan kata sandimu. Bila tetap gagal, minta bantuan petugas Sarpras.'],
        ];
    @endphp

    <style>
        .guide-nav { margin-bottom: var(--s-6); }
        .guide-sec { margin-bottom: var(--s-7); }
        .guide-sec > .sec-head { margin-bottom: var(--s-4); }
        .guide-num { display: grid; place-items: center; width: 2.4rem; height: 2.4rem; flex: none; border-radius: 50%; background: var(--t-bg); color: var(--t-fg); border: 1px solid var(--t-line); font: 600 .9375rem var(--font-head); }
        .guide-desc { margin-top: 2px; font-size: .9375rem; color: var(--ink-2); }
        .guide-status { flex: none; width: 7.75rem; }
        .rule { display: flex; align-items: flex-start; gap: .75rem; padding: .85rem 1.25rem; border-bottom: 1px solid var(--line); }
        .rule:last-child { border-bottom: 0; }
        .rule .row-ico { width: 2rem; height: 2rem; margin-top: 1px; }
        .rule p { color: var(--ink-2); }
        .faq details { border-bottom: 1px solid var(--line); }
        .faq details:last-child { border-bottom: 0; }
        .faq summary { display: flex; align-items: center; justify-content: space-between; gap: 1rem; min-height: var(--tap); padding: .85rem 1.25rem; font-weight: 600; cursor: pointer; list-style: none; transition: background var(--dur); }
        .faq summary::-webkit-details-marker { display: none; }
        .faq summary:hover { background: #FDFBF7; }
        .faq summary::after { content: "+"; flex: none; display: grid; place-items: center; width: 1.75rem; height: 1.75rem; border-radius: 50%; background: var(--surface-2); border: 1px solid var(--line); font-size: 1.125rem; line-height: 1; color: var(--primary); }
        .faq details[open] summary::after { content: "−"; background: var(--primary-tint); border-color: transparent; }
        .faq .faq-body { padding: 0 1.25rem 1rem; color: var(--muted); max-width: 42rem; }
        .guide-cta { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: var(--s-4); padding: var(--s-5); border: 1px solid #C5D0E3; border-radius: 20px; background: linear-gradient(120deg, #fff 0%, var(--primary-tint) 100%); }
        .guide-cta .cta-act { display: flex; flex-wrap: wrap; gap: .6rem; }
    </style>

    {{-- Judul halaman --}}
    <div class="mb-8">
        <p class="eyebrow rise">Panduan</p>
        <h1 class="display mt-3">
            <span class="line"><span style="--i:0">Cara meminjam alat</span></span>
            <span class="line"><span style="--i:1" class="hl">lewat GESIT</span></span>
        </h1>
        <p class="page-lead rise" style="--i:3">
            <span class="typed" data-type='["Dari mengajukan sampai mengembalikan, semuanya ada di sini.","Baca sekali, lalu ajukan peminjaman dengan percaya diri.","Bingung dengan status pengajuan? Cek bagian Arti status."]'>Dari mengajukan sampai mengembalikan, semuanya ada di sini.</span>
        </p>
    </div>

    {{-- Lompat ke bagian --}}
    <nav class="guide-nav rise" style="--i:4" aria-label="Isi panduan">
        <div class="filters">
            <a href="#alur" class="filter-chip">Alur singkat</a>
            <a href="#formulir" class="filter-chip">Mengisi formulir</a>
            <a href="#status" class="filter-chip">Arti status</a>
            <a href="#aturan" class="filter-chip">Hal penting</a>
            <a href="#tanya" class="filter-chip">Pertanyaan umum</a>
        </div>
    </nav>

    {{-- 1. Alur singkat --}}
    <section id="alur" class="guide-sec">
        <div class="sec-head"><h2 class="t-sec">Alur singkat</h2></div>
        <div class="card card-pad rise" style="--i:2">
            <ol class="steps">
                @foreach ($alur as [$judul, $isi])
                    <li><div><b>{{ $judul }}</b><span>{{ $isi }}</span></div></li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- 2. Mengisi formulir --}}
    <section id="formulir" class="guide-sec">
        <div class="sec-head">
            <h2 class="t-sec">Mengisi formulir pengajuan</h2>
            <a href="{{ route('siswa.ajukan') }}" class="link t-small">Buka formulir</a>
        </div>
        <div class="list rise" style="--i:2">
            @foreach ($formulir as $i => [$judul, $isi])
                <div class="list-row" style="--i: {{ $i }}; align-items:flex-start">
                    <span class="guide-num tone-primary">{{ $i + 1 }}</span>
                    <div class="list-main">
                        <div class="list-title">{{ $judul }}</div>
                        <div class="guide-desc">{{ $isi }}</div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="alert alert-info mt-4 rise" style="--i:3" role="note">
            <svg class="ico" aria-hidden="true"><use href="#i-info"/></svg>
            <span>Setelah menekan <b>Kirim pengajuan</b>, kamu kembali ke Beranda dan pengajuanmu tampil di Riwayat dengan status Menunggu.</span>
        </div>
    </section>

    {{-- 3. Arti status --}}
    <section id="status" class="guide-sec">
        <div class="sec-head"><h2 class="t-sec">Arti status pengajuan</h2></div>
        <div class="list rise" style="--i:2">
            @foreach ($status as $i => [$nilai, $isi])
                <div class="list-row" style="--i: {{ $i }}; align-items:flex-start">
                    <div class="guide-status"><x-status :nilai="$nilai" /></div>
                    <div class="list-main guide-desc" style="margin-top:0">{{ $isi }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 4. Hal penting --}}
    <section id="aturan" class="guide-sec">
        <div class="sec-head"><h2 class="t-sec">Hal penting yang perlu diingat</h2></div>
        <div class="card overflow-hidden rise" style="--i:2">
            @foreach ($aturan as [$ikon, $isi])
                <div class="rule tone-wait">
                    <span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-{{ $ikon }}"/></svg></span>
                    <p>{{ $isi }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 5. Pertanyaan umum --}}
    <section id="tanya" class="guide-sec">
        <div class="sec-head"><h2 class="t-sec">Pertanyaan umum</h2></div>
        <div class="card overflow-hidden faq rise" style="--i:2">
            @foreach ($tanya as [$q, $a])
                <details>
                    <summary>{{ $q }}</summary>
                    <p class="faq-body">{{ $a }}</p>
                </details>
            @endforeach
        </div>
    </section>

    {{-- Ajakan --}}
    <section class="guide-cta rise" style="--i:2" aria-labelledby="cta-judul">
        <div>
            <h2 class="t-sec" id="cta-judul">Siap meminjam alat?</h2>
            <p class="t-small t-muted" style="margin-top:.25rem">Pengajuan hanya butuh sekitar satu menit.</p>
        </div>
        <div class="cta-act">
            <a href="{{ route('siswa.ajukan') }}" class="btn btn-primary">
                <svg class="ico" aria-hidden="true"><use href="#i-plus"/></svg>Ajukan peminjaman
            </a>
            <a href="{{ route('siswa.beranda') }}" class="btn btn-secondary">Kembali ke Beranda</a>
        </div>
    </section>
@endsection