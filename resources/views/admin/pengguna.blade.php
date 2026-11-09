{{-- Letak file: resources/views/admin/pengguna.blade.php (FILE BARU) --}}
@extends('layouts.admin')
@section('title', 'Data Pengguna')

@section('content')
    @php
        $labelNomor = $jenis === 'siswa' ? 'NIPD' : 'NIP';
        $labelInfo  = $jenis === 'siswa' ? 'Kelas' : 'Mata pelajaran';
        $adaFilter  = request()->filled('cari');
        $tglFmt     = fn ($t) => $t ? \Illuminate\Support\Carbon::parse($t)->format('d/m/Y') : null;
    @endphp

    <section class="banner tone-primary rise mb-6">
        <div class="hero-dots" aria-hidden="true"></div>
        <div class="banner-body">
            <p class="eyebrow">Data sekolah</p>
            <h1 class="display mt-3">
                <span class="line"><span style="--i:0">Data <span class="hl">Pengguna</span></span></span>
            </h1>
            <p class="page-lead rise" style="--i:2">
                Hanya NIPD dan NIP yang ada di daftar ini yang bisa mendaftar akun GESIT. Tambah, ubah, atau impor datanya di sini.
            </p>
            <div class="hero-actions rise" style="--i:3">
                <button type="button" data-open="dlg-tambah" data-reset
                        data-form-action="{{ route('admin.pengguna.store', $jenis) }}" class="btn btn-primary">
                    <svg class="ico" aria-hidden="true"><use href="#i-plus"/></svg>Tambah {{ $jenis }}
                </button>
                <button type="button" data-open="dlg-impor" data-reset
                        data-form-action="{{ route('admin.pengguna.impor', $jenis) }}" class="btn btn-secondary">
                    <svg class="ico" aria-hidden="true"><use href="#i-layers"/></svg>Impor CSV
                </button>
            </div>
        </div>
        <x-banner-art ikon="user" :mini="['book', 'check', 'shield']" />
    </section>

    <div class="stats stats-3 mb-6">
        <div class="stat tone-primary rise" style="--i:2">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-user"/></svg></span>
            <div class="stat-label">Siswa di data sekolah</div>
            <div class="stat-num">{{ $jumlah['siswa'] }}</div>
        </div>
        <div class="stat tone-ok rise" style="--i:3">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-book"/></svg></span>
            <div class="stat-label">Guru di data sekolah</div>
            <div class="stat-num">{{ $jumlah['guru'] }}</div>
        </div>
        <div class="stat tone-wait rise" style="--i:4">
            <span class="stat-ico"><svg class="ico" aria-hidden="true"><use href="#i-clock"/></svg></span>
            <div class="stat-label">Akun menunggu persetujuan</div>
            <div class="stat-num">{{ $menunggu->count() }}</div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert mb-5 rise" role="alert">
            <svg class="ico" aria-hidden="true"><use href="#i-alert"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    {{-- Kata sandi sementara hasil atur ulang: tampil sekali, hilang saat halaman dimuat ulang --}}
    @if (session('sandi_sementara'))
        <div class="alert alert-info mb-5 rise" role="status">
            <svg class="ico" aria-hidden="true"><use href="#i-info"/></svg>
            <span>
                Kata sandi sementara untuk <b>{{ session('sandi_sementara.nama') }}</b>:
                <code style="font-size:1.05rem;font-weight:700;letter-spacing:.04em;user-select:all">{{ session('sandi_sementara.sandi') }}</code>.
                Catat sekarang, karena tidak akan ditampilkan lagi. Minta pemiliknya menggantinya lewat menu Profil setelah masuk.
            </span>
        </div>
    @endif

    {{-- Akun baru yang menunggu persetujuan --}}
    @if ($menunggu->count())
        <section class="mb-8 rise" style="--i:3" aria-labelledby="menunggu-judul">
            <div class="sec-head">
                <h2 class="t-sec" id="menunggu-judul">Menunggu persetujuan</h2>
                <span class="t-small t-muted">Data tanggal lahirnya belum ada di data sekolah. Pastikan pendaftarnya benar-benar orang yang bersangkutan sebelum menyetujui.</span>
            </div>
            <div class="card" style="overflow:hidden">
                @foreach ($menunggu as $u)
                    <div class="list-row">
                        <x-avatar :user="$u" />
                        <div class="list-main">
                            <div class="list-title">{{ $u->name }}</div>
                            <div class="list-sub">
                                {{ $u->role === 'siswa' ? 'Siswa' : 'Guru' }} · {{ $u->role === 'siswa' ? 'NIPD' : 'NIP' }} {{ $u->nisn_nip }} · {{ $u->email }}
                                · daftar {{ $u->created_at?->translatedFormat('j M, H:i') }}
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.akun.tolak', $u) }}"
                                  data-confirm data-confirm-title="Tolak pendaftaran ini?"
                                  data-confirm-text="Akun {{ $u->name }} akan dihapus. Pemilik asli nomornya bisa mendaftar ulang."
                                  data-confirm-ok="Ya, tolak">
                                @csrf
                                <button type="submit" class="btn btn-ghost is-danger btn-sm">Tolak</button>
                            </form>
                            <form method="POST" action="{{ route('admin.akun.setujui', $u) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <svg class="ico ico-sm" aria-hidden="true"><use href="#i-check"/></svg>Setujui
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Pilih jenis --}}
    <div class="filters mb-4 rise" style="--i:3" role="group" aria-label="Pilih jenis data">
        <a href="{{ route('admin.pengguna', ['jenis' => 'siswa']) }}" class="filter-chip {{ $jenis === 'siswa' ? 'is-on' : '' }}">
            <svg class="ico ico-sm" aria-hidden="true"><use href="#i-user"/></svg>Siswa <span class="n">{{ $jumlah['siswa'] }}</span>
        </a>
        <a href="{{ route('admin.pengguna', ['jenis' => 'guru']) }}" class="filter-chip {{ $jenis === 'guru' ? 'is-on' : '' }}">
            <svg class="ico ico-sm" aria-hidden="true"><use href="#i-book"/></svg>Guru <span class="n">{{ $jumlah['guru'] }}</span>
        </a>
    </div>

    @if ($tanpaLahir > 0)
        <div class="alert alert-info mb-4 rise" role="status">
            <svg class="ico" aria-hidden="true"><use href="#i-info"/></svg>
            <span>
                {{ $tanpaLahir }} {{ $jenis }} belum punya tanggal lahir. Mereka tetap bisa mendaftar, tetapi akunnya perlu disetujui petugas dan tidak bisa mengganti kata sandi sendiri.
                Lengkapi lewat <b>Impor CSV</b> (kolom ke-4) agar pendaftaran langsung aktif dan lebih aman.
            </span>
        </div>
    @endif

    {{-- Pencarian --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-5 rise" style="--i:4">
        <input type="hidden" name="jenis" value="{{ $jenis }}">
        <div class="search flex-1" style="min-width:14rem">
            <svg class="ico" aria-hidden="true"><use href="#i-search"/></svg>
            <input type="search" name="cari" value="{{ request('cari') }}" placeholder="Cari nama, {{ $labelNomor }}, atau {{ strtolower($labelInfo) }}…" class="field" aria-label="Cari {{ $jenis }}">
        </div>
        <button type="submit" class="btn btn-secondary">Cari</button>
        @if ($adaFilter)
            <a href="{{ route('admin.pengguna', ['jenis' => $jenis]) }}" class="btn btn-ghost">Atur ulang</a>
        @endif
    </form>

    {{-- Tabel --}}
    <div class="table-wrap rise" style="--i:5">
        @if ($data->count())
            <table class="tbl">
                <thead>
                    <tr>
                        <th>{{ $labelNomor }}</th>
                        <th>Nama</th>
                        <th>{{ $labelInfo }}</th>
                        <th>Tanggal lahir</th>
                        <th>Akun</th>
                        <th class="r">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $r)
                        @php
                            $nomor = $r->{$kolom};
                            $info  = $r->{$kolomInfo};
                            $a     = $akun->get($nomor);
                        @endphp
                        <tr>
                            <td><span class="kode">{{ $nomor }}</span></td>
                            <td style="font-weight:600">{{ $r->nama }}</td>
                            <td>{{ $info ?: '-' }}</td>
                            <td>
                                @if ($r->tanggal_lahir)
                                    <span class="num">{{ $tglFmt($r->tanggal_lahir) }}</span>
                                @else
                                    <span class="t-muted">Belum ada</span>
                                @endif
                            </td>
                            <td>
                                @if (! $a)
                                    <span class="t-muted">Belum daftar</span>
                                @elseif ($a->menunggu())
                                    <x-status nilai="menunggu" />
                                @else
                                    <x-status nilai="disetujui" label="Aktif" />
                                @endif
                            </td>
                            <td class="r">
                                <div class="flex items-center justify-end gap-1">
                                    @if ($a && ! $a->menunggu())
                                        <form method="POST" action="{{ route('admin.akun.reset', $a) }}"
                                              data-confirm data-confirm-title="Atur ulang kata sandi?"
                                              data-confirm-text="Kata sandi {{ $r->nama }} diganti dengan kata sandi sementara, dan sesi masuknya dikeluarkan."
                                              data-confirm-ok="Ya, atur ulang">
                                            @csrf
                                            <button type="submit" class="btn btn-ghost btn-sm btn-icon" aria-label="Atur ulang kata sandi {{ $r->nama }}" title="Atur ulang kata sandi">
                                                <svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-ghost btn-sm btn-icon" aria-label="Ubah {{ $r->nama }}" title="Ubah"
                                            data-open="dlg-ubah"
                                            data-form-action="{{ route('admin.pengguna.update', [$jenis, $r->id]) }}"
                                            data-fill="{{ json_encode([
                                                '#u-nomor' => $nomor,
                                                '#u-nama'  => $r->nama,
                                                '#u-info'  => $info ?? '',
                                                '#u-lahir' => $r->tanggal_lahir ? substr($r->tanggal_lahir, 0, 10) : '',
                                            ]) }}">
                                        <svg class="ico" aria-hidden="true"><use href="#i-edit"/></svg>
                                    </button>
                                    @if (! $a)
                                        <form method="POST" action="{{ route('admin.pengguna.destroy', [$jenis, $r->id]) }}"
                                              data-confirm data-confirm-title="Hapus dari data sekolah?"
                                              data-confirm-text="{{ $r->nama }} ({{ $nomor }}) tidak akan bisa mendaftar lagi."
                                              data-confirm-ok="Ya, hapus">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-ghost is-danger btn-sm btn-icon" aria-label="Hapus {{ $r->nama }}" title="Hapus">
                                                <svg class="ico" aria-hidden="true"><use href="#i-trash"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="table-foot">
                <span>Menampilkan {{ $data->firstItem() }}–{{ $data->lastItem() }} dari {{ $data->total() }} {{ $jenis }}</span>
                <div>{{ $data->links() }}</div>
            </div>
        @else
            <div class="empty">
                <span class="empty-ico"><svg class="ico" aria-hidden="true"><use href="#i-search"/></svg></span>
                <h3>Tidak ada data yang cocok</h3>
                <p>Coba kata kunci lain, atau tambahkan lewat tombol di atas.</p>
            </div>
        @endif
    </div>

    {{-- Dialog tambah --}}
    <dialog id="dlg-tambah" class="dlg" aria-labelledby="tambah-judul">
        <form method="POST" action="{{ route('admin.pengguna.store', $jenis) }}" class="flex flex-col flex-1 min-h-0">
            @csrf
            <div class="dlg-head">
                <div>
                    <p class="t-xs t-muted">Data sekolah</p>
                    <h2 id="tambah-judul" class="t-sec">Tambah {{ $jenis }}</h2>
                </div>
                <button type="button" class="btn btn-ghost btn-icon" data-close aria-label="Tutup"><svg class="ico" aria-hidden="true"><use href="#i-x"/></svg></button>
            </div>
            <div class="dlg-body space-y-4">
                <div>
                    <label class="label" for="t-nomor">{{ $labelNomor }}</label>
                    <input id="t-nomor" name="nomor" inputmode="numeric" class="field" required
                           placeholder="{{ $jenis === 'siswa' ? 'Contoh: 23409' : 'Contoh: 197512112005011005' }}">
                </div>
                <div>
                    <label class="label" for="t-nama">Nama lengkap</label>
                    <input id="t-nama" name="nama" class="field" required>
                </div>
                <div>
                    <label class="label" for="t-info">{{ $labelInfo }}</label>
                    <input id="t-info" name="info" class="field" placeholder="{{ $jenis === 'siswa' ? 'Contoh: XII RPL B' : 'Contoh: Matematika' }}">
                </div>
                <div>
                    <label class="label" for="t-lahir">Tanggal lahir</label>
                    <input id="t-lahir" type="date" name="tanggal_lahir" class="field">
                    <p class="hint">Dipakai untuk memastikan pendaftar benar-benar orangnya. Bila dikosongkan, akunnya perlu disetujui petugas.</p>
                </div>
            </div>
            <div class="dlg-foot">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </dialog>

    {{-- Dialog ubah --}}
    <dialog id="dlg-ubah" class="dlg" aria-labelledby="ubah-judul">
        <form method="POST" action="#" class="flex flex-col flex-1 min-h-0">
            @csrf @method('PUT')
            <div class="dlg-head">
                <div>
                    <p class="t-xs t-muted">{{ $labelNomor }} <span id="u-nomor"></span></p>
                    <h2 id="ubah-judul" class="t-sec">Ubah data {{ $jenis }}</h2>
                </div>
                <button type="button" class="btn btn-ghost btn-icon" data-close aria-label="Tutup"><svg class="ico" aria-hidden="true"><use href="#i-x"/></svg></button>
            </div>
            <div class="dlg-body space-y-4">
                <div>
                    <label class="label" for="u-nama">Nama lengkap</label>
                    <input id="u-nama" name="nama" class="field" required>
                </div>
                <div>
                    <label class="label" for="u-info">{{ $labelInfo }}</label>
                    <input id="u-info" name="info" class="field">
                </div>
                <div>
                    <label class="label" for="u-lahir">Tanggal lahir</label>
                    <input id="u-lahir" type="date" name="tanggal_lahir" class="field">
                    <p class="hint">Mengubah nama atau {{ strtolower($labelInfo) }} juga memperbarui akun yang sudah terdaftar.</p>
                </div>
            </div>
            <div class="dlg-foot">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </dialog>

    {{-- Dialog impor CSV --}}
    <dialog id="dlg-impor" class="dlg" aria-labelledby="impor-judul">
        <form method="POST" action="{{ route('admin.pengguna.impor', $jenis) }}" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0">
            @csrf
            <div class="dlg-head">
                <div>
                    <p class="t-xs t-muted">Data {{ $jenis }}</p>
                    <h2 id="impor-judul" class="t-sec">Impor dari CSV</h2>
                </div>
                <button type="button" class="btn btn-ghost btn-icon" data-close aria-label="Tutup"><svg class="ico" aria-hidden="true"><use href="#i-x"/></svg></button>
            </div>
            <div class="dlg-body space-y-4">
                <div>
                    <label class="label" for="i-berkas">Berkas CSV</label>
                    <input id="i-berkas" type="file" name="berkas" accept=".csv,text/csv,text/plain" class="field" required>
                </div>
                <div class="t-small t-muted">
                    <p>Urutan kolom: <b>{{ strtolower($labelNomor) }}, nama, {{ $jenis === 'siswa' ? 'kelas' : 'mata_pelajaran' }}, tanggal_lahir</b>. Kolom tanggal lahir boleh dikosongkan.</p>
                    <p style="margin-top:.5rem">Tanggal lahir ditulis <b>2008-03-25</b> atau <b>25/03/2008</b>. Baris judul di baris pertama boleh ada.</p>
                    <p style="margin-top:.5rem">{{ $labelNomor }} yang sudah ada akan diperbarui (cocok untuk kenaikan kelas), yang belum ada ditambahkan. Tidak ada data yang dihapus.</p>
                </div>
            </div>
            <div class="dlg-foot">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-primary">Impor</button>
            </div>
        </form>
    </dialog>
@endsection
