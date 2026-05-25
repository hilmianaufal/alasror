@props([
  'padding' => 'p-4',
])

<div {{ $attributes->merge([
  'class' => "rounded-[1.75rem] border border-white/70 bg-white {$padding} shadow-xl shadow-slate-200/70"
]) }}>
  {{ $slot }}
</div>