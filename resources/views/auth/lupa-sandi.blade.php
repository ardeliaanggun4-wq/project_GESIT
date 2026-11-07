<!doctype html>
{{-- Letak file: resources/views/auth/lupa-sandi.blade.php (FILE BARU) --}}
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Lupa kata sandi · GESIT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head')
    @include('partials.auth-style')
</head>
<body>
    @include('partials.icons')
    @include('partials.dekor')
    <a href="#konten" class="skip-link">Lewati ke formulir</a>

    <div class="auth">

        {{-- Panel cerita (hanya di layar lebar) --}}
        <section class="auth-art" aria-label="Tentang GESIT">
            <div class="brand">
                <span class="brand-mark"><svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg></span>
                <span class="brand-name">GESIT</span>
            </div>

            <div>
                <p class="auth-headline" data-split>Lupa kata sandi? Buat yang <span class="hl">baru</span> dalam semenit.</p>
                <p class="t-muted rise" style="--i:6;margin-top:1rem;max-width:30rem;font-size:1.0625rem">
                    Cukup isi NIPD atau NIP dan email akunmu, lalu pilih kata sandi baru. Setelah itu kamu bisa langsung masuk dan meminjam alat lab.
                </p>
            </div>

            <div class="rak-wrap">
                @include('partials.lab-art')
            </div>
        </section>

        {{-- Formulir --}}
        <main id="konten" class="auth-form">
            <div class="auth-card">
                @include('partials.auth-hiasan')
                <div class="brand auth-brand-m">
                    <span class="brand-mark"><svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg></span>
                    <span>
                        <span class="brand-name">GESIT</span>
                        <span class="brand-sub">Gerakan Sarana Inventaris Terpadu</span>
                    </span>
                </div>

                <h1 class="t-page" data-split>Ganti kata sandi</h1>
                <p class="t-muted" style="margin-top:.35rem">Isi data akunmu untuk membuat kata sandi baru.</p>

                @if ($errors->any())
                    <div class="alert" role="alert" id="galat" style="margin-top:1.25rem">
                        <svg class="ico" aria-hidden="true"><use href="#i-alert"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('sandi.simpan') }}" style="margin-top:1.5rem">
                    @csrf

                    <div>
                        <label class="label" for="input-nisn">NIPD / NIP</label>
                        <input type="text" name="nisn_nip" id="input-nisn" class="field"
                               inputmode="numeric" autocomplete="username"
                               placeholder="Contoh: 23409"
                               value="{{ old('nisn_nip') }}" required autofocus>
                    </div>

                    <div style="margin-top:1.25rem">
                        <label class="label" for="input-email">Email akun</label>
                        <input type="email" name="email" id="input-email" class="field"
                               autocomplete="email" placeholder="nama@gesit.sch.id"
                               value="{{ old('email') }}" required>
                    </div>

                    <div style="margin-top:1.25rem">
                        <label class="label" for="input-sandi">Kata sandi baru</label>
                        <div class="field-wrap">
                            <input type="password" name="password" id="input-sandi" class="field"
                                   style="padding-right:3.25rem" autocomplete="new-password" minlength="8" required>
                            <button type="button" class="field-act field-eye" id="lihat-sandi" aria-controls="input-sandi input-sandi2" aria-pressed="false" aria-label="Tampilkan kata sandi">
                                <svg class="ico" aria-hidden="true"><use href="#i-eye"/></svg>
                            </button>
                        </div>
                        <p class="hint">Minimal 8 karakter.</p>
                    </div>

                    <div style="margin-top:1.25rem">
                        <label class="label" for="input-sandi2">Ulangi kata sandi baru</label>
                        <input type="password" name="password_confirmation" id="input-sandi2" class="field"
                               autocomplete="new-password" minlength="8" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="margin-top:1.75rem;min-height:3rem">Simpan kata sandi baru</button>
                </form>

                <p class="t-small" style="margin-top:1.5rem;text-align:center">
                    <a href="{{ route('login') }}" class="link">Kembali ke halaman masuk</a>
                </p>
            </div>
        </main>
    </div>

    @include('partials.motion')

    <script>
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