{{-- Letak file: resources/views/partials/motion.blade.php (FILE BARU) --}}
{{-- Interaksi: efek klik tombol, hitung angka, modal, notifikasi, status loading saat submit. --}}
@verbatim
<script>
(() => {
    const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* 1. Efek riak saat tombol ditekan */
    document.addEventListener('pointerdown', (e) => {
        const btn = e.target.closest('.btn');
        if (!btn || reduce) return;
        const r = btn.getBoundingClientRect(), s = Math.max(r.width, r.height);
        const dot = document.createElement('span');
        dot.className = 'ripple';
        dot.style.cssText = `width:${s}px;height:${s}px;left:${e.clientX - r.left - s / 2}px;top:${e.clientY - r.top - s / 2}px`;
        btn.appendChild(dot);
        dot.addEventListener('animationend', () => dot.remove());
    });

    /* 2. Angka berhitung naik: <span data-count="12">12</span> */
    window.countUp = (el, delay = 0) => {
        const end = parseInt(el.dataset.count, 10);
        if (isNaN(end) || reduce) return;
        const dur = 900, t0 = performance.now() + delay;
        el.textContent = '0';
        const tick = (t) => {
            const p = Math.min(1, Math.max(0, (t - t0) / dur));
            el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
            if (p < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    };
    document.querySelectorAll('[data-count]').forEach((el, i) => window.countUp(el, 350 + i * 90));

    /* 3. Modal: data-modal-open="id" / data-modal-close */
    const bukaModal = (m) => {
        if (!m) return;
        m.classList.add('is-open');
        document.body.classList.add('modal-open');
        setTimeout(() => m.querySelector('input:not([type=hidden]), select, textarea')?.focus(), 250);
    };
    const tutupModal = (m) => {
        if (!m) return;
        m.classList.remove('is-open');
        if (!document.querySelector('.modal.is-open')) document.body.classList.remove('modal-open');
    };
    document.addEventListener('click', (e) => {
        const buka = e.target.closest('[data-modal-open]');
        if (buka) return bukaModal(document.getElementById(buka.dataset.modalOpen));
        const tutup = e.target.closest('[data-modal-close]');
        if (tutup) return tutupModal(tutup.closest('.modal'));
        if (e.target.classList.contains('modal')) tutupModal(e.target);
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') document.querySelectorAll('.modal.is-open').forEach(tutupModal);
    });

    /* 4. Notifikasi: hilang sendiri atau saat diklik */
    document.querySelectorAll('.flash').forEach((el) => {
        const hilang = () => { el.classList.add('is-out'); setTimeout(() => el.remove(), 400); };
        el.addEventListener('click', hilang);
        setTimeout(hilang, 4500);
    });

    /* 5. Tombol submit menampilkan status loading */
    document.addEventListener('submit', (e) => {
        if (e.defaultPrevented) return;
        const btn = e.target.querySelector('button:not([type=button])');
        if (btn) setTimeout(() => btn.classList.add('is-loading'), 0);
    });
})();
</script>
@endverbatim