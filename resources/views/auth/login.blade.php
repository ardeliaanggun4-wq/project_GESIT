<!doctype html>
{{-- Letak file: resources/views/auth/login.blade.php (timpa file lama) --}}
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Masuk · GESIT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head')
</head>
<body>
    @include('partials.icons')
    @include('partials.dekor')
    <a href="#konten" class="skip-link">Lewati ke formulir masuk</a>

    <div class="auth">

        {{-- Panel cerita: rak penyimpanan alat sekolah (hanya di layar lebar) --}}
        <section class="auth-art" aria-label="Tentang GESIT">
            <div class="brand">
                <span class="brand-mark"><svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg></span>
                <span class="brand-name">GESIT</span>
            </div>

            <div>
                <p class="auth-headline" data-split>Semua alat sekolah, tertata di satu rak.</p>
                <p class="t-muted rise" style="--i:6;margin-top:1rem;max-width:30rem;font-size:1.0625rem">
                    Ajukan pinjam dari HP, pantau statusnya, lalu ambil alat di ruang Sarpras begitu pengajuanmu disetujui.
                </p>
            </div>

            {{-- Ilustrasi buatan sendiri; dekoratif --}}
            <div class="rak-wrap">
            <span class="chip-float cf-1"><svg class="ico ico-sm" aria-hidden="true"><use href="#i-send"/></svg>Ajukan dari HP</span>
            <span class="chip-float cf-2"><svg class="ico ico-sm" aria-hidden="true"><use href="#i-clock"/></svg>Pantau status</span>
            <span class="chip-float cf-3"><svg class="ico ico-sm" aria-hidden="true"><use href="#i-check"/></svg>Siap diambil</span>
            <svg class="rak rise" style="--i:7" viewBox="0 0 520 400" aria-hidden="true" focusable="false">
                {{-- rangka rak --}}
                <rect x="36" y="24" width="8" height="360" rx="3" fill="#BFB299"/>
                <rect x="476" y="24" width="8" height="360" rx="3" fill="#BFB299"/>
                <rect x="30" y="130" width="460" height="10" rx="4" fill="#CBBFA8"/>
                <rect x="30" y="250" width="460" height="10" rx="4" fill="#CBBFA8"/>
                <rect x="30" y="370" width="460" height="10" rx="4" fill="#CBBFA8"/>

                {{-- rak 1: alat praktik lab --}}
                <rect x="60" y="70" width="110" height="60" rx="8" fill="#1E8A7A"/>
                <rect x="78" y="94" width="46" height="24" rx="5" fill="#fff"/>
                <rect x="84" y="101" width="34" height="3" rx="1.5" fill="#BFD9D4"/>
                <rect x="84" y="108" width="20" height="3" rx="1.5" fill="#BFD9D4"/>
                <path d="M214 70h12v26l24 34H190l24-34z" fill="#E3F3EF" stroke="#1E8A7A" stroke-width="3" stroke-linejoin="round"/>
                <path d="M200 116h40l10 14H190z" fill="#1E8A7A"/>
                <rect x="282" y="88" width="44" height="42" rx="6" fill="#E3F3EF" stroke="#1E8A7A" stroke-width="3"/>
                <path d="M284 108h40v16a4 4 0 0 1-4 4h-32a4 4 0 0 1-4-4z" fill="#1E8A7A"/>
                <rect x="350" y="94" width="96" height="36" rx="7" fill="#E3F3EF" stroke="#1E8A7A" stroke-width="3"/>
                <rect x="366" y="104" width="28" height="14" rx="3" fill="#1E8A7A" opacity=".85"/>

                {{-- rak 2: alat olahraga --}}
                <circle cx="104" cy="205" r="45" fill="#D9622B"/>
                <path d="M59 205h90M104 160c-24 22-24 68 0 90M104 160c24 22 24 68 0 90" fill="none" stroke="#fff" stroke-opacity=".75" stroke-width="3"/>
                <path d="M190 250l22-62 22 62z" fill="#D9622B"/>
                <polygon points="199.9,222 224.1,222 227.6,232 196.4,232" fill="#fff" opacity=".85"/>
                <rect x="184" y="246" width="56" height="4" rx="2" fill="#A4410F"/>
                <rect x="270" y="196" width="120" height="54" rx="8" fill="#D9622B"/>
                <rect x="288" y="212" width="46" height="24" rx="5" fill="#fff"/>
                <rect x="294" y="219" width="34" height="3" rx="1.5" fill="#F1CDBB"/>
                <rect x="294" y="226" width="20" height="3" rx="1.5" fill="#F1CDBB"/>
                <ellipse cx="440" cy="205" rx="22" ry="28" fill="none" stroke="#D9622B" stroke-width="5"/>
                <path d="M440 177v56M418 205h44" stroke="#D9622B" stroke-opacity=".45" stroke-width="2"/>
                <path d="M440 233v17" stroke="#A4410F" stroke-width="6" stroke-linecap="round"/>

                {{-- rak 3: kesenian dan multimedia --}}
                <rect x="60" y="316" width="100" height="54" rx="8" fill="#A8479A"/>
                <rect x="76" y="330" width="40" height="24" rx="5" fill="#fff"/>
                <rect x="82" y="337" width="28" height="3" rx="1.5" fill="#E8CFE4"/>
                <rect x="82" y="344" width="16" height="3" rx="1.5" fill="#E8CFE4"/>
                <ellipse cx="210" cy="350" rx="36" ry="20" fill="#F6E8F4" stroke="#A8479A" stroke-width="3"/>
                <circle cx="192" cy="344" r="5" fill="#1E8A7A"/>
                <circle cx="208" cy="338" r="5" fill="#D9622B"/>
                <circle cx="224" cy="342" r="5" fill="#C8961E"/>
                <circle cx="196" cy="358" r="5" fill="#34507A"/>
                <path d="M268 368l14-50" stroke="#9A7B4F" stroke-width="5" stroke-linecap="round"/>
                <path d="M279 330l3-14" stroke="#A8479A" stroke-width="7" stroke-linecap="round"/>
                <rect x="318" y="334" width="92" height="36" rx="8" fill="#C8961E"/>
                <circle cx="348" cy="352" r="12" fill="#fff"/>
                <circle cx="348" cy="352" r="6" fill="#745400"/>
                <path d="M376 346h26M376 353h26M376 360h26" stroke="#fff" stroke-opacity=".7" stroke-width="3" stroke-linecap="round"/>
                <rect x="428" y="300" width="46" height="70" rx="8" fill="#FBF0D5" stroke="#C8961E" stroke-width="3"/>
                <circle cx="451" cy="322" r="7" fill="#C8961E"/>
                <circle cx="451" cy="348" r="13" fill="#C8961E"/>
                <circle cx="451" cy="348" r="5" fill="#FBF0D5"/>
            </svg>
            </div>

            {{-- Kode warna kategori, sama dengan yang dipakai di seluruh aplikasi --}}
            <div class="flex flex-wrap gap-2 rise" style="--i:9" aria-label="Kategori alat">
                <x-kategori nilai="praktik_lab" />
                <x-kategori nilai="olahraga" />
                <x-kategori nilai="kesenian" />
                <x-kategori nilai="multimedia" />
            </div>
        </section>

        {{-- Formulir --}}
        <main id="konten" class="auth-form">
            <div class="auth-corner" aria-hidden="true">
                <svg class="ico ico-sm"><use href="#i-calendar"/></svg><span data-date="pendek">{{ now()->translatedFormat('l, j M') }}</span>
                <svg class="ico ico-sm"><use href="#i-clock"/></svg><span data-clock>{{ now()->format('H:i') }}</span>
            </div>
            <div class="auth-card">
                <div class="brand auth-brand-m">
                    <span class="brand-mark"><svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg></span>
                    <span>
                        <span class="brand-name">GESIT</span>
                        <span class="brand-sub">Gerakan Sarana Inventaris Terpadu</span>
                    </span>
                </div>

                {{-- Pemilih peran: murni tampilan, mengisi role_hint --}}
                <div class="seg" role="group" aria-label="Masuk sebagai">
                    <button type="button" id="peran-siswa" aria-pressed="true">Siswa / Guru</button>
                    <button type="button" id="peran-admin" aria-pressed="false">Admin / Petugas</button>
                </div>

                <h1 class="t-page" style="margin-top:1.75rem" data-split>Masuk ke GESIT</h1>
                <p id="catatan-peran" class="t-muted" style="margin-top:.35rem" aria-live="polite">Siswa masuk dengan NISN, guru dengan NIP.</p>

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
                        <label class="label" for="input-nisn" id="label-nisn">NISN / NIP</label>
                        <input type="text" name="nisn_nip" id="input-nisn" class="field"
                               inputmode="numeric" autocomplete="username"
                               placeholder="Contoh: 0091234567"
                               value="{{ old('nisn_nip') }}" required autofocus
                               @if ($errors->any()) aria-invalid="true" aria-describedby="galat" @endif>
                    </div>

                    <div style="margin-top:1.25rem">
                        <label class="label" for="input-sandi">Kata sandi</label>
                        <div class="field-wrap">
                            <input type="password" name="password" id="input-sandi" class="field"
                                   style="padding-right:6rem" autocomplete="current-password" required>
                            <button type="button" class="field-act" id="lihat-sandi" aria-controls="input-sandi" aria-pressed="false">Lihat</button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="margin-top:1.75rem;min-height:3rem">Masuk</button>
                </form>

                <p class="t-small t-muted" style="margin-top:1.5rem;text-align:center">
                    Lupa kata sandi? Minta petugas Sarpras mengatur ulang akunmu.
                    <span class="soon-tag" style="margin-top:.4rem">Reset mandiri segera hadir</span>
                </p>

                <ul class="feats rise" style="--i:8">
                    <li class="tone-primary"><span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-send"/></svg></span><b>Ajukan</b><small>dari HP, kapan saja</small></li>
                    <li class="tone-wait"><span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-clock"/></svg></span><b>Pantau</b><small>status pengajuanmu</small></li>
                    <li class="tone-ok"><span class="row-ico"><svg class="ico" aria-hidden="true"><use href="#i-box"/></svg></span><b>Ambil</b><small>di ruang Sarpras</small></li>
                </ul>
            </div>
        </main>
    </div>

    @include('partials.motion')

    <script>
        const peran = {
            siswa: { catatan: 'Siswa masuk dengan NISN, guru dengan NIP.', label: 'NISN / NIP', contoh: 'Contoh: 0091234567' },
            admin: { catatan: 'Petugas Sarpras masuk dengan NIP.', label: 'NIP petugas', contoh: 'Contoh: 0011234567' }
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

        // Lihat / sembunyikan kata sandi
        document.getElementById('lihat-sandi').addEventListener('click', function () {
            const input = document.getElementById('input-sandi');
            const tampil = input.type === 'password';
            input.type = tampil ? 'text' : 'password';
            this.textContent = tampil ? 'Sembunyi' : 'Lihat';
            this.setAttribute('aria-pressed', String(tampil));
        });
    </script>
</body>
</html>