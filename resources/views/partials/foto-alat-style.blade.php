{{-- Letak file: resources/views/partials/foto-alat-style.blade.php (FILE BARU) --}}
{{-- Gaya foto alat: dipakai di Data Alat (admin) dan Ajukan Pinjam (siswa) --}}
<style>
    .alat-thumb{width:2.4rem;height:2.4rem;flex:none;border-radius:12px;object-fit:cover;border:1px solid var(--line);background:var(--surface-2)}
    .alat-thumb.lg{width:3.25rem;height:3.25rem;border-radius:14px}
    .foto-prev{display:block;width:100%;max-height:11rem;margin-top:.6rem;object-fit:cover;border:1px solid var(--line);border-radius:var(--r-ctl);background:var(--surface-2)}
    .foto-prev[hidden]{display:none}
    .pick-foto{display:block;width:100%;aspect-ratio:4/3;margin-bottom:.9rem;object-fit:cover;border:1px solid var(--line);border-radius:var(--r-ctl);background:var(--surface-2)}
    .pick-foto[hidden]{display:none}
    .pick-desk{margin-top:.8rem;font-size:.9375rem;line-height:1.5;color:var(--ink-2)}
    .pick-desk[hidden]{display:none}
    .cek-hapus{display:flex;align-items:center;gap:.5rem;margin-top:.5rem;font-size:.9375rem;color:var(--ink-2)}
</style>