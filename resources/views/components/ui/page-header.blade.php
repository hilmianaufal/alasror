@props([
  'title',
  'subtitle' => null,
  'icon' => 'bi-stars',
])

<div class="mb-6 flex flex-col gap-4 rounded-[2rem] bg-gradient-to-br from-emerald-600 via-emerald-500 to-lime-400 p-5 text-white shadow-2xl shadow-emerald-300/40 sm:flex-row sm:items-center sm:justify-between">
  <div class="flex items-center gap-4">
    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 text-2xl backdrop-blur">
      <i class="bi {{ $icon }}"></i>
    </div>

    <div>
      <h1 class="text-xl font-black tracking-tight sm:text-2xl">
        {{ $title }}
      </h1>

      @if($subtitle)
        <p class="mt-1 text-sm font-medium text-white/80">
          {{ $subtitle }}
        </p>
      @endif
    </div>
  </div>

  @if(isset($actions))
    <div class="flex flex-wrap gap-2">
      {{ $actions }}
    </div>
  @endif
</div>