@extends('layouts.app')

@section('title','Tambah Jadwal Sholat')
@section('mobile_title','Tambah Sholat')

@section('content')

<x-ui.page-header
    title="Tambah Jadwal Sholat"
    subtitle="Tambahkan jadwal sholat baru"
    icon="bi-plus-circle"
/>

<form method="POST" action="{{ route('prayers.store') }}">
    @csrf

    <div class="mx-auto max-w-4xl">

        <x-ui.card>

            <div class="mb-6">
                <div class="text-lg font-black text-slate-900">
                    Informasi Jadwal Sholat
                </div>

                <div class="mt-1 text-sm text-slate-500">
                    Atur nama, jam scan dan toleransi keterlambatan.
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">

                <x-ui.form-group label="Nama Sholat" required>
                    <x-ui.input
                        name="name"
                        :value="old('name')"
                        placeholder="Contoh: Subuh" />
                </x-ui.form-group>

                <x-ui.form-group label="Urutan" required>
                    <x-ui.input
                        type="number"
                        name="order"
                        :value="old('order', 1)" />
                </x-ui.form-group>

                <x-ui.form-group label="Jam Mulai Scan" required>
                    <x-ui.input
                        type="time"
                        name="start_time"
                        :value="old('start_time')" />
                </x-ui.form-group>

                <x-ui.form-group label="Jam Berakhir Scan" required>
                    <x-ui.input
                        type="time"
                        name="end_time"
                        :value="old('end_time')" />
                </x-ui.form-group>

                <x-ui.form-group label="Toleransi Terlambat (Menit)" required>
                    <x-ui.input
                        type="number"
                        name="late_minutes"
                        :value="old('late_minutes', 10)" />
                </x-ui.form-group>

                <x-ui.form-group label="Status">
                    <x-ui.select name="is_active">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </x-ui.select>
                </x-ui.form-group>

            </div>

            <div class="mt-8 flex flex-wrap gap-3">

                <x-ui.button type="submit">
                    <i class="bi bi-check-circle"></i>
                    Simpan Jadwal
                </x-ui.button>

                <x-ui.button
                    :href="route('prayers.index')"
                    variant="secondary">
                    Kembali
                </x-ui.button>

            </div>

        </x-ui.card>

    </div>

</form>

@endsection
