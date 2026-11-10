{{-- Letak file: resources/views/partials/auth-tentang.blade.php (timpa file lama) --}}
{{-- Isi halaman awal setelah hero: tentang, alasan, cara kerja, alat, dan tombol Masuk / Daftar akun di paling bawah.
     Teks muncul bertahap saat di-scroll; tanpa JavaScript atau dengan "kurangi gerak", semua langsung tampil. --}}

<section id="tentang" class="tentang" aria-labelledby="tentang-judul">
    <div class="tt-wrap">

        {{-- 1. Tentang: judul di kiri, penjelasan di kanan --}}
        <div class="tt-sec tt-split">
            <div>
                <p class="tt-cap rv">Tentang GESIT</p>
                <h2 id="tentang-judul" class="tt-judul rv" style="--d:1">Satu tempat untuk semua alat praktik sekolah.</h2>
            </div>
            <div>
                <p class="tt-isi rv" style="--d:2">
                    GESIT singkatan dari <b>Gerakan Sarana Inventaris Terpadu</b>. Web ini dibuat agar peminjaman alat
                    praktik tidak lagi lewat catatan kertas atau tanya satu per satu ke petugas.
                </p>
                <p class="tt-isi rv" style="--d:3">
                    Siswa dan guru mengajukan dari HP, petugas GESIT memeriksa dan menyetujui, dan semua pihak
                    bisa melihat alat mana yang sedang dipinjam dan mana yang tersedia.
                </p>
            </div>
        </div>

        {{-- 2. Tiga alasan --}}
        <ul class="tt-poin">
            <li class="rv" style="--d:1">
                <span class="row-ico tone-primary"><svg class="ico" aria-hidden="true"><use href="#i-send"/></svg></span>
                <b>Cepat</b>
                <small>Ajukan dari HP, tidak perlu antre di ruang Sarpras.</small>
            </li>
            <li class="rv" style="--d:2">
                <span class="row-ico tone-wait"><svg class="ico" aria-hidden="true"><use href="#i-clock"/></svg></span>
                <b>Terpantau</b>
                <small>Status pengajuan bisa dilihat kapan saja.</small>
            </li>
            <li class="rv" style="--d:3">
                <span class="row-ico tone-ok"><svg class="ico" aria-hidden="true"><use href="#i-shield"/></svg></span>
                <b>Tertib</b>
                <small>Stok dan riwayat pinjam tercatat rapi.</small>
            </li>
        </ul>

        {{-- 3. Cara kerja: empat langkah sejajar --}}
        <div class="tt-sec">
            <p class="tt-cap tt-tengah rv">Cara kerjanya</p>
            <h2 class="tt-judul tt-tengah rv" style="--d:1">Empat langkah, tanpa antre.</h2>

            <ol class="tt-langkah">
                <li class="rv" style="--d:1">
                    <span class="tt-no" aria-hidden="true">1</span>
                    <b>Ajukan</b>
                    <small>Pilih alat dan jumlahnya, lalu kirim pengajuan.</small>
                </li>
                <li class="rv" style="--d:2">
                    <span class="tt-no" aria-hidden="true">2</span>
                    <b>Tunggu persetujuan</b>
                    <small>Petugas GESIT memeriksa, statusnya bisa kamu pantau.</small>
                </li>
                <li class="rv" style="--d:3">
                    <span class="tt-no" aria-hidden="true">3</span>
                    <b>Ambil alat</b>
                    <small>Setelah disetujui, ambil di ruang Sarpras.</small>
                </li>
                <li class="rv" style="--d:4">
                    <span class="tt-no" aria-hidden="true">4</span>
                    <b>Kembalikan</b>
                    <small>Kembalikan dalam kondisi baik agar teman lain bisa pinjam.</small>
                </li>
            </ol>
        </div>

        {{-- 4. Alat yang tersedia --}}
        <div class="tt-alat rv">
            <div>
                <p class="tt-cap">Alat yang tersedia</p>
                <h2 class="tt-judul" style="font-size:clamp(1.4rem,2.6vw,1.9rem)">Mulai dari perlengkapan lab komputer.</h2>
                <p class="tt-isi">Stok setiap alat selalu diperbarui, jadi kamu tahu sebelum mengajukan apakah alatnya masih ada.</p>
            </div>
            <div class="flex flex-wrap gap-2" aria-label="Alat lab">
                <x-kategori nilai="komputer" label="Mouse" />
                <x-kategori nilai="komputer" label="Keyboard" />
                <x-kategori nilai="komputer" label="Laptop" />
            </div>
        </div>

        {{-- 5. Ajakan: dua tombol jelas, Masuk (utama) dan Daftar akun (pendamping) --}}
        <div class="tt-cta rv">
            <h2>Siap meminjam alat?</h2>
            <p>Sudah punya akun? Masuk dengan NIS atau NIP. Belum punya? Daftar dulu, hanya semenit.</p>
            <div class="tt-cta-aksi">
                <a href="{{ route('login') }}" class="tt-btn tt-btn-masuk">
                    <svg class="ico" aria-hidden="true"><use href="#i-user"/></svg>Masuk
                </a>
                <a href="{{ route('daftar') }}" class="tt-btn tt-btn-daftar">
                    <svg class="ico" aria-hidden="true"><use href="#i-plus"/></svg>Daftar akun
                </a>
            </div>
        </div>

        <p class="tt-foot">© {{ now()->year }} GESIT · Gerakan Sarana Inventaris Terpadu</p>
    </div>
</section>

@verbatim
<style>
/* ===== Bagian tentang ===== */
.tentang{position:relative;overflow:hidden;background:transparent;padding:clamp(3.5rem,6vw,5.5rem) clamp(1.25rem,4vw,4rem) 2rem}
.tentang::before{content:"";position:absolute;inset:0;pointer-events:none;background:radial-gradient(30rem 22rem at 0 0,rgba(30,138,122,.10),transparent 70%),radial-gradient(28rem 20rem at 100% 60%,rgba(52,80,122,.09),transparent 70%)}
.tt-wrap{position:relative;max-width:96rem;margin:0 auto}
.tt-sec{margin-bottom:clamp(3.5rem,7vw,5.5rem)}
.tt-cap{margin:0 0 .75rem;font-size:.75rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--c-lab-ink)}
.tt-judul{margin:0;font:600 clamp(1.7rem,3.6vw,2.6rem)/1.15 var(--font-head);letter-spacing:-.015em;color:var(--ink);max-width:26ch}
.tt-isi{margin:0 0 1rem;max-width:42rem;font-size:1.0625rem;line-height:1.65;color:var(--ink-2)}
.tt-isi b{color:var(--ink)}
.tt-tengah{text-align:center;margin-left:auto;margin-right:auto;max-width:none}

/* 1. Judul kiri, penjelasan kanan */
.tt-split{display:grid;grid-template-columns:minmax(0,1fr);gap:1.5rem 4rem;align-items:start}
@media (min-width:860px){.tt-split{grid-template-columns:minmax(0,1.05fr) minmax(0,1fr)}.tt-split .tt-isi:first-child{margin-top:.4rem}}

/* 2. Tiga poin */
.tt-poin{list-style:none;margin:calc(-1 * clamp(1rem,3vw,2rem)) 0 clamp(3.5rem,7vw,5.5rem);padding:0;display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem}
@media (max-width:760px){.tt-poin{grid-template-columns:minmax(0,1fr)}}
.tt-poin li{display:grid;grid-template-columns:auto 1fr;grid-template-rows:auto auto;column-gap:.9rem;align-items:center;padding:1.1rem 1.25rem;background:var(--surface);border:1px solid var(--line);border-radius:18px;box-shadow:0 14px 30px -24px rgba(52,80,122,.55)}
.tt-poin .row-ico{grid-row:1 / span 2}
.tt-poin b{font:600 1.0625rem/1.3 var(--font-head)}
.tt-poin small{font-size:.9rem;line-height:1.45;color:var(--muted)}

/* 3. Empat langkah sejajar, dengan garis penghubung di layar lebar */
.tt-langkah{list-style:none;counter-reset:l;margin:2.25rem 0 0;padding:0;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1.25rem;position:relative}
@media (max-width:980px){.tt-langkah{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width:520px){.tt-langkah{grid-template-columns:minmax(0,1fr)}}
@media (min-width:981px){.tt-langkah::before{content:"";position:absolute;left:12.5%;right:12.5%;top:1.5rem;height:2px;background:repeating-linear-gradient(90deg,var(--line-strong) 0 6px,transparent 6px 12px)}}
.tt-langkah li{position:relative;display:flex;flex-direction:column;align-items:center;text-align:center;gap:.3rem}
.tt-no{position:relative;display:grid;place-items:center;width:3rem;height:3rem;margin-bottom:.6rem;border-radius:50%;background:linear-gradient(180deg,#3F5F8F,var(--primary));color:#fff;font:700 1.2rem/1 var(--font-head);box-shadow:0 0 0 6px var(--bg),0 8px 16px -8px rgba(52,80,122,.9)}
.tt-langkah li:nth-child(2) .tt-no{background:linear-gradient(180deg,#D9A62E,var(--c-mm))}
.tt-langkah li:nth-child(3) .tt-no{background:linear-gradient(180deg,#2BA08E,var(--c-lab))}
.tt-langkah li:nth-child(4) .tt-no{background:linear-gradient(180deg,#6B7886,#4B5560)}
.tt-langkah b{font:600 1.0625rem/1.3 var(--font-head)}
.tt-langkah small{max-width:18rem;font-size:.9375rem;line-height:1.5;color:var(--muted)}

/* 4. Alat tersedia: pita sejajar */
.tt-alat{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1.5rem 2.5rem;margin-bottom:clamp(3.5rem,7vw,5.5rem);padding:clamp(1.5rem,3vw,2.25rem);background:var(--sand);border:1px solid var(--line);border-radius:24px}
.tt-alat>div:first-child{max-width:36rem}
.tt-alat .tt-isi{margin:.75rem 0 0;font-size:1rem}

/* 5. Panel ajakan dengan dua tombol */
.tt-cta{position:relative;overflow:hidden;text-align:center;padding:clamp(2.25rem,5vw,3.5rem) 1.5rem;border-radius:28px;color:#fff;background:radial-gradient(22rem 14rem at 90% 0,rgba(30,138,122,.55),transparent 70%),linear-gradient(160deg,#3F5F8F,#27405F);box-shadow:0 28px 50px -30px rgba(39,64,95,.9)}
.tt-cta h2{margin:0;font:600 clamp(1.6rem,3.4vw,2.3rem)/1.15 var(--font-head);letter-spacing:-.015em}
.tt-cta p{margin:.7rem auto 0;max-width:30rem;color:rgba(255,255,255,.82);font-size:1.0625rem}
.tt-cta-aksi{display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem;margin-top:1.75rem}
.tt-btn{display:inline-flex;align-items:center;justify-content:center;gap:.55rem;min-width:11.5rem;min-height:3.25rem;padding:0 1.6rem;border-radius:999px;font:600 1.0625rem/1 var(--font-body);text-decoration:none;border:2px solid transparent;transition:transform var(--dur),background var(--dur),box-shadow var(--dur),color var(--dur)}
.tt-btn .ico{width:1.2rem;height:1.2rem}
.tt-btn-masuk{background:#fff;color:var(--primary);box-shadow:0 12px 22px -12px rgba(0,0,0,.55)}
.tt-btn-masuk:hover{transform:translateY(-2px);box-shadow:0 16px 26px -12px rgba(0,0,0,.6)}
.tt-btn-daftar{background:transparent;color:#fff;border-color:rgba(255,255,255,.7)}
.tt-btn-daftar:hover{background:rgba(255,255,255,.14);border-color:#fff;transform:translateY(-2px)}
.tt-btn:focus-visible{outline:3px solid #fff;outline-offset:3px}
@media (max-width:480px){.tt-cta-aksi{flex-direction:column}.tt-btn{width:100%}}

.tt-foot{margin:2.25rem 0 0;text-align:center;font-size:.8125rem;color:var(--muted)}

/* Muncul saat di-scroll: hanya aktif bila JS jalan */
.rv-on .rv{opacity:0;transform:translateY(26px);transition:opacity .7s var(--ease),transform .7s var(--ease);transition-delay:calc(var(--d,0)*.12s)}
.rv-on .rv.is-in{opacity:1;transform:none}
@media (prefers-reduced-motion:reduce){.rv-on .rv{opacity:1;transform:none;transition:none}}
</style>

<script>
(() => {
    const els = document.querySelectorAll('.tentang .rv');
    if (!els.length || !('IntersectionObserver' in window)) return;
    if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    document.documentElement.classList.add('rv-on');
    const io = new IntersectionObserver((daftar) => {
        daftar.forEach((e) => {
            if (!e.isIntersecting) return;
            e.target.classList.add('is-in');
            io.unobserve(e.target);
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });
    els.forEach((el) => io.observe(el));
})();
</script>
@endverbatim