@extends('layouts.app')

@section('title','Rekap Kegiatan')
@section('mobile_title','Rekap Kegiatan')

@section('content')

<x-ui.page-header
  title="Rekap Kegiatan"
  subtitle="Monitoring absensi kegiatan santri"
  icon="bi-clipboard-check"
>
  <x-slot:actions>
    <x-ui.button :href="route('activities.scan')" variant="secondary">
      <i class="bi bi-qr-code"></i>
      Scan
    </x-ui.button>

    <x-ui.button :href="route('activities.recap.export.excel', request()->query())" variant="secondary">
      <i class="bi bi-file-earmark-excel"></i>
      Excel
    </x-ui.button>
  </x-slot:actions>
</x-ui.page-header>

<x-ui.card class="mb-6">
  <form method="GET">
    <div class="grid gap-4 lg:grid-cols-5">

      <div>
        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-slate-400">
          Tanggal
        </label>
        <x-ui.input type="date" name="date" value="{{ $date }}" />
      </div>

      <div>
        <label class="mb-2 block text-xs font-black uppercase tracking-wide text-slate-400">
          Kegiatan
        </label>
        <x-ui.select name="activity_id">
          @foreach($activities as $activity)
            <option value="{{ $activity->id }}" @selected($selectedActivity && $selectedActivity->id === $activity->id)>
              {{ $activity->name }}
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
          @foreach($kelasList as $k)
            <option value="{{ $k }}" @selected($kelas === $k)>
              {{ $k }}
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
          @foreach($kamarList as $km)
            <option value="{{ $km }}" @selected($kamar === $km)>
              {{ $km }}
            </option>
          @endforeach
        </x-ui.select>
      </div>

      <div class="flex items-end gap-2">
        <x-ui.button type="submit" class="flex-1 justify-center">
          <i class="bi bi-search"></i>
          Filter
        </x-ui.button>

        <x-ui.button :href="route('activities.recap')" variant="secondary">
          Reset
        </x-ui.button>
      </div>

    </div>
  </form>
</x-ui.card>

<div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
  <x-ui.stat-card label="Total Santri" :value="$totalStudents" icon="bi-people" tone="blue" />
  <x-ui.stat-card label="Hadir" :value="$hadirCount" icon="bi-check-circle" tone="emerald" />
  <x-ui.stat-card label="Terlambat" :value="$terlambatCount" icon="bi-clock" tone="amber" />
  <x-ui.stat-card label="Belum" :value="$belumCount" icon="bi-x-circle" tone="red" />
</div>

<div class="grid gap-6 lg:grid-cols-12">

  {{-- Sudah Absen --}}
  <div class="lg:col-span-8">
    <x-ui.card padding="p-0">

      <div class="border-b border-slate-100 p-5">
        <div class="text-lg font-black text-slate-900">
          Sudah Absen
        </div>

        <div class="text-sm font-medium text-slate-500">
          {{ $selectedActivity?->name ?? '-' }} • {{ $date }}
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full min-w-[720px]">
          <thead class="bg-slate-50 text-left text-xs font-black uppercase tracking-wide text-slate-400">
            <tr>
              <th class="px-6 py-4">Santri</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4">Jam</th>
              <th class="px-6 py-4">Kelas/Kamar</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-100">
            @forelse($attendances as $attendance)
              <tr class="transition hover:bg-emerald-50/40">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-4">
                    <img
                      src="{{ $attendance->student->photoUrl() }}"
                      alt="{{ $attendance->student->name }}"
                      class="h-14 w-14 rounded-2xl object-cover ring-2 ring-emerald-100">

                    <div>
                      <div class="font-black text-slate-900">
                        {{ $attendance->student->name }}
                      </div>
                      <div class="text-sm font-semibold text-slate-500">
                        {{ $attendance->student->nis }}
                      </div>
                    </div>
                  </div>
                </td>

                <td class="px-6 py-4">
                  @if($attendance->status === 'hadir')
                    <x-ui.badge tone="emerald">Hadir</x-ui.badge>
                  @elseif($attendance->status === 'terlambat')
                    <x-ui.badge tone="amber">Telat</x-ui.badge>
                  @elseif($attendance->status === 'izin')
                    <x-ui.badge tone="blue">Izin</x-ui.badge>
                  @elseif($attendance->status === 'sakit')
                    <x-ui.badge tone="red">Sakit</x-ui.badge>
                  @elseif($attendance->status === 'pulang')
                    <x-ui.badge tone="slate">Pulang</x-ui.badge>
                  @else
                    <x-ui.badge tone="slate">{{ ucfirst($attendance->status) }}</x-ui.badge>
                  @endif
                </td>

                <td class="px-6 py-4 font-bold text-slate-600">
                  {{ optional($attendance->scanned_at)->format('H:i') ?? '-' }}
                </td>

                <td class="px-6 py-4">
                  <div class="flex flex-wrap gap-2">
                    <x-ui.badge tone="blue">
                      {{ $attendance->student->kelas ?? '-' }}
                    </x-ui.badge>

                    <x-ui.badge tone="emerald">
                      {{ $attendance->student->kamar ?? '-' }}
                    </x-ui.badge>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="p-10">
                  <x-ui.empty-state
                    title="Belum ada absensi"
                    subtitle="Belum ada santri yang scan kegiatan."
                    icon="bi-clipboard-x" />
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if(method_exists($attendances, 'hasPages') && $attendances->hasPages())
        <div class="border-t border-slate-100 p-5">
          {{ $attendances->links() }}
        </div>
      @endif

    </x-ui.card>
  </div>

  {{-- Belum Absen --}}
  <div class="lg:col-span-4">
    <x-ui.card padding="p-0">

      <div class="border-b border-slate-100 p-5">
        <div class="text-lg font-black text-slate-900">
          Belum Absen
        </div>

        <div class="text-sm font-medium text-slate-500">
          {{ $absentStudents->count() }} santri
        </div>
      </div>

      <div class="max-h-[720px] overflow-y-auto">
        @forelse($absentStudents as $student)
          <div class="border-b border-slate-100 p-4">
            <div class="flex items-start gap-4">
              <img
                src="{{ $student->photoUrl() }}"
                alt="{{ $student->name }}"
                class="h-14 w-14 rounded-2xl object-cover ring-2 ring-red-100">

              <div class="min-w-0 flex-1">
                <div class="truncate font-black text-slate-900">
                  {{ $student->name }}
                </div>

                <div class="text-sm font-semibold text-slate-500">
                  {{ $student->nis }}
                </div>

                <div class="mt-2 flex flex-wrap gap-2">
                  <x-ui.badge tone="blue">
                    {{ $student->kelas ?? '-' }}
                  </x-ui.badge>

                  <x-ui.badge tone="emerald">
                    {{ $student->kamar ?? '-' }}
                  </x-ui.badge>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">

                  <form method="POST" action="{{ route('activities.recap.mark-status') }}">
                    @csrf
                    <input type="hidden" name="activity_id" value="{{ $selectedActivity?->id }}">
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="status" value="izin">

                    <button class="rounded-2xl border border-blue-100 bg-blue-50 px-3 py-2 text-xs font-black text-blue-600">
                      Izin
                    </button>
                  </form>

                  <form method="POST" action="{{ route('activities.recap.mark-status') }}">
                    @csrf
                    <input type="hidden" name="activity_id" value="{{ $selectedActivity?->id }}">
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="status" value="sakit">

                    <button class="rounded-2xl border border-amber-100 bg-amber-50 px-3 py-2 text-xs font-black text-amber-600">
                      Sakit
                    </button>
                  </form>

                  <form method="POST" action="{{ route('activities.recap.mark-status') }}">
                    @csrf
                    <input type="hidden" name="activity_id" value="{{ $selectedActivity?->id }}">
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="status" value="pulang">

                    <button class="rounded-2xl border border-slate-200 bg-slate-100 px-3 py-2 text-xs font-black text-slate-600">
                      Pulang
                    </button>
                  </form>

                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="p-8">
            <x-ui.empty-state
              title="Semua sudah terdata"
              subtitle="Tidak ada santri yang belum absen."
              icon="bi-check-circle" />
          </div>
        @endforelse
      </div>

    </x-ui.card>
  </div>

</div>

@endsection