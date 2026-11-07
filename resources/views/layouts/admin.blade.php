<!doctype html>
{{-- Letak file: resources/views/layouts/admin.blade.php (timpa file lama) --}}
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Admin') · GESIT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head')
</head>
<body>
    @include('partials.icons')
    @include('partials.dekor')
    <a href="#konten" class="skip-link">Lewati ke konten utama</a>

    @include('partials.sidebar-toggle')

    <div class="shell">
        <aside class="sidebar">
            <a href="{{ route('admin.ringkasan') }}" class="brand side-brand" aria-label="GESIT, ke halaman Ringkasan">
                <span class="brand-mark"><svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg></span>
                <span>
                    <span class="brand-name">GESIT</span>
                    <span class="brand-sub">Inventaris sekolah</span>
                </span>
            </a>

            <nav class="side-nav" aria-label="Menu admin">
                @php
                    $menu = [
                        ['admin.ringkasan', 'Ringkasan', 'chart'],
                        ['admin.verifikasi', 'Verifikasi', 'verif'],
                        ['admin.data-alat', 'Data Alat', 'grid'],
                        ['admin.laporan', 'Laporan', 'book'],
                    ];
                @endphp
                @php $antrean = \App\Models\Peminjaman::where('status', 'menunggu')->count(); @endphp
                @foreach ($menu as [$rute, $label, $ikon])
                    @php $aktif = request()->routeIs($rute); @endphp
                    <a href="{{ route($rute) }}" class="side-link {{ $aktif ? 'is-active' : '' }}" @if ($aktif) aria-current="page" @endif>
                        <svg class="ico" aria-hidden="true"><use href="#i-{{ $ikon }}"/></svg>{{ $label }}
                        @if ($rute === 'admin.verifikasi' && $antrean > 0)
                            <span class="nav-badge" aria-label="{{ $antrean }} menunggu">{{ $antrean }}</span>
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
                        <div class="who-role">Petugas GESIT</div>
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