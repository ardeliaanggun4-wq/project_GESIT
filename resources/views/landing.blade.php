<!doctype html>
{{-- Letak file: resources/views/landing.blade.php (FILE BARU) --}}
{{-- Halaman awal untuk tamu: pengantar, tentang GESIT, dan di paling bawah tombol Masuk / Daftar akun. --}}
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>GESIT · Peminjaman alat praktik sekolah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head')
    @include('partials.auth-style')
</head>
<body>
    @include('partials.icons')
    @include('partials.dekor')
    <a href="#tentang" class="skip-link">Lewati ke bagian tentang GESIT</a>

    {{-- Bilah atas: merek dan jam --}}
    <header class="lp-top">
        <div class="brand">
            <span class="brand-mark"><svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg></span>
            <span>
                <span class="brand-name">GESIT</span>
                <span class="brand-sub">Gerakan Sarana Inventaris Terpadu</span>
            </span>
        </div>
        <div class="lp-clock" aria-hidden="true">
            <svg class="ico ico-sm"><use href="#i-calendar"/></svg><span data-date="pendek">{{ now()->translatedFormat('l, j M') }}</span>
            <svg class="ico ico-sm"><use href="#i-clock"/></svg><span data-clock>{{ now()->format('H:i') }}</span>
        </div>
    </header>

    <main id="konten">

        {{-- Pengantar --}}
        <section class="lp-hero" aria-labelledby="judul-awal">
            <div class="lp-text">
                <p class="lp-eyebrow rise"><svg class="ico" aria-hidden="true"><use href="#i-flask"/></svg>Peminjaman alat praktik sekolah</p>
                <h1 id="judul-awal" class="lp-headline" data-split>Alat <span class="hl">praktik lab</span>, siap dipinjam kapan saja.</h1>
                <p class="lp-lead rise" style="--i:6">
                    Ajukan pinjam mouse, keyboard, atau CPU dari HP, pantau statusnya, lalu ambil alat di ruang Sarpras begitu pengajuanmu disetujui.
                </p>
                <div class="lp-chips flex flex-wrap gap-2 rise" style="--i:9" aria-label="Alat lab">
                    <x-kategori nilai="komputer" label="Mouse" />
                    <x-kategori nilai="komputer" label="Keyboard" />
                    <x-kategori nilai="komputer" label="Laptop" />
                </div>
                <a href="#tentang" class="lp-more rise" style="--i:11">
                    Kenali GESIT
                    <svg class="ico" aria-hidden="true" style="transform:rotate(90deg)"><use href="#i-arrow"/></svg>
                </a>
            </div>

            {{-- Ilustrasi lab (dekoratif) --}}
            <div class="lp-stage">
                <span class="lp-tag">Lab komputer</span>
                <div class="rak-wrap">
                    @include('partials.lab-art')
                </div>
            </div>
        </section>

        {{-- Tentang, cara kerja, alat, dan tombol Masuk / Daftar akun di paling bawah --}}
        @include('partials.auth-tentang')
    </main>

    @include('partials.motion')

    @verbatim
    <style>
    .lp-top,.lp-hero{max-width:calc(96rem + 2*var(--tt-pad));margin-left:auto;margin-right:auto;padding-left:var(--tt-pad);padding-right:var(--tt-pad)}
    body{--tt-pad:clamp(1.25rem,4vw,4rem)}

    .lp-top{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding-top:1.25rem}
    .lp-clock{display:none;align-items:center;gap:.5rem;padding:.35rem .8rem;border-radius:999px;background:var(--surface);border:1px solid var(--line);font-size:.8125rem;font-weight:600;color:var(--ink-2);font-variant-numeric:tabular-nums}
    .lp-clock .ico{color:var(--primary)}
    @media (min-width:600px){.lp-clock{display:inline-flex}}

    .lp-hero{display:grid;grid-template-columns:minmax(0,1fr);gap:2.5rem;align-items:center;padding-top:2.5rem;padding-bottom:3rem}
    @media (min-width:980px){.lp-hero{grid-template-columns:minmax(0,1.05fr) minmax(0,1fr);gap:4rem;padding-top:3.5rem;padding-bottom:4.5rem}}

    .lp-eyebrow{display:inline-flex;align-items:center;gap:.5rem;margin:0;padding:.35rem .9rem .35rem .65rem;border-radius:999px;background:var(--c-lab-tint);border:1px solid #CFE6DF;font-size:.8125rem;font-weight:700;color:var(--c-lab-ink)}
    .lp-eyebrow .ico{width:1.05rem;height:1.05rem}
    .lp-headline{margin:1.1rem 0 0;font:600 clamp(2.4rem,5vw,4.1rem)/1.06 var(--font-head);letter-spacing:-.025em;max-width:14ch}
    .lp-hero .hl{background:linear-gradient(rgba(30,138,122,.26),rgba(30,138,122,.26)) no-repeat 0 90%/100% .34em;border-radius:.1em}
    .lp-lead{margin:1.25rem 0 0;max-width:34rem;font-size:1.1875rem;line-height:1.6;color:var(--ink-2)}
    .lp-chips{margin-top:1.5rem}
    .lp-more{display:inline-flex;align-items:center;gap:.5rem;margin-top:2rem;font-weight:600;color:var(--primary)}
    .lp-more .ico{width:1.1rem;height:1.1rem}
    .lp-more:hover{color:var(--primary-hover)}

    /* panggung ilustrasi */
    .lp-stage{position:relative;overflow:hidden;padding:clamp(2rem,3.5vw,3rem) clamp(1.25rem,3vw,2.5rem) clamp(1.25rem,3vw,2rem);border-radius:36px;border:1px solid #CFE6DF;background:linear-gradient(160deg,#E3F3EF 0%,#F4F9F6 70%);box-shadow:0 34px 60px -44px rgba(30,138,122,.7)}
    .lp-stage::before{content:"";position:absolute;right:1.5rem;top:1.5rem;width:7.5rem;height:5.5rem;opacity:.5;background-image:radial-gradient(var(--c-lab) 1.6px,transparent 1.8px);background-size:14px 14px;pointer-events:none}
    .lp-stage::after{content:"";position:absolute;left:-5rem;bottom:-6rem;width:18rem;height:18rem;border-radius:50%;background:radial-gradient(circle at 60% 40%,rgba(52,80,122,.16),transparent 68%);pointer-events:none}
    .lp-stage>*{position:relative;z-index:1}
    .lp-stage .rak-wrap{max-width:34rem}
    .lp-stage svg.rak{display:block;width:100%;height:auto}
    .lp-stage svg.lab *{transform-box:fill-box;transform-origin:center}
    .lp-tag{position:absolute !important;top:1.1rem;left:1.25rem;padding:.25rem .7rem;border-radius:999px;background:rgba(255,255,255,.85);border:1px solid #CFE6DF;font-size:.75rem;font-weight:700;letter-spacing:.04em;color:var(--c-lab-ink)}

    @media (prefers-reduced-motion:no-preference){
        .lp-stage{animation:labUp .7s var(--ease) .2s both}
        .lp-hero .brand-mark,.lp-top .brand-mark{animation:labSpin .8s var(--ease) both}
        .lp-hero .hl{animation:labHl .9s var(--ease) 1.1s both}
        .lp-hero .chip-kat{animation:labUp .55s var(--ease) both}
        .lp-hero .chip-kat:nth-child(1){animation-delay:1.5s}
        .lp-hero .chip-kat:nth-child(2){animation-delay:1.65s}
        .lp-hero .chip-kat:nth-child(3){animation-delay:1.8s}
    }
    </style>
    @endverbatim
</body>
</html>