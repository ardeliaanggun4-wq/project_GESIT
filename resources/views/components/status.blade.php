{{-- Letak file: resources/views/components/status.blade.php --}}
{{-- Pakai: <x-status :nilai="$p->status" />  atau  <x-status :nilai="$a->kondisi" />
     Ikon + teks (tidak bergantung pada warna saja). --}}
@props(['nilai', 'label' => null])
@php
    $peta = [
        // status peminjaman
        'menunggu'   => ['Menunggu', 'clock'],
        'disetujui'  => ['Disetujui', 'check'],
        'dipinjam'   => ['Dipinjam', 'box'],
        'ditolak'    => ['Ditolak', 'x'],
        'selesai'    => ['Selesai', 'check'],
        // kondisi alat
        'baik'       => ['Baik', 'check'],
        'perlu_cek'  => ['Perlu Cek', 'alert'],
        'rusak'      => ['Rusak', 'x'],
        'stok_habis' => ['Stok Habis', 'minus'],
    ];
    [$teks, $ikon] = $peta[$nilai] ?? [ucfirst(str_replace('_', ' ', $nilai)), 'info'];
@endphp
<span {{ $attributes->merge(['class' => 'status']) }} data-status="{{ $nilai }}">
    <svg class="ico" aria-hidden="true"><use href="#i-{{ $ikon }}"/></svg>{{ $label ?? $teks }}
</span>