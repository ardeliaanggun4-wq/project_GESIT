{{-- Letak file: resources/views/partials/head.blade.php (timpa file lama) --}}
{{-- Font, konfigurasi Tailwind, design token, dan semua komponen GESIT. Dipakai oleh semua layout. --}}
<meta name="theme-color" content="#34507A">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Familjen+Grotesk:wght@500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

@verbatim
<script>
    tailwind.config = { theme: { extend: {
        colors: {
            bg: 'var(--bg)', surface: 'var(--surface)', line: 'var(--line)',
            ink: 'var(--ink)', muted: 'var(--muted)',
            primary: 'var(--primary)', danger: 'var(--danger)'
        },
        fontFamily: {
            sans: ['Source Sans 3', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            display: ['Familjen Grotesk', 'ui-sans-serif', 'system-ui', 'sans-serif']
        }
    } } }
</script>

<style>
/* =====================================================================
   1. DESIGN TOKEN
   ===================================================================== */
:root{
    /* font */
    --font-head:'Familjen Grotesk',ui-sans-serif,system-ui,sans-serif;
    --font-body:'Source Sans 3',ui-sans-serif,system-ui,sans-serif;

    /* warna dasar */
    --bg:#FBF8F3; --surface:#FFFFFF; --surface-2:#F5F1E9; --sand:#F1ECE1;
    --line:#E7E1D7; --line-strong:#CFC8BA; --line-input:#8A857B;
    --ink:#1F2933; --ink-2:#3E4A56; --muted:#5A6672;

    /* warna utama */
    --primary:#34507A; --primary-hover:#27405F; --primary-tint:#E8EDF5; --on-primary:#FFFFFF;
    --danger:#B3322F; --danger-hover:#942725; --danger-tint:#FBE6E4;

    /* warna kategori: titik/garis, latar lembut, teks (teks lolos kontras AA) */
    --c-lab:#1E8A7A;   --c-lab-tint:#E3F3EF;   --c-lab-ink:#0F6354;
    --c-olah:#D9622B;  --c-olah-tint:#FBEBE2;  --c-olah-ink:#A4410F;
    --c-kes:#A8479A;   --c-kes-tint:#F6E8F4;   --c-kes-ink:#85307A;
    --c-mm:#C8961E;    --c-mm-tint:#FBF0D5;    --c-mm-ink:#745400;

    /* jarak */
    --s-1:.25rem; --s-2:.5rem; --s-3:.75rem; --s-4:1rem; --s-5:1.5rem; --s-6:2rem; --s-7:3rem;

    /* bentuk */
    --r-ctl:10px; --r-card:14px; --r-pill:999px;
    --shadow-float:0 14px 36px rgba(31,41,51,.16),0 2px 8px rgba(31,41,51,.08);

    /* gerak & ukuran */
    --ease:cubic-bezier(.22,.7,.2,1); --dur:.18s;
    --tap:44px; --sidebar-w:248px;
}

/* Kategori: pasang data-kat="praktik_lab|olahraga|kesenian|multimedia" pada elemen apa pun */
[data-kat="praktik_lab"]{--kat:var(--c-lab);--kat-tint:var(--c-lab-tint);--kat-ink:var(--c-lab-ink)}
[data-kat="olahraga"]{--kat:var(--c-olah);--kat-tint:var(--c-olah-tint);--kat-ink:var(--c-olah-ink)}
[data-kat="kesenian"]{--kat:var(--c-kes);--kat-tint:var(--c-kes-tint);--kat-ink:var(--c-kes-ink)}
[data-kat="multimedia"]{--kat:var(--c-mm);--kat-tint:var(--c-mm-tint);--kat-ink:var(--c-mm-ink)}

/* Status & kondisi: pasang data-status="..." (lihat components/status.blade.php) */
[data-status="menunggu"],[data-status="perlu_cek"]{--st-tint:#FDF0D0;--st-ink:#855400;--st-line:#E9C770}
[data-status="disetujui"],[data-status="dipinjam"],[data-status="baik"]{--st-tint:#E2F3E8;--st-ink:#1D6837;--st-line:#9BD1AE}
[data-status="ditolak"],[data-status="rusak"]{--st-tint:#FBE6E4;--st-ink:#A12B2B;--st-line:#EDB1AD}
[data-status="stok_habis"],[data-status="selesai"]{--st-tint:#ECEAE4;--st-ink:#4B5560;--st-line:#CFCBC0}

/* =====================================================================
   2. DASAR
   ===================================================================== */
html{scroll-behavior:smooth;-webkit-text-size-adjust:100%;scroll-padding-top:5rem}
body{margin:0;min-height:100dvh;font-family:var(--font-body);font-size:1rem;line-height:1.5;color:var(--ink);background:var(--bg);-webkit-font-smoothing:antialiased}
body.dialog-open{overflow:hidden}
a{color:inherit;text-decoration:none}
::selection{background:var(--primary-tint);color:var(--primary-hover)}
:focus-visible{outline:3px solid var(--primary);outline-offset:2px;border-radius:4px}
.skip-link{position:absolute;left:1rem;top:-4rem;z-index:100;padding:.6rem 1rem;border-radius:var(--r-ctl);background:var(--primary);color:var(--on-primary);font-weight:600;transition:top var(--dur)}
.skip-link:focus{top:1rem}
.sr-only-x{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}

.ico{width:1.25em;height:1.25em;flex:none;fill:none;stroke:currentColor;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round}
.ico-sm{width:1rem;height:1rem}
.ico-lg{width:1.75rem;height:1.75rem}

/* Tipografi */
.t-page{font:600 clamp(1.5rem,2.4vw,1.875rem)/1.2 var(--font-head);letter-spacing:-.01em}
.t-sec{font:600 1.125rem/1.3 var(--font-head);letter-spacing:-.005em}
.t-card{font:600 1rem/1.35 var(--font-head)}
.t-muted{color:var(--muted)}
.t-small{font-size:.875rem}
.t-xs{font-size:.8125rem}
.num{font-variant-numeric:tabular-nums}
.num-big{font:600 clamp(2rem,4vw,2.75rem)/1 var(--font-head);font-variant-numeric:tabular-nums;letter-spacing:-.02em}
/* Label kode alat: tipis, angka tabular, bukan mono kapital */
.kode{display:inline-block;padding:1px 8px;border:1px solid var(--line);border-radius:6px;background:var(--surface-2);color:var(--ink-2);font-size:.8125rem;font-weight:500;font-variant-numeric:tabular-nums;letter-spacing:.02em;white-space:nowrap}

/* =====================================================================
   3. TOMBOL
   ===================================================================== */
.btn{position:relative;display:inline-flex;align-items:center;justify-content:center;gap:.5rem;min-height:var(--tap);padding:0 1.1rem;border:1px solid transparent;border-radius:var(--r-ctl);font:600 1rem/1 var(--font-body);cursor:pointer;white-space:nowrap;transition:background var(--dur),border-color var(--dur),color var(--dur),transform var(--dur)}
.btn:active{transform:translateY(1px)}
.btn[disabled],.btn:disabled{opacity:.5;cursor:not-allowed}
.btn-primary{background:var(--primary);color:var(--on-primary)}
.btn-primary:hover{background:var(--primary-hover)}
.btn-secondary{background:var(--surface);color:var(--ink);border-color:var(--line-strong)}
.btn-secondary:hover{background:var(--surface-2);border-color:var(--ink-2)}
.btn-ghost{background:transparent;color:var(--ink-2)}
.btn-ghost:hover{background:var(--surface-2);color:var(--ink)}
.btn-danger{background:var(--danger);color:#fff}
.btn-danger:hover{background:var(--danger-hover)}
.btn-ok{background:#1D6837;color:#fff}
.btn-ok:hover{background:#17542C}
.btn-sm{min-height:36px;padding:0 .8rem;font-size:.9375rem}
.btn-icon{width:var(--tap);padding:0}
.btn-block{width:100%}
@media (pointer:coarse){.btn-sm{min-height:var(--tap)}}
.btn.is-loading{pointer-events:none;color:transparent}
.btn.is-loading::after{content:"";position:absolute;width:1.1rem;height:1.1rem;border:2px solid currentColor;border-right-color:transparent;border-radius:50%;animation:spin .7s linear infinite}
.btn-primary.is-loading::after,.btn-danger.is-loading::after,.btn-ok.is-loading::after{border-color:#fff;border-right-color:transparent}
.btn-secondary.is-loading::after,.btn-ghost.is-loading::after{border-color:var(--ink-2);border-right-color:transparent}
.link{color:var(--primary);font-weight:600;text-decoration:underline;text-underline-offset:3px;text-decoration-thickness:1px}
.link:hover{color:var(--primary-hover);text-decoration-thickness:2px}

/* =====================================================================
   4. FORM
   ===================================================================== */
.label{display:block;margin-bottom:.35rem;font-size:.9375rem;font-weight:600;color:var(--ink)}
.field{display:block;width:100%;min-height:var(--tap);padding:.6rem .85rem;border:1px solid var(--line-input);border-radius:var(--r-ctl);background:var(--surface);font:400 1rem/1.4 var(--font-body);color:var(--ink);transition:border-color var(--dur),box-shadow var(--dur)}
.field::placeholder{color:#767F88}
.field:hover{border-color:var(--ink-2)}
.field:focus{outline:3px solid var(--primary);outline-offset:1px;border-color:var(--primary)}
.field[aria-invalid="true"]{border-color:var(--danger);background:#FFFBFA}
textarea.field{min-height:6rem;resize:vertical}
select.field{appearance:none;padding-right:2.5rem;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235A6672' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M5 9l7 7 7-7'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right .8rem center;background-size:1.1rem}
.hint{margin-top:.35rem;font-size:.875rem;color:var(--muted)}
.field-error{margin-top:.35rem;font-size:.875rem;font-weight:600;color:var(--danger)}
.field-wrap{position:relative}
.field-wrap .field-act{position:absolute;right:2px;top:2px;bottom:2px;min-width:var(--tap);padding:0 .8rem;border-radius:8px;font-size:.9375rem;font-weight:600;color:var(--primary);background:transparent;cursor:pointer}
.field-wrap .field-act:hover{background:var(--primary-tint)}
.search{position:relative}
.search .ico{position:absolute;left:.85rem;top:50%;translate:0 -50%;color:var(--muted);pointer-events:none}
.search .field{padding-left:2.6rem}

/* Stepper jumlah: - [angka] + */
.stepper{display:inline-grid;grid-template-columns:var(--tap) minmax(3.5rem,5rem) var(--tap);border:1px solid var(--line-input);border-radius:var(--r-ctl);background:var(--surface);overflow:hidden}
.stepper button{min-height:var(--tap);font-size:1.25rem;font-weight:600;color:var(--primary);background:var(--surface-2);cursor:pointer;transition:background var(--dur)}
.stepper button:hover{background:var(--primary-tint)}
.stepper input{width:100%;text-align:center;border:0;font:600 1.0625rem var(--font-body);font-variant-numeric:tabular-nums;background:transparent;-moz-appearance:textfield}
.stepper input::-webkit-outer-spin-button,.stepper input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}

/* Kontrol segmen (pilihan peran, dll.) */
.seg{display:grid;grid-auto-flow:column;grid-auto-columns:1fr;gap:4px;padding:4px;background:var(--surface-2);border:1px solid var(--line);border-radius:14px}
.seg button{min-height:var(--tap);padding:0 .75rem;border-radius:var(--r-ctl);font-weight:600;color:var(--ink-2);cursor:pointer;transition:background var(--dur),color var(--dur),box-shadow var(--dur)}
.seg button:hover{color:var(--ink)}
.seg button[aria-pressed="true"]{background:var(--surface);color:var(--primary);box-shadow:0 0 0 1px var(--line-strong)}

/* =====================================================================
   5. KARTU, PENANDA KATEGORI, STATUS
   ===================================================================== */
.card{background:var(--surface);border:1px solid var(--line);border-radius:var(--r-card)}
.card-pad{padding:var(--s-5)}
.card-hover{transition:border-color var(--dur),box-shadow var(--dur),transform var(--dur)}
.card-hover:hover{border-color:var(--line-strong);box-shadow:0 4px 14px rgba(31,41,51,.06)}

.dot{display:inline-block;width:.6rem;height:.6rem;flex:none;border-radius:50%;background:var(--kat,var(--muted))}
/* Garis kategori di sisi kiri kartu/baris daftar */
.has-kat{position:relative}
.has-kat::before{content:"";position:absolute;left:0;top:10px;bottom:10px;width:4px;border-radius:0 4px 4px 0;background:var(--kat)}
/* Garis kategori di sisi kiri baris tabel */
.tbl tr.has-kat>td:first-child{box-shadow:inset 4px 0 0 var(--kat);padding-left:1.25rem}

.chip-kat{display:inline-flex;align-items:center;gap:.4rem;padding:2px 10px 2px 8px;border-radius:var(--r-pill);background:var(--kat-tint);color:var(--kat-ink);font-size:.875rem;font-weight:600;white-space:nowrap}
.status{display:inline-flex;align-items:center;gap:.35rem;padding:2px 10px 2px 8px;border-radius:var(--r-pill);background:var(--st-tint);color:var(--st-ink);border:1px solid var(--st-line);font-size:.875rem;font-weight:600;white-space:nowrap}
.status .ico{width:1rem;height:1rem;stroke-width:2}

/* Chip filter */
.filters{display:flex;gap:.5rem;overflow-x:auto;padding:2px;margin:-2px;scrollbar-width:none}
.filters::-webkit-scrollbar{display:none}
.filter-chip{display:inline-flex;align-items:center;gap:.45rem;min-height:var(--tap);padding:0 1rem;border:1px solid var(--line-strong);border-radius:var(--r-pill);background:var(--surface);font-weight:600;font-size:.9375rem;color:var(--ink-2);white-space:nowrap;cursor:pointer;transition:background var(--dur),border-color var(--dur),color var(--dur)}
.filter-chip:hover{border-color:var(--kat,var(--primary));background:var(--kat-tint,var(--primary-tint))}
.filter-chip[aria-pressed="true"],.filter-chip.is-on{border-color:var(--kat,var(--primary));background:var(--kat-tint,var(--primary-tint));color:var(--kat-ink,var(--primary-hover))}
.filter-chip .n{font-variant-numeric:tabular-nums;font-weight:500;opacity:.8}

/* Tab */
.tabs{display:flex;gap:.25rem;border-bottom:1px solid var(--line);overflow-x:auto;scrollbar-width:none}
.tabs::-webkit-scrollbar{display:none}
.tab{display:inline-flex;align-items:center;gap:.5rem;min-height:var(--tap);padding:0 1rem;margin-bottom:-1px;border-bottom:2px solid transparent;font-weight:600;color:var(--muted);white-space:nowrap;cursor:pointer;transition:color var(--dur),border-color var(--dur)}
.tab:hover{color:var(--ink)}
.tab[aria-selected="true"],.tab.is-active{color:var(--primary);border-bottom-color:var(--primary)}
.tab .n{min-width:1.5rem;padding:0 6px;border-radius:var(--r-pill);background:var(--surface-2);border:1px solid var(--line);font-size:.8125rem;text-align:center;font-variant-numeric:tabular-nums}
.tab[aria-selected="true"] .n,.tab.is-active .n{background:var(--primary-tint);border-color:transparent;color:var(--primary-hover)}

/* Tabel */
.table-wrap{overflow-x:auto;border:1px solid var(--line);border-radius:var(--r-card);background:var(--surface)}
.tbl{width:100%;border-collapse:collapse;font-size:.9375rem}
.tbl th{padding:.7rem 1rem;background:var(--surface-2);border-bottom:1px solid var(--line);text-align:left;font-size:.8125rem;font-weight:600;color:var(--muted);white-space:nowrap}
.tbl td{padding:.8rem 1rem;border-bottom:1px solid var(--line);vertical-align:middle}
.tbl tr:last-child td{border-bottom:0}
.tbl tbody tr{transition:background var(--dur)}
.tbl tbody tr:hover{background:#FDFBF7}
.tbl .r{text-align:right}
.stok{font:600 1.0625rem var(--font-body);font-variant-numeric:tabular-nums}
.stok-low{color:var(--danger)}
.meter{height:4px;width:4.5rem;border-radius:var(--r-pill);background:var(--line);overflow:hidden}
.meter>i{display:block;height:100%;border-radius:inherit;background:var(--kat,var(--primary))}

/* Daftar rapat */
.list{border:1px solid var(--line);border-radius:var(--r-card);background:var(--surface);overflow:hidden}
.list-row{display:flex;align-items:center;gap:var(--s-3);padding:.85rem 1rem .85rem 1.25rem;border-bottom:1px solid var(--line);transition:background var(--dur)}
.list-row:last-child{border-bottom:0}
a.list-row:hover,.list-row.is-hover:hover{background:#FDFBF7}

/* Pesan, kosong */
.alert{display:flex;gap:.6rem;align-items:flex-start;padding:.75rem 1rem;border-radius:var(--r-ctl);background:var(--danger-tint);border:1px solid #EDB1AD;color:#7F1F1F;font-weight:500}
.alert .ico{margin-top:2px;color:var(--danger)}
.alert-info{background:var(--primary-tint);border-color:#C5D0E3;color:var(--primary-hover)}
.alert-info .ico{color:var(--primary)}
.empty{display:flex;flex-direction:column;align-items:center;text-align:center;gap:.4rem;padding:var(--s-6) var(--s-5);color:var(--muted)}
.empty .empty-ico{display:grid;place-items:center;width:3.25rem;height:3.25rem;margin-bottom:.4rem;border-radius:50%;background:var(--surface-2);border:1px solid var(--line);color:var(--ink-2)}
.empty h3{font:600 1.0625rem var(--font-head);color:var(--ink)}
.empty p{max-width:26rem}
.soon-tag{display:inline-block;padding:0 8px;border-radius:var(--r-pill);background:var(--surface-2);border:1px solid var(--line);font-size:.75rem;font-weight:600;color:var(--muted);white-space:nowrap}

/* Avatar, merek */
.avatar{display:grid;place-items:center;width:2.25rem;height:2.25rem;flex:none;border-radius:50%;background:var(--primary-tint);color:var(--primary-hover);font:600 .875rem var(--font-head)}
.brand{display:inline-flex;align-items:center;gap:.65rem}
.brand-mark{display:grid;place-items:center;width:2.25rem;height:2.25rem;border-radius:10px;background:var(--primary);color:#fff}
.brand-name{display:block;font:700 1.125rem/1.1 var(--font-head);letter-spacing:.02em}
.brand-sub{display:block;font-size:.8125rem;color:var(--muted)}

/* =====================================================================
   6. DIALOG & DRAWER (memakai elemen <dialog> bawaan browser)
   ===================================================================== */
dialog.dlg{padding:0;border:0;border-radius:var(--r-card);background:var(--surface);color:var(--ink);box-shadow:var(--shadow-float);width:min(30rem,calc(100vw - 2rem));max-height:calc(100dvh - 2rem);overflow:hidden}
dialog.dlg[open]{display:flex;flex-direction:column;animation:dlgIn .2s var(--ease)}
dialog::backdrop{background:rgba(31,41,51,.45)}
dialog[open]::backdrop{animation:fade .2s ease}
dialog.drawer{margin:0 0 0 auto;height:100dvh;max-height:100dvh;width:min(28rem,100vw);max-width:100vw;border-radius:var(--r-card) 0 0 var(--r-card)}
dialog.drawer[open]{animation:drawerIn .26s var(--ease)}
.dlg-head{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:1rem 1.25rem;border-bottom:1px solid var(--line)}
.dlg-body{flex:1;overflow-y:auto;padding:1.25rem}
.dlg-foot{display:flex;justify-content:flex-end;gap:.5rem;padding:1rem 1.25rem;border-top:1px solid var(--line);background:var(--surface)}
.dlg-icon{display:grid;place-items:center;width:2.75rem;height:2.75rem;margin-bottom:.75rem;border-radius:50%;background:var(--danger-tint);color:var(--danger)}

/* Notifikasi berhasil */
.toast{position:fixed;left:50%;bottom:1.25rem;z-index:60;translate:-50% 0;display:flex;align-items:center;gap:.65rem;max-width:min(32rem,calc(100vw - 2rem));padding:.7rem .6rem .7rem 1rem;border-radius:var(--r-ctl);background:var(--ink);color:#fff;font-weight:500;box-shadow:var(--shadow-float);animation:toastIn .3s var(--ease)}
.toast .ico:first-child{color:#8FE0AE}
.toast-x{display:grid;place-items:center;width:2.25rem;height:2.25rem;border-radius:8px;color:#D5DAE0;cursor:pointer}
.toast-x:hover{background:rgba(255,255,255,.12);color:#fff}
.toast.is-out{opacity:0;translate:-50% 8px;transition:opacity .25s,translate .25s}

/* =====================================================================
   7. LAYOUT ADMIN & SISWA (sama: sidebar di desktop, header + tab menu di HP)
   ===================================================================== */
.shell{display:grid;grid-template-columns:var(--sidebar-w) minmax(0,1fr);min-height:100dvh}
.sidebar{position:sticky;top:0;z-index:30;height:100dvh;display:grid;grid-template-rows:auto 1fr auto;gap:var(--s-5);padding:var(--s-5) var(--s-4);background:var(--surface);border-right:1px solid var(--line)}
.side-nav{display:flex;flex-direction:column;gap:2px;align-content:start}
.side-link{display:flex;align-items:center;gap:.75rem;min-height:var(--tap);padding:0 .75rem;border-radius:var(--r-ctl);font-weight:500;color:var(--ink-2);transition:background var(--dur),color var(--dur)}
.side-link:hover{background:var(--surface-2);color:var(--ink)}
.side-link.is-active{background:var(--primary-tint);color:var(--primary);font-weight:600}
.side-link.is-soon{color:var(--muted);cursor:not-allowed}
.side-link.is-soon:hover{background:transparent;color:var(--muted)}
.side-link .soon-tag{margin-left:auto}
.side-user{display:flex;flex-direction:column;gap:.75rem;padding-top:var(--s-4);border-top:1px solid var(--line)}
.side-user .who{display:flex;align-items:center;gap:.65rem;min-width:0}
.side-user .who-name{font-weight:600;line-height:1.2;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.side-user .who-role{font-size:.8125rem;color:var(--muted)}
.side-user .btn{width:100%}
.main{min-width:0;max-width:1240px;width:100%;padding:var(--s-6) var(--s-6) var(--s-7);animation:pageIn .32s var(--ease) both}
.page-head{display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:var(--s-3) var(--s-4);margin-bottom:var(--s-5)}
.page-head p{margin-top:.25rem;color:var(--muted)}

@media (max-width:1023px){
    .shell{display:block}
    .sidebar{height:auto;grid-template-columns:minmax(0,1fr) auto;grid-template-rows:auto auto;gap:.25rem 1rem;padding:.5rem var(--s-4) 0;border-right:0;border-bottom:1px solid var(--line)}
    .side-brand{grid-column:1;grid-row:1;align-self:center}
    .side-user{grid-column:2;grid-row:1;flex-direction:row;align-items:center;padding:0;border-top:0}
    .side-user .who{display:none}
    .side-user .btn{width:auto}
    .side-nav{grid-column:1/-1;grid-row:2;flex-direction:row;gap:.25rem;overflow-x:auto;padding-bottom:.4rem;scrollbar-width:none}
    .side-nav::-webkit-scrollbar{display:none}
    .side-link{white-space:nowrap}
    .main{padding:var(--s-5) var(--s-4) var(--s-6)}
}

/* =====================================================================
   8. HALAMAN MASUK
   ===================================================================== */
.auth{display:grid;grid-template-columns:minmax(0,1fr);min-height:100dvh}
.auth-art{display:none;flex-direction:column;justify-content:space-between;gap:var(--s-5);padding:var(--s-6) 3rem;background:var(--sand);border-right:1px solid var(--line)}
.auth-art .auth-headline{font:600 clamp(2rem,3.4vw,2.9rem)/1.1 var(--font-head);letter-spacing:-.02em;max-width:17ch}
.auth-art svg.rak{width:100%;max-width:34rem;height:auto;margin:0 auto}
.auth-form{display:flex;align-items:center;justify-content:center;padding:var(--s-5) var(--s-4)}
.auth-card{width:100%;max-width:24rem;animation:pageIn .4s var(--ease) both}
.auth-brand-m{margin-bottom:var(--s-6)}
@media (min-width:900px){
    .auth{grid-template-columns:minmax(0,1.15fr) minmax(0,1fr)}
    .auth-art{display:flex}
    .auth-brand-m{display:none}
}

/* =====================================================================
   8b. ELEMEN HALAMAN: judul beranimasi, hero, kartu angka, grafik, daftar
   ===================================================================== */
/* nada warna (dipakai kartu angka, ikon baris, penghitung) */
.tone-primary{--t-bg:var(--primary-tint);--t-fg:var(--primary);--t-line:#C5D0E3}
.tone-wait{--t-bg:#FDF0D0;--t-fg:#855400;--t-line:#E9C770}
.tone-ok{--t-bg:#E2F3E8;--t-fg:#1D6837;--t-line:#9BD1AE}
.tone-no{--t-bg:var(--danger-tint);--t-fg:var(--danger);--t-line:#EDB1AD}
.tone-idle{--t-bg:#ECEAE4;--t-fg:#4B5560;--t-line:#CFCBC0}

/* judul halaman */
.eyebrow{display:inline-flex;align-items:center;gap:.55rem;font-size:.875rem;font-weight:600;color:var(--primary);letter-spacing:.01em}
.eyebrow::before{content:"";width:1.5rem;height:2px;border-radius:2px;background:currentColor}
.display{font:600 clamp(1.9rem,4.2vw,2.9rem)/1.08 var(--font-head);letter-spacing:-.02em}
.display .hl{color:var(--primary)}
.page-lead{max-width:38rem;margin-top:.9rem;color:var(--muted);font-size:1.0625rem}
.typed{display:inline-block;min-height:1.5em;color:var(--ink-2)}
.caret::after{content:"";display:inline-block;width:2px;height:1.05em;margin-left:3px;background:var(--primary);vertical-align:-.15em;animation:blink 1s steps(1) infinite}

/* teks naik per baris & per kata */
.line{display:block;overflow:hidden;padding-bottom:.1em;margin-bottom:-.1em}
.line>span{display:block;transform:translateY(108%);animation:lineUp .75s var(--ease) both;animation-delay:calc(var(--i,0)*.12s + .1s)}
.words .w{display:inline-block;overflow:hidden;vertical-align:top;padding-bottom:.12em;margin-bottom:-.12em}
.words .w>span{display:inline-block;transform:translateY(110%);animation:lineUp .6s var(--ease) both;animation-delay:calc(var(--w,0)*.06s + .1s)}
.rise{opacity:0;transform:translateY(14px);animation:riseIn .6s var(--ease) both;animation-delay:calc(var(--i,0)*.08s + .12s)}
@keyframes lineUp{to{transform:none}}
@keyframes riseIn{to{opacity:1;transform:none}}
@keyframes blink{50%{opacity:0}}
@keyframes floatY{0%,100%{transform:translateY(0)}50%{transform:translateY(-9px)}}
@keyframes grow{from{transform:scaleY(0)}to{transform:none}}
@keyframes growX{from{transform:scaleX(0)}to{transform:none}}
@keyframes pop{from{opacity:0;transform:scale(.6)}to{opacity:1;transform:none}}

/* hero (kotak sambutan di halaman utama) */
.hero{position:relative;overflow:hidden;padding:var(--s-6) var(--s-5);border:1px solid #C5D0E3;border-radius:20px;background:linear-gradient(120deg,#fff 0%,var(--primary-tint) 100%)}
.hero-dots{position:absolute;inset:0;pointer-events:none;background-image:radial-gradient(rgba(52,80,122,.18) 1.2px,transparent 1.5px);background-size:18px 18px;-webkit-mask-image:linear-gradient(110deg,transparent 35%,#000 100%);mask-image:linear-gradient(110deg,transparent 35%,#000 100%)}
.hero-body{position:relative;max-width:34rem}
.hero-actions{position:relative;display:flex;flex-wrap:wrap;gap:.6rem;margin-top:var(--s-5)}
.hero-art{display:none;position:absolute;right:1.5rem;top:50%;translate:0 -50%;width:15.5rem;height:auto;pointer-events:none}
.hero-art .fl{animation:floatY 5.5s ease-in-out infinite}
.hero-art .fl:nth-of-type(2){animation-delay:-1.4s}
.hero-art .fl:nth-of-type(3){animation-delay:-2.8s}
.hero-art .fl:nth-of-type(4){animation-delay:-4.1s}
@media (min-width:860px){.hero{padding:var(--s-6) 18.5rem var(--s-6) var(--s-6)}.hero-art{display:block}}

/* kartu angka */
.stats{display:grid;gap:var(--s-4);grid-template-columns:repeat(2,minmax(0,1fr))}
.stats-3{grid-template-columns:minmax(0,1fr)}
@media (min-width:640px){.stats-3{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media (min-width:1024px){.stats-4{grid-template-columns:repeat(4,minmax(0,1fr))}}
.stat{position:relative;display:flex;flex-direction:column;gap:.85rem;padding:var(--s-4) var(--s-5);background:var(--surface);border:1px solid var(--line);border-radius:var(--r-card);transition:transform var(--dur),box-shadow var(--dur),border-color var(--dur)}
a.stat:hover{transform:translateY(-3px);border-color:var(--t-line);box-shadow:0 10px 24px rgba(31,41,51,.08)}
.stat-ico{display:grid;place-items:center;width:2.5rem;height:2.5rem;border-radius:12px;background:var(--t-bg);color:var(--t-fg);border:1px solid var(--t-line)}
.stat-ico .ico{width:1.3rem;height:1.3rem}
.stat-label{font-size:.9375rem;font-weight:600;color:var(--ink-2)}
.stat-num{font:600 clamp(2rem,4vw,2.6rem)/1 var(--font-head);letter-spacing:-.02em;font-variant-numeric:tabular-nums}
.stat-note{font-size:.8125rem;color:var(--muted)}
.stat-go{position:absolute;right:1rem;top:1rem;color:var(--muted);transition:transform var(--dur),color var(--dur)}
a.stat:hover .stat-go{transform:translateX(3px);color:var(--t-fg)}
.stat.is-alert{border-color:var(--t-line);background:linear-gradient(180deg,#fff,var(--t-bg))}

/* judul bagian & aksi cepat */
.sec-head{display:flex;align-items:baseline;justify-content:space-between;gap:1rem;margin-bottom:var(--s-3)}
.sec-head .t-small{color:var(--muted)}
.qa-grid{display:grid;gap:var(--s-3);grid-template-columns:minmax(0,1fr)}
@media (min-width:700px){.qa-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
.qa{display:flex;align-items:center;gap:.9rem;padding:var(--s-4);background:var(--surface);border:1px solid var(--line);border-radius:var(--r-card);transition:transform var(--dur),box-shadow var(--dur),border-color var(--dur)}
.qa:hover{transform:translateY(-3px);border-color:var(--t-line);box-shadow:0 10px 24px rgba(31,41,51,.08)}
.qa b{display:block;font:600 1rem/1.3 var(--font-head)}
.qa small{display:block;margin-top:2px;color:var(--muted);font-size:.875rem}
.qa .arrow{margin-left:auto;color:var(--muted);transition:transform var(--dur),color var(--dur)}
.qa:hover .arrow{transform:translateX(4px);color:var(--t-fg)}

/* tip bergerak */
.tipbar{display:flex;align-items:center;gap:.75rem;padding:.8rem 1rem;border:1px dashed var(--line-strong);border-radius:var(--r-card);background:var(--surface-2);color:var(--ink-2)}
.tipbar .ico{color:var(--c-mm)}
.tipbar b{font-weight:600;color:var(--ink)}

/* grafik batang 7 hari */
.bars{display:flex;align-items:stretch;gap:.75rem;height:12rem;padding-top:1.5rem}
.bar-col{flex:1;display:flex;flex-direction:column;min-width:0}
.bar-area{flex:1;display:flex;align-items:flex-end}
.bar{position:relative;width:100%;border-radius:8px 8px 4px 4px;background:var(--primary-tint);border:1px solid #C5D0E3;transform-origin:bottom;animation:grow .8s var(--ease) both;animation-delay:calc(var(--i,0)*.07s + .3s);transition:background var(--dur)}
.bar:hover{background:#D3DDEC}
.bar.is-peak{background:var(--primary);border-color:var(--primary)}
.bar-v{position:absolute;left:50%;top:-1.45rem;translate:-50% 0;font-size:.8125rem;font-weight:600;font-variant-numeric:tabular-nums;color:var(--ink-2)}
.bar-l{margin-top:.5rem;text-align:center;font-size:.8125rem;color:var(--muted)}
.chart-sum{display:flex;flex-wrap:wrap;gap:.4rem 1.5rem;margin-bottom:var(--s-2);font-size:.9375rem;color:var(--muted)}
.chart-sum b{color:var(--ink);font:600 1.25rem var(--font-head)}

/* batang kategori & kondisi */
.track{height:8px;border-radius:99px;background:var(--surface-2);border:1px solid var(--line);overflow:hidden}
.fill{height:100%;border-radius:inherit;background:var(--kat,var(--primary));transform-origin:left;animation:growX .9s var(--ease) both;animation-delay:calc(var(--i,0)*.1s + .35s)}
.stackbar{display:flex;gap:3px;height:14px;border-radius:99px;overflow:hidden}
.stackbar>i{display:block;height:100%;min-width:6px;transform-origin:left;animation:growX .9s var(--ease) both;animation-delay:calc(var(--i,0)*.12s + .35s)}
.legend,.steps{list-style:none;padding:0;margin:0}
.legend{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.65rem 1.25rem;margin-top:var(--s-4)}
.legend li{display:flex;align-items:center;gap:.5rem;font-size:.9375rem}
.legend b{margin-left:auto;font-variant-numeric:tabular-nums}
.sw{width:.7rem;height:.7rem;flex:none;border-radius:3px}

/* daftar dengan ikon bulat */
.row-ico{display:grid;place-items:center;width:2.4rem;height:2.4rem;flex:none;border-radius:50%;background:var(--t-bg);color:var(--t-fg);border:1px solid var(--t-line)}
.row-ico .ico{width:1.15rem;height:1.15rem}
.list-main{flex:1;min-width:0}
.list-title{font-weight:600;line-height:1.3}
.list-sub{margin-top:2px;font-size:.875rem;color:var(--muted)}
.list-row .status,.list-row .chip-kat{flex:none}
.list-row{animation:riseIn .5s var(--ease) both;animation-delay:calc(var(--i,0)*.06s + .35s);opacity:0}
.date-tile{display:grid;place-items:center;align-content:center;width:3rem;height:3.25rem;flex:none;border-radius:12px;background:var(--surface-2);border:1px solid var(--line);line-height:1}
.date-tile b{font:600 1.25rem/1 var(--font-head)}
.date-tile small{margin-top:3px;font-size:.6875rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--muted)}
.attn{display:flex;gap:.6rem;align-items:center}

/* langkah bernomor */
.steps{counter-reset:langkah;display:grid;gap:1.1rem}
.steps>li{position:relative;display:grid;grid-template-columns:2rem minmax(0,1fr);gap:.8rem;align-items:start}
.steps>li::before{counter-increment:langkah;content:counter(langkah);display:grid;place-items:center;width:2rem;height:2rem;border-radius:50%;background:var(--primary);color:#fff;font:600 .9375rem var(--font-head)}
.steps>li:not(:last-child)::after{content:"";position:absolute;left:calc(1rem - 1px);top:2.25rem;bottom:-1rem;width:2px;background:var(--line)}
.steps b{display:block;font-weight:600}
.steps span{display:block;margin-top:2px;font-size:.9375rem;color:var(--muted)}

/* kolom verifikasi */
.cols-3{display:grid;gap:var(--s-5);align-items:start;grid-template-columns:minmax(0,1fr)}
@media (min-width:900px){.cols-3{grid-template-columns:repeat(3,minmax(0,1fr))}}
.col-head{display:flex;align-items:center;gap:.6rem;padding:0 .25rem .8rem}
.col-head .row-ico{width:2rem;height:2rem}
.count{margin-left:auto;min-width:1.9rem;padding:1px 9px;border-radius:99px;background:var(--t-bg);color:var(--t-fg);border:1px solid var(--t-line);font-weight:600;text-align:center;font-variant-numeric:tabular-nums}
.pj{position:relative;overflow:hidden;padding:var(--s-4) var(--s-4) var(--s-4) 1.3rem;background:var(--surface);border:1px solid var(--line);border-radius:var(--r-card);transition:transform var(--dur),box-shadow var(--dur),border-color var(--dur)}
.pj::before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;background:var(--t-fg)}
.pj:hover{transform:translateY(-2px);border-color:var(--line-strong);box-shadow:0 8px 20px rgba(31,41,51,.07)}
.pj-top{display:flex;align-items:center;gap:.65rem}
.pj-meta{display:flex;flex-wrap:wrap;gap:.3rem .9rem;margin-top:.75rem;font-size:.875rem;color:var(--muted)}
.pj-meta span{display:inline-flex;align-items:center;gap:.3rem}
.pj-meta .ico{width:1rem;height:1rem}
.pj-note{margin-top:.7rem;padding:.55rem .7rem;border-radius:var(--r-ctl);background:var(--surface-2);font-size:.9375rem;color:var(--ink-2)}
.pj-act{display:flex;gap:.5rem;margin-top:var(--s-4)}
.pj-act form{flex:1}
.pj-act .btn{width:100%}

/* tabel data alat & tombol ikon */
.tbl .alat-nama{font-weight:600}
.tbl .alat-lok{display:flex;align-items:center;gap:.3rem;margin-top:2px;font-size:.8125rem;color:var(--muted)}
.tbl .alat-lok .ico{width:.9rem;height:.9rem}
.stok-cell{display:flex;align-items:center;gap:.7rem}
.btn-ghost.is-danger:hover{background:var(--danger-tint);color:var(--danger)}
.nav-badge{margin-left:auto;min-width:1.4rem;padding:0 7px;border-radius:99px;background:var(--danger);color:#fff;font-size:.75rem;font-weight:700;text-align:center;line-height:1.35rem}
.table-foot{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;padding:.9rem 1rem;border-top:1px solid var(--line);font-size:.9375rem;color:var(--muted)}

/* form pengajuan */
.form-grid{display:grid;gap:var(--s-5);grid-template-columns:minmax(0,1fr);align-items:start}
@media (min-width:1180px){.form-grid{grid-template-columns:minmax(0,1.6fr) minmax(0,1fr)}}
.form-head{display:flex;align-items:center;gap:.7rem;padding:1rem 1.5rem;border-bottom:1px solid var(--line)}
.form-head .row-ico{width:2.1rem;height:2.1rem}
.label-no{display:inline-grid;place-items:center;width:1.4rem;height:1.4rem;margin-right:.5rem;border-radius:50%;background:var(--primary-tint);color:var(--primary-hover);font-size:.75rem;font-weight:700;vertical-align:1px}
.pick{padding:var(--s-5);border:1px solid var(--line);border-radius:var(--r-card);background:var(--surface)}
.pick-empty{display:flex;flex-direction:column;align-items:center;gap:.5rem;padding:var(--s-4) 0;text-align:center;color:var(--muted)}
.pick-empty .row-ico{width:3rem;height:3rem}
.pick-name{font:600 1.25rem/1.25 var(--font-head)}
.pick dl{display:grid;gap:.6rem;margin-top:var(--s-4);font-size:.9375rem}
.pick dl div{display:flex;align-items:center;justify-content:space-between;gap:1rem}
.pick dt{color:var(--muted)}
.pick dd{font-weight:600;text-align:right}
.pick[hidden],.pick-empty[hidden]{display:none}
.pick:not([hidden]){animation:pop .35s var(--ease) both}

@media (max-width:640px){
    .hero{padding:var(--s-5) var(--s-4)}
    .bars{gap:.4rem}
    .legend{grid-template-columns:minmax(0,1fr)}
}

/* =====================================================================
   8c. HIASAN & ELEMEN TAMBAHAN: latar, banner halaman, kartu kategori, jam, footer
   ===================================================================== */
/* latar berwarna lembut di belakang semua halaman */
body::before{content:"";position:fixed;inset:0;z-index:-1;pointer-events:none;background:radial-gradient(38rem 26rem at 92% -6%,rgba(52,80,122,.13),transparent 62%),radial-gradient(30rem 24rem at -6% 42%,rgba(30,138,122,.10),transparent 62%),radial-gradient(28rem 22rem at 72% 108%,rgba(217,98,43,.09),transparent 62%)}
.dekor{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden}
.dk{position:absolute;height:auto;opacity:.32;animation:floatY 9s ease-in-out infinite}
.dk-1{width:5.5rem;right:4%;top:18%;color:var(--c-lab)}
.dk-2{width:2.6rem;left:3%;top:62%;color:var(--c-olah);animation-delay:-3s}
.dk-3{width:3.4rem;right:7%;bottom:9%;color:var(--c-kes);animation-delay:-5s}
.dk-4{width:6rem;left:42%;top:3%;color:var(--c-mm);animation-delay:-7s}
@media (max-width:760px){.dk-3,.dk-4{display:none}}
.main{position:relative;z-index:1}

/* tombol & merek sedikit lebih hidup */
.btn-primary{background:linear-gradient(180deg,#3F5F8F,var(--primary));box-shadow:0 6px 14px -7px rgba(52,80,122,.85)}
.btn-primary:hover{background:linear-gradient(180deg,#34507A,var(--primary-hover));transform:translateY(-1px)}
.btn-primary:active{transform:translateY(1px)}
.brand-mark{background:linear-gradient(135deg,#4F72A6,var(--primary));box-shadow:0 6px 14px -6px rgba(52,80,122,.75)}
.side-link .ico{transition:transform var(--dur)}
.side-link:hover .ico{transform:scale(1.12)}
.side-link.is-active{box-shadow:inset 3px 0 0 var(--primary)}
.empty .empty-ico{box-shadow:0 0 0 8px rgba(52,80,122,.06);animation:floatY 4.5s ease-in-out infinite}
.alert[hidden]{display:none}

/* garis warna empat kategori di tepi atas kartu */
.card-accent{position:relative;overflow:hidden}
.card-accent::before{content:"";position:absolute;left:0;right:0;top:0;height:3px;background:linear-gradient(90deg,var(--c-lab),var(--c-olah),var(--c-kes),var(--c-mm))}

/* hiasan di kotak sambutan & kartu angka */
.hero::before{content:"";position:absolute;right:-4rem;top:-5rem;width:16rem;height:16rem;border-radius:50%;background:radial-gradient(circle,rgba(52,80,122,.18),transparent 65%);pointer-events:none}
.stat{overflow:hidden}
.stat::before{content:"";position:absolute;right:-1.3rem;bottom:-1.3rem;width:5.2rem;height:5.2rem;border-radius:50%;background:var(--t-bg);opacity:.75;pointer-events:none}
.stat::after{content:"";position:absolute;right:1.5rem;bottom:1.4rem;width:1.1rem;height:1.1rem;border-radius:50%;border:3px solid var(--t-line);pointer-events:none}

/* banner halaman dalam (Ajukan, Verifikasi, Data Alat) */
.banner{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;gap:var(--s-5);padding:var(--s-5);border:1px solid var(--t-line);border-radius:20px;background:linear-gradient(120deg,#fff 0%,var(--t-bg) 100%)}
.banner::before{content:"";position:absolute;right:-3rem;top:-4rem;width:14rem;height:14rem;border-radius:50%;background:radial-gradient(circle,var(--t-bg),transparent 68%);pointer-events:none}
.banner-body{position:relative;min-width:0;max-width:36rem}
.banner-art{position:relative;display:none;flex:none;width:11.5rem;height:9rem}
.banner-art .big{position:absolute;right:1.4rem;top:50%;translate:0 -50%;rotate:-6deg;display:grid;place-items:center;width:5.6rem;height:5.6rem;border-radius:28px;background:var(--t-fg);color:#fff;box-shadow:0 16px 30px -12px var(--t-fg);animation:floatY 6s ease-in-out infinite}
.banner-art .big .ico{width:2.6rem;height:2.6rem;stroke-width:1.5}
.banner-art .mini{position:absolute;display:grid;place-items:center;width:2.7rem;height:2.7rem;border-radius:14px;background:#fff;border:1px solid var(--t-line);color:var(--t-fg);box-shadow:0 8px 18px rgba(31,41,51,.10);animation:floatY 5s ease-in-out infinite}
.banner-art .mini-1{left:0;top:.3rem;animation-delay:-1s}
.banner-art .mini-2{left:.8rem;bottom:.2rem;animation-delay:-2.5s}
.banner-art .mini-3{right:0;top:-.2rem;animation-delay:-4s}
@media (min-width:760px){.banner-art{display:block}}
@media (max-width:640px){.banner{padding:var(--s-5) var(--s-4)}}

/* ikon kategori & kartu kategori */
.kat-ico{display:grid;place-items:center;width:2.4rem;height:2.4rem;flex:none;border-radius:12px;background:var(--kat-tint);color:var(--kat-ink);border:1px solid color-mix(in srgb,var(--kat) 28%,transparent)}
.kat-ico .ico{width:1.2rem;height:1.2rem}
.kat-ico.lg{width:3rem;height:3rem;border-radius:14px}
.kat-ico.lg .ico{width:1.5rem;height:1.5rem}
.kat-grid{display:grid;gap:var(--s-3);grid-template-columns:repeat(2,minmax(0,1fr))}
@media (min-width:900px){.kat-grid{grid-template-columns:repeat(4,minmax(0,1fr))}}
.kat-tile{position:relative;overflow:hidden;display:flex;flex-direction:column;gap:.2rem;padding:var(--s-4);background:linear-gradient(160deg,#fff 40%,var(--kat-tint));border:1px solid var(--line);border-radius:var(--r-card);transition:transform var(--dur),box-shadow var(--dur),border-color var(--dur)}
.kat-tile:hover{transform:translateY(-3px);border-color:var(--kat);box-shadow:0 10px 24px rgba(31,41,51,.08)}
.kat-tile::after{content:"";position:absolute;right:-1.6rem;bottom:-1.6rem;width:5.2rem;height:5.2rem;border-radius:50%;background:var(--kat);opacity:.12;pointer-events:none}
.kat-tile b{margin-top:.6rem;font:600 1rem/1.25 var(--font-head)}
.kat-tile small{font-size:.875rem;color:var(--muted)}
.kat-tile .arrow{position:absolute;right:.9rem;top:.9rem;color:var(--muted);transition:transform var(--dur),color var(--dur)}
.kat-tile:hover .arrow{transform:translateX(3px);color:var(--kat-ink)}

/* tip berganti, daftar centang, jam */
.tip-rot{display:inline-block;transition:opacity .3s,transform .3s}
.tip-rot.is-swap{opacity:0;transform:translateY(4px)}
.check-list{list-style:none;padding:0;margin:var(--s-4) 0 0;display:grid;gap:.75rem}
.check-list li{display:flex;gap:.65rem;align-items:flex-start;color:var(--ink-2)}
.check-list .ico{margin-top:2px;color:#1D6837}
.side-card{position:relative;overflow:hidden;margin-top:auto;padding:1rem;border-radius:var(--r-card);background:linear-gradient(140deg,var(--primary) 0%,#4F72A6 100%);color:#fff}
.side-card::before{content:"";position:absolute;right:-1.8rem;top:-1.8rem;width:6rem;height:6rem;border-radius:50%;background:rgba(255,255,255,.12)}
.side-card::after{content:"";position:absolute;right:1.4rem;bottom:-1.6rem;width:4rem;height:4rem;border-radius:50%;background:rgba(255,255,255,.08)}
.side-card .sc-label{position:relative;display:flex;align-items:center;gap:.4rem;margin-bottom:.55rem;font-size:.8125rem;font-weight:600;color:rgba(255,255,255,.85)}
.side-card .clock{position:relative;font:600 1.65rem/1 var(--font-head);letter-spacing:-.01em;font-variant-numeric:tabular-nums}
.side-card .sc-date{position:relative;margin-top:.35rem;font-size:.875rem;color:rgba(255,255,255,.88)}
@media (max-width:1023px){.side-card{display:none}}

/* footer */
.site-foot{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem 1.5rem;margin-top:var(--s-7);padding-top:var(--s-4);border-top:1px dashed var(--line-strong);font-size:.875rem;color:var(--muted)}
.foot-brand{display:inline-flex;align-items:center;gap:.6rem}
.foot-brand b{color:var(--ink);font-family:var(--font-head)}
.foot-kat{display:flex;flex-wrap:wrap;gap:.35rem .9rem}
.foot-kat span{display:inline-flex;align-items:center;gap:.35rem}

/* halaman masuk: hiasan & elemen tambahan */
.auth>*{position:relative;z-index:1}
.auth-art{position:relative;overflow:hidden}
.auth-art::before{content:"";position:absolute;inset:0;pointer-events:none;background:radial-gradient(24rem 18rem at 100% 0,rgba(52,80,122,.16),transparent 65%),radial-gradient(22rem 18rem at 0 100%,rgba(217,98,43,.14),transparent 65%)}
.auth-art>*{position:relative}
.rak-wrap{position:relative;width:100%;max-width:34rem;margin:0 auto}
.chip-float{position:absolute;z-index:2;display:inline-flex;align-items:center;gap:.45rem;padding:.4rem .85rem .4rem .6rem;border-radius:var(--r-pill);background:#fff;border:1px solid var(--line);box-shadow:0 10px 22px rgba(31,41,51,.12);font-size:.875rem;font-weight:600;color:var(--ink-2);white-space:nowrap;animation:floatY 6s ease-in-out infinite}
.chip-float .ico{color:var(--primary)}
.cf-1{left:-1rem;top:8%}
.cf-2{right:-.5rem;top:44%;animation-delay:-2s}
.cf-3{left:14%;bottom:-.6rem;animation-delay:-4s}
.auth-corner{position:absolute;top:1rem;right:1rem;display:inline-flex;align-items:center;gap:.5rem;padding:.35rem .8rem;border-radius:var(--r-pill);background:var(--surface);border:1px solid var(--line);font-size:.8125rem;font-weight:600;color:var(--ink-2);font-variant-numeric:tabular-nums}
.auth-corner .ico{color:var(--primary)}
.feats{list-style:none;padding:0;margin:var(--s-6) 0 0;display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:.6rem}
.feats li{display:flex;flex-direction:column;align-items:center;gap:.3rem;padding:.85rem .4rem;text-align:center;background:rgba(255,255,255,.75);border:1px solid var(--line);border-radius:var(--r-card)}
.feats b{font:600 .9375rem var(--font-head)}
.feats small{font-size:.8125rem;line-height:1.3;color:var(--muted)}

/* =====================================================================
   9. GERAK (hemat) & AKSESIBILITAS GERAK
   ===================================================================== */
@keyframes pageIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
@keyframes fade{from{opacity:0}to{opacity:1}}
@keyframes dlgIn{from{opacity:0;transform:translateY(8px) scale(.98)}to{opacity:1;transform:none}}
@keyframes drawerIn{from{opacity:0;transform:translateX(28px)}to{opacity:1;transform:none}}
@keyframes toastIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
@keyframes spin{to{transform:rotate(360deg)}}
/* Umpan balik satu kali untuk baris/kartu yang baru berubah: tambahkan class .flash-ok */
.flash-ok{animation:flashOk 1.4s ease-out 1}
@keyframes flashOk{from{background:#E2F3E8}to{background:transparent}}

@media (prefers-reduced-motion:reduce){
    html{scroll-behavior:auto}
    *,*::before,*::after{animation-duration:.01ms !important;animation-iteration-count:1 !important;transition-duration:.01ms !important}
}
</style>
@endverbatim