<!doctype html>
{{-- Letak file: resources/views/auth/login.blade.php (timpa file lama) --}}
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Masuk · GESIT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head')
    @include('partials.auth-style')
</head>
<body>
    @include('partials.icons')
    @include('partials.dekor')
    <a href="#konten" class="skip-link">Lewati ke formulir masuk</a>

    <div class="auth">

        {{-- Panel cerita: lab komputer, alat praktik yang bisa dipinjam (hanya di layar lebar) --}}
        <section class="auth-art" aria-label="Tentang GESIT">
            <div class="brand">
                <span class="brand-mark"><svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg></span>
                <span class="brand-name">GESIT</span>
            </div>

            <div>
                <p class="auth-headline" data-split>Alat <span class="hl">praktik lab</span>, siap dipinjam kapan saja.</p>
                <p class="t-muted rise" style="--i:6;margin-top:1rem;max-width:30rem;font-size:1.0625rem">
                    Ajukan pinjam mouse, keyboard, atau CPU dari HP, pantau statusnya, lalu ambil alat di ruang Sarpras begitu pengajuanmu disetujui.
                </p>
            </div>

            {{-- Ilustrasi buatan sendiri; dekoratif. Animasinya ada di blok CSS bagian head. --}}
            <div class="rak-wrap">
            @include('partials.lab-art')
            </div>

            {{-- Daftar alat lab yang tersedia untuk dipinjam --}}
            <div class="flex flex-wrap gap-2 rise" style="--i:9" aria-label="Alat lab">
                <x-kategori nilai="komputer" label="Mouse" />
                <x-kategori nilai="komputer" label="Keyboard" />
                <x-kategori nilai="komputer" label="Laptop" />
            </div>
        </section>

        {{-- Formulir --}}
        <main id="konten" class="auth-form">
            <div class="auth-corner" aria-hidden="true">
                <svg class="ico ico-sm"><use href="#i-calendar"/></svg><span data-date="pendek">{{ now()->translatedFormat('l, j M') }}</span>
                <svg class="ico ico-sm"><use href="#i-clock"/></svg><span data-clock>{{ now()->format('H:i') }}</span>
            </div>
            <div class="auth-card">
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
                    <a href="{{ route('login') }}" aria-current="page"><svg class="ico" aria-hidden="true"><use href="#i-user"/></svg>Masuk</a>
                    <a href="{{ route('daftar') }}"><svg class="ico" aria-hidden="true"><use href="#i-plus"/></svg>Daftar</a>
                </nav>

                <span class="frame-cap" id="cap-peran">Masuk sebagai</span>
                <div class="peran-grid" role="group" aria-labelledby="cap-peran">
                    <button type="button" id="peran-siswa" aria-pressed="true"><svg class="ico" aria-hidden="true"><use href="#i-user"/></svg>Siswa / Guru</button>
                    <button type="button" id="peran-admin" aria-pressed="false"><svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg>Admin</button>
                </div>

                <hr class="frame-sep">

                <h1 class="t-page" style="margin-top:1.1rem" data-split>Masuk ke GESIT</h1>
                <p id="catatan-peran" class="t-muted" style="margin-top:.35rem" aria-live="polite">Siswa masuk dengan NIPD, guru dengan NIP.</p>

                @if ($errors->any())
                    <div class="alert" role="alert" id="galat" style="margin-top:1.25rem">
                        <svg class="ico" aria-hidden="true"><use href="#i-alert"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" style="margin-top:1.5rem">
                    @csrf
                    {{-- role_hint hanya untuk validasi ramah-pengguna di controller --}}
                    <input type="hidden" name="role_hint" id="role_hint" value="siswa">

                    <div>
                        <label class="label" for="input-nisn" id="label-nisn">NIPD / NIP</label>
                        <input type="text" name="nisn_nip" id="input-nisn" class="field"
                               inputmode="numeric" autocomplete="username"
                               placeholder="Contoh: 23409 (siswa) atau 197512112005011005 (guru)"
                               value="{{ old('nisn_nip') }}" required autofocus
                               @if ($errors->any()) aria-invalid="true" aria-describedby="galat" @endif>
                    </div>

                    <div style="margin-top:1.25rem">
                        <label class="label" for="input-sandi">Kata sandi</label>
                        <div class="field-wrap">
                            <input type="password" name="password" id="input-sandi" class="field"
                                   style="padding-right:3.25rem" autocomplete="current-password" required>
                            <button type="button" class="field-act field-eye" id="lihat-sandi" aria-controls="input-sandi" aria-pressed="false" aria-label="Tampilkan kata sandi">
                                <svg class="ico" aria-hidden="true"><use href="#i-eye"/></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-cta">
                        Masuk<span class="go"><svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg></span>
                    </button>
                </form>
                    <a href="{{ route('sandi.lupa') }}" class="btn btn-secondary btn-block btn-pill">
                        <svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg>Lupa kata sandi?
                    </a>
                </div>

                <ul class="feats rise" style="--i:8">
                    <li class="tone-primary"><span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-send"/></svg></span><b>Ajukan</b><small>dari HP, kapan saja</small></li>
                    <li class="tone-wait"><span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-clock"/></svg></span><b>Pantau</b><small>status pengajuanmu</small></li>
                    <li class="tone-ok"><span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-box"/></svg></span><b>Ambil</b><small>di ruang Sarpras</small></li>
                </ul>
            </div>
        </main>
    </div>

    @include('partials.toast')
    @include('partials.motion')

    <script>
        const peran = {
            siswa: { catatan: 'Siswa masuk dengan NIPD, guru dengan NIP.', label: 'NIPD / NIP', contoh: 'Contoh: 23409 (siswa) atau 197512112005011005 (guru)' },
            admin: { catatan: 'Petugas GESIT masuk dengan NIP.', label: 'NIP petugas', contoh: 'Contoh: 0011234567' }
        };

        function pilihPeran(nama) {
            const p = peran[nama];
            document.getElementById('peran-siswa').setAttribute('aria-pressed', String(nama === 'siswa'));
            document.getElementById('peran-admin').setAttribute('aria-pressed', String(nama === 'admin'));
            document.getElementById('role_hint').value = nama;
            document.getElementById('catatan-peran').textContent = p.catatan;
            document.getElementById('label-nisn').textContent = p.label;
            document.getElementById('input-nisn').placeholder = p.contoh;
        }
        document.getElementById('peran-siswa').addEventListener('click', () => pilihPeran('siswa'));
        document.getElementById('peran-admin').addEventListener('click', () => pilihPeran('admin'));

        // Lihat / sembunyikan kata sandi (ikon mata)
        document.getElementById('lihat-sandi').addEventListener('click', function () {
            const input = document.getElementById('input-sandi');
            const tampil = input.type === 'password';
            input.type = tampil ? 'text' : 'password';
            this.querySelector('use').setAttribute('href', tampil ? '#i-eye-off' : '#i-eye');
            this.setAttribute('aria-label', tampil ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            this.setAttribute('aria-pressed', String(tampil));
        });
    </script>
</body>
</html>