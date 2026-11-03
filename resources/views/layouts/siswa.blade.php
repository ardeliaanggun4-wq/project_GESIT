<!doctype html>
{{-- Letak file: resources/views/layouts/siswa.blade.php (timpa file lama) --}}
{{-- Kerangka sama dengan layouts/admin.blade.php: sidebar di layar lebar, header + tab menu di HP. --}}
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Beranda') · GESIT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head')
</head>
<body>
    @include('partials.icons')
    @include('partials.dekor')
    <a href="#konten" class="skip-link">Lewati ke konten utama</a>

    <div class="shell">
        <aside class="sidebar">
            <a href="{{ route('siswa.beranda') }}" class="brand side-brand" aria-label="GESIT, ke halaman Beranda">
                <span class="brand-mark"><svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg></span>
                <span>
                    <span class="brand-name">GESIT</span>
                    <span class="brand-sub">Inventaris sekolah</span>
                </span>
            </a>

            <nav class="side-nav" aria-label="Menu siswa">
                @php
                    $menu = [
                        ['siswa.beranda', 'Beranda', 'home'],
                        ['siswa.ajukan', 'Ajukan Pinjam', 'plus'],
                        ['siswa.riwayat', 'Riwayat', 'loan'],
                        ['siswa.panduan', 'Panduan', 'book'],
                    ];
                    // Pengajuan yang statusnya berubah sejak Riwayat terakhir dibuka
                    $penggunaNav = auth()->user();
                    $baruNav = \App\Models\Peminjaman::where('user_id', $penggunaNav->id)
                        ->where('status', '!=', 'menunggu')
                        ->when(
                            $penggunaNav->notif_dilihat_at,
                            fn ($q, $t) => $q->where('updated_at', '>', $t),
                            fn ($q) => $q->where('updated_at', '>=', now()->subDays(7))
                        )
                        ->count();
                @endphp
                @foreach ($menu as [$rute, $label, $ikon])
                    @php $aktif = request()->routeIs($rute); @endphp
                    <a href="{{ route($rute) }}" class="side-link {{ $aktif ? 'is-active' : '' }}" @if ($aktif) aria-current="page" @endif>
                        <svg class="ico" aria-hidden="true"><use href="#i-{{ $ikon }}"/></svg>{{ $label }}
                        @if ($rute === 'siswa.riwayat' && $baruNav > 0)
                            <span class="nav-badge" aria-label="{{ $baruNav }} pembaruan baru">{{ $baruNav }}</span>
                        @endif
                    </a>
                @endforeach

                {{-- Jam & tanggal --}}
                <div class="side-card">
                    <div class="sc-label"><svg class="ico ico-sm" aria-hidden="true"><use href="#i-clock"/></svg>Waktu sekarang</div>
                    <div class="clock" data-clock data-sec>{{ now()->format('H:i:s') }}</div>
                    <div class="sc-date" data-date="pendek">{{ now()->translatedFormat('l, j M') }}</div>
                </div>
            </nav>

            <div class="side-user">
                <a href="{{ route('profil') }}" class="who" title="Ubah foto profil" aria-label="Profil {{ auth()->user()->name }}, ubah foto">
                    <x-avatar :user="auth()->user()" />
                    <div class="min-w-0">
                        <div class="who-name">{{ auth()->user()->name }}</div>
                        <div class="who-role">{{ auth()->user()->kelas_jabatan ?: 'Siswa' }}</div>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm">
                        <svg class="ico ico-sm" aria-hidden="true"><use href="#i-logout"/></svg>Keluar
                    </button>
                </form>
            </div>
        </aside>

        <main id="konten" class="main" tabindex="-1">
            @yield('content')
            @include('partials.footer')
        </main>
    </div>

    @include('partials.toast')
    @include('partials.motion')
    @stack('scripts')
</body>
</html>