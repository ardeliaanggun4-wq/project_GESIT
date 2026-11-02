{{-- Letak file: resources/views/components/banner-art.blade.php (FILE BARU) --}}
{{-- Pakai di dalam <section class="banner tone-...">: <x-banner-art ikon="send" :mini="['calendar','box','check']" /> --}}
@props(['ikon', 'mini' => []])
<div class="banner-art" aria-hidden="true">
    @foreach ($mini as $m)
        <span class="mini mini-{{ $loop->iteration }}"><svg class="ico"><use href="#i-{{ $m }}"/></svg></span>
    @endforeach
    <span class="big"><svg class="ico"><use href="#i-{{ $ikon }}"/></svg></span>
</div>