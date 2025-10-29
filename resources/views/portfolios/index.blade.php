@extends('layouts.public')

@section('title', 'Vote in UN Brahma College Election')
@section('header', 'Cast Your Votes')

@section('content')
  <div class="py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <!-- Portfolios Grid -->
      <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($portfolios as $portfolio)
          <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-xl" data-aos="fade-up"
            data-aos-delay="{{ $loop->index * 100 }}">
            <!-- Portfolio Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
              <h3 class="text-2xl font-bold text-white">{{ $portfolio->name }}</h3>
              <p class="mt-2 text-blue-100">
                {{ $portfolio->candidates->count() }} Candidate(s)
              </p>
            </div>

            <!-- Candidates List -->
            <div class="divide-y divide-gray-200">
              @foreach ($portfolio->candidates as $candidate)
                <div class="flex items-center justify-between p-6 transition-colors duration-200 hover:bg-gray-50">
                  <div class="flex items-center space-x-4">
                    <!-- Candidate Photo -->
                    <div class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-full bg-gray-100">
                      @if ($candidate->photo_path)
                        <img src="{{ asset('storage/' . $candidate->photo_path) }}" alt="{{ $candidate->name }}"
                          class="h-full w-full object-cover">
                      @else
                        <i class="fas fa-user text-3xl text-gray-400"></i>
                      @endif
                    </div>

                    <!-- Candidate Info -->
                    <div>
                      <h4 class="font-semibold text-gray-900">{{ $candidate->name }}</h4>
                      <p class="text-sm text-gray-500">{{ $candidate->votes_count }} votes</p>
                    </div>
                  </div>

                  <!-- Vote Button -->
                  <a href="{{ route('candidates.show', $candidate) }}"
                    class="inline-flex items-center rounded-xl border border-blue-300 bg-blue-50 px-4 py-2 font-semibold text-blue-600 transition-colors duration-200 hover:bg-blue-100">
                    <i class="fas fa-vote-yea mr-2"></i>
                    Vote
                  </a>
                </div>
              @endforeach
            </div>

            <!-- Portfolio Footer -->
            <div class="bg-gray-50 px-6 py-4">
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">
                  @if ($portfolio->voted)
                    <i class="fas fa-check-circle mr-2 text-green-500"></i>
                    Voted
                  @else
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Not voted yet
                  @endif
                </span>
                <span class="text-sm font-medium text-gray-900">
                  Total Votes: {{ $portfolio->total_votes }}
                </span>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Instructions -->
      <div class="mt-12 rounded-3xl border border-gray-100 bg-white p-8 shadow-xl" data-aos="fade-up">
        <div class="mb-6 flex items-center space-x-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
            <i class="fas fa-info-circle text-2xl text-blue-600"></i>
          </div>
          <h3 class="text-2xl font-bold text-gray-900">Voting Instructions</h3>
        </div>

        <div class="space-y-4 text-gray-600">
          <p class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-500"></i>
            You can vote for one candidate in each portfolio
          </p>
          <p class="flex items-center">
            <i class="fas fa-mobile-alt mr-3 text-blue-500"></i>
            Verify your identity using your mobile number
          </p>
          <p class="flex items-center">
            <i class="fas fa-shield-alt mr-3 text-yellow-500"></i>
            Each vote is secured and counted anonymously
          </p>
          <p class="flex items-center">
            <i class="fas fa-clock mr-3 text-red-500"></i>
            Voting closes at {{ $election_end_time }}
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection
