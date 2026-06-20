@extends('layouts.app')

@section('title','Rekap Kegiatan Umum')
@section('mobile_title','Kegiatan')

@section('content')

<x-ui.page-header
  title="Rekap Kegiatan Umum"
  subtitle="Rekap seluruh kegiatan umum harian"
  icon="bi-journal-check"
/>
<x-slot:actions>
  <x-ui.button :href="route('rekap-kegiatan.export.excel', ['period' => 'daily'] + request()->query())" variant="secondary">
    <i class="bi bi-file-earmark-excel"></i>
    Excel
  </x-ui.button>
</x-slot:actions>

<x-ui.card class="mb-6">
  <form method="GET">
    <div class="grid gap-4 lg:grid-cols-5">

      <x-ui.form-group label="Tanggal">
        <x-ui.input type="date" name="date" value="{{ $date }}" />
      </x-ui.form-group>

      <x-ui.form-group label="Jenis Santri">
        <x-ui.select name="gender">
          <option value="">Semua</option>
          <option value="putra" @selected($gender === 'putra')>Putra</option>
          <option value="putri" @selected($gender === 'putri')>Putri</option>
        </x-ui.select>
      </x-ui.form-group>

      <x-ui.form-group label="Jenjang">
        <x-ui.select name="kelas">
          <option value="">Semua</option>
          @foreach($kelasList as $item)
            <option value="{{ $item }}" @selected($kelas === $item)>{{ $item }}</option>
          @endforeach
        </x-ui.select>
      </x-ui.form-group>

      <x-ui.form-group label="Kamar">
        <x-ui.select name="kamar">
          <option value="">Semua</option>
          @foreach($kamarList as $item)
            <option value="{{ $item }}" @selected($kamar === $item)>{{ $item }}</option>
          @endforeach
        </x-ui.select>
      </x-ui.form-group>

      <div class="flex items-end">
        <x-ui.button type="submit" class="w-full justify-center">
          <i class="bi bi-search"></i>
          Filter
        </x-ui.button>
      </div>

    </div>
  </form>
</x-ui.card>

<div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-7">
  <x-ui.stat-card label="Santri" :value="$summary['total_santri']" icon="bi-people" tone="blue" />
  <x-ui.stat-card label="Target" :value="$summary['total_target']" icon="bi-calendar-check" tone="slate" />
  <x-ui.stat-card label="Hadir" :value="$summary['hadir']" icon="bi-check-circle" tone="emerald" />
  <x-ui.stat-card label="Telat" :value="$summary['telat']" icon="bi-clock" tone="amber" />
  <x-ui.stat-card label="Izin" :value="$summary['izin']" icon="bi-envelope-check" tone="blue" />
  <x-ui.stat-card label="Sakit" :value="$summary['sakit']" icon="bi-heart-pulse" tone="red" />
  <x-ui.stat-card label="Alpa" :value="$summary['alpa']" icon="bi-x-circle" tone="red" />
</div>

<x-ui.card padding="p-0">
  <div class="border-b border-slate-100 p-5">
    <div class="text-lg font-black text-slate-900">
      Detail Rekap Kegiatan Umum
    </div>
    <div class="text-sm font-medium text-slate-500">
      {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
      • {{ $activities->pluck('name')->join(', ') ?: 'Belum ada kegiatan umum' }}
    </div>
  </div>

  <div class="w-full overflow-x-auto">
    <table class="w-full min-w-[920px]">
      <thead class="bg-slate-50 text-left text-xs font-black uppercase tracking-wide text-slate-400">
        <tr>
          <th class="px-6 py-4">Santri</th>
          <th class="px-6 py-4 text-center">Hadir</th>
          <th class="px-6 py-4 text-center">Telat</th>
          <th class="px-6 py-4 text-center">Izin</th>
          <th class="px-6 py-4 text-center">Sakit</th>
          <th class="px-6 py-4 text-center">Pulang</th>
          <th class="px-6 py-4 text-center">Alpa</th>
          <th class="px-6 py-4 text-right">Total</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-slate-100">
        @forelse($rows as $row)
          @php $student = $row['student']; @endphp

          <tr class="transition hover:bg-emerald-50/40">
            <td class="px-6 py-4">
              <div class="flex items-center gap-4">
                <img src="{{ $student->photoUrl() }}"
                     class="h-14 w-14 rounded-2xl object-cover ring-2 ring-emerald-100"
                     alt="{{ $student->name }}">

                <div>
                  <div class="font-black text-slate-900">{{ $student->name }}</div>
                  <div class="text-sm font-semibold text-slate-500">
                    {{ $student->nis }} • {{ $student->kelas ?? '-' }} • {{ $student->kamar ?? '-' }}
                  </div>

                  @if($student->gender)
                    <div class="mt-1">
                      <x-ui.badge tone="{{ $student->gender === 'putra' ? 'blue' : 'red' }}">
                        {{ $student->gender === 'putra' ? 'Putra' : 'Putri' }}
                      </x-ui.badge>
                    </div>
                  @endif
                </div>
              </div>
            </td>

            <td class="px-6 py-4 text-center font-black text-emerald-600">{{ $row['hadir'] }}</td>
            <td class="px-6 py-4 text-center font-black text-amber-600">{{ $row['telat'] }}</td>
            <td class="px-6 py-4 text-center font-black text-blue-600">{{ $row['izin'] }}</td>
            <td class="px-6 py-4 text-center font-black text-red-600">{{ $row['sakit'] }}</td>
            <td class="px-6 py-4 text-center font-black text-slate-600">{{ $row['pulang'] }}</td>
            <td class="px-6 py-4 text-center font-black text-red-600">{{ $row['alpa'] }}</td>

            <td class="px-6 py-4 text-right font-black text-slate-900">
              {{ $row['total'] }}/{{ $row['target'] }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="p-10">
              <x-ui.empty-state
                title="Tidak ada data"
                subtitle="Belum ada data santri untuk filter ini."
                icon="bi-people" />
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</x-ui.card>

@endsection
