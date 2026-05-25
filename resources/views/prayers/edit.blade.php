@extends('layouts.app')

@section('title','Edit Jadwal Sholat')
@section('mobile_title','Edit Sholat')

@section('content')

<x-ui.page-header
  title="Edit Jadwal Sholat"
  subtitle="{{ $prayer->name }} • Atur waktu scan dan toleransi"
  icon="bi-pencil-square"
>
  <x-slot:actions>
    <x-ui.button :href="route('prayers.index')" variant="secondary">
      Kembali
    </x-ui.button>
  </x-slot:actions>
</x-ui.page-header>

<form method="POST" action="{{ route('prayers.update', $prayer) }}">
  @csrf
  @method('PUT')

  <div class="grid gap-6 lg:grid-cols-12">

    {{-- Form utama --}}
    <div class="lg:col-span-8">
      <x-ui.card>

        <div class="mb-6">
          <div class="text-lg font-black text-slate-900">
            Pengaturan Waktu
          </div>

          <div class="mt-1 text-sm font-medium text-slate-500">
            Scan hanya bisa dilakukan pada rentang waktu yang ditentukan.
          </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">

          <x-ui.form-group label="Jam Mulai Scan" required>
            <x-ui.input
              type="time"
              name="start_time"
              value="{{ old('start_time', substr($prayer->start_time, 0, 5)) }}" />
          </x-ui.form-group>

          <x-ui.form-group label="Jam Selesai Scan" required>
            <x-ui.input
              type="time"
              name="end_time"
              value="{{ old('end_time', substr($prayer->end_time, 0, 5)) }}" />
          </x-ui.form-group>

          <div class="md:col-span-2">
            <x-ui.form-group label="Toleransi Telat" required>
              <x-ui.input
                type="number"
                name="late_minutes"
                min="0"
                max="180"
                value="{{ old('late_minutes', $prayer->late_minutes) }}"
                placeholder="Menit toleransi keterlambatan" />
            </x-ui.form-group>
          </div>

        </div>

      </x-ui.card>
    </div>

    {{-- Sidebar --}}
    <div class="lg:col-span-4">
      <div class="space-y-6">

        <x-ui.card>
          <div class="mb-4">
            <div class="text-lg font-black text-slate-900">
              Status Sholat
            </div>

            <div class="text-sm text-slate-500">
              Sholat aktif akan muncul di jadwal dan perhitungan absensi.
            </div>
          </div>

          <label class="flex cursor-pointer items-center justify-between rounded-2xl bg-emerald-50 p-4">
            <div>
              <div class="text-sm font-black text-emerald-900">
                Aktif
              </div>

              <div class="text-xs font-semibold text-emerald-600">
                Muncul di scan & rekap
              </div>
            </div>

            <input
              class="h-5 w-5 rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500"
              type="checkbox"
              name="is_active"
              value="1"
              @checked(old('is_active', $prayer->is_active))>
          </label>
        </x-ui.card>

        <x-ui.card>
          <div class="rounded-[1.5rem] bg-gradient-to-br from-emerald-600 to-lime-500 p-5 text-white">
            <div class="text-xs font-black uppercase tracking-wide text-white/70">
              Jadwal
            </div>

            <div class="mt-2 text-2xl font-black">
              {{ $prayer->name }}
            </div>

            <div class="mt-4 grid grid-cols-2 gap-3">
              <div class="rounded-2xl bg-white/20 p-3 text-center">
                <div class="text-[10px] font-black uppercase text-white/60">
                  Mulai
                </div>
                <div class="mt-1 font-black">
                  {{ substr($prayer->start_time, 0, 5) }}
                </div>
              </div>

              <div class="rounded-2xl bg-white/20 p-3 text-center">
                <div class="text-[10px] font-black uppercase text-white/60">
                  Selesai
                </div>
                <div class="mt-1 font-black">
                  {{ substr($prayer->end_time, 0, 5) }}
                </div>
              </div>
            </div>

            <div class="mt-4 rounded-2xl bg-white p-3 text-center text-emerald-700">
              <div class="text-xs font-black uppercase text-emerald-400">
                Toleransi
              </div>
              <div class="mt-1 text-lg font-black">
                {{ $prayer->late_minutes }} menit
              </div>
            </div>
          </div>
        </x-ui.card>

        <x-ui.card>
          <div class="space-y-3">
            <x-ui.button type="submit" class="w-full justify-center">
              <i class="bi bi-check-circle"></i>
              Simpan Perubahan
            </x-ui.button>

            <x-ui.button :href="route('prayers.index')" variant="secondary" class="w-full justify-center">
              Batal
            </x-ui.button>
          </div>
        </x-ui.card>

      </div>
    </div>

  </div>
</form>

@endsection