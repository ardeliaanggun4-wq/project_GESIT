{{-- Letak file: resources/views/components/avatar.blade.php --}}
{{-- Pakai: <x-avatar :user="auth()->user()" />
     Ukuran besar: <x-avatar :user="$user" style="width:6rem;height:6rem;font-size:1.75rem;overflow:hidden" />
     Menampilkan foto bila ada, kalau tidak, inisial nama. --}}
@props(['user'])
<span {{ $attributes->merge(['class' => 'avatar', 'style' => 'overflow:hidden']) }} aria-hidden="true">
    @if ($user->foto)
        <img src="{{ asset('storage/' . $user->foto) }}" alt="" style="width:100%;height:100%;object-fit:cover">
    @else
        {{ $user->initials() }}
    @endif
</span>