<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Panel - UN Brahma College Election')</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    .admin-gradient {
      background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    }

    .stat-card {
      transition: all 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }
  </style>
</head>

<body class="h-full bg-gray-50">
  <div class="min-h-full">
    <!-- Modern Admin Navigation -->
    <nav class="bg-gray-900 shadow-xl">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="flex items-center space-x-3">
                <div class="admin-gradient flex h-12 w-12 items-center justify-center rounded-xl shadow-lg">
                  <i class="fas fa-shield-halved text-2xl text-white"></i>
                </div>
                <div>
                  <h1 class="text-xl font-bold text-white">Admin Panel</h1>
                  <p class="text-xs text-gray-400">Election Management</p>
                </div>
              </div>
            </div>
            <div class="ml-10 hidden md:block">
              <div class="flex items-center space-x-2">
                <a href="{{ route('admin.dashboard') }}"
                  class="{{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} flex items-center space-x-2 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200">
                  <i class="fas fa-chart-line"></i>
                  <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.candidates.index') }}"
                  class="{{ request()->routeIs('admin.candidates.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} flex items-center space-x-2 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200">
                  <i class="fas fa-users"></i>
                  <span>Candidates</span>
                </a>
                <a href="{{ route('admin.voters') }}"
                  class="{{ request()->routeIs('admin.voters') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} flex items-center space-x-2 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200">
                  <i class="fas fa-user-check"></i>
                  <span>Voters</span>
                </a>
              </div>
            </div>
          </div>
          <div class="hidden md:block">
            <div class="ml-4 flex items-center space-x-3 md:ml-6">
              <a href="{{ route('home') }}"
                class="flex items-center space-x-2 rounded-lg px-4 py-2 text-sm text-gray-300 transition-all duration-200 hover:bg-gray-800 hover:text-white">
                <i class="fas fa-external-link-alt"></i>
                <span>View Site</span>
              </a>
              <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"
                  class="flex items-center space-x-2 rounded-lg px-4 py-2 text-sm text-gray-300 transition-all duration-200 hover:bg-gray-800 hover:text-white">
                  <i class="fas fa-sign-out-alt"></i>
                  <span>Logout</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Modern Header -->
    <header class="border-b border-gray-200 bg-white shadow-md">
      <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1
          class="flex items-center space-x-3 bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-4xl font-bold tracking-tight text-transparent">
          <span>@yield('header', 'Admin Dashboard')</span>
        </h1>
      </div>
    </header>

    <main class="pb-12">
      <div class="mx-auto max-w-7xl py-8 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if (session('success'))
          <div
            class="mb-6 rounded-2xl border-l-4 border-green-500 bg-gradient-to-r from-green-50 to-emerald-50 p-6 shadow-lg">
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
          <div class="mb-6 rounded-2xl border-l-4 border-red-500 bg-gradient-to-r from-red-50 to-rose-50 p-6 shadow-lg">
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
  </div>
</body>

</html>
