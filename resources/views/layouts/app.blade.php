<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'RSUD Bandung Kiwari')</title>
  @vite(['resources/css/app.css'])
  <script>
    (function() {
      const theme = localStorage.getItem('theme') || 'light';
      if (theme === 'dark') {
        document.documentElement.classList.add('dark');
      }
    })();
  </script>
</head>
<body class="bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-200 transition-colors duration-300">

  {{-- MODE MOBILE --}}
  @php
      $userAgent = request()->header('User-Agent') ?? '';
      $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);
  @endphp
  @if($isMobile)
      <script>console.log("MODE: Mobile");</script>
  @else
      <script>console.log("MODE: Desktop");</script>
  @endif

  @include('layouts.header')
  <main class="min-h-screen">
    @yield('content')
  </main>

  @include('layouts.footer')
  
  {{-- DARK MODE TOGGLE SCRIPT --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggle = document.getElementById('dark-mode-toggle');
      const html = document.documentElement;
      const currentTheme = localStorage.getItem('theme') || 'light';
      if (toggle) toggle.checked = currentTheme === 'dark';
      if (toggle) {
        toggle.addEventListener('change', function() {
          const newTheme = this.checked ? 'dark' : 'light';
          newTheme === 'dark'
            ? html.classList.add('dark')
            : html.classList.remove('dark');
          localStorage.setItem('theme', newTheme);
        });
      }
    });
  </script>
  @stack('scripts')
</body>
</html>
