@extends('layouts.app')

@section('title','Jadwal Sholat')
@section('mobile_title','Jadwal Sholat')

@section('content')

@php
  $totalAktif = collect($prayers)->where('is_active', true)->count();
  $totalOff = collect($prayers)->where('is_active', false)->count();
@endphp

<x-ui.page-header
  title="Jadwal Sholat"
  subtitle="Atur jam buka/tutup scan dan toleransi keterlambatan"
  icon="bi-clock-history"
>
  <x-slot:actions>
    <x-ui.button :href="route('dashboard')" variant="secondary">
      Dashboard
    </x-ui.button>
  </x-slot:actions>
</x-ui.page-header>

<div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-3">
  <x-ui.stat-card label="Total Sholat" :value="$prayers->count()" icon="bi-calendar2-week" tone="emerald" />
  <x-ui.stat-card label="Aktif" :value="$totalAktif" icon="bi-check-circle" tone="blue" />
  <x-ui.stat-card label="Nonaktif" :value="$totalOff" icon="bi-x-circle" tone="red" />
</div>

{{-- Desktop --}}
<div class="hidden lg:block">
  <x-ui.card padding="p-0">
    <div class="flex items-center justify-between border-b border-slate-100 p-5">
      <div>
        <div class="text-lg font-black text-slate-900">
          Daftar Jadwal Sholat
        </div>
        <div class="text-sm text-slate-500">
          Kelola jadwal scan absensi sholat
        </div>
      </div>
    </div>

    <div class="w-full overflow-x-auto">
      <table class="w-full min-w-[720px]">
        <thead class="bg-slate-50 text-left text-xs font-black uppercase tracking-wide text-slate-400">
          <tr>
            <th class="px-6 py-4">Sholat</th>
            <th class="px-6 py-4">Jam Scan</th>
            <th class="px-6 py-4">Toleransi</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4 text-right">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-100">
          @forelse($prayers as $prayer)
            <tr class="transition hover:bg-emerald-50/40">
              <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                  <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-xl text-emerald-600">
                    <i class="bi bi-moon-stars"></i>
                  </div>

                  <div>
                    <div class="font-black text-slate-900">
                      {{ $prayer->name }}
                    </div>
                    <div class="text-sm font-semibold text-slate-500">
                      Urutan {{ $prayer->order }}
                    </div>
                  </div>
                </div>
              </td>

              <td class="px-6 py-4">
                <div class="font-black text-slate-900">
                  {{ substr($prayer->start_time, 0, 5) }}
                  -
                  {{ substr($prayer->end_time, 0, 5) }}
                </div>
                <div class="text-sm font-semibold text-slate-400">
                  Buka - tutup scan
                </div>
              </td>

              <td class="px-6 py-4">
                <x-ui.badge tone="amber">
                  {{ $prayer->late_minutes }} menit
                </x-ui.badge>
              </td>

              <td class="px-6 py-4">
                @if($prayer->is_active)
                  <x-ui.badge tone="emerald">Aktif</x-ui.badge>
                @else
                  <x-ui.badge tone="slate">Off</x-ui.badge>
                @endif
              </td>

              <td class="px-6 py-4">
                <div class="flex justify-end">
                  <x-ui.button
                    :href="route('prayers.edit', $prayer)"
                    variant="secondary">
                    <i class="bi bi-pencil"></i>
                    Edit
                  </x-ui.button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="p-8">
                <x-ui.empty-state
                  title="Belum ada jadwal"
                  subtitle="Data sholat belum tersedia."
                  icon="bi-clock-history" />
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </x-ui.card>
</div>

{{-- Mobile --}}
<div class="space-y-4 lg:hidden">
  @forelse($prayers as $prayer)
    <x-ui.card>
      <div class="flex items-start gap-4">
        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-2xl text-emerald-600">
          <i class="bi bi-moon-stars"></i>
        </div>

        <div class="min-w-0 flex-1">
          <div class="flex items-start justify-between gap-3">
            <div>
              <div class="text-base font-black text-slate-900">
                {{ $prayer->name }}
              </div>

              <div class="mt-1 text-sm font-bold text-slate-500">
                {{ substr($prayer->start_time, 0, 5) }}
                -
                {{ substr($prayer->end_time, 0, 5) }}
              </div>
            </div>

            @if($prayer->is_active)
              <x-ui.badge tone="emerald">Aktif</x-ui.badge>
            @else
              <x-ui.badge tone="slate">Off</x-ui.badge>
            @endif
          </div>

          <div class="mt-3 flex flex-wrap gap-2">
            <x-ui.badge tone="amber">
              Telat {{ $prayer->late_minutes }} menit
            </x-ui.badge>

            <x-ui.badge tone="slate">
              Urutan {{ $prayer->order }}
            </x-ui.badge>
          </div>

          <div class="mt-4">
            <x-ui.button
              :href="route('prayers.edit', $prayer)"
              variant="secondary"
              class="w-full justify-center">
              <i class="bi bi-pencil"></i>
              Edit Jadwal
            </x-ui.button>
          </div>
        </div>
      </div>
    </x-ui.card>
  @empty
    <x-ui.empty-state
      title="Belum ada jadwal"
      subtitle="Data sholat belum tersedia."
      icon="bi-clock-history" />
  @endforelse
</div>

@endsection