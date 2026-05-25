@props([
  'href' => null,
  'variant' => 'primary',
  'type' => 'button',
])

@php
  $classes = [
    'primary' => 'bg-gradient-to-r from-emerald-600 to-lime-500 text-white shadow-lg shadow-emerald-300/40 hover:shadow-emerald-400/50',
    'secondary' => 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50',
    'danger' => 'bg-red-600 text-white shadow-lg shadow-red-200 hover:bg-red-700',
    'ghost' => 'bg-transparent text-slate-600 hover:bg-slate-100',
  ];

  $class = $classes[$variant] ?? $classes['primary'];
@endphp

@if($href)
  <a href="{{ $href }}"
     {{ $attributes->merge([
       'class' => "inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2 text-sm font-black transition active:scale-95 {$class}"
     ]) }}>
    {{ $slot }}
  </a>
@else
  <button type="{{ $type }}"
          {{ $attributes->merge([
            'class' => "inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2 text-sm font-black transition active:scale-95 {$class}"
          ]) }}>
    {{ $slot }}
  </button>
@endif