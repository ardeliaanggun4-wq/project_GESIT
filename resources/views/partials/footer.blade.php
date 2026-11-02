{{-- Letak file: resources/views/partials/footer.blade.php (FILE BARU) --}}
<footer class="site-foot">
    <div class="foot-brand">
        <span class="brand-mark" style="width:1.75rem;height:1.75rem;border-radius:8px"><svg class="ico ico-sm" aria-hidden="true"><use href="#i-grid"/></svg></span>
        <span><b>GESIT</b> · Gerakan Sarana Inventaris Terpadu</span>
    </div>
    <div class="foot-kat" aria-hidden="true">
        <span data-kat="praktik_lab"><i class="dot"></i>Lab</span>
        <span data-kat="olahraga"><i class="dot"></i>Olahraga</span>
        <span data-kat="kesenian"><i class="dot"></i>Kesenian</span>
        <span data-kat="multimedia"><i class="dot"></i>Multimedia</span>
    </div>
    <span>© {{ now()->year }} GESIT</span>
</footer>