{{-- Letak file: resources/views/partials/sidebar-toggle.blade.php (FILE BARU) --}}
{{-- Tombol garis tiga (☰) untuk menyembunyikan / menampilkan menu samping admin & siswa.
     Dipasang di layout, tepat sebelum <div class="shell">. Pilihan tersimpan di browser.
     Hanya aktif di layar lebar (>= 1024px); di HP menu tetap berupa header + tab seperti biasa. --}}
<button type="button" class="sb-toggle" id="sb-toggle" aria-expanded="true" aria-label="Sembunyikan menu samping" title="Sembunyikan menu samping">
    <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
</button>

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
@endverbatim