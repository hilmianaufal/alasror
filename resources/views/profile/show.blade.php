@extends('layouts.app')

@section('title','Profil Saya')
@section('mobile_title','Profil')

@section('content')

<x-ui.page-header
  title="Profil Saya"
  subtitle="Informasi akun dan akses petugas"
  icon="bi-person-circle"
/>

<div class="grid gap-6 lg:grid-cols-12">

  {{-- Profile Hero --}}
  <div class="lg:col-span-4">
    <x-ui.card>
      <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-emerald-700 via-emerald-500 to-lime-400 p-6 text-center text-white">

        <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-white/20 blur-2xl"></div>
        <div class="absolute -bottom-20 -left-20 h-48 w-48 rounded-full bg-lime-300/30 blur-3xl"></div>

        <div class="relative z-10">
          <img
            src="{{ $user->avatarUrl() }}"
            class="mx-auto h-32 w-32 rounded-[2rem] object-cover ring-4 ring-white/80 shadow-2xl"
            alt="{{ $user->name }}">

          <div class="mt-5 text-2xl font-black">
            {{ $user->name }}
          </div>

          <div class="mt-1 text-sm font-bold text-white/75">
            {{ $user->email }}
          </div>

          <div class="mt-4 flex flex-wrap justify-center gap-2">
            @foreach($user->roles as $role)
              <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-black">
                {{ ucfirst($role->name) }}
              </span>
            @endforeach
          </div>
        </div>
      </div>

      <div class="mt-5 grid grid-cols-2 gap-3">
        <div class="rounded-2xl bg-emerald-50 p-4 text-center">
          <div class="text-xs font-black uppercase text-emerald-400">
            Status
          </div>

          <div class="mt-1 font-black text-emerald-700">
            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
          </div>
        </div>

        <div class="rounded-2xl bg-blue-50 p-4 text-center">
          <div class="text-xs font-black uppercase text-blue-400">
            Login
          </div>

          <div class="mt-1 text-sm font-black text-blue-700">
            {{ $user->last_login_at ? $user->last_login_at->format('d M') : '-' }}
          </div>
        </div>
      </div>
    </x-ui.card>
  </div>

  {{-- Detail --}}
  <div class="lg:col-span-5">
    <x-ui.card>
      <div class="mb-5">
        <div class="text-lg font-black text-slate-900">
          Informasi Akun
        </div>
        <div class="text-sm font-medium text-slate-500">
          Detail data petugas
        </div>
      </div>

      <div class="space-y-4">

        <div class="rounded-2xl bg-slate-50 p-4">
          <div class="text-xs font-black uppercase tracking-wide text-slate-400">
            Nama
          </div>
          <div class="mt-1 font-black text-slate-900">
            {{ $user->name }}
          </div>
        </div>

        <div class="rounded-2xl bg-slate-50 p-4">
          <div class="text-xs font-black uppercase tracking-wide text-slate-400">
            Email
          </div>
          <div class="mt-1 break-all font-black text-slate-900">
            {{ $user->email }}
          </div>
        </div>

        <div class="rounded-2xl bg-slate-50 p-4">
          <div class="text-xs font-black uppercase tracking-wide text-slate-400">
            No. HP
          </div>
          <div class="mt-1 font-black text-slate-900">
            {{ $user->phone ?? '-' }}
          </div>
        </div>

        <div class="rounded-2xl bg-slate-50 p-4">
          <div class="text-xs font-black uppercase tracking-wide text-slate-400">
            Login Terakhir
          </div>
          <div class="mt-1 font-black text-slate-900">
            {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : '-' }}
          </div>
        </div>

        <div class="rounded-2xl bg-emerald-50 p-4">
          <div class="text-xs font-black uppercase tracking-wide text-emerald-400">
            Catatan
          </div>
          <div class="mt-1 text-sm font-semibold text-emerald-700">
            {{ $user->notes ?? 'Tidak ada catatan.' }}
          </div>
        </div>

      </div>
    </x-ui.card>
  </div>

  {{-- Actions --}}
  <div class="lg:col-span-3">
    <x-ui.card>
      <div class="mb-5">
        <div class="text-lg font-black text-slate-900">
          Aksi Akun
        </div>
        <div class="text-sm text-slate-500">
          Kelola profil dan sesi login.
        </div>
      </div>

      <div class="space-y-3">
        <x-ui.button
          :href="route('users.edit', $user)"
          class="w-full justify-center">
          <i class="bi bi-pencil"></i>
          Edit Profil
        </x-ui.button>

        <form method="POST" action="{{ route('logout') }}">
          @csrf

          <button
            class="w-full rounded-2xl border border-red-100 bg-red-50 px-4 py-2 text-sm font-black text-red-600 transition hover:bg-red-100">
            <i class="bi bi-box-arrow-right"></i>
            Logout
          </button>
        </form>
      </div>
    </x-ui.card>
  </div>

</div>

@endsection