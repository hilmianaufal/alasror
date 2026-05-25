@extends('layouts.app')

@section('title','Riwayat Absensi Santri')
@section('mobile_title','Riwayat Absensi')

@section('content')

<x-ui.page-header
  title="Riwayat Absensi"
  subtitle="{{ $student->name }} • {{ $student->nis }} • {{ $month }}"
  icon="bi-calendar-check"
>
  <x-slot:actions>
    <x-ui.button :href="route('students.show', $student)" variant="secondary">
      Profil
    </x-ui.button>

    <x-ui.button :href="route('rekap.monthly', ['month' => $month])" variant="secondary">
      Rekap Bulanan
    </x-ui.button>
  </x-slot:actions>
</x-ui.page-header>

<x-ui.card class="mb-6">
  <form method="GET">
    <div class="grid gap-4 md:grid-cols-4">
      <div>
        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-slate-400">
          Bulan
        </label>

        <x-ui.input
          type="month"
          name="month"
          value="{{ $month }}" />
      </div>

      <div class="flex items-end">
        <x-ui.button type="submit" class="w-full">
          <i class="bi bi-filter"></i>
          Terapkan
        </x-ui.button>
      </div>
    </div>
  </form>
</x-ui.card>

<div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-5">
  <x-ui.stat-card label="Expected" :value="$expected" icon="bi-calendar2-check" tone="emerald" />
  <x-ui.stat-card label="Total Scan" :value="$scanTotal" icon="bi-qr-code-scan" tone="blue" />
  <x-ui.stat-card label="Hadir" :value="$hadir" icon="bi-check-circle" tone="emerald" />
  <x-ui.stat-card label="Terlambat" :value="$telat" icon="bi-clock" tone="amber" />
  <x-ui.stat-card label="Belum" :value="$belum" icon="bi-x-circle" tone="red" />
</div>

<x-ui.card padding="p-0">
  <div class="flex flex-col gap-2 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="text-lg font-black text-slate-900">
        Kalender Absensi
      </div>
      <div class="text-sm font-medium text-slate-500">
        H = Hadir • T = Telat • - = Belum
      </div>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full min-w-[720px] text-left text-sm">
      <thead class="bg-slate-50 text-xs font-black uppercase tracking-wide text-slate-400">
        <tr>
          <th class="px-5 py-4">Tanggal</th>

          @foreach($prayers as $p)
            <th class="px-5 py-4 text-center">
              {{ $p->name }}
            </th>
          @endforeach
        </tr>
      </thead>

      <tbody class="divide-y divide-slate-100">
        @foreach($dates as $d)
          @php
            $carbon = \Illuminate\Support\Carbon::parse($d);
            $isFriday = $carbon->dayOfWeek === 5;
            $isSunday = $carbon->dayOfWeek === 0;
          @endphp

          <tr class="
            transition
            {{ $isFriday ? 'bg-amber-50/60' : '' }}
            {{ $isSunday ? 'bg-slate-50/80' : '' }}
            hover:bg-emerald-50/50
          ">
            <td class="px-5 py-4">
              <div class="font-black text-slate-900">
                {{ $carbon->format('d M') }}
              </div>
              <div class="text-xs font-semibold text-slate-400">
                {{ $carbon->translatedFormat('l') }}
              </div>
            </td>

            @foreach($prayers as $p)
              @php
                $cell = $map[$d][$p->id] ?? null;
              @endphp

              <td class="px-5 py-4 text-center">
                @if(!$cell)
                  <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-slate-100 text-sm font-black text-slate-400">
                    -
                  </span>
                @elseif($cell['status'] === 'hadir')
                  <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-emerald-100 text-sm font-black text-emerald-700">
                    H
                  </span>
                  <div class="mt-1 text-[11px] font-semibold text-slate-400">
                    {{ $cell['time'] }}
                  </div>
                @elseif($cell['status'] === 'terlambat')
                  <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-amber-100 text-sm font-black text-amber-700">
                    T
                  </span>
                  <div class="mt-1 text-[11px] font-semibold text-slate-400">
                    {{ $cell['time'] }}
                  </div>
                @else
                  <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-blue-100 text-sm font-black text-blue-700">
                    {{ strtoupper(substr($cell['status'], 0, 1)) }}
                  </span>
                  <div class="mt-1 text-[11px] font-semibold text-slate-400">
                    {{ $cell['time'] ?? '-' }}
                  </div>
                @endif
              </td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</x-ui.card>

@endsection