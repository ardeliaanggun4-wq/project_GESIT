{{-- Letak file: resources/views/partials/head.blade.php (FILE BARU) --}}
{{-- Berisi font, konfigurasi Tailwind, dan seluruh gaya + animasi GESIT. Dipakai oleh semua layout. --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@62..125,500..900&family=IBM+Plex+Mono:wght@400;500;600&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

@verbatim
<script>
    tailwind.config = { theme: { extend: {
        colors: { paper: '#ece7da', ink: '#17211c', signal: '#e4501d', olive: '#56693a', brick: '#a8321e' },
        fontFamily: {
            sans: ['Public Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            display: ['Archivo', 'sans-serif'],
            mono: ['IBM Plex Mono', 'ui-monospace', 'monospace']
        }
    } } }
</script>

<style>
:root{
    --paper:#ece7da; --paper-2:#e2dccb; --card:#f7f4ea;
    --ink:#17211c; --ink-soft:#46524b; --mute:#5f685f;
    --signal:#e4501d; --olive:#56693a; --mustard:#8f6a00; --brick:#a8321e;
    --tint:rgba(228,80,29,.08);
    --ease:cubic-bezier(.2,.8,.2,1); --spring:cubic-bezier(.3,1.4,.5,1);
}
html{scroll-behavior:smooth}
body{
    font-family:'Public Sans',system-ui,sans-serif; color:var(--ink); background-color:var(--paper);
    background-image:radial-gradient(rgba(23,33,28,.12) 1px,transparent 1px); background-size:20px 20px;
    -webkit-font-smoothing:antialiased; animation:pageIn .5s var(--ease) both;
}
body.modal-open{overflow:hidden}
:focus-visible{outline:2px solid var(--signal); outline-offset:2px}
::selection{background:var(--signal); color:var(--card)}

/* ---------- Tipografi ---------- */
.display{font:800 clamp(1.9rem,4vw,2.9rem)/1.02 'Archivo',sans-serif; font-stretch:118%; letter-spacing:-.02em}
.h-sec{font:700 1rem/1.2 'Archivo',sans-serif; font-stretch:110%; letter-spacing:.01em}
.num{font:800 2.6rem/1 'Archivo',sans-serif; font-stretch:125%; letter-spacing:-.02em; font-variant-numeric:tabular-nums}
.mono-label{font:500 .68rem/1.3 'IBM Plex Mono',monospace; letter-spacing:.1em; text-transform:uppercase; color:var(--mute)}
.muted{color:var(--mute)} .soft{color:var(--ink-soft)}
.kode{font:500 .8rem 'IBM Plex Mono',monospace; letter-spacing:.02em}

/* ---------- Animasi teks ---------- */
.line{display:block; overflow:hidden; padding-bottom:.14em; margin-bottom:-.14em}
.line>span{display:inline-block; animation:lineUp .8s var(--ease) backwards; animation-delay:calc(var(--i,0)*90ms + .1s)}
.hl{padding:0 .12em; margin:0 -.12em; background:linear-gradient(var(--signal),var(--signal)) no-repeat 0 90%/0% 34%; animation:hl .9s var(--ease) .75s forwards}
.caret::after{content:''; display:inline-block; width:.5ch; height:1em; margin-left:2px; background:var(--signal); vertical-align:-.12em; animation:blink 1s steps(1) infinite}
.rise{animation:rise .6s var(--ease) backwards; animation-delay:calc(var(--i,0)*70ms)}

/* ---------- Topbar & navigasi ---------- */
.topbar{background:var(--card); border-bottom:1.5px solid var(--ink)}
.brand{display:flex; align-items:center; gap:.7rem}
.brand-mark{position:relative; width:2.4rem; height:2.4rem; display:grid; place-items:center; background:var(--ink); color:var(--paper);
    font:800 1.1rem 'Archivo',sans-serif; font-stretch:125%; clip-path:polygon(0 0,70% 0,100% 30%,100% 100%,0 100%); transition:transform .4s var(--spring)}
.brand-mark::after{content:''; position:absolute; top:0; right:0; width:30%; height:30%; background:var(--signal)}
.brand:hover .brand-mark{transform:rotate(-8deg) scale(1.07)}
.brand-name{display:block; font:800 1.15rem/1 'Archivo',sans-serif; font-stretch:125%; letter-spacing:.04em}
.brand-sub{display:block; margin-top:.2rem; font:400 .62rem/1.2 'IBM Plex Mono',monospace; letter-spacing:.04em; color:var(--mute)}
.avatar{width:2.1rem; height:2.1rem; display:grid; place-items:center; background:var(--paper-2); border:1.5px solid var(--ink); border-radius:4px;
    font:800 .8rem 'Archivo',sans-serif; transition:transform .3s var(--spring)}
.user-chip:hover .avatar{transform:rotate(6deg)}
.nav{display:flex; gap:.25rem; overflow-x:auto; scrollbar-width:none}
.nav::-webkit-scrollbar{display:none}
.nav-link{position:relative; display:inline-flex; align-items:baseline; gap:.45rem; padding:.85rem .9rem; white-space:nowrap;
    font:600 .78rem 'Archivo',sans-serif; font-stretch:110%; letter-spacing:.06em; text-transform:uppercase; color:var(--ink-soft); transition:color .2s, transform .15s}
.nav-no{font:500 .62rem 'IBM Plex Mono',monospace; color:var(--mute); transition:color .2s, transform .3s var(--spring)}
.nav-link::after{content:''; position:absolute; left:.9rem; right:.9rem; bottom:-1.5px; height:3px; background:var(--signal); transform:scaleX(0); transform-origin:left; transition:transform .35s var(--ease)}
.nav-link:hover,.nav-link.is-active{color:var(--ink)}
.nav-link:hover::after,.nav-link.is-active::after{transform:scaleX(1)}
.nav-link:hover .nav-no{color:var(--signal); transform:translateY(-2px)}
.nav-link:active{transform:scale(.95)}

/* ---------- Tombol ---------- */
.btn{--c:var(--ink); position:relative; isolation:isolate; overflow:hidden; display:inline-flex; align-items:center; justify-content:center; gap:.5rem;
    font:600 .76rem/1 'Archivo',sans-serif; font-stretch:112%; letter-spacing:.08em; text-transform:uppercase; cursor:pointer;
    padding:.85rem 1.2rem; border:1.5px solid var(--c); border-radius:4px; box-shadow:3px 3px 0 var(--c);
    transition:transform .15s var(--ease), box-shadow .2s var(--ease), color .25s}
.btn::before{content:''; position:absolute; inset:0; z-index:-1; background:var(--signal); transform:translateY(101%); transition:transform .35s var(--ease)}
.btn:hover::before{transform:none}
.btn:hover{transform:translate(-1px,-1px); box-shadow:4px 4px 0 var(--c)}
.btn:active{transform:translate(3px,3px) scale(.98); box-shadow:0 0 0 var(--c)}
.btn-primary{background:var(--ink); color:var(--paper)}
.btn-primary:hover{color:var(--ink)}
.btn-ghost{background:var(--card); color:var(--ink)}
.btn-ok{--c:var(--olive); background:var(--card); color:var(--olive)}
.btn-ok::before{background:var(--olive)} .btn-ok:hover{color:var(--card)}
.btn-no{--c:var(--brick); background:var(--card); color:var(--brick)}
.btn-no::before{background:var(--brick)} .btn-no:hover{color:var(--card)}
.btn-sm{padding:.55rem .8rem; font-size:.66rem; box-shadow:2px 2px 0 var(--c)}
.btn.is-loading{pointer-events:none; opacity:.85}
.btn.is-loading::after{content:''; width:.8em; height:.8em; border:2px solid currentColor; border-right-color:transparent; border-radius:50%; animation:spin .6s linear infinite}
.ripple{position:absolute; z-index:-1; border-radius:50%; background:currentColor; opacity:.28; transform:scale(0); animation:ripple .6s var(--ease) forwards; pointer-events:none}

.link-act{position:relative; background:none; border:0; padding:.2rem 0; cursor:pointer; color:var(--ink);
    font:600 .7rem 'IBM Plex Mono',monospace; letter-spacing:.06em; text-transform:uppercase; transition:transform .15s, color .2s}
.link-act::after{content:''; position:absolute; left:0; right:0; bottom:0; height:1.5px; background:currentColor; transform:scaleX(.3); transform-origin:left; transition:transform .3s var(--ease)}
.link-act:hover{color:var(--signal)} .link-act:hover::after{transform:scaleX(1)}
.link-act:active{transform:translateY(1px)}
.link-act.danger{color:var(--brick)} .link-act.danger:hover{color:var(--signal)}

/* ---------- Kartu, ledger, tabel ---------- */
.card{background:var(--card); border:1.5px solid var(--ink); border-radius:6px}
.card-lift{transition:transform .25s var(--ease), box-shadow .25s var(--ease)}
.card-lift:hover{transform:translate(-2px,-2px); box-shadow:4px 4px 0 var(--ink)}
.card-lift:active{transform:scale(.99); box-shadow:0 0 0 var(--ink)}
.ledger{display:grid; gap:1.5px; background:var(--ink); border:1.5px solid var(--ink); border-radius:6px; overflow:hidden}
.ledger>*{position:relative; background:var(--card); padding:1.3rem 1.4rem; transition:background .25s}
.ledger a:hover,.ledger a:focus-visible{background:#f3e1d6}
.ledger .arrow{position:absolute; top:1.2rem; right:1.3rem; font:500 .8rem 'IBM Plex Mono',monospace; opacity:0; transform:translateX(-6px); transition:.3s var(--ease)}
.ledger a:hover .arrow{opacity:1; transform:none}
.dot{display:inline-block; width:.5rem; height:.5rem; flex-shrink:0}

.ledger-table{width:100%; border-collapse:collapse; font-size:.875rem}
.ledger-table thead th{padding:.8rem 1.1rem; text-align:left; white-space:nowrap; background:var(--paper-2); border-bottom:1.5px solid var(--ink);
    font:500 .66rem 'IBM Plex Mono',monospace; letter-spacing:.1em; text-transform:uppercase; color:var(--ink-soft)}
.ledger-table tbody td{padding:1rem 1.1rem; vertical-align:middle; border-bottom:1px dashed rgba(23,33,28,.3)}
.ledger-table tbody tr{transition:background .2s}
.ledger-table tbody tr:hover{background:var(--tint)}
.ledger-table tbody tr:last-child td{border-bottom:0}

.list-row{display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 1.4rem; border-bottom:1px dashed rgba(23,33,28,.3);
    transition:background .2s, padding-left .25s var(--ease)}
.list-row:last-child{border-bottom:0}
.list-row:hover{background:var(--tint); padding-left:1.9rem}
.date-tile{width:3rem; height:3.4rem; flex-shrink:0; display:grid; align-content:center; justify-items:center; background:var(--paper-2); border:1.5px solid var(--ink); border-radius:4px; transition:transform .3s var(--spring)}
.date-tile b{font:800 1.15rem/1 'Archivo',sans-serif; font-stretch:120%}
.date-tile small{font:500 .58rem 'IBM Plex Mono',monospace; letter-spacing:.1em; text-transform:uppercase; color:var(--ink-soft)}
.list-row:hover .date-tile{transform:rotate(-5deg)}
.empty{padding:2rem 1.4rem; font-size:.875rem; color:var(--mute)}

/* Cap status */
.stamp{--c:var(--ink-soft); display:inline-block; padding:.32rem .55rem; border:1.5px solid var(--c); border-radius:3px; color:var(--c); white-space:nowrap;
    font:600 .64rem/1 'IBM Plex Mono',monospace; letter-spacing:.1em; text-transform:uppercase; transform:rotate(-2deg);
    animation:stampIn .5s var(--spring) backwards; animation-delay:calc(var(--i,0)*70ms + .35s)}
.stamp-ok{--c:var(--olive)} .stamp-wait{--c:var(--mustard)} .stamp-no{--c:var(--brick)}

/* Grafik */
.bar{position:relative; width:100%; background:var(--ink); transform-origin:bottom; transition:background .2s;
    animation:grow .7s var(--ease) backwards; animation-delay:calc(var(--i,0)*70ms + .3s)}
.bar::before{content:attr(data-v); position:absolute; top:-1.4rem; left:50%; transform:translateX(-50%); font:500 .68rem 'IBM Plex Mono',monospace; color:var(--ink-soft)}
.bar.is-peak{background:var(--signal)} .bar:hover{background:var(--olive)}
.track{height:.7rem; border:1.5px solid var(--ink); background:var(--card); overflow:hidden}
.fill{height:100%; background:repeating-linear-gradient(135deg,var(--ink) 0 4px,transparent 4px 7px); transform-origin:left;
    animation:growX .8s var(--ease) backwards; animation-delay:calc(var(--i,0)*90ms + .4s)}

/* Kartu kanban */
.tag-card{--c:var(--mustard); border-top:5px solid var(--c)}
.tag-card.ok{--c:var(--olive)} .tag-card.no{--c:var(--brick)}
.col-head{display:flex; align-items:center; gap:.6rem; margin-bottom:.9rem; padding-bottom:.6rem; border-bottom:1.5px solid var(--ink)}
.col-head .count{margin-left:auto; font:600 .8rem 'IBM Plex Mono',monospace}

/* ---------- Form ---------- */
.label{display:block; margin-bottom:.35rem; font:500 .68rem 'IBM Plex Mono',monospace; letter-spacing:.1em; text-transform:uppercase; color:var(--ink-soft)}
.label-no{color:var(--signal); margin-right:.4rem}
.field,.field-box{width:100%; font:500 .92rem 'Public Sans',sans-serif; color:var(--ink); background:transparent; outline:none;
    transition:border-color .2s, box-shadow .25s var(--ease), transform .2s}
.field{padding:.6rem 0; border:0; border-bottom:1.5px solid var(--ink); border-radius:0}
.field:focus{border-bottom-color:var(--signal); box-shadow:0 1.5px 0 0 var(--signal)}
.field-box{padding:.65rem .8rem; border:1.5px solid var(--ink); border-radius:4px; background:var(--card)}
.field-box:focus{box-shadow:3px 3px 0 var(--signal); transform:translate(-1px,-1px)}
.field::placeholder,.field-box::placeholder{color:var(--mute); font-weight:400}
.form-head{display:flex; align-items:center; gap:.8rem; padding:.7rem 1.5rem; background:var(--ink); color:var(--paper); border-radius:4px 4px 0 0; font:500 .7rem 'IBM Plex Mono',monospace; letter-spacing:.1em; text-transform:uppercase}
.form-head b{color:var(--signal); font-weight:600}
.alert{border:1.5px solid var(--brick); border-left-width:6px; background:var(--card); color:var(--brick); border-radius:4px; padding:.8rem 1rem; font-size:.875rem; animation:rise .5s var(--ease) backwards}
.flash{display:flex; align-items:center; justify-content:space-between; gap:.8rem; padding:.8rem 1rem; font-size:.875rem; cursor:pointer;
    background:var(--card); border:1.5px solid var(--olive); border-left-width:6px; border-radius:4px; animation:slideIn .5s var(--spring) backwards; transition:opacity .4s, transform .4s}
.flash.is-out{opacity:0; transform:translateY(-8px)}

/* Segmen peran (login) */
.seg{position:relative; display:grid; grid-template-columns:1fr 1fr; border:1.5px solid var(--ink); border-radius:4px; background:var(--card); padding:3px}
.seg-pill{position:absolute; top:3px; bottom:3px; left:3px; width:calc(50% - 3px); background:var(--ink); border-radius:2px; transition:transform .4s var(--spring)}
.seg button{position:relative; z-index:1; background:transparent; border:0; cursor:pointer; padding:.6rem .8rem; font:600 .72rem 'Archivo',sans-serif; font-stretch:110%; letter-spacing:.06em; text-transform:uppercase; color:var(--ink-soft); transition:color .25s}
.seg button[aria-selected=true]{color:var(--paper)}
.seg button:active{transform:scale(.96)}

/* Tag inventaris (login) */
.tag{position:relative; width:min(100%,19rem); padding:3rem 1.5rem 1.5rem; background:var(--card); color:var(--ink); border-radius:10px 10px 6px 6px;
    box-shadow:6px 8px 0 rgba(0,0,0,.35); transform-origin:50% -3rem; transform:rotate(-4deg); animation:sway 6s ease-in-out infinite; cursor:pointer; user-select:none}
.tag::before{content:''; position:absolute; top:1rem; left:50%; width:.95rem; height:.95rem; margin-left:-.475rem; border-radius:50%; background:#1f2e22; box-shadow:inset 0 0 0 2px rgba(23,33,28,.35)}
.tag::after{content:''; position:absolute; bottom:calc(100% - 1.45rem); left:50%; width:2px; height:3rem; margin-left:-1px; background:rgba(236,231,218,.55)}
.tag:active{animation-play-state:paused; transform:rotate(-4deg) scale(.97)}

/* ---------- Modal ---------- */
.modal{position:fixed; inset:0; z-index:50; display:grid; place-items:center; padding:1rem; background:rgba(23,33,28,.6);
    opacity:0; visibility:hidden; transition:opacity .22s, visibility .22s}
.modal.is-open{opacity:1; visibility:visible}
.modal-panel{transform:translateY(16px) scale(.96); transition:transform .35s var(--spring); max-height:92vh; overflow-y:auto}
.modal.is-open .modal-panel{transform:none}

/* ---------- Keyframes ---------- */
@keyframes pageIn{from{opacity:0}to{opacity:1}}
@keyframes lineUp{from{transform:translateY(110%)}to{transform:none}}
@keyframes hl{to{background-size:100% 34%}}
@keyframes rise{from{opacity:0; transform:translateY(14px)}to{opacity:1; transform:none}}
@keyframes slideIn{from{opacity:0; transform:translateX(-18px)}to{opacity:1; transform:none}}
@keyframes stampIn{from{opacity:0; transform:scale(1.7) rotate(-9deg)}to{opacity:1; transform:rotate(-2deg)}}
@keyframes grow{from{transform:scaleY(0)}to{transform:none}}
@keyframes growX{from{transform:scaleX(0)}to{transform:none}}
@keyframes ripple{to{transform:scale(4); opacity:0}}
@keyframes spin{to{transform:rotate(360deg)}}
@keyframes blink{50%{opacity:0}}
@keyframes sway{0%,100%{transform:rotate(-4deg)}50%{transform:rotate(-1deg)}}

@media (prefers-reduced-motion:reduce){
    *,*::before,*::after{animation-duration:.01ms !important; animation-delay:0s !important; animation-iteration-count:1 !important; transition-duration:.01ms !important}
}


/* ---------- Responsive layout: ponsel, tablet, desktop ---------- */
*,*::before,*::after{box-sizing:border-box}
html{width:100%; max-width:100%; overflow-x:clip; -webkit-text-size-adjust:100%}
body{min-width:320px; width:100%; max-width:100%; overflow-x:clip}
img,svg,video,canvas,iframe{max-width:100%}
img,video{height:auto}
input,select,textarea,button{font:inherit; max-width:100%}
input,select,textarea{min-width:0}
h1,h2,h3,p,a,button,td,th,label,span{overflow-wrap:anywhere}
main{min-width:0}
.card,.modal-panel{min-width:0; max-width:100%}
.nav{max-width:100%; padding-bottom:1px; overscroll-behavior-x:contain; -webkit-overflow-scrolling:touch}
.ledger-table{max-width:100%}
.card:has(>.ledger-table),.card:has(table){overflow-x:auto; -webkit-overflow-scrolling:touch}
.ledger-table th,.ledger-table td{overflow-wrap:normal}
.field,.field-box{min-width:0; max-width:100%}
button,.btn{touch-action:manipulation}
.modal{overflow-y:auto; overscroll-behavior:contain}
.modal-panel{width:min(100%,36rem); max-height:calc(100dvh - 2rem); overflow:auto}
@media(max-width:767px){
  .topbar>.mx-auto.max-w-6xl:first-child{height:auto; min-height:4rem; padding:.65rem 1rem; gap:.65rem; flex-wrap:wrap}
  .brand{min-width:0; gap:.5rem}
  .brand-mark{width:2rem;height:2rem;flex:0 0 2rem}
  .brand-name{font-size:1.05rem}
  .user-chip{gap:.45rem; min-width:0}
  .user-chip form{flex-shrink:0}
  .nav{padding-left:.65rem;padding-right:.65rem}
  .nav-link{padding:.75rem .65rem;font-size:.7rem}
  main.mx-auto.max-w-6xl{padding:1.5rem 1rem}
  .display{font-size:clamp(1.8rem,8vw,2.5rem);line-height:1.06;overflow-wrap:anywhere}
  .num{font-size:clamp(1.8rem,8vw,2.5rem)}
  .card.p-6{padding:1rem}
  .ledger>*{padding:1rem}
  .list-row{align-items:flex-start; padding:.85rem .9rem; gap:.7rem; flex-wrap:wrap}
  .list-row:hover{padding-left:.9rem}
  .flex.items-center.gap-4{gap:.65rem}
  .form-head{padding:.7rem 1rem;flex-wrap:wrap}
  .modal{padding:.5rem;place-items:center}
  .modal-panel{max-height:calc(100dvh - 1rem);padding:1rem}
  .ledger-table{font-size:.78rem}
  .ledger-table thead th{padding:.65rem .7rem;font-size:.6rem}
  .ledger-table tbody td{padding:.7rem}
  .btn{max-width:100%; white-space:normal; line-height:1.25; text-align:center}
  .tag{max-width:100%}
}
@media(max-width:480px){
  .topbar>.mx-auto.max-w-6xl:first-child{align-items:center}
  .brand-sub{display:none!important}
  .user-chip .avatar{width:1.8rem;height:1.8rem}
  .btn-sm{padding:.5rem .6rem;font-size:.62rem}
  .nav-link{gap:.3rem;letter-spacing:.035em}
  .nav-no{font-size:.58rem}
  main.mx-auto.max-w-6xl{padding:1.25rem .75rem}
  .display{font-size:clamp(1.65rem,8vw,2.15rem)}
  .grid.grid-cols-2{grid-template-columns:minmax(0,1fr)}
  .grid.md\:grid-cols-3{grid-template-columns:minmax(0,1fr)}
  .grid.lg\:grid-cols-3{grid-template-columns:minmax(0,1fr)}
  .ledger.grid-cols-2{grid-template-columns:minmax(0,1fr)}
  .flex.justify-end.gap-3,.flex.flex-wrap.gap-3{gap:.6rem}
  .flex.justify-end.gap-3>*{flex:1 1 auto}
  .flash{align-items:flex-start; flex-wrap:wrap}
  .seg button{padding:.65rem .45rem;font-size:.65rem}
}
@media(prefers-reduced-motion:reduce){
  html{scroll-behavior:auto}
}

</style>
@endverbatim