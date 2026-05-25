@extends('layouts.app')

@section('title','Rekap Harian')
@section('mobile_title','Rekap')

@section('content')

<x-ui.page-header
  title="Rekap Harian"
  subtitle="Monitoring absensi sholat santri"
  icon="bi-clipboard-data"
>
  <x-slot:actions>

    <x-ui.button
      :href="route('rekap.export.excel', request()->query())"
      variant="secondary">
      <i class="bi bi-file-earmark-excel"></i>
      Excel
    </x-ui.button>

    <x-ui.button
      :href="route('rekap.export.pdf', request()->query())"
      variant="secondary">
      <i class="bi bi-file-earmark-pdf"></i>
      PDF
    </x-ui.button>

  </x-slot:actions>
</x-ui.page-header>

{{-- FILTER --}}
<x-ui.card class="mb-6">
  <form method="GET">

    <div class="grid gap-4 lg:grid-cols-5">

      <div>
        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-slate-400">
          Tanggal
        </label>

        <x-ui.input
          type="date"
          name="date"
          value="{{ $date }}" />
      </div>

      <div>
        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-slate-400">
          Sholat
        </label>

        <x-ui.select name="prayer_id">
          @foreach($prayers as $prayer)
            <option
              value="{{ $prayer->id }}"
              @selected($selectedPrayer?->id == $prayer->id)>
              {{ $prayer->name }}
            </option>
          @endforeach
        </x-ui.select>
      </div>

      <div>
        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-slate-400">
          Kelas
        </label>

        <x-ui.select name="kelas">
          <option value="">Semua</option>

          @foreach($kelasList as $kelas)
            <option
              value="{{ $kelas }}"
              @selected($groupKelas == $kelas)>
              {{ $kelas }}
            </option>
          @endforeach
        </x-ui.select>
      </div>

      <div>
        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-slate-400">
          Kamar
        </label>

        <x-ui.select name="kamar">
          <option value="">Semua</option>

          @foreach($kamarList as $kamar)
            <option
              value="{{ $kamar }}"
              @selected($groupKamar == $kamar)>
              {{ $kamar }}
            </option>
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

{{-- STATS --}}
<div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">

  <x-ui.stat-card
    label="Total Santri"
    :value="$totalStudents"
    icon="bi-people"
    tone="blue" />

  <x-ui.stat-card
    label="Hadir"
    :value="$hadirCount"
    icon="bi-check-circle"
    tone="emerald" />

  <x-ui.stat-card
    label="Terlambat"
    :value="$terlambatCount"
    icon="bi-clock"
    tone="amber" />

  <x-ui.stat-card
    label="Belum Absen"
    :value="$belumCount"
    icon="bi-x-circle"
    tone="red" />

</div>

<div class="grid gap-6 lg:grid-cols-12">

  {{-- SUDAH ABSEN --}}
  <div class="lg:col-span-8">

    <x-ui.card padding="p-0">

      <div class="flex items-center justify-between border-b border-slate-100 p-5">
        <div>
          <div class="text-lg font-black text-slate-900">
            Sudah Absen
          </div>

          <div class="text-sm text-slate-500">
            {{ $attendances->total() }} data
          </div>
        </div>
      </div>

      <div class="overflow-x-auto">

        <table class="w-full min-w-[720px]">

          <thead class="bg-slate-50 text-left text-xs font-black uppercase tracking-wide text-slate-400">
            <tr>
              <th class="px-6 py-4">Santri</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4">Jam Scan</th>
              <th class="px-6 py-4">Sholat</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-100">

            @forelse($attendances as $attendance)

              <tr class="transition hover:bg-emerald-50/40">

                <td class="px-6 py-4">

                  <div class="flex items-center gap-4">

                    <img
                      src="{{ $attendance->student->photoUrl() }}"
                      class="h-14 w-14 rounded-2xl object-cover ring-2 ring-emerald-100">

                    <div>
                      <div class="font-black text-slate-900">
                        {{ $attendance->student->name }}
                      </div>

                      <div class="text-sm font-semibold text-slate-500">
                        {{ $attendance->student->nis }}
                      </div>

                      <div class="mt-1 flex gap-2">

                        @if($attendance->student->kelas)
                          <x-ui.badge tone="blue">
                            {{ $attendance->student->kelas }}
                          </x-ui.badge>
                        @endif

                        @if($attendance->student->kamar)
                          <x-ui.badge tone="emerald">
                            {{ $attendance->student->kamar }}
                          </x-ui.badge>
                        @endif

                      </div>
                    </div>

                  </div>

                </td>

                <td class="px-6 py-4">

                  @if($attendance->status === 'hadir')
                    <x-ui.badge tone="emerald">
                      Hadir
                    </x-ui.badge>
                  @elseif($attendance->status === 'terlambat')
                    <x-ui.badge tone="amber">
                      Terlambat
                    </x-ui.badge>
                  @else
                    <x-ui.badge tone="slate">
                      {{ ucfirst($attendance->status) }}
                    </x-ui.badge>
                  @endif

                </td>

                <td class="px-6 py-4 font-bold text-slate-600">
                  {{ \Carbon\Carbon::parse($attendance->scanned_at)->format('H:i:s') }}
                </td>

                <td class="px-6 py-4">
                  <x-ui.badge tone="slate">
                    {{ $selectedPrayer?->name }}
                  </x-ui.badge>
                </td>

              </tr>

            @empty

              <tr>
                <td colspan="4" class="p-10">

                  <x-ui.empty-state
                    title="Belum ada absensi"
                    subtitle="Data scan belum tersedia."
                    icon="bi-clipboard-x" />

                </td>
              </tr>

            @endforelse

          </tbody>

        </table>

      </div>

      @if($attendances->hasPages())
        <div class="border-t border-slate-100 p-5">
          {{ $attendances->links() }}
        </div>
      @endif

    </x-ui.card>

  </div>

  {{-- BELUM ABSEN --}}
  <div class="lg:col-span-4">

    <x-ui.card padding="p-0">

      <div class="border-b border-slate-100 p-5">

        <div class="text-lg font-black text-slate-900">
          Belum Absen
        </div>

        <div class="text-sm text-slate-500">
          {{ $absentStudents->count() }} santri
        </div>

      </div>

      <div class="max-h-[720px] overflow-y-auto">

        @forelse($absentStudents as $student)

          <div class="flex items-center gap-4 border-b border-slate-100 p-4">

            <img
              src="{{ $student->photoUrl() }}"
              class="h-14 w-14 rounded-2xl object-cover ring-2 ring-red-100">

            <div class="min-w-0 flex-1">

              <div class="truncate font-black text-slate-900">
                {{ $student->name }}
              </div>

              <div class="text-sm font-semibold text-slate-500">
                {{ $student->nis }}
              </div>

              <div class="mt-2 flex flex-wrap gap-2">

                @if($student->kelas)
                  <x-ui.badge tone="blue">
                    {{ $student->kelas }}
                  </x-ui.badge>
                @endif

                @if($student->kamar)
                  <x-ui.badge tone="emerald">
                    {{ $student->kamar }}
                  </x-ui.badge>
                @endif

              </div>

            </div>

            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-red-50 text-red-500">
              <i class="bi bi-x-lg"></i>
            </div>

          </div>

        @empty

          <div class="p-8">

            <x-ui.empty-state
              title="Semua hadir"
              subtitle="Tidak ada santri yang belum absen."
              icon="bi-check-circle" />

          </div>

        @endforelse

      </div>

    </x-ui.card>

  </div>

</div>

@endsection