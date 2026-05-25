<select
  {{ $attributes->merge([
    'class' => 'w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 outline-none transition focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100'
  ]) }}>
  {{ $slot }}
</select>