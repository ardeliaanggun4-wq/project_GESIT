{{-- Letak file: resources/views/components/kat-ico.blade.php (FILE BARU) --}}
{{-- Pakai: <x-kat-ico nilai="elektronika" />  atau  <x-kat-ico nilai="elektronika" class="lg" />  (kotak ikon berwarna kategori) --}}
@props(['nilai'])
@php
    $ikon = ['komputer' => 'box', 'elektronika' => 'bulb', 'perkakas' => 'wrench', 'multimedia' => 'camera'][$nilai] ?? 'box';
@endphp
<span {{ $attributes->merge(['class' => 'kat-ico']) }} data-kat="{{ $nilai }}"><svg class="ico" aria-hidden="true"><use href="#i-{{ $ikon }}"/></svg></span>