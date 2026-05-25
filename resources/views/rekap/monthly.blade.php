@extends('layouts.app')

@section('title','Rekap Bulanan')
@section('mobile_title','Bulanan')

@section('content')

<x-ui.page-header
  title="Rekap Bulanan"
  subtitle="Periode {{ $start }} s/d {{ $end }} • {{ $daysInMonth }} hari • {{ $prayerCount }} sholat/hari"
  icon="bi-calendar3"
>
  <x-slot:actions>
    <x-ui.button :href="route('rekap.index')" variant="secondary">
      Harian
    </x-ui.button>

    <x-ui.button :href="route('rekap.monthly.export.excel', request()->query())" variant="secondary">
      Excel
    </x-ui.button>

    <x-ui.button :href="route('rekap.monthly.export.pdf', request()->query())" variant="secondary">
      PDF
    </x-ui.button>
  </x-slot:actions>
</x-ui.page-header>

<x-ui.card class="mb-6">
  <form method="GET">
    <div class="grid gap-4 lg:grid-cols-4">
      <div>
        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-slate-400">
          Bulan
        </label>
        <x-ui.input type="month" name="month" value="{{ $month }}" />
      </div>

      <div>
        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-slate-400">
          Kelas
        </label>
        <x-ui.select name="kelas">
          <option value="">Semua</option>
          @foreach($kelasList as $k)
            <option value="{{ $k }}" @selected($kelas === $k)>{{ $k }}</option>
          @endforeach
        </x-ui.select>
      </div>

      <div>
        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-slate-400">
          Kamar
        </label>
        <x-ui.select name="kamar">
          <option value="">Semua</option>
          @foreach($kamarList as $km)
            <option value="{{ $km }}" @selected($kamar === $km)>{{ $km }}</option>
          @endforeach
        </x-ui.select>
      </div>

      <div class="flex items-end">
        <x-ui.button type="submit" class="w-full justify-center">
          <i class="bi bi-search"></i>
          Filter
        </x-ui.button>
      </div>
    </div>
  </form>
</x-ui.card>

<div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-5">
  <x-ui.stat-card label="Total Santri" :value="$totalStudents" icon="bi-people" tone="blue" />
  <x-ui.stat-card label="Expected" :value="$globalExpected" icon="bi-calendar-check" tone="slate" />
  <x-ui.stat-card label="Total Scan" :value="$globalScan" icon="bi-qr-code-scan" tone="emerald" />
  <x-ui.stat-card label="Hadir" :value="$globalHadir" icon="bi-check-circle" tone="emerald" />
  <x-ui.stat-card label="Terlambat" :value="$globalTelat" icon="bi-clock" tone="amber" />
</div>

<x-ui.card padding="p-0">
  <div class="flex flex-col gap-2 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <div class="text-lg font-black text-slate-900">
        Daftar Santri
      </div>
      <div class="text-sm font-medium text-slate-500">
        Expected per santri: {{ $expectedPerStudent }}
      </div>
    </div>
  </div>

  <div class="w-full overflow-x-auto">
    <table class="w-full min-w-[860px]">
      <thead class="bg-slate-50 text-left text-xs font-black uppercase tracking-wide text-slate-400">
        <tr>
          <th class="px-6 py-4">Santri</th>
          <th class="px-6 py-4 text-center">Hadir</th>
          <th class="px-6 py-4 text-center">Telat</th>
          <th class="px-6 py-4 text-center">Scan</th>
          <th class="px-6 py-4 text-center">Belum</th>
          <th class="px-6 py-4 text-right">Persentase</th>
          <th class="px-6 py-4 text-right">Detail</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-slate-100">
        @forelse($rows as $row)
          @php
            $scan = (int) $row->total_scan;
            $belum = max(0, $expectedPerStudent - $scan);
            $pct = $expectedPerStudent > 0 ? round(($scan / $expectedPerStudent) * 100) : 0;
          @endphp

          <tr class="transition hover:bg-emerald-50/40">
            <td class="px-6 py-4">
              <div class="font-black text-slate-900">
                {{ $row->name }}
              </div>
              <div class="mt-1 text-sm font-semibold text-slate-500">
                {{ $row->nis }} • {{ $row->kelas ?? '-' }} • {{ $row->kamar ?? '-' }}
              </div>
            </td>

            <td class="px-6 py-4 text-center font-black text-emerald-600">
              {{ (int) $row->hadir }}
            </td>

            <td class="px-6 py-4 text-center font-black text-amber-600">
              {{ (int) $row->terlambat }}
            </td>

            <td class="px-6 py-4 text-center font-black text-slate-900">
              {{ $scan }}
            </td>

            <td class="px-6 py-4 text-center font-black text-red-600">
              {{ $belum }}
            </td>

            <td class="px-6 py-4 text-right">
              <div class="inline-flex items-center gap-3">
                <div class="h-2 w-28 overflow-hidden rounded-full bg-slate-100">
                  <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-lime-400"
                       style="width: {{ min(100, $pct) }}%"></div>
                </div>
                <span class="font-black text-slate-700">{{ $pct }}%</span>
              </div>
            </td>

            <td class="px-6 py-4 text-right">
              <x-ui.button
                :href="route('students.attendance.show', ['student' => $row->id, 'month' => $month])"
                variant="secondary">
                Detail
              </x-ui.button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="p-10">
              <x-ui.empty-state
                title="Tidak ada data"
                subtitle="Belum ada data rekap bulanan."
                icon="bi-calendar-x" />
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</x-ui.card>

@endsection