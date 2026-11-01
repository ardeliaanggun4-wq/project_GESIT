{{-- Letak file: resources/views/components/kategori.blade.php --}}
{{-- Pakai: <x-kategori nilai="praktik_lab" />  (titik warna + nama kategori) --}}
@props(['nilai', 'label' => null])
@php
    $peta = [
        'praktik_lab' => 'Alat Praktik Lab',
        'olahraga'    => 'Alat Olahraga',
        'kesenian'    => 'Alat Kesenian',
        'multimedia'  => 'Multimedia',
    ];
@endphp
<span {{ $attributes->merge(['class' => 'chip-kat']) }} data-kat="{{ $nilai }}"><i class="dot" aria-hidden="true"></i>{{ $label ?? ($peta[$nilai] ?? ucfirst(str_replace('_', ' ', $nilai))) }}</span>