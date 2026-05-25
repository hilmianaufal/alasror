<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login • SIDAPDA</title>

  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#059669">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-slate-900 antialiased">

  <main class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-8">

    {{-- Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-950 via-emerald-800 to-lime-600"></div>
    <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-lime-300/30 blur-3xl"></div>
    <div class="absolute -right-24 bottom-10 h-80 w-80 rounded-full bg-emerald-300/30 blur-3xl"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,.18),transparent_35%)]"></div>

    <section class="relative z-10 grid w-full max-w-5xl overflow-hidden rounded-[2.5rem] bg-white/90 shadow-2xl shadow-emerald-950/40 backdrop-blur-xl lg:grid-cols-2">

      {{-- Left Branding --}}
      <div class="relative hidden overflow-hidden bg-gradient-to-br from-emerald-700 via-emerald-600 to-lime-500 p-10 text-white lg:block">

        <div class="absolute -right-20 -top-20 h-60 w-60 rounded-full bg-white/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-lime-200/30 blur-3xl"></div>

        <div class="relative z-10 flex h-full flex-col justify-between">
          <div>
            <div class="flex h-20 w-20 items-center justify-center rounded-[1.7rem] bg-white shadow-2xl shadow-emerald-950/20">
              <img
                src="{{ asset('images/logo.png.png') }}"
                alt="Logo SIDAPDA"
                class="h-14 w-14 object-contain">
            </div>

            <h1 class="mt-8 text-4xl font-black tracking-tight">
              SIDAPDA
            </h1>

            <p class="mt-3 max-w-sm text-base font-medium leading-relaxed text-white/80">
              Sistem Informasi Digital Absensi Pondok Darussalam berbasis QR Code.
            </p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="rounded-3xl bg-white/15 p-4 backdrop-blur">
              <div class="text-2xl font-black">
                QR
              </div>
              <div class="mt-1 text-xs font-bold text-white/70">
                Absensi cepat
              </div>
            </div>

            <div class="rounded-3xl bg-white/15 p-4 backdrop-blur">
              <div class="text-2xl font-black">
                Live
              </div>
              <div class="mt-1 text-xs font-bold text-white/70">
                Monitoring realtime
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Login Form --}}
      <div class="p-6 sm:p-10">

        {{-- Mobile Brand --}}
        <div class="mb-8 text-center lg:hidden">
          <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.7rem] bg-gradient-to-br from-emerald-600 to-lime-400 shadow-xl shadow-emerald-300/50">
            <img
              src="{{ asset('images/logo.png.png') }}"
              alt="Logo SIDAPDA"
              class="h-14 w-14 object-contain">
          </div>

          <div class="mt-4 text-2xl font-black text-emerald-950">
            SIDAPDA
          </div>

          <div class="mt-1 text-sm font-semibold text-slate-500">
            Absensi Pondok Digital
          </div>
        </div>

        <div class="mb-8">
          <div class="inline-flex rounded-full bg-emerald-50 px-4 py-2 text-xs font-black uppercase tracking-wide text-emerald-700 ring-1 ring-emerald-100">
            Login Petugas
          </div>

          <h2 class="mt-5 text-3xl font-black tracking-tight text-slate-950">
            Selamat Datang
          </h2>

          <p class="mt-2 text-sm font-medium text-slate-500">
            Masuk untuk mengakses sistem absensi santri.
          </p>
        </div>

        @if ($errors->any())
          <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700">
            {{ $errors->first() }}
          </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
          @csrf

          <div>
            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-slate-400">
              Email
            </label>

            <div class="relative">
              <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

              <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="email@example.com"
                class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm font-semibold outline-none transition placeholder:text-slate-400 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
            </div>
          </div>

          <div>
            <label class="mb-2 block text-xs font-black uppercase tracking-wide text-slate-400">
              Password
            </label>

            <div class="relative">
              <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

              <input
                type="password"
                name="password"
                required
                placeholder="Masukkan password"
                class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm font-semibold outline-none transition placeholder:text-slate-400 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
            </div>
          </div>

          <button
            class="flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 to-lime-500 px-5 py-3 text-sm font-black text-white shadow-xl shadow-emerald-300/50 transition hover:-translate-y-0.5 active:scale-95">
            <i class="bi bi-box-arrow-in-right"></i>
            Masuk
          </button>

          <button
            id="btnInstall"
            type="button"
            class="hidden w-full items-center justify-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-black text-emerald-700 transition hover:bg-emerald-100">
            <i class="bi bi-phone"></i>
            Install Aplikasi
          </button>
        </form>

        <div class="mt-8 rounded-3xl bg-slate-50 p-4">
          <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
              <i class="bi bi-shield-check"></i>
            </div>

            <div>
              <div class="text-sm font-black text-slate-900">
                Sistem Aman & Mobile Friendly
              </div>
              <div class="mt-1 text-xs font-semibold leading-relaxed text-slate-500">
                Optimized untuk petugas pondok, scan QR, rekap absensi, dan monitoring santri.
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>
  </main>

  <script>
    let deferredPrompt = null;
    const btnInstall = document.getElementById('btnInstall');

    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      deferredPrompt = e;

      btnInstall.classList.remove('hidden');
      btnInstall.classList.add('flex');
    });

    btnInstall?.addEventListener('click', async () => {
      if (deferredPrompt) {
        deferredPrompt.prompt();
        await deferredPrompt.userChoice;
        deferredPrompt = null;

        btnInstall.classList.add('hidden');
        btnInstall.classList.remove('flex');
        return;
      }

      alert('Install via menu browser:\n\nChrome (⋮) → Install app / Add to Home screen.');
    });

    if ('serviceWorker' in navigator) {
      window.addEventListener('load', async () => {
        try {
          await navigator.serviceWorker.register('/sw.js');
        } catch (e) {
          console.error('SW register failed', e);
        }
      });
    }
  </script>

</body>
</html>