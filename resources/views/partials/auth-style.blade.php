{{-- Letak file: resources/views/partials/auth-style.blade.php (FILE BARU) --}}
{{-- Gaya dan animasi masuk untuk halaman masuk dan lupa kata sandi. --}}
@verbatim
<style>
/* Animasi masuk halaman login: ilustrasi lab, judul, formulir.
   Semua keyframe hanya berisi keadaan awal (from), jadi tanpa animasi pun tampilan tetap utuh. */
.auth-art .hl{background:linear-gradient(rgba(30,138,122,.24),rgba(30,138,122,.24)) no-repeat 0 90%/100% .34em;border-radius:.1em}
.auth-art svg.lab *{transform-box:fill-box;transform-origin:center}

@media (prefers-reduced-motion:no-preference){
    /* teks & merek */
    .auth-art .brand-mark{animation:labSpin .8s var(--ease) both}
    .auth-art .hl{animation:labHl .9s var(--ease) 1.1s both}
    .auth-art .chip-kat{animation:labUp .55s var(--ease) both}
    .auth-art .chip-kat:nth-child(1){animation-delay:1.5s}
    .auth-art .chip-kat:nth-child(2){animation-delay:1.65s}
    .auth-art .chip-kat:nth-child(3){animation-delay:1.8s}

    /* formulir: muncul berurutan */
    .auth-card .brand,.auth-card>.auth-frame,.auth-card>p,.auth-card .alert,
    .auth-card form>div,.auth-card form>button{animation:labUp .55s var(--ease) both}
    .auth-card .brand{animation-delay:.15s}
    .auth-card>.auth-panel{animation-delay:.2s}
    .auth-card>p#catatan-peran{animation-delay:.5s}
    .auth-card form>div:nth-of-type(1){animation-delay:.6s}
    .auth-card form>div:nth-of-type(2){animation-delay:.72s}
    .auth-card form>button{animation:labUp .55s var(--ease) .85s both,labRing 1.3s ease-out 1.7s both}
    .auth-card>p.t-small{animation-delay:1s}
    .feats li{animation:labUp .5s var(--ease) both}
    .feats li:nth-child(1){animation-delay:1.1s}
    .feats li:nth-child(2){animation-delay:1.22s}
    .feats li:nth-child(3){animation-delay:1.34s}

    /* ilustrasi: latar, meja, perangkat masuk satu per satu */
    svg.lab .l-blob{animation:labPop 1s var(--ease) .05s both}
    svg.lab .l-desk{animation:labUp .6s var(--ease) .2s both}
    svg.lab .l-mon{animation:labPop .7s var(--ease) .4s both}
    svg.lab .l-tower{animation:labUp .7s var(--ease) .7s both}
    svg.lab .l-kb{animation:labUp .6s var(--ease) .9s both}
    svg.lab .l-pad{animation:labPop .5s var(--ease) 1.2s both}
    svg.lab .l-mouse{animation:labPop .5s var(--ease) 1.35s both}
    svg.lab .l-chip-in{animation:labPop .7s var(--ease) .6s both}
    svg.lab .l-chip{animation:labFloat 5s ease-in-out 1.4s infinite}
    svg.lab .l-usb{animation:labDrop .8s cubic-bezier(.3,1.6,.5,1) 1.5s both}
    svg.lab .l-wifi{animation:labPop .5s var(--ease) 1.7s both}

    /* isi layar: titik jendela, baris kode "diketik", kurung kode tergambar */
    svg.lab .l-dot{animation:labPop .35s var(--ease) calc(var(--d)*1s) both}
    svg.lab .l-line{transform-origin:left center;animation:labType .45s var(--ease) calc(var(--d)*1s) both}
    svg.lab .l-caret{animation:labPop .1s linear 2.3s both,labBlink 1s steps(1) 2.4s infinite}
    svg.lab .l-code{stroke-dasharray:1;animation:labDraw 1s ease-in-out 1.8s both}
    svg.lab .l-cur-in{animation:labPop .4s var(--ease) 2.7s both}
    svg.lab .l-cur{animation:labCursor 8s ease-in-out 3.3s infinite}
    svg.lab .l-shine{animation:labShine 7s ease-in-out 3s infinite}

    /* kabel mouse tergambar, tombol mouse berkedip klik */
    svg.lab .l-cable{stroke-dasharray:1;animation:labDraw .8s ease-out 1.6s both}
    svg.lab .l-click{animation:labClick 5s ease-in-out 3.6s infinite}

    /* detail yang terus hidup pelan-pelan */
    svg.lab .l-led{animation:labBlink 2.4s steps(1) 2s infinite}
    svg.lab .l-led2{animation-duration:3.1s;animation-delay:2.6s}
    svg.lab .l-ping{animation:labPing 3s ease-out 2.4s infinite}
    svg.lab .l-vent{animation:labType .4s var(--ease) calc(var(--d)*1s) both}
    svg.lab .l-disc{animation:labDisc 4s ease-in-out 2.5s infinite}
    svg.lab .l-core{animation:labCore 2.4s ease-in-out 2s infinite}
    svg.lab .l-key{animation:labKey 4.2s ease-in-out calc(2.4s + var(--k)*1s) infinite}
    svg.lab .l-w2,svg.lab .l-w1,svg.lab .l-w0{animation:labWifi 2.4s ease-in-out 2.2s infinite}
    svg.lab .l-w1{animation-delay:2.45s}
    svg.lab .l-w2{animation-delay:2.7s}
    svg.lab .l-tw{animation:labTwinkle 3.2s ease-in-out 1.5s infinite}
    svg.lab .l-tw2{animation-delay:2.3s}
    svg.lab .l-tw3{animation-delay:.9s}
}
@keyframes labUp{from{opacity:0;transform:translateY(22px)}}
@keyframes labPop{from{opacity:0;transform:scale(.6)}}
@keyframes labSpin{from{opacity:0;transform:scale(.4) rotate(-90deg)}}
@keyframes labDrop{from{opacity:0;transform:translateY(-46px) rotate(-14deg)}}
@keyframes labHl{from{background-size:0 .34em}}
@keyframes labType{from{opacity:0;transform:scaleX(0)}}
@keyframes labDraw{from{stroke-dashoffset:1}to{stroke-dashoffset:0}}
@keyframes labBlink{50%{opacity:.15}}
@keyframes labFloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-9px)}}
@keyframes labCursor{
    0%,8%{transform:translate(0,0)}
    28%{transform:translate(-120px,-70px)}
    34%{transform:translate(-120px,-70px) scale(.85)}
    40%{transform:translate(-120px,-70px)}
    62%{transform:translate(-40px,-10px)}
    100%{transform:translate(0,0)}
}
@keyframes labShine{0%{transform:translateX(-120px)}35%,100%{transform:translateX(320px)}}
@keyframes labClick{0%,84%,100%{fill:#1E8A7A;transform:none}90%{fill:#C8961E;transform:translateY(1.5px)}}
@keyframes labPing{0%{transform:scale(1);stroke-opacity:.7}80%,100%{transform:scale(2);stroke-opacity:0}}
@keyframes labDisc{0%,40%,100%{transform:translateX(0)}60%{transform:translateX(26px)}}
@keyframes labCore{0%,100%{fill:#E3F3EF}50%{fill:#C8961E}}
@keyframes labKey{0%,82%,100%{fill:#BFD9D4;transform:none}90%{fill:#1E8A7A;transform:translateY(1.5px)}}
@keyframes labWifi{0%,100%{opacity:.25}35%{opacity:1}}
@keyframes labTwinkle{0%,100%{opacity:.15;transform:scale(.7)}50%{opacity:.8;transform:scale(1.2)}}
@keyframes labRing{from{box-shadow:0 0 0 0 rgba(52,80,122,.45)}to{box-shadow:0 0 0 16px rgba(52,80,122,0)}}

/* Satu bingkai dari atas ke bawah: tab, peran, formulir, tombol */
.auth-card{max-width:27rem}
.auth-card.is-wide{max-width:32rem}
.auth-frame{padding:1.25rem;border:1px solid var(--line);border-radius:24px;background:linear-gradient(180deg,#fff 0%,var(--surface-2) 100%);box-shadow:0 18px 38px -26px rgba(52,80,122,.6),inset 0 1px 0 #fff}
.frame-cap{display:block;margin:.9rem .1rem .5rem;font-size:.75rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted)}
div>.frame-cap:first-child{margin-top:0}
.frame-sep{height:1px;margin:1.1rem 0 0;border:0;background:var(--line)}
.btn-pill{min-height:2.75rem;margin-top:.75rem;border-radius:999px;font-size:.9375rem}
.btn-pill .ico{width:1.1rem;height:1.1rem;color:var(--primary)}
@media (max-width:420px){.auth-frame{padding:1rem;border-radius:20px}}

/* Tab Masuk / Daftar: pil dengan ikon */
.auth-tabs{display:grid;grid-template-columns:1fr 1fr;gap:4px;padding:4px;background:var(--surface-2);border:1px solid var(--line);border-radius:999px}
.auth-tabs a{display:flex;align-items:center;justify-content:center;gap:.5rem;min-height:2.75rem;border-radius:999px;font-weight:600;color:var(--muted);transition:color var(--dur),background var(--dur),box-shadow var(--dur)}
.auth-tabs a:hover{color:var(--ink)}
.auth-tabs a[aria-current="page"]{background:linear-gradient(180deg,#3F5F8F,var(--primary));color:#fff;box-shadow:0 6px 14px -7px rgba(52,80,122,.85)}
.auth-tabs .ico{width:1.1rem;height:1.1rem}
@media (prefers-reduced-motion:no-preference){
    .auth-card form>div:nth-of-type(n+3){animation-delay:.84s}
}

/* Pilihan peran: chip berbentuk pil */
.peran-grid{display:flex;flex-wrap:wrap;gap:.5rem}
.peran-grid button{display:inline-flex;align-items:center;gap:.5rem;min-height:2.5rem;padding:0 1.1rem 0 .85rem;background:var(--surface);border:1.5px solid var(--line-strong);border-radius:999px;font-weight:600;color:var(--ink-2);cursor:pointer;transition:border-color var(--dur),background var(--dur),color var(--dur),box-shadow var(--dur)}
.peran-grid button .ico{width:1.1rem;height:1.1rem}
.peran-grid button:hover{border-color:var(--primary);color:var(--primary)}
.peran-grid button[aria-pressed="true"]{border-color:var(--primary);background:var(--primary-tint);color:var(--primary);box-shadow:0 0 0 3px rgba(52,80,122,.12)}

/* Baris dua kolom & lebar kartu */
.f-row{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;align-items:start;margin-top:1.25rem}
@media (max-width:520px){.f-row{grid-template-columns:minmax(0,1fr)}}

/* Tombol utama: label di tengah, panah dalam lingkaran di kanan */
.btn-cta{position:relative;justify-content:center;min-height:3.25rem;margin-top:1.75rem;padding:0 3.4rem;border-radius:999px;font-size:1.0625rem;text-align:center}
.btn-cta .go{position:absolute;right:.5rem;top:50%;display:grid;place-items:center;width:2.4rem;height:2.4rem;margin-top:-1.2rem;border-radius:50%;background:rgba(255,255,255,.2);transition:transform var(--dur),background var(--dur)}
.btn-cta:hover .go{transform:translateX(3px);background:rgba(255,255,255,.3)}

/* ===== Hiasan di sekitar kartu masuk / daftar ===== */
.auth-form{position:relative;overflow:hidden}
.auth-card{position:relative;isolation:isolate}
.ah{position:absolute;inset:0;z-index:-1;pointer-events:none}
.ah-blob{position:absolute;border-radius:50%}
.ah-b1{top:-5rem;right:-6rem;width:15rem;height:15rem;background:radial-gradient(circle at 35% 35%,rgba(52,80,122,.20),transparent 68%)}
.ah-b2{bottom:-4rem;left:-5rem;width:14rem;height:14rem;background:radial-gradient(circle at 60% 40%,rgba(30,138,122,.20),transparent 68%)}
.ah-dots,.ah-ring{display:none}
.ah-dots{position:absolute;width:7.5rem;height:5.5rem;opacity:.55;background-image:radial-gradient(var(--c-lab) 1.6px,transparent 1.8px);background-size:14px 14px}
.ah-d1{top:-1.6rem;left:-2.2rem}
.ah-d2{bottom:-1.4rem;right:-2.2rem;background-image:radial-gradient(var(--c-olah) 1.6px,transparent 1.8px)}
.ah-ring{position:absolute;left:-3.4rem;bottom:8rem;width:5.5rem;height:5.5rem;border-radius:50%;border:6px solid var(--c-kes);opacity:.22}
@media (min-width:600px){.ah-dots,.ah-ring{display:block}
/* Tombol mata pada kolom kata sandi */
.field-wrap .field-act.field-eye{display:inline-flex;align-items:center;justify-content:center;padding:0;min-width:0;width:var(--tap)}
.field-wrap .field-act.field-eye .ico{width:1.25rem;height:1.25rem}
</style>
@endverbatim