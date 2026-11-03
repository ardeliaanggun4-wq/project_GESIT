{{-- Letak file: resources/views/partials/dekor.blade.php (timpa file lama) --}}
{{-- Latar dekoratif semua halaman: bentuk melayang pelan + elemen tambahan di sisi kanan layar lebar.
     - Murni hiasan: aria-hidden, tidak bisa diklik, berada di belakang konten.
     - Bentuk bergerak sedikit mengikuti scroll (parallax) dan melayang pelan.
     - Semua gerakan dimatikan bila pengguna memilih "kurangi animasi". --}}
<div class="dekor" aria-hidden="true">

    {{-- ===== Inti: tampil di semua ukuran layar ===== --}}
    <span class="dx dx-ring-a" style="--k:.10"><svg class="dxi" viewBox="0 0 80 80" focusable="false" style="--t:9s"><circle cx="40" cy="40" r="30" fill="none" stroke="currentColor" stroke-width="5"/></svg></span>
    <span class="dx dx-plus-a" style="--k:.16"><svg class="dxi" viewBox="0 0 40 40" focusable="false" style="--t:10s;--d:-3s"><path d="M20 6v28M6 20h28" stroke="currentColor" stroke-width="5" stroke-linecap="round"/></svg></span>
    <span class="dx dx-tri-a" style="--k:.22"><svg class="dxi" viewBox="0 0 60 60" focusable="false" style="--t:11s;--d:-5s;--r:-8deg"><path d="M30 8l24 42H6z" fill="none" stroke="currentColor" stroke-width="5" stroke-linejoin="round"/></svg></span>

    {{-- Kabut warna lembut di kanan atas dan kanan bawah --}}
    <span class="dx dx-blob dx-blob-a" style="--k:.05"><i class="dxi"></i></span>
    <span class="dx dx-blob dx-blob-b" style="--k:.08"><i class="dxi"></i></span>

    {{-- ===== Sisi kanan: tampil di layar lebar (ruang kosong di samping konten) ===== --}}
    <div class="dx-gutter">
        {{-- Lingkaran orbit dengan titik yang berputar --}}
        <span class="dx dx-orbit" style="--k:.06">
            <svg viewBox="0 0 240 240" focusable="false">
                <circle cx="120" cy="120" r="112" fill="none" stroke="var(--c-lab)" stroke-width="1.5" stroke-dasharray="3 9" stroke-linecap="round"/>
                <circle cx="120" cy="120" r="76" fill="none" stroke="var(--primary)" stroke-width="1.5" opacity=".6"/>
                <circle cx="120" cy="120" r="8" fill="var(--c-mm)"/>
                <g class="dx-orbit-spin"><circle cx="232" cy="120" r="9" fill="var(--c-olah)"/></g>
                <g class="dx-orbit-spin dx-orbit-spin-2"><circle cx="120" cy="44" r="6" fill="var(--c-kes)"/></g>
            </svg>
        </span>

        {{-- Petak titik-titik --}}
        <span class="dx dx-dots" style="--k:.14"><i class="dxi"></i></span>

        {{-- Garis bergelombang --}}
        <span class="dx dx-wave" style="--k:.20"><svg class="dxi" viewBox="0 0 160 30" focusable="false" style="--t:12s;--d:-2s;--r:2deg"><path d="M4 15c10-14 20-14 30 0s20 14 30 0 20-14 30 0 20 14 30 0 20-14 28-3" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round"/></svg></span>

        {{-- Bentuk-bentuk kecil warna kategori --}}
        <span class="dx dx-s1" style="--k:.12"><svg class="dxi" viewBox="0 0 60 60" focusable="false" style="--t:8s;--r:14deg"><rect x="12" y="12" width="36" height="36" rx="6" transform="rotate(45 30 30)" fill="none" stroke="currentColor" stroke-width="5"/></svg></span>
        <span class="dx dx-s2" style="--k:.24"><svg class="dxi" viewBox="0 0 80 80" focusable="false" style="--t:10s;--d:-4s"><circle cx="40" cy="40" r="22" fill="currentColor"/></svg></span>
        <span class="dx dx-s3" style="--k:.18"><svg class="dxi" viewBox="0 0 40 40" focusable="false" style="--t:9s;--d:-6s;--r:-12deg"><path d="M20 6v28M6 20h28" stroke="currentColor" stroke-width="5" stroke-linecap="round"/></svg></span>
        <span class="dx dx-s4" style="--k:.10"><svg class="dxi" viewBox="0 0 80 40" focusable="false" style="--t:11s;--d:-1s"><path d="M6 36a34 34 0 0 1 68 0z" fill="none" stroke="currentColor" stroke-width="5" stroke-linejoin="round"/></svg></span>
        <span class="dx dx-s5" style="--k:.26"><svg class="dxi" viewBox="0 0 60 60" focusable="false" style="--t:12s;--d:-7s;--r:10deg"><path d="M30 8l24 42H6z" fill="none" stroke="currentColor" stroke-width="5" stroke-linejoin="round"/></svg></span>
    </div>
</div>

@verbatim
<style>
    /* Dasar */
    .dx { position: absolute; display: block; transform: translate3d(0, calc(var(--sy, 0) * var(--k, 0) * -1px), 0); will-change: transform; }
    .dxi { display: block; width: 100%; height: auto; opacity: .32; animation: dxFloat var(--t, 9s) ease-in-out infinite; animation-delay: var(--d, 0s); }
    @keyframes dxFloat { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-14px) rotate(var(--r, 6deg)); } }
    @keyframes dxDrift { 0%, 100% { transform: translate(0, 0) scale(1); } 50% { transform: translate(-26px, 20px) scale(1.07); } }
    @keyframes dxSpin  { to { transform: rotate(360deg); } }

    /* Inti */
    .dx-ring-a { width: 5.5rem; right: 4%;  top: 18%;    color: var(--c-lab); }
    .dx-plus-a { width: 2.6rem; left: 3%;   top: 62%;    color: var(--c-olah); }
    .dx-tri-a  { width: 3.4rem; right: 7%;  bottom: 9%;  color: var(--c-kes); }

    /* Kabut warna */
    .dx-blob .dxi { height: auto; aspect-ratio: 1; border-radius: 50%; opacity: 1; filter: blur(48px); animation: dxDrift 20s ease-in-out infinite; }
    .dx-blob-a { width: 26rem; right: -8rem; top: -7rem; }
    .dx-blob-a .dxi { background: radial-gradient(circle at 35% 35%, rgba(30,138,122,.22), rgba(52,80,122,.10) 60%, transparent 72%); }
    .dx-blob-b { width: 24rem; right: 6%; bottom: -10rem; }
    .dx-blob-b .dxi { background: radial-gradient(circle at 40% 40%, rgba(217,98,43,.18), rgba(200,150,30,.10) 60%, transparent 72%); animation-delay: -9s; }

    /* Sisi kanan: hanya muncul bila ada ruang kosong di samping konten
       (lebar menu samping + lebar konten maksimal = 1488px) */
    .dx-gutter { display: none; }
    @media (min-width: 1600px) {
        .dx-gutter { display: block; --gx: calc(var(--sidebar-w, 248px) + 1240px); --gw: calc(100vw - var(--gx)); position: absolute; inset: 0; }

        .dx-orbit { width: 15rem; left: calc(var(--gx) + var(--gw) * .5 - 7.5rem); top: 12%; }
        .dx-orbit svg { display: block; width: 100%; height: auto; opacity: .5; }
        .dx-orbit-spin { transform-origin: 120px 120px; animation: dxSpin 26s linear infinite; }
        .dx-orbit-spin-2 { animation-duration: 16s; animation-direction: reverse; }

        .dx-dots { width: 11rem; left: calc(var(--gx) + var(--gw) * .22); top: 44%; color: var(--primary); }
        .dx-dots .dxi {
            aspect-ratio: 1; height: auto; opacity: .35; animation-duration: 13s;
            background-image: radial-gradient(currentColor 1.6px, transparent 1.8px);
            background-size: 16px 16px;
            -webkit-mask-image: radial-gradient(circle at center, #000 30%, transparent 72%);
                    mask-image: radial-gradient(circle at center, #000 30%, transparent 72%);
        }

        .dx-wave { width: 9rem;  left: calc(var(--gx) + var(--gw) * .55); top: 52%; color: var(--c-mm); }
        .dx-s1   { width: 3.4rem; left: calc(var(--gx) + var(--gw) * .30); top: 30%; color: var(--c-kes); }
        .dx-s2   { width: 1.6rem; left: calc(var(--gx) + var(--gw) * .78); top: 38%; color: var(--c-lab); }
        .dx-s3   { width: 2.4rem; left: calc(var(--gx) + var(--gw) * .66); top: 68%; color: var(--c-olah); }
        .dx-s4   { width: 4.6rem; left: calc(var(--gx) + var(--gw) * .18); top: 76%; color: var(--c-lab); }
        .dx-s5   { width: 2.8rem; left: calc(var(--gx) + var(--gw) * .86); top: 84%; color: var(--c-mm); }

        /* bentuk inti agar tidak menumpuk dengan hiasan baru */
        .dx-ring-a { display: none; }
    }

    @media (prefers-reduced-motion: reduce) {
        .dxi, .dx-orbit-spin { animation: none !important; }
        .dx { transform: none !important; will-change: auto; }
    }
</style>
<script>
    /* Parallax ringan: bentuk bergeser pelan mengikuti scroll */
    (function () {
        var dekor = document.querySelector('.dekor');
        if (!dekor || (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches)) return;
        var menunggu = false;
        function perbarui() {
            dekor.style.setProperty('--sy', window.scrollY || 0);
            menunggu = false;
        }
        window.addEventListener('scroll', function () {
            if (!menunggu) { menunggu = true; requestAnimationFrame(perbarui); }
        }, { passive: true });
        perbarui();
    })();
</script>
@endverbatim