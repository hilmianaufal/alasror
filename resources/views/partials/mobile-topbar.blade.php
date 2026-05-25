<header class="sticky top-0 z-40 border-b border-emerald-100 bg-white/80 px-4 py-3 backdrop-blur-xl lg:hidden">
  <div class="flex items-center justify-between">

    {{-- Left --}}
    <div class="flex items-center gap-3">
      <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 via-emerald-600 to-lime-400 text-white shadow-lg shadow-emerald-300/50">
        <i class="bi bi-stars text-xl"></i>
      </div>

      <div>
        <div class="text-sm font-black leading-tight text-emerald-950">
          @yield('mobile_title', 'Absensi QR')
        </div>
        <div class="text-xs font-medium text-slate-500">
          {{ now()->translatedFormat('l, d M Y') }}
        </div>
      </div>
    </div>

    {{-- Right --}}
    <div class="flex items-center gap-2">
      <button type="button"
              class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
        <i class="bi bi-bell"></i>
      </button>

      @auth
        <a href="{{ route('profile.show') }}">
          <img src="{{ auth()->user()->avatarUrl() }}"
               alt="avatar"
               class="h-11 w-11 rounded-2xl object-cover ring-2 ring-emerald-100">
        </a>
      @endauth
    </div>

  </div>
</header>