{{-- Letak file: resources/views/partials/motion.blade.php (timpa file lama) --}}
{{-- Interaksi: dialog & drawer, dialog konfirmasi, notifikasi, status loading saat kirim form.
     Animasi teks, angka berhitung, dan mengetik ada di bagian 5. --}}

{{-- Dialog konfirmasi bersama (pengganti confirm() bawaan browser).
     Pakai pada form: <form data-confirm data-confirm-title="Hapus alat ini?" data-confirm-text="..." data-confirm-ok="Ya, hapus"> --}}
<dialog id="dlg-konfirmasi" class="dlg" aria-labelledby="konfirmasi-judul" aria-describedby="konfirmasi-teks">
    <div class="dlg-body">
        <div class="dlg-icon"><svg class="ico ico-lg" aria-hidden="true"><use href="#i-alert"/></svg></div>
        <h2 id="konfirmasi-judul" class="t-sec">Lanjutkan tindakan ini?</h2>
        <p id="konfirmasi-teks" class="t-muted" style="margin-top:.35rem"></p>
    </div>
    <div class="dlg-foot">
        <button type="button" class="btn btn-secondary" data-close autofocus>Batal</button>
        <button type="button" class="btn btn-danger" id="konfirmasi-ok">Ya, lanjutkan</button>
    </div>
</dialog>

@verbatim
<script>
(() => {
    const $ = (s, r = document) => r.querySelector(s);

    /* 1. Dialog & drawer (elemen <dialog>)
       Buka : <button data-open="id-dialog">
       Isi  : data-form-action="url" (ubah action form), data-fill='{"#selector":"nilai"}', data-reset (kosongkan form)
       Tutup: <button data-close>, tombol Esc, atau klik area gelap */
    document.addEventListener('click', (e) => {
        const buka = e.target.closest('[data-open]');
        if (buka) {
            const dlg = document.getElementById(buka.dataset.open);
            if (!dlg) return;
            const form = dlg.querySelector('form');
            if (form && 'reset' in buka.dataset) form.reset();
            if (form && buka.dataset.formAction) form.action = buka.dataset.formAction;
            if (buka.dataset.fill) {
                try {
                    Object.entries(JSON.parse(buka.dataset.fill)).forEach(([sel, val]) => {
                        const el = dlg.querySelector(sel);
                        if (!el) return;
                        if (el.matches('input, select, textarea')) el.value = val; else el.textContent = val;
                    });
                } catch (err) { /* data-fill tidak valid: abaikan */ }
            }
            dlg.showModal();
            document.body.classList.add('dialog-open');
            return;
        }
        const tutup = e.target.closest('[data-close]');
        if (tutup) { tutup.closest('dialog')?.close(); return; }
        if (e.target instanceof HTMLDialogElement) e.target.close(); // klik area gelap
    });
    document.querySelectorAll('dialog').forEach((d) => d.addEventListener('close', () => {
        if (!document.querySelector('dialog[open]')) document.body.classList.remove('dialog-open');
    }));

    /* 2. Konfirmasi sebelum kirim form */
    let formMenunggu = null;
    const dlgKonfirmasi = $('#dlg-konfirmasi');
    const tombolOk = $('#konfirmasi-ok');

    /* 3. Kirim form: konfirmasi (jika diminta) lalu tampilkan status loading */
    document.addEventListener('submit', (e) => {
        if (e.defaultPrevented) return;
        const f = e.target;

        if (f.hasAttribute('data-confirm') && !f.dataset.dikonfirmasi) {
            e.preventDefault();
            formMenunggu = f;
            $('#konfirmasi-judul').textContent = f.dataset.confirmTitle || 'Lanjutkan tindakan ini?';
            $('#konfirmasi-teks').textContent = f.dataset.confirmText || '';
            tombolOk.textContent = f.dataset.confirmOk || 'Ya, lanjutkan';
            tombolOk.className = 'btn ' + (f.dataset.confirmTone === 'primary' ? 'btn-primary' : 'btn-danger');
            dlgKonfirmasi.showModal();
            return;
        }

        if (f.dataset.busy) { e.preventDefault(); return; } // cegah kirim ganda
        f.dataset.busy = '1';
        const btn = e.submitter || f.querySelector('button[type=submit], button:not([type])');
        if (btn) { btn.classList.add('is-loading'); btn.setAttribute('aria-busy', 'true'); }
    });
    tombolOk?.addEventListener('click', () => {
        const f = formMenunggu;
        if (!f) return;
        f.dataset.dikonfirmasi = '1';
        dlgKonfirmasi.close();
        f.requestSubmit();
    });
    // Kembali lewat tombol "back": bersihkan status loading
    addEventListener('pageshow', (e) => {
        if (!e.persisted) return;
        document.querySelectorAll('form[data-busy]').forEach((f) => { delete f.dataset.busy; delete f.dataset.dikonfirmasi; });
        document.querySelectorAll('.btn.is-loading').forEach((b) => { b.classList.remove('is-loading'); b.removeAttribute('aria-busy'); });
    });

    /* 5. Animasi teks & angka (dimatikan otomatis bila perangkat meminta gerak dikurangi)
       [data-split]  : kata-kata naik satu per satu
       [data-type]   : mengetik bergantian, isi berupa daftar kalimat JSON
       [data-count]  : angka berhitung naik dari 0
       [data-greet]  : diisi "Selamat pagi/siang/sore/malam" sesuai jam perangkat */
    const kurangiGerak = matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.querySelectorAll('[data-greet]').forEach((el) => {
        const j = new Date().getHours();
        el.textContent = j < 11 ? 'Selamat pagi' : j < 15 ? 'Selamat siang' : j < 18 ? 'Selamat sore' : 'Selamat malam';
    });

    document.querySelectorAll('[data-split]').forEach((el) => {
        const bungkus = (node) => [...node.childNodes].forEach((n) => {
            if (n.nodeType === 3) {
                const frag = document.createDocumentFragment();
                n.textContent.split(/(\s+)/).forEach((tok) => {
                    if (!tok) return;
                    if (/^\s+$/.test(tok)) { frag.append(' '); return; }
                    const w = document.createElement('span'); w.className = 'w';
                    const d = document.createElement('span'); d.textContent = tok;
                    w.append(d); frag.append(w);
                });
                n.replaceWith(frag);
            } else if (n.nodeType === 1) bungkus(n);
        });
        bungkus(el);
        el.classList.add('words');
        el.querySelectorAll('.w').forEach((w, i) => w.style.setProperty('--w', i));
    });

    document.querySelectorAll('[data-type]').forEach((el) => {
        let daftar;
        try { daftar = JSON.parse(el.dataset.type); } catch (err) { return; }
        if (!Array.isArray(daftar) || !daftar.length) return;
        el.textContent = daftar[0];
        if (kurangiGerak) return;
        el.textContent = '';
        el.classList.add('caret');
        let i = 0, j = 0, hapus = false;
        const jalan = () => {
            const t = daftar[i];
            if (!hapus) {
                el.textContent = t.slice(0, ++j);
                if (j === t.length) {
                    if (daftar.length === 1) { el.classList.remove('caret'); return; }
                    hapus = true; return setTimeout(jalan, 2400);
                }
                return setTimeout(jalan, 38);
            }
            el.textContent = t.slice(0, --j);
            if (j === 0) { hapus = false; i = (i + 1) % daftar.length; return setTimeout(jalan, 350); }
            setTimeout(jalan, 16);
        };
        setTimeout(jalan, 800);
    });

    document.querySelectorAll('[data-count]').forEach((el) => {
        const akhir = parseInt(el.dataset.count, 10);
        if (kurangiGerak || isNaN(akhir) || akhir < 1) return;
        const mulai = performance.now() + 300, lama = 900;
        el.textContent = '0';
        const langkah = (t) => {
            const p = Math.min(1, Math.max(0, (t - mulai) / lama));
            el.textContent = Math.round(akhir * (1 - Math.pow(1 - p, 3)));
            if (p < 1) requestAnimationFrame(langkah);
        };
        requestAnimationFrame(langkah);
    });

    /* 6. Jam & tanggal langsung, serta tip yang berganti
       [data-clock] : jam perangkat (tambah data-sec untuk detik)
       [data-date]  : tanggal perangkat (data-date="pendek" tanpa tahun)
       [data-rotate]: daftar kalimat JSON yang berganti tiap beberapa detik */
    document.querySelectorAll('[data-clock]').forEach((el) => {
        const tik = () => {
            const o = { hour: '2-digit', minute: '2-digit' };
            if (el.hasAttribute('data-sec')) o.second = '2-digit';
            el.textContent = new Date().toLocaleTimeString('id-ID', o).replace(/\./g, ':');
        };
        tik();
        setInterval(tik, 1000);
    });
    document.querySelectorAll('[data-date]').forEach((el) => {
        const o = { weekday: 'long', day: 'numeric', month: el.dataset.date === 'pendek' ? 'short' : 'long' };
        if (el.dataset.date !== 'pendek') o.year = 'numeric';
        el.textContent = new Date().toLocaleDateString('id-ID', o);
    });
    document.querySelectorAll('[data-rotate]').forEach((el) => {
        let daftar;
        try { daftar = JSON.parse(el.dataset.rotate); } catch (err) { return; }
        if (!Array.isArray(daftar) || daftar.length < 2 || kurangiGerak) return;
        let i = 0;
        setInterval(() => {
            el.classList.add('is-swap');
            setTimeout(() => { i = (i + 1) % daftar.length; el.textContent = daftar[i]; el.classList.remove('is-swap'); }, 300);
        }, 5000);
    });

    /* 4. Notifikasi berhasil: hilang sendiri atau saat ditutup */
    document.querySelectorAll('.toast').forEach((t) => {
        const tutup = () => { t.classList.add('is-out'); setTimeout(() => t.remove(), 300); };
        t.querySelector('[data-toast-close]')?.addEventListener('click', tutup);
        setTimeout(tutup, 5000);
    });
})();
</script>
@endverbatim