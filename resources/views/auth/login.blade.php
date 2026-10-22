<!doctype html>
{{-- Letak file: resources/views/auth/login.blade.php (timpa file lama) --}}
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>GESIT · Masuk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head')
</head>
<body class="min-h-screen grid md:grid-cols-[1.1fr_1fr]">

    {{-- Panel kiri --}}
    <section class="relative overflow-hidden p-8 md:p-12 flex flex-col justify-between gap-10"
             style="background:#1f2e22; color:var(--paper); background-image:radial-gradient(rgba(236,231,218,.1) 1px,transparent 1px); background-size:20px 20px;">
        <div class="brand">
            <span class="brand-mark" style="background:var(--paper); color:var(--ink)">G</span>
            <span class="brand-name">GESIT</span>
        </div>

        <div class="flex flex-col gap-10">
            <div>
                <h1 class="display" style="font-size:clamp(2.1rem,4.2vw,3.4rem)">
                    <span class="line"><span style="--i:0">Pinjam alat,</span></span>
                    <span class="line"><span style="--i:1"><span class="hl">bukan ribet.</span></span></span>
                </h1>
                <p class="rise mt-6 max-w-sm text-sm leading-relaxed" style="--i:5; color:rgba(236,231,218,.72)">
                    Satu tempat untuk mengajukan, memantau, dan mengembalikan alat praktik — tanpa buku catatan yang gampang hilang.
                </p>
            </div>

            <div class="hidden md:flex justify-end rise pt-10 pr-6" style="--i:6">
                <div class="tag" data-recount title="Klik untuk menghitung ulang">
                    <div class="mono-label">Tag inventaris</div>
                    <div class="num mt-3" style="font-size:3.4rem" data-count="{{ $totalAlat ?? 147 }}">{{ $totalAlat ?? 147 }}</div>
                    <div class="font-semibold text-sm mt-2 leading-snug">alat aktif dipantau minggu ini</div>
                    <div class="mt-5 flex items-center justify-between">
                        <span class="kode muted">STOK REAL-TIME</span>
                        <span class="stamp stamp-ok" style="--i:8">Terpantau</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mono-label" style="color:rgba(236,231,218,.45)">Gerakan Sarana Inventaris Terpadu</div>
    </section>

    {{-- Panel kanan --}}
    <section class="flex items-center justify-center p-8">
        <div class="w-full max-w-sm">

            {{-- Pemilih peran: murni tampilan --}}
            <div class="seg mb-8 rise" style="--i:1" role="tablist">
                <span class="seg-pill" id="tab-pill"></span>
                <button type="button" id="tab-siswa" role="tab" onclick="pilihPeran('siswa')">Siswa / Guru</button>
                <button type="button" id="tab-admin" role="tab" onclick="pilihPeran('admin')">Admin / Petugas</button>
            </div>

            <h2 class="display" style="font-size:2rem">
                <span class="line"><span style="--i:2">Masuk ke akun kamu</span></span>
            </h2>
            <p id="catatan-peran" class="caret text-sm soft mt-3 min-h-[1.5rem]"></p>

            @if ($errors->any())
                <div class="alert mt-5">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6 mt-8">
                @csrf
                {{-- role_hint hanya untuk validasi ramah-pengguna di controller (opsional) --}}
                <input type="hidden" name="role_hint" id="role_hint" value="siswa">

                <div class="rise" style="--i:3">
                    <label class="label" for="input-nisn">NISN / NIP</label>
                    <input type="text" name="nisn_nip" id="input-nisn" placeholder="Contoh: 0091234567" class="field" required autofocus>
                </div>

                <div class="rise" style="--i:4">
                    <label class="label" for="input-sandi">Kata sandi</label>
                    <div class="relative">
                        <input type="password" name="password" id="input-sandi" placeholder="••••••••" class="field pr-14" required>
                        <button type="button" id="lihat-sandi" class="link-act absolute right-0 top-1/2 -translate-y-1/2">Lihat</button>
                    </div>
                </div>

                <div class="rise" style="--i:5">
                    <button type="submit" class="btn btn-primary w-full" style="padding-block:1rem">Masuk</button>
                </div>

                <div class="text-center rise" style="--i:6">
                    <a href="#" class="link-act muted">Lupa kata sandi?</a>
                </div>
            </form>
        </div>
    </section>

    @include('partials.motion')

    <script>
        const catatan = {
            siswa: 'Masuk dengan NISN (siswa) atau NIP (guru).',
            admin: 'Masuk dengan NIP petugas Sarpras.'
        };
        let ketikTimer;

        // Efek mengetik untuk catatan di bawah judul
        function ketik(el, teks) {
            clearInterval(ketikTimer);
            if (matchMedia('(prefers-reduced-motion: reduce)').matches) { el.textContent = teks; return; }
            el.textContent = '';
            let i = 0;
            ketikTimer = setInterval(() => {
                el.textContent = teks.slice(0, ++i);
                if (i >= teks.length) clearInterval(ketikTimer);
            }, 22);
        }

        function pilihPeran(peran) {
            const admin = peran === 'admin';
            document.getElementById('tab-siswa').setAttribute('aria-selected', String(!admin));
            document.getElementById('tab-admin').setAttribute('aria-selected', String(admin));
            document.getElementById('tab-pill').style.transform = admin ? 'translateX(100%)' : 'translateX(0)';
            document.getElementById('input-nisn').placeholder = admin ? 'Contoh: 0011234567' : 'Contoh: 0091234567';
            document.getElementById('role_hint').value = peran;
            ketik(document.getElementById('catatan-peran'), catatan[peran]);
        }

        // Tampilan awal
        pilihPeran('siswa');

        // Tampilkan / sembunyikan kata sandi
        document.getElementById('lihat-sandi').addEventListener('click', function () {
            const input = document.getElementById('input-sandi');
            const tampil = input.type === 'password';
            input.type = tampil ? 'text' : 'password';
            this.textContent = tampil ? 'Sembunyi' : 'Lihat';
        });

        // Klik tag inventaris = angka dihitung ulang
        document.querySelector('[data-recount]')?.addEventListener('click', function () {
            const angka = this.querySelector('[data-count]');
            if (angka) window.countUp(angka);
        });
    </script>
</body>
</html>