<aside class="hidden min-h-screen w-72 shrink-0 border-r border-emerald-100 bg-white/90 backdrop-blur-xl lg:sticky lg:top-0 lg:flex lg:flex-col">

  {{-- Brand --}}
  <div class="flex h-24 items-center gap-3 px-6">
      <div class="flex h-[52px] w-[52px] items-center justify-center overflow-hidden rounded-[1.4rem] bg-white shadow-xl shadow-emerald-300/40 ring-2 ring-emerald-100">
          <img
              src="{{ asset('images/logo.png.png') }}"
              alt="Logo"
              class="h-10 w-10 object-contain">
      </div>

    <div>
      <div class="text-xl font-black tracking-tight text-emerald-950">
        SIDAPDA
      </div>
      <div class="text-xs font-semibold uppercase tracking-wider text-emerald-500">
        Sistem Informasi Digital Absensi
      </div>
    </div>
  </div>

  {{-- Menu --}}
  <nav class="flex-1 space-y-1 px-4 pb-6">

    @php
      $menus = [
        [
          'label' => 'Dashboard',
          'route' => 'dashboard',
          'active' => 'dashboard',
          'icon' => 'bi-house-door',
          'permission' => null,
        ],
        [
          'label' => 'Scan Sholat',
          'route' => 'scan.index',
          'active' => 'scan.*',
          'icon' => 'bi-qr-code-scan',
          'permission' => 'scan_qr',
        ],
        [
          'label' => 'Scan Kegiatan',
          'route' => 'activities.scan',
          'active' => 'activities.scan',
          'icon' => 'bi-qr-code',
          'permission' => 'scan_qr',
        ],
        [
          'label' => 'Data Santri',
          'route' => 'students.index',
          'active' => 'students.*',
          'icon' => 'bi-people',
          'permission' => 'manage_students',
        ],
        [
          'label' => 'Rekap Harian',
          'route' => 'rekap.index',
          'active' => 'rekap.index',
          'icon' => 'bi-clipboard-data',
          'permission' => 'view_reports',
        ],
        [
          'label' => 'Rekap Bulanan',
          'route' => 'rekap.monthly',
          'active' => 'rekap.monthly',
          'icon' => 'bi-calendar3',
          'permission' => 'view_reports',
        ],
        [
          'label' => 'Jadwal Sholat',
          'route' => 'prayers.index',
          'active' => 'prayers.*',
          'icon' => 'bi-clock-history',
          'permission' => 'manage_prayers',
        ],
        [
          'label' => 'Kegiatan',
          'route' => 'activities.index',
          'active' => 'activities.index',
          'icon' => 'bi-calendar-check',
          'permission' => 'manage_prayers',
        ],
        [
          'label' => 'Rekap Kegiatan',
          'route' => 'activities.recap',
          'active' => 'activities.recap',
          'icon' => 'bi-clipboard-check',
          'permission' => 'view_reports',
        ],
        [
          'label' => 'Users',
          'route' => 'users.index',
          'active' => 'users.*',
          'icon' => 'bi-person-gear',
          'permission' => 'manage_users',
        ],
      ];
    @endphp

    @foreach($menus as $menu)
      @if(!$menu['permission'] || auth()->user()?->can($menu['permission']))
        <a href="{{ route($menu['route']) }}"
           class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition-all duration-200
           {{ request()->routeIs($menu['active'])
              ? 'bg-gradient-to-r from-emerald-600 to-lime-500 text-white shadow-lg shadow-emerald-300/50'
              : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700' }}">

          <div class="flex h-9 w-9 items-center justify-center rounded-xl
            {{ request()->routeIs($menu['active'])
                ? 'bg-white/20 text-white'
                : 'bg-slate-100 text-slate-500 group-hover:bg-emerald-100 group-hover:text-emerald-700' }}">
            <i class="bi {{ $menu['icon'] }}"></i>
          </div>

          <span>{{ $menu['label'] }}</span>
        </a>
      @endif
    @endforeach

  </nav>

  {{-- User Card --}}
  @auth
    <div class="border-t border-emerald-100 p-4">
      <a href="{{ route('profile.show') }}"
         class="flex items-center gap-3 rounded-3xl bg-gradient-to-br from-emerald-50 to-lime-50 p-3 transition hover:shadow-lg hover:shadow-emerald-100">

        <img src="{{ auth()->user()->avatarUrl() }}"
             class="h-12 w-12 rounded-2xl object-cover ring-2 ring-white">

        <div class="min-w-0 flex-1">
          <div class="truncate text-sm font-black text-emerald-950">
            {{ auth()->user()->name }}
          </div>
          <div class="truncate text-xs text-slate-500">
            {{ auth()->user()->email }}
          </div>
        </div>

        <i class="bi bi-chevron-right text-sm text-emerald-500"></i>
      </a>

      <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button class="flex w-full items-center justify-center gap-2 rounded-2xl border border-red-100 bg-red-50 px-4 py-2 text-sm font-bold text-red-600 transition hover:bg-red-100">
          <i class="bi bi-box-arrow-right"></i>
          Logout
        </button>
      </form>
    </div>
  @endauth

</aside>