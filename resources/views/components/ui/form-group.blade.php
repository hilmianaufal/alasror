@props([
  'label',
  'required' => false,
])

<div>
  <label class="mb-2 block text-xs font-black uppercase tracking-wide text-slate-400">
    {{ $label }}

    @if($required)
      <span class="text-red-500">*</span>
    @endif
  </label>

  {{ $slot }}

  @error($attributes->get('name'))
    <div class="mt-2 text-sm font-bold text-red-500">
      {{ $message }}
    </div>
  @enderror
</div>