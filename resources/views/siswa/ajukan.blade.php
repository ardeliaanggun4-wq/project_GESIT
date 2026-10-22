{{-- Letak file: resources/views/siswa/ajukan.blade.php (timpa file lama) --}}
@extends('layouts.siswa')
@section('title', 'Ajukan Pinjam')

@section('content')
    <div class="mb-8">
        <p class="mono-label rise">Halaman 02 · Ajukan pinjam</p>
        <h1 class="display mt-3">
            <span class="line"><span style="--i:0">Formulir pengajuan</span></span>
            <span class="line"><span style="--i:1">peminjaman</span></span>
        </h1>
        <p class="soft text-sm mt-4 rise" style="--i:3">Isi selengkap mungkin agar petugas Sarpras bisa memverifikasi lebih cepat.</p>
    </div>

    @if ($errors->any())
        <div class="alert mb-6 max-w-2xl">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('siswa.ajukan.store') }}" class="card max-w-2xl rise" style="--i:2">
        @csrf
        <div class="form-head"><b>FR-04</b><span>Formulir peminjaman alat</span></div>

        <div class="p-6 md:p-8 space-y-7">
            <div>
                <label class="label" for="alat_id"><span class="label-no">01</span>Nama alat / inventaris</label>
                <select id="alat_id" name="alat_id" class="field" required>
                    <option value="">Pilih alat</option>
                    @foreach ($daftarAlat as $alat)
                        <option value="{{ $alat->id }}" {{ old('alat_id') == $alat->id ? 'selected' : '' }}>
                            {{ $alat->nama }} — {{ $alat->lokasi }} ({{ $alat->stok }} unit tersedia)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid sm:grid-cols-2 gap-7">
                <div>
                    <label class="label" for="tgl_pinjam"><span class="label-no">02</span>Tanggal pinjam</label>
                    <input id="tgl_pinjam" type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}" class="field" required>
                </div>
                <div>
                    <label class="label" for="tgl_kembali"><span class="label-no">03</span>Rencana kembali</label>
                    <input id="tgl_kembali" type="date" name="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana') }}" class="field" required>
                </div>
            </div>

            <div>
                <label class="label" for="jumlah"><span class="label-no">04</span>Jumlah alat</label>
                <input id="jumlah" type="number" min="1" name="jumlah" value="{{ old('jumlah', 1) }}" class="field" required>
            </div>

            <div>
                <label class="label" for="keperluan"><span class="label-no">05</span>Keperluan penggunaan</label>
                <textarea id="keperluan" name="keperluan" rows="3" placeholder="Contoh: praktik mata pelajaran Pemrograman Web, kelas XII RPL B"
                          class="field-box">{{ old('keperluan') }}</textarea>
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="btn btn-primary">Kirim pengajuan</button>
                <button type="button" class="btn btn-ghost">Simpan draf</button>
            </div>
        </div>
    </form>
@endsection