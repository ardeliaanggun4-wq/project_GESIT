{{-- Letak file: resources/views/components/kat-ico.blade.php (FILE BARU) --}}
{{-- Pakai: <x-kat-ico nilai="olahraga" />  atau  <x-kat-ico nilai="olahraga" class="lg" />  (kotak ikon berwarna kategori) --}}
@props(['nilai'])
@php
    $ikon = ['praktik_lab' => 'flask', 'olahraga' => 'ball', 'kesenian' => 'palette', 'multimedia' => 'camera'][$nilai] ?? 'box';
@endphp
<span {{ $attributes->merge(['class' => 'kat-ico']) }} data-kat="{{ $nilai }}"><svg class="ico" aria-hidden="true"><use href="#i-{{ $ikon }}"/></svg></span>