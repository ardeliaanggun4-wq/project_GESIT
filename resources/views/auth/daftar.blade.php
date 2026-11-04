<!doctype html>
{{-- Letak file: resources/views/auth/daftar.blade.php (FILE BARU) --}}
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar · GESIT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head')
    @include('partials.auth-style')
</head>
<body>
    @include('partials.icons')
    @include('partials.dekor')
    <a href="#konten" class="skip-link">Lewati ke formulir pendaftaran</a>

    <div class="auth">

        {{-- Panel cerita (hanya di layar lebar) --}}
        <section class="auth-art" aria-label="Tentang GESIT">
            <div class="brand">
                <span class="brand-mark"><svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg></span>
                <span class="brand-name">GESIT</span>
            </div>

            <div>
                <p class="auth-headline" data-split>Buat akun, lalu <span class="hl">pinjam alat</span> tanpa antre.</p>
                <p class="t-muted rise" style="--i:6;margin-top:1rem;max-width:30rem;font-size:1.0625rem">
                    Daftar sekali dengan NISN atau NIP. Setelah itu kamu bisa mengajukan pinjam dari HP dan memantau statusnya kapan saja.
                </p>
            </div>

            <div class="rak-wrap">
                @include('partials.lab-art')
            </div>

            <div class="flex flex-wrap gap-2 rise" style="--i:9" aria-label="Alat lab">
                <x-kategori nilai="komputer" label="Mouse" />
                <x-kategori nilai="komputer" label="Keyboard" />
                <x-kategori nilai="komputer" label="Laptop" />
            </div>
        </section>

        {{-- Formulir --}}
        <main id="konten" class="auth-form">
            <div class="auth-card is-wide">
                @include('partials.auth-hiasan')
                <div class="brand auth-brand-m">
                    <span class="brand-mark"><svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg></span>
                    <span>
                        <span class="brand-name">GESIT</span>
                        <span class="brand-sub">Gerakan Sarana Inventaris Terpadu</span>
                    </span>
                </div>

                <div class="auth-frame auth-panel">
                    <nav class="auth-tabs" aria-label="Masuk atau daftar">
                    <a href="{{ route('login') }}"><svg class="ico" aria-hidden="true"><use href="#i-user"/></svg>Masuk</a>
                    <a href="{{ route('daftar') }}" aria-current="page"><svg class="ico" aria-hidden="true"><use href="#i-plus"/></svg>Daftar</a>
                </nav>

                <hr class="frame-sep">

                <h1 class="t-page" style="margin-top:1.1rem" data-split>Buat akun GESIT</h1>
                <p class="t-muted" style="margin-top:.35rem">Untuk siswa dan guru. Akun admin dibuat oleh pihak sekolah.</p>

                @if ($errors->any())
                    <div class="alert" role="alert" id="galat" style="margin-top:1.25rem">
                        <svg class="ico" aria-hidden="true"><use href="#i-alert"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('daftar.simpan') }}" style="margin-top:1.5rem">
                    @csrf
                    <input type="hidden" name="role" id="role" value="{{ old('role', 'siswa') === 'guru' ? 'guru' : 'siswa' }}">

                    <div>
                        <span class="frame-cap" id="label-peran">Daftar sebagai</span>
                        <div class="peran-grid" role="group" aria-labelledby="label-peran">
                            <button type="button" id="peran-siswa" aria-pressed="true"><svg class="ico" aria-hidden="true"><use href="#i-user"/></svg>Siswa</button>
                            <button type="button" id="peran-guru" aria-pressed="false"><svg class="ico" aria-hidden="true"><use href="#i-book"/></svg>Guru</button>
                        </div>
                    </div>

                    <div style="margin-top:1.25rem">
                        <label class="label" for="input-nama">Nama lengkap</label>
                        <input type="text" name="name" id="input-nama" class="field" autocomplete="name"
                               placeholder="Contoh: Aulia Rahma" value="{{ old('name') }}" maxlength="100" required autofocus>
                    </div>

                    <div class="f-row">
                        <div>
                            <label class="label" for="input-nisn" id="label-nisn">NISN</label>
                            <input type="text" name="nisn_nip" id="input-nisn" class="field" inputmode="numeric"
                                   autocomplete="username" placeholder="Contoh: 0091234567" value="{{ old('nisn_nip') }}" required>
                        </div>
                        <div>
                            <label class="label" for="input-kelas" id="label-kelas">Kelas</label>
                            <input type="text" name="kelas_jabatan" id="input-kelas" class="field"
                                   placeholder="Contoh: XII RPL B" value="{{ old('kelas_jabatan') }}" maxlength="60" required>
                        </div>
                    </div>

                    <div style="margin-top:1.25rem">
                        <label class="label" for="input-email">Email</label>
                        <input type="email" name="email" id="input-email" class="field" autocomplete="email"
                               placeholder="nama@gesit.sch.id" value="{{ old('email') }}" required>
                        <p class="hint">Dipakai untuk mengganti kata sandi jika kamu lupa.</p>
                    </div>

                    <div class="f-row">
                        <div>
                            <label class="label" for="input-sandi">Kata sandi</label>
                            <div class="field-wrap">
                                <input type="password" name="password" id="input-sandi" class="field"
                                       style="padding-right:3.25rem" autocomplete="new-password" minlength="8" required>
                                <button type="button" class="field-act field-eye" id="lihat-sandi" aria-controls="input-sandi input-sandi2" aria-pressed="false" aria-label="Tampilkan kata sandi">
                                    <svg class="ico" aria-hidden="true"><use href="#i-eye"/></svg>
                                </button>
                            </div>
                            <p class="hint">Minimal 8 karakter.</p>
                        </div>
                        <div>
                            <label class="label" for="input-sandi2">Ulangi kata sandi</label>
                            <input type="password" name="password_confirmation" id="input-sandi2" class="field"
                                   autocomplete="new-password" minlength="8" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-cta">
                        Daftar sekarang<span class="go"><svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg></span>
                    </button>
                </form>
                </div>
            </div>
        </main>
    </div>

    @include('partials.toast')
    @include('partials.motion')

    <script>
        const peran = {
            siswa: { id: 'NISN', kelas: 'Kelas', idContoh: 'Contoh: 0091234567', kelasContoh: 'Contoh: XII RPL B' },
            guru:  { id: 'NIP', kelas: 'Jabatan / mata pelajaran', idContoh: 'Contoh: 198501012010011001', kelasContoh: 'Contoh: Guru Olahraga' }
        };

        function pilihPeran(nama) {
            const p = peran[nama];
            document.getElementById('peran-siswa').setAttribute('aria-pressed', String(nama === 'siswa'));
            document.getElementById('peran-guru').setAttribute('aria-pressed', String(nama === 'guru'));
            document.getElementById('role').value = nama;
            document.getElementById('label-nisn').textContent = p.id;
            document.getElementById('input-nisn').placeholder = p.idContoh;
            document.getElementById('label-kelas').textContent = p.kelas;
            document.getElementById('input-kelas').placeholder = p.kelasContoh;
        }
        document.getElementById('peran-siswa').addEventListener('click', () => pilihPeran('siswa'));
        document.getElementById('peran-guru').addEventListener('click', () => pilihPeran('guru'));
        pilihPeran(document.getElementById('role').value);

        // Lihat / sembunyikan kata sandi (ikon mata), berlaku untuk kedua kolom kata sandi
        document.getElementById('lihat-sandi').addEventListener('click', function () {
            const kolom = [document.getElementById('input-sandi'), document.getElementById('input-sandi2')];
            const tampil = kolom[0].type === 'password';
            kolom.forEach((k) => { k.type = tampil ? 'text' : 'password'; });
            this.querySelector('use').setAttribute('href', tampil ? '#i-eye-off' : '#i-eye');
            this.setAttribute('aria-label', tampil ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            this.setAttribute('aria-pressed', String(tampil));
        });
    </script>
</body>
</html>