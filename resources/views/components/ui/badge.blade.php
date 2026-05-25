@props([
  'tone' => 'emerald',
])

@php
  $classes = [
    'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
    'blue' => 'bg-blue-50 text-blue-700 ring-blue-100',
    'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
    'red' => 'bg-red-50 text-red-700 ring-red-100',
    'slate' => 'bg-slate-100 text-slate-600 ring-slate-200',
    'dark' => 'bg-slate-900 text-white ring-slate-800',
  ];

  $class = $classes[$tone] ?? $classes['emerald'];
@endphp

<span {{ $attributes->merge([
  'class' => "inline-flex items-center rounded-full px-3 py-1 text-xs font-black ring-1 {$class}"
]) }}>
  {{ $slot }}
</span>