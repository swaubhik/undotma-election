<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Student Election - UN Brahma College')</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .gradient-text {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .glass-effect {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .card-hover {
      transition: all 0.3s ease;
    }

    .card-hover:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .floating {
      animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {

      0%,
      100% {
        transform: translateY(0px);
      }

      50% {
        transform: translateY(-20px);
      }
    }

    .pulse-glow {
      animation: pulse-glow 2s ease-in-out infinite;
    }

    @keyframes pulse-glow {

      0%,
      100% {
        box-shadow: 0 0 20px rgba(102, 126, 234, 0.5);
      }

      50% {
        box-shadow: 0 0 40px rgba(102, 126, 234, 0.8);
      }
    }
  </style>
</head>

<body class="h-full bg-gradient-to-br from-gray-50 to-gray-100">
  <div class="min-h-full">
    <!-- Modern Navigation -->
    <nav class="gradient-bg sticky top-0 z-50 bg-opacity-95 shadow-lg backdrop-blur-sm">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="flex items-center space-x-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-lg">
                  <i class="fas fa-graduation-cap gradient-text text-2xl"></i>
                </div>
                <div>
                  <h1 class="text-xl font-bold text-white">UN Brahma College</h1>
                  <p class="text-xs text-indigo-200">Student Election 2025</p>
                </div>
              </div>
            </div>
            <div class="ml-10 hidden md:block">
              <div class="flex items-center space-x-2">
                <a href="{{ route('home') }}"
                  class="{{ request()->routeIs('home') || request()->routeIs('candidates.*') ? 'bg-white bg-opacity-20' : '' }} flex items-center space-x-2 rounded-xl px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 hover:bg-white hover:bg-opacity-20">
                  <i class="fas fa-users"></i>
                  <span>Candidates</span>
                </a>
                <a href="{{ route('results') }}"
                  class="{{ request()->routeIs('results') ? 'bg-white bg-opacity-20' : '' }} flex items-center space-x-2 rounded-xl px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 hover:bg-white hover:bg-opacity-20">
                  <i class="fas fa-chart-bar"></i>
                  <span>Live Results</span>
                </a>
              </div>
            </div>
          </div>
          @auth
            @if (auth()->user()->isAdmin())
              <div>
                <a href="{{ route('admin.dashboard') }}"
                  class="flex items-center space-x-2 rounded-xl bg-white px-4 py-2.5 text-sm font-medium text-indigo-600 shadow-lg transition-all duration-200 hover:bg-opacity-90">
                  <i class="fas fa-shield-halved"></i>
                  <span>Admin Panel</span>
                </a>
              </div>
            @endif
          @endauth
        </div>
      </div>
    </nav>

    <!-- Modern Header -->
    @hasSection('header')
      <header class="border-b border-gray-200 bg-white shadow-md">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
          <h1 class="gradient-text flex items-center space-x-3 text-4xl font-bold tracking-tight">
            <span>@yield('header', 'Student Election')</span>
          </h1>
        </div>
      </header>
    @endif

    <main class="pb-12">
      <div class="mx-auto max-w-7xl py-8 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if (session('success'))
          <div
            class="mb-6 rounded-2xl border-l-4 border-green-500 bg-gradient-to-r from-green-50 to-emerald-50 p-6 shadow-lg"
            data-aos="fade-down">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-3xl text-green-500"></i>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
              </div>
            </div>
          </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
          <div class="mb-6 rounded-2xl border-l-4 border-red-500 bg-gradient-to-r from-red-50 to-rose-50 p-6 shadow-lg"
            data-aos="fade-down">
            <div class="flex">
              <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-3xl text-red-500"></i>
              </div>
              <div class="ml-4">
                <h3 class="mb-2 text-sm font-medium text-red-800">Please fix the following errors:</h3>
                <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-700">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        @endif

        @yield('content')
      </div>
    </main>

    <!-- Modern Footer -->
    <footer class="gradient-bg mt-12">
      <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 text-white md:grid-cols-3">
          <div>
            <div class="mb-4 flex items-center space-x-3">
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white">
                <i class="fas fa-graduation-cap gradient-text text-xl"></i>
              </div>
              <h3 class="text-lg font-bold">UN Brahma College</h3>
            </div>
            <p class="text-sm text-indigo-200">Empowering democracy through transparent student elections.</p>
          </div>
          <div>
            <h4 class="mb-4 font-semibold">Quick Links</h4>
            <ul class="space-y-2 text-sm text-indigo-200">
              <li><a href="{{ route('home') }}" class="transition-colors hover:text-white"><i
                    class="fas fa-chevron-right mr-2"></i>Candidates</a></li>
              <li><a href="{{ route('results') }}" class="transition-colors hover:text-white"><i
                    class="fas fa-chevron-right mr-2"></i>Live Results</a></li>
            </ul>
          </div>
          <div>
            <h4 class="mb-4 font-semibold">Contact</h4>
            <ul class="space-y-2 text-sm text-indigo-200">
              <li><i class="fas fa-envelope mr-2"></i>election@unbrahma.edu</li>
              <li><i class="fas fa-phone mr-2"></i>+91 XXX XXX XXXX</li>
              <li><i class="fas fa-map-marker-alt mr-2"></i>UN Brahma College Campus</li>
            </ul>
          </div>
        </div>
        <div class="mt-8 border-t border-indigo-500 pt-8 text-center text-sm text-indigo-200">
          <p>&copy; {{ date('Y') }} UN Brahma College. All rights reserved. Built with <i
              class="fas fa-heart text-red-400"></i></p>
        </div>
      </div>
    </footer>
  </div>

  <script>
    // Initialize AOS
    AOS.init({
      duration: 800,
      once: true,
      offset: 100
    });
  </script>
</body>

</html>
