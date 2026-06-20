@extends('layouts.app')

@section('title', 'Edit Jadwal Sholat')
@section('mobile_title', 'Edit Sholat')

@section('content')

<x-ui.page-header
    title="Edit Jadwal Sholat"
    subtitle="{{ $prayer->name }} • Atur waktu scan dan toleransi"
    icon="bi-pencil-square"
>
    <x-slot:actions>
        <x-ui.button
            :href="route('prayers.index')"
            variant="secondary"
        >
            <i class="bi bi-arrow-left"></i>
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
                    <x-ui.form-group label="Nama Sholat" required>
                        <x-ui.input
                            name="name"
                            :value="old('name', $prayer->name)"
                            placeholder="Contoh: Subuh"
                        />
                    </x-ui.form-group>

                    <x-ui.form-group label="Urutan" required>
                        <x-ui.input
                            type="number"
                            name="order"
                            :value="old('order', $prayer->order)"
                        />
                    </x-ui.form-group>

                    <x-ui.form-group label="Jam Mulai Scan" required>
                        <x-ui.input
                            type="time"
                            name="start_time"
                            :value="old('start_time', substr($prayer->start_time, 0, 5))"
                        />
                    </x-ui.form-group>

                    <x-ui.form-group label="Jam Berakhir Scan" required>
                        <x-ui.input
                            type="time"
                            name="end_time"
                            :value="old('end_time', substr($prayer->end_time, 0, 5))"
                        />
                    </x-ui.form-group>

                    <x-ui.form-group label="Toleransi Terlambat" required>
                        <x-ui.input
                            type="number"
                            name="late_minutes"
                            :value="old('late_minutes', $prayer->late_minutes)"
                            min="0"
                        />
                    </x-ui.form-group>

                    <x-ui.form-group label="Status">
                        <x-ui.select name="is_active">
                            <option value="1" @selected(old('is_active', $prayer->is_active) == 1)>
                                Aktif
                            </option>
                            <option value="0" @selected(old('is_active', $prayer->is_active) == 0)>
                                Nonaktif
                            </option>
                        </x-ui.select>
                    </x-ui.form-group>
                </div>
            </x-ui.card>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-4">
            <div class="space-y-6">

                <x-ui.card>
                    <div class="mb-4">
                        <div class="text-lg font-black text-slate-900">
                            Informasi Status
                        </div>

                        <div class="mt-1 text-sm text-slate-500">
                            Sholat aktif akan muncul di jadwal scan dan perhitungan rekap absensi.
                        </div>
                    </div>

                    <div class="rounded-2xl bg-emerald-50 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="text-sm font-black text-emerald-900">
                                    {{ old('is_active', $prayer->is_active) ? 'Aktif' : 'Nonaktif' }}
                                </div>

                                <div class="mt-1 text-xs font-semibold text-emerald-600">
                                    {{ old('is_active', $prayer->is_active) ? 'Muncul di scan & rekap' : 'Tidak muncul di scan' }}
                                </div>
                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-emerald-600 shadow-sm">
                                <i class="bi {{ old('is_active', $prayer->is_active) ? 'bi-check-circle' : 'bi-x-circle' }}"></i>
                            </div>
                        </div>
                    </div>
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
                        <x-ui.button
                            type="submit"
                            class="w-full justify-center"
                        >
                            <i class="bi bi-check-circle"></i>
                            Simpan Perubahan
                        </x-ui.button>

                        <x-ui.button
                            :href="route('prayers.index')"
                            variant="secondary"
                            class="w-full justify-center"
                        >
                            Batal
                        </x-ui.button>
                    </div>
                </x-ui.card>

            </div>
        </div>

    </div>
</form>

@endsection
