<!doctype html>
{{-- Letak file: resources/views/layouts/siswa.blade.php (timpa file lama) --}}
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Beranda') · GESIT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.head')
</head>
<body class="has-bottomnav">
    @include('partials.icons')
    @include('partials.dekor')
    <a href="#konten" class="skip-link">Lewati ke konten utama</a>

    @php
        $menu = [
            ['siswa.beranda', 'Beranda', 'home'],
            ['siswa.ajukan', 'Ajukan pinjam', 'plus'],
        ];
    @endphp

    <header class="topbar">
        <div class="topbar-in">
            <a href="{{ route('siswa.beranda') }}" class="brand" aria-label="GESIT, ke Beranda">
                <span class="brand-mark"><svg class="ico" aria-hidden="true"><use href="#i-grid"/></svg></span>
                <span class="brand-name">GESIT</span>
            </a>

            {{-- Menu atas: hanya tampil di layar lebar. Di HP memakai navigasi bawah. --}}
            <nav class="top-nav" aria-label="Menu utama">
                @foreach ($menu as [$rute, $label, $ikon])
                    @php $aktif = request()->routeIs($rute); @endphp
                    <a href="{{ route($rute) }}" class="top-link {{ $aktif ? 'is-active' : '' }}" @if ($aktif) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
                <span class="top-link is-soon" aria-disabled="true">Panduan <span class="soon-tag">Segera hadir</span></span>
            </nav>

            <div class="top-user">
                <span class="clock-chip"><svg class="ico ico-sm" aria-hidden="true"><use href="#i-clock"/></svg><span data-clock>{{ now()->format('H:i') }}</span></span>
                <div class="who">
                    <b>{{ auth()->user()->name }}</b>
                    <span>{{ auth()->user()->kelas_jabatan }}</span>
                </div>
                <span class="avatar" aria-hidden="true">{{ auth()->user()->initials() }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm" aria-label="Keluar dari akun">
                        <svg class="ico ico-sm" aria-hidden="true"><use href="#i-logout"/></svg>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main id="konten" class="main-siswa" tabindex="-1">
        @yield('content')
        @include('partials.footer')
    </main>

    {{-- Navigasi bawah untuk HP --}}
    <nav class="bottomnav" aria-label="Menu utama">
        @foreach ($menu as [$rute, $label, $ikon])
            @php $aktif = request()->routeIs($rute); @endphp
            <a href="{{ route($rute) }}" class="bn-item {{ $aktif ? 'is-active' : '' }}" @if ($aktif) aria-current="page" @endif>
                <svg class="ico" aria-hidden="true"><use href="#i-{{ $ikon }}"/></svg>{{ $label }}
            </a>
        @endforeach
        <span class="bn-item is-soon" aria-disabled="true">
            <svg class="ico" aria-hidden="true"><use href="#i-book"/></svg>Panduan
            <span class="soon-tag" style="font-size:.6875rem;line-height:1.3">Segera</span>
        </span>
    </nav>

    @include('partials.toast')
    @include('partials.motion')
    @stack('scripts')
</body>
</html>