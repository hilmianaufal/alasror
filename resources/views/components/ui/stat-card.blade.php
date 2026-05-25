@props([
  'label',
  'value',
  'icon' => 'bi-graph-up',
  'tone' => 'emerald',
])

@php
  $tones = [
    'emerald' => 'from-emerald-500 to-lime-400 text-emerald-700 bg-emerald-50',
    'blue' => 'from-blue-500 to-cyan-400 text-blue-700 bg-blue-50',
    'amber' => 'from-amber-400 to-orange-400 text-amber-700 bg-amber-50',
    'red' => 'from-red-500 to-rose-400 text-red-700 bg-red-50',
    'slate' => 'from-slate-500 to-slate-400 text-slate-700 bg-slate-50',
  ];

  $toneClass = $tones[$tone] ?? $tones['emerald'];
@endphp

<div class="rounded-[1.6rem] border border-white/70 bg-white p-4 shadow-lg shadow-slate-200/70">
  <div class="flex items-start justify-between gap-3">
    <div>
      <div class="text-xs font-bold uppercase tracking-wide text-slate-400">
        {{ $label }}
      </div>
      <div class="mt-2 text-2xl font-black text-slate-900">
        {{ $value }}
      </div>
    </div>

    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br {{ explode(' ', $toneClass)[0] }} {{ explode(' ', $toneClass)[1] }} text-white shadow-lg">
      <i class="bi {{ $icon }}"></i>
    </div>
  </div>
</div>