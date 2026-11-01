{{-- Letak file: resources/views/partials/toast.blade.php (FILE BARU) --}}
{{-- Umpan balik saat aksi berhasil: membaca session('status'). Hilang sendiri setelah beberapa detik. --}}
@if (session('status'))
    <div class="toast" role="status">
        <svg class="ico" aria-hidden="true"><use href="#i-check"/></svg>
        <span>{{ session('status') }}</span>
        <button type="button" class="toast-x" data-toast-close aria-label="Tutup pemberitahuan">
            <svg class="ico ico-sm" aria-hidden="true"><use href="#i-x"/></svg>
        </button>
    </div>
@endif