{{-- Letak file: resources/views/partials/auth-hiasan.blade.php (timpa file lama) --}}
{{-- Hiasan di sekeliling kartu masuk/daftar/lupa sandi, plus tombol "Kembali ke beranda".
     Pakai di dalam <div class="auth-card">: @include('partials.auth-hiasan') --}}
<div class="ah" aria-hidden="true">
    {{-- latar lembut & pola titik --}}
    <span class="ah-blob ah-b1"></span>
    <span class="ah-blob ah-b2"></span>
    <span class="ah-dots ah-d1"></span>
    <span class="ah-dots ah-d2"></span>
    <span class="ah-ring"></span>

</div>

{{-- Kembali ke halaman awal (pengantar & tentang GESIT) --}}
<a href="{{ url('/') }}" class="auth-back">
    <svg class="ico" aria-hidden="true" style="transform:rotate(180deg)"><use href="#i-arrow"/></svg>
    Kembali ke beranda
</a>

@verbatim
<style>
.auth-back{display:inline-flex;align-items:center;gap:.45rem;margin:0 0 1rem;padding:.45rem 1rem .45rem .75rem;border-radius:999px;background:var(--surface);border:1px solid var(--line-strong);font-size:.875rem;font-weight:600;color:var(--ink-2);text-decoration:none;box-shadow:0 6px 14px -10px rgba(31,41,51,.45);transition:color var(--dur),border-color var(--dur),transform var(--dur)}
.auth-back .ico{width:1.05rem;height:1.05rem;color:var(--primary);transition:transform var(--dur)}
.auth-back:hover{color:var(--primary);border-color:var(--primary)}
.auth-back:hover .ico{transform:translateX(-3px) rotate(180deg) !important}
.auth-back:focus-visible{outline:3px solid var(--primary);outline-offset:3px}
@media (prefers-reduced-motion:no-preference){.auth-back{animation:labUp .5s var(--ease) both}}
</style>
@endverbatim