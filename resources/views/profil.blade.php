{{-- Letak file: resources/views/profil.blade.php --}}
@extends($user->isAdmin() ? 'layouts.admin' : 'layouts.siswa')
@section('title', 'Profil')

@section('content')
    <div class="mb-8">
        <p class="eyebrow rise">Profil</p>
        <h1 class="display mt-3">
            <span class="line"><span style="--i:0">Foto <span class="hl">profil</span></span></span>
        </h1>
        <p class="page-lead rise" style="--i:2">Foto tampil di samping namamu. Format JPG, PNG, atau WEBP, maksimal 2 MB.</p>
    </div>

    @if ($errors->any())
        <div class="alert mb-6 rise" role="alert">
            <svg class="ico" aria-hidden="true"><use href="#i-alert"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="card card-pad rise" style="--i:2; max-width:36rem">
        <div style="display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap">
            <div id="pratinjau">
                <x-avatar :user="$user" style="width:6rem;height:6rem;font-size:1.75rem;overflow:hidden" />
            </div>
            <div>
                <div class="t-card">{{ $user->name }}</div>
                <div class="t-small t-muted">{{ $user->kelas_jabatan ?: ucfirst($user->role) }} · {{ $user->nisn_nip }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('profil.foto') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="label" for="foto">Pilih foto baru</label>
                <input id="foto" type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="field" required>
                <p class="hint" id="hint-foto">Foto persegi akan terlihat paling rapi.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary">
                    <svg class="ico" aria-hidden="true"><use href="#i-check"/></svg>Simpan foto
                </button>
            </div>
        </form>

        @if ($user->foto)
            <form method="POST" action="{{ route('profil.foto.hapus') }}" class="mt-3"
                  data-confirm
                  data-confirm-title="Hapus foto profil?"
                  data-confirm-text="Tampilanmu akan kembali memakai inisial nama."
                  data-confirm-ok="Ya, hapus">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-ghost is-danger btn-sm">
                    <svg class="ico ico-sm" aria-hidden="true"><use href="#i-trash"/></svg>Hapus foto
                </button>
            </form>
        @endif
    </div>
@endsection

@push('scripts')
<script>
(() => {
    const input = document.getElementById('foto');
    const hint = document.getElementById('hint-foto');
    const kotak = document.querySelector('#pratinjau .avatar');
    input.addEventListener('change', () => {
        const f = input.files[0];
        if (!f) return;
        if (f.size > 2 * 1024 * 1024) {
            hint.textContent = 'Ukuran foto melebihi 2 MB. Pilih foto yang lebih kecil.';
            hint.style.color = 'var(--danger)';
            input.value = '';
            return;
        }
        hint.textContent = f.name;
        hint.style.color = '';
        kotak.innerHTML = '';
        const img = document.createElement('img');
        img.src = URL.createObjectURL(f);
        img.alt = '';
        img.style.cssText = 'width:100%;height:100%;object-fit:cover';
        kotak.appendChild(img);
    });
})();
</script>
@endpush