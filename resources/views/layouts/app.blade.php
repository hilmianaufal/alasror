<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Absensi QR')</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>

<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">

  <div class="min-h-screen lg:flex">

    @include('partials.desktop-sidebar')

    <div class="flex min-h-screen min-w-0 flex-1 flex-col">

      @include('partials.mobile-topbar')

      <main class="min-w-0 flex-1 overflow-x-hidden px-4 pb-24 pt-4 sm:px-6 lg:px-8 lg:pb-8 lg:pt-8">
        <div class="mx-auto w-full max-w-7xl">
          @include('partials.flash')
          @yield('content')
        </div>
      </main>

      @include('partials.mobile-bottomnav')

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @stack('scripts')

</body>
</html>