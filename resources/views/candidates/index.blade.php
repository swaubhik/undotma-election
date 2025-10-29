@extends('layouts.public')

@section('title', 'Candidates - UN Brahma College Election')
@section('header', 'Meet the Candidates')

@section('content')
  <div class="px-4 py-12 sm:px-6 lg:px-8">
    <!-- Search & Filters Section -->
    <div class="mb-12 rounded-3xl border border-gray-100 bg-white p-6 shadow-lg" data-aos="fade-up">
      <form method="GET" class="space-y-6">
        <!-- Filter Controls -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
          <!-- Portfolio Filter -->
          <div>
            <label for="portfolio" class="block text-sm font-medium text-gray-700">Portfolio</label>
            <select id="portfolio" name="portfolio"
              class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
              <option value="">All Portfolios</option>
              @foreach ($portfolios as $portfolio)
                <option value="{{ $portfolio->id }}" {{ request('portfolio') == $portfolio->id ? 'selected' : '' }}>
                  {{ $portfolio->name }} ({{ $portfolio->candidates_count }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Department Filter -->
          @if ($departments->isNotEmpty())
            <div>
              <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
              <select id="department" name="department"
                class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="">All Departments</option>
                @foreach ($departments as $dept)
                  <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                    {{ $dept }}
                  </option>
                @endforeach
              </select>
            </div>
          @endif

          <!-- Year Filter -->
          @if ($years->isNotEmpty())
            <div>
              <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
              <select id="year" name="year"
                class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="">All Years</option>
                @foreach ($years as $year)
                  <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                    {{ $year }}
                  </option>
                @endforeach
              </select>
            </div>
          @endif

          <!-- Sort -->
          <div>
            <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
            <div class="mt-1 flex rounded-lg border border-gray-300 bg-white shadow-sm">
              <select id="sort" name="sort"
                class="block w-full rounded-l-lg border-0 bg-transparent px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="votes" {{ $sort === 'votes' ? 'selected' : '' }}>Votes</option>
                <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Name</option>
              </select>
              <button type="submit" name="direction" value="{{ $direction === 'asc' ? 'desc' : 'asc' }}"
                class="flex items-center justify-center border-l border-gray-300 px-3 hover:bg-gray-50">
                @if ($direction === 'asc')
                  <i class="fas fa-sort-amount-up text-gray-500"></i>
                @else
                  <i class="fas fa-sort-amount-down text-gray-500"></i>
                @endif
              </button>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between">
          <!-- Filter/Reset -->
          <div class="flex space-x-4">
            <button type="submit"
              class="inline-flex items-center space-x-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
              <i class="fas fa-filter"></i>
              <span>Apply Filters</span>
            </button>
            <a href="{{ route('candidates.index') }}"
              class="inline-flex items-center space-x-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
              <i class="fas fa-undo"></i>
              <span>Reset</span>
            </a>
          </div>
        </div>
      </form>
    </div>

    <!-- Results Info -->
    <div class="mb-8 flex items-center justify-between" data-aos="fade-up">
      <p class="text-sm text-gray-600">
        Showing <span class="font-semibold">{{ $candidates->count() }}</span> candidates
        @if (request()->hasAny(['portfolio', 'department', 'year']))
          with current filters
        @endif
      </p>
    </div>

    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
      @forelse($candidates as $candidate)
        <div
          class="card-hover group overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-lg transition-all duration-300 hover:border-blue-200 hover:shadow-2xl"
          data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

          <!-- Gradient Header -->
          <div class="h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>

          <div class="p-8">
            <!-- Candidate Photo -->
            @if ($candidate->photo)
              <div class="relative mb-6 overflow-hidden rounded-2xl transition-shadow duration-300 group-hover:shadow-xl">
                <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}"
                  class="h-56 w-full transform object-cover transition-transform duration-500 group-hover:scale-110">
                <div
                  class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                </div>
              </div>
            @else
              <div
                class="mb-6 flex h-56 w-full items-center justify-center rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 transition-shadow duration-300 group-hover:shadow-xl">
                <i class="fas fa-user-circle text-8xl text-blue-300"></i>
              </div>
            @endif

            <!-- Candidate Info -->
            <div class="space-y-4">
              <div>
                <h3
                  class="text-2xl font-bold text-gray-900 transition-all duration-300 group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-purple-600 group-hover:bg-clip-text group-hover:text-transparent">
                  {{ $candidate->name }}
                </h3>
              </div>

              <!-- Position Badge -->
              @if ($candidate->position)
                <div class="flex items-center space-x-2">
                  <span
                    class="inline-flex items-center space-x-2 rounded-full border border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-2">
                    <i class="fas fa-crown text-yellow-500"></i>
                    <p
                      class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-sm font-semibold text-transparent">
                      {{ $candidate->position }}
                    </p>
                  </span>
                </div>
              @endif

              <!-- Department & Year -->
              @if ($candidate->department || $candidate->year)
                <div class="flex flex-wrap gap-3">
                  @if ($candidate->department)
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                      <i class="fas fa-building text-blue-500"></i>
                      <span class="font-medium">{{ $candidate->department }}</span>
                    </div>
                  @endif
                  @if ($candidate->year)
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                      <i class="fas fa-graduation-cap text-purple-500"></i>
                      <span class="font-medium">{{ $candidate->year }}</span>
                    </div>
                  @endif
                </div>
              @endif

              <!-- Bio -->
              @if ($candidate->bio)
                <p class="line-clamp-3 text-sm leading-relaxed text-gray-600">
                  {{ Str::limit($candidate->bio, 150) }}
                </p>
              @endif

              <!-- Vote Count -->
              <div class="border-t border-gray-100 pt-4">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle text-xl text-green-500"></i>
                    <span class="text-sm text-gray-600">
                      <strong class="text-lg font-bold text-gray-900">{{ $candidate->votes_count }}</strong>
                      <span class="text-gray-500">{{ Str::plural('vote', $candidate->votes_count) }}</span>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Vote Button -->
              <a href="{{ route('candidates.show', $candidate) }}"
                class="group/btn mt-6 inline-flex w-full transform items-center justify-center space-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-center font-bold text-white transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/30">
                <i class="fas fa-vote-yea transition-transform duration-300 group-hover/btn:scale-125"></i>
                <span>Vote for {{ Str::of($candidate->name)->explode(' ')->first() }}</span>
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="col-span-full py-20 text-center" data-aos="fade-up">
          <i class="fas fa-inbox mb-6 block text-8xl text-gray-300"></i>
          <h3 class="mb-2 text-2xl font-bold text-gray-600">No Candidates Available</h3>
          <p class="text-lg text-gray-500">Candidates will be added soon. Please check back later.</p>
        </div>
      @endforelse
    </div>
  </div>

  <!-- AOS Initialization -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true
    });
  </script>
@endsection
