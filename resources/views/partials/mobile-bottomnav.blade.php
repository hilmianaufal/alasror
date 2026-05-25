<nav class="fixed inset-x-0 bottom-0 z-50 px-4 pb-4 lg:hidden">
  <div class="mx-auto max-w-md">
    <div class="relative rounded-[2rem] bg-gradient-to-r from-emerald-700 via-emerald-600 to-lime-500 p-2 shadow-2xl shadow-emerald-500/30">

      <div class="flex items-center justify-around">

        <a href="{{ route('dashboard') }}"
           class="flex h-12 w-12 items-center justify-center rounded-2xl text-xl transition
           {{ request()->routeIs('dashboard')
              ? 'bg-white/20 text-white'
              : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
          <i class="bi bi-house-door"></i>
        </a>

        <a href="{{ route('students.index') }}"
           class="flex h-12 w-12 items-center justify-center rounded-2xl text-xl transition
           {{ request()->routeIs('students.*')
              ? 'bg-white/20 text-white'
              : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
          <i class="bi bi-people"></i>
        </a>

        {{-- Tombol Scan utama --}}
        <a href="{{ route('scan.index') }}"
        class="-mt-9 flex h-[68px] w-[68px] items-center justify-center rounded-[1.6rem] bg-white text-3xl text-emerald-600 shadow-xl shadow-emerald-950/20 ring-4 ring-emerald-100 transition active:scale-95">
        <i class="bi bi-qr-code-scan"></i>
        </a>

        <a href="{{ route('rekap.index') }}"
           class="flex h-12 w-12 items-center justify-center rounded-2xl text-xl transition
           {{ request()->routeIs('rekap.*')
              ? 'bg-white/20 text-white'
              : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
          <i class="bi bi-clipboard-data"></i>
        </a>

        <a href="{{ route('profile.show') }}"
           class="flex h-12 w-12 items-center justify-center rounded-2xl text-xl transition
           {{ request()->routeIs('profile.*')
              ? 'bg-white/20 text-white'
              : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
          <i class="bi bi-person"></i>
        </a>

      </div>
    </div>
  </div>
</nav>