@props([
  'title' => 'Belum ada data',
  'subtitle' => 'Data akan muncul di sini.',
  'icon' => 'bi-inbox',
])

<div class="rounded-[1.75rem] border border-dashed border-emerald-200 bg-emerald-50/60 p-8 text-center">
  <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-2xl text-emerald-600 shadow">
    <i class="bi {{ $icon }}"></i>
  </div>

  <div class="mt-4 text-sm font-black text-emerald-950">
    {{ $title }}
  </div>

  <div class="mt-1 text-sm text-slate-500">
    {{ $subtitle }}
  </div>
</div>