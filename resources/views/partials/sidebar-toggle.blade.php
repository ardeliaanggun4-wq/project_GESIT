{{-- Letak file: resources/views/partials/sidebar-toggle.blade.php (FILE BARU) --}}
{{-- Tombol garis tiga (☰) untuk menyembunyikan / menampilkan menu samping admin & siswa.
     Dipasang di layout, tepat sebelum <div class="shell">. Pilihan tersimpan di browser.
     Layar lebar (>= 1024px): menu samping bisa disembunyikan. HP: ada bar atas dengan tombol burger yang membuka menu sebagai laci dari sisi kiri. --}}
<button type="button" class="sb-toggle" id="sb-toggle" aria-expanded="true" aria-label="Sembunyikan menu samping" title="Sembunyikan menu samping">
    <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
</button>

{{-- ===== HP / tablet: bar atas + burger, menu membuka sebagai laci dari samping ===== --}}
<header class="mbar">
    <button type="button" class="mb-burger" id="mb-toggle" aria-expanded="false" aria-controls="menu-samping" aria-label="Buka menu">
        <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
    </button>
    <a href="{{ request()->routeIs('admin.*') ? route('admin.ringkasan') : route('siswa.beranda') }}" class="brand" aria-label="GESIT, ke halaman utama">
        <span class="brand-mark"><svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg></span>
        <span>
            <span class="brand-name">GESIT</span>
            <span class="brand-sub">Inventaris sekolah</span>
        </span>
    </a>
</header>
<button type="button" class="mb-close" id="mb-close" aria-label="Tutup menu" tabindex="-1">
    <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
</button>
<div class="mb-scrim" id="mb-scrim" hidden></div>

@verbatim
<style>
.sb-toggle{display:none}

/* Isi halaman lebih lebar supaya sisi kanan tidak kosong di layar besar
   (sebelumnya dibatasi 1240px). Saat menu samping disembunyikan, isi dipusatkan dan lebih lebar lagi. */
.main{max-width:1680px}
@media (min-width:1024px){
    .sb-hidden .main{max-width:1800px;margin-left:auto;margin-right:auto}
}

@media (min-width:1024px){
    .sb-toggle{position:fixed;z-index:40;top:1.5rem;left:calc(var(--sidebar-w) - 3.25rem);display:grid;place-items:center;width:2.5rem;height:2.5rem;padding:0;border-radius:12px;border:1px solid var(--line);background:var(--surface);color:var(--ink-2);cursor:pointer;box-shadow:0 6px 14px -10px rgba(31,41,51,.5)}
    .sb-toggle:hover{color:var(--primary);border-color:var(--primary);background:var(--primary-tint)}
    .sb-toggle:focus-visible{outline:3px solid var(--primary);outline-offset:2px}

    /* keadaan tersembunyi: menu samping keluar layar, tombol pindah ke pojok kiri atas */
    .sb-hidden .shell{grid-template-columns:0 minmax(0,1fr)}
    .sb-hidden .sidebar{width:var(--sidebar-w);transform:translateX(-100%);visibility:hidden;pointer-events:none}
    .sb-hidden .sb-toggle{top:.9rem;left:.9rem;box-shadow:0 10px 20px -12px rgba(31,41,51,.6)}
    .sb-hidden .main{padding-top:4.25rem}

    /* animasi baru aktif setelah halaman selesai dimuat, supaya tidak "berkedip" saat dibuka */
    .sb-ready .shell{transition:grid-template-columns .28s var(--ease)}
    .sb-ready .sidebar{transition:transform .28s var(--ease),visibility 0s}
    .sb-ready.sb-hidden .sidebar{transition:transform .28s var(--ease),visibility 0s .28s}
    .sb-ready .sb-toggle{transition:top .28s var(--ease),left .28s var(--ease),color var(--dur),border-color var(--dur),background var(--dur)}
    .sb-ready .main{transition:padding-top .28s var(--ease)}
}
@media (prefers-reduced-motion:reduce){
    .sb-ready .shell,.sb-ready .sidebar,.sb-ready .sb-toggle,.sb-ready .main{transition:none !important}
}

/* ===== HP / tablet (< 1024px) ===== */
.mbar,.mb-close,.mb-scrim{display:none}
@media (max-width:1023px){
    .mbar{position:sticky;top:0;z-index:35;display:flex;align-items:center;gap:.75rem;padding:.5rem var(--s-4);background:var(--surface);border-bottom:1px solid var(--line)}
    .mb-burger{display:grid;place-items:center;flex:none;width:var(--tap);height:var(--tap);padding:0;border-radius:12px;border:1px solid var(--line);background:var(--surface);color:var(--ink-2);cursor:pointer}
    .mb-burger:hover{color:var(--primary);border-color:var(--primary);background:var(--primary-tint)}
    .mb-burger:focus-visible,.mb-close:focus-visible{outline:3px solid var(--primary);outline-offset:2px}

    .mb-scrim{position:fixed;inset:0;z-index:55;display:block;background:rgba(31,41,51,.5);opacity:0;pointer-events:none}
    .mb-scrim[hidden]{display:none}
    .mb-close{position:fixed;z-index:61;top:.75rem;left:calc(min(18rem,86vw) - 3.25rem);display:grid;place-items:center;width:2.5rem;height:2.5rem;padding:0;border-radius:12px;border:1px solid var(--line);background:var(--surface);color:var(--ink-2);cursor:pointer;opacity:0;visibility:hidden}

    .mb-open{overflow:hidden}
    .mb-open .sidebar{transform:none;visibility:visible;box-shadow:0 0 40px rgba(31,41,51,.28)}
    .mb-open .mb-scrim{opacity:1;pointer-events:auto}
    .mb-open .mb-close{opacity:1;visibility:visible}
    /* nama merek di laci tidak perlu bertabrakan dengan tombol tutup */
    .sidebar .side-brand{padding-right:2.5rem}

    .mb-ready .sidebar{transition:transform .28s var(--ease),visibility 0s .28s,box-shadow .28s}
    .mb-ready.mb-open .sidebar{transition:transform .28s var(--ease),visibility 0s,box-shadow .28s}
    .mb-ready .mb-scrim{transition:opacity .28s var(--ease)}
    .mb-ready .mb-close{transition:opacity .2s,visibility 0s .2s}
    .mb-ready.mb-open .mb-close{transition:opacity .2s .1s,visibility 0s}
}
@media (prefers-reduced-motion:reduce){
    .mb-ready .sidebar,.mb-ready .mb-scrim,.mb-ready .mb-close{transition:none !important}
}
</style>

<script>
(() => {
    const root = document.documentElement, KUNCI = 'gesit-sidebar';
    const tombol = document.getElementById('sb-toggle');
    if (!tombol) return;

    const atur = (sembunyi, simpan) => {
        root.classList.toggle('sb-hidden', sembunyi);
        const label = sembunyi ? 'Tampilkan menu samping' : 'Sembunyikan menu samping';
        tombol.setAttribute('aria-expanded', String(!sembunyi));
        tombol.setAttribute('aria-label', label);
        tombol.title = label;
        if (simpan) { try { localStorage.setItem(KUNCI, sembunyi ? 'hidden' : 'shown'); } catch (e) { /* abaikan */ } }
    };

    let awal = false;
    try { awal = localStorage.getItem(KUNCI) === 'hidden'; } catch (e) { /* abaikan */ }
    atur(awal, false); // dijalankan sebelum menu samping digambar, jadi tidak ada kedipan

    requestAnimationFrame(() => requestAnimationFrame(() => root.classList.add('sb-ready')));
    tombol.addEventListener('click', () => atur(!root.classList.contains('sb-hidden'), true));
})();
</script>

<script>
// Dijalankan setelah halaman selesai dibaca, karena menu samping (.sidebar) berada di bawah skrip ini.
(() => {
  const mulai = () => {
    const root = document.documentElement;
    const burger = document.getElementById('mb-toggle');
    const tutup = document.getElementById('mb-close');
    const scrim = document.getElementById('mb-scrim');
    const menu = document.querySelector('.sidebar');
    if (!burger || !menu) return;
    menu.id = menu.id || 'menu-samping';
    const layarHP = window.matchMedia('(max-width:1023px)');

    const atur = (buka) => {
        root.classList.toggle('mb-open', buka);
        scrim.hidden = !buka;
        burger.setAttribute('aria-expanded', String(buka));
        burger.setAttribute('aria-label', buka ? 'Tutup menu' : 'Buka menu');
        tutup.tabIndex = buka ? 0 : -1;
        if (buka) tutup.focus(); else if (root.dataset.mbFokus) burger.focus();
    };

    burger.addEventListener('click', () => { root.dataset.mbFokus = '1'; atur(true); });
    tutup.addEventListener('click', () => atur(false));
    scrim.addEventListener('click', () => atur(false));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && root.classList.contains('mb-open')) atur(false); });
    // pindah halaman lewat menu: laci menutup sendiri
    menu.addEventListener('click', (e) => { if (e.target.closest('a')) atur(false); });
    // layar dilebarkan / diputar: pastikan laci tidak tertinggal terbuka
    layarHP.addEventListener('change', () => { if (!layarHP.matches) atur(false); });
    // mode halaman tersimpan (bfcache): jangan kembali dalam keadaan terbuka
    window.addEventListener('pageshow', () => atur(false));

    requestAnimationFrame(() => requestAnimationFrame(() => root.classList.add('mb-ready')));
  };
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', mulai); else mulai();
})();
</script>
@endverbatim