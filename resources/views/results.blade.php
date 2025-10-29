@extends('layouts.public')

@section('title', 'Live Results - UN Brahma College Election')
@section('header', 'Live Election Results')

@section('content')
  <div class="px-4 py-12 sm:px-6 lg:px-8">
    <!-- Stats Overview -->
    <div class="mb-12 grid gap-8 md:grid-cols-3">
      <!-- Total Votes Card -->
      <div class="card-hover rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50 to-indigo-50 p-8 shadow-lg"
        data-aos="fade-up">
        <div class="flex items-center justify-between">
          <div>
            <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-gray-600">Total Votes Cast</p>
            <p class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-5xl font-black text-transparent"
              id="total-votes">
              {{ $totalVotes }}
            </p>
          </div>
          <div
            class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg">
            <i class="fas fa-check-circle text-3xl text-white"></i>
          </div>
        </div>
      </div>

      <!-- Participation Card -->
      <div
        class="card-hover rounded-3xl border border-purple-100 bg-gradient-to-br from-purple-50 to-pink-50 p-8 shadow-lg"
        data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center justify-between">
          <div>
            <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-gray-600">Eligible Voters</p>
            <p class="bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-5xl font-black text-transparent">
              {{ \App\Models\User::where('role', 'voter')->count() }}
            </p>
          </div>
          <div
            class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-purple-500 to-pink-600 shadow-lg">
            <i class="fas fa-users text-3xl text-white"></i>
          </div>
        </div>
      </div>

      <!-- Candidates Card -->
      <div
        class="card-hover rounded-3xl border border-green-100 bg-gradient-to-br from-green-50 to-emerald-50 p-8 shadow-lg"
        data-aos="fade-up" data-aos-delay="200">
        <div class="flex items-center justify-between">
          <div>
            <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-gray-600">Total Candidates</p>
            <p class="bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-5xl font-black text-transparent">
              {{ \App\Models\Candidate::count() }}
            </p>
          </div>
          <div
            class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-emerald-600 shadow-lg">
            <i class="fas fa-user-tie text-3xl text-white"></i>
          </div>
        </div>
      </div>
    </div>

    @if ($portfolios->isNotEmpty())
      @foreach ($portfolios as $portfolio)
        @if ($portfolio->candidates->isNotEmpty())
          <!-- Portfolio Section -->
          <div class="mb-16 overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-lg" data-aos="fade-up">
            <div class="border-b bg-gradient-to-r from-gray-50 to-white p-8">
              <h3 class="text-3xl font-bold text-gray-900">{{ $portfolio->name }}</h3>
              <p class="mt-2 text-gray-600">{{ $portfolio->candidates->count() }} Candidates</p>
            </div>

            <!-- Charts Section -->
            <div class="grid gap-8 p-8 lg:grid-cols-2">
              <!-- Pie Chart -->
              <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-lg" data-aos="fade-up">
                <h3 class="mb-6 flex items-center space-x-3 text-2xl font-bold text-gray-900">
                  <i class="fas fa-chart-pie text-blue-600"></i>
                  <span>Vote Distribution</span>
                </h3>
                <div id="voteDistributionChart-{{ $portfolio->id }}"></div>
              </div>

              <!-- Bar Chart -->
              <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-lg" data-aos="fade-up"
                data-aos-delay="100">
                <h3 class="mb-6 flex items-center space-x-3 text-2xl font-bold text-gray-900">
                  <i class="fas fa-chart-bar text-indigo-600"></i>
                  <span>Candidate Standings</span>
                </h3>
                <div id="candidateStandingsChart-{{ $portfolio->id }}"></div>
              </div>
            </div>

            <!-- Detailed Rankings -->
            <div class="border-t bg-gradient-to-r from-gray-50 to-white p-8">
              <h4 class="mb-8 flex items-center space-x-3 text-2xl font-bold text-gray-900">
                <i class="fas fa-list text-purple-600"></i>
                <span>Detailed Rankings</span>
              </h4>

              <div class="space-y-4">
                @foreach ($portfolio->candidates as $index => $candidate)
                  <div
                    class="candidate-row rounded-2xl border-l-4 bg-white p-6 transition-all duration-300 hover:shadow-lg"
                    data-candidate-id="{{ $candidate->id }}" data-portfolio-id="{{ $portfolio->id }}"
                    style="border-left-color: hsl({{ ($index * 360) / $portfolio->candidates->count() }}, 100%, 50%)">

                    <div class="flex items-center justify-between">
                      <!-- Rank and Name -->
                      <div class="flex items-center space-x-4">
                        @if ($index === 0)
                          <span
                            class="inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-yellow-400 to-yellow-500 text-lg font-bold text-white shadow-lg">
                            <i class="fas fa-crown"></i>
                          </span>
                        @elseif ($index === 1)
                          <span
                            class="inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-gray-400 to-gray-500 text-lg font-bold text-white shadow-lg">
                            2
                          </span>
                        @elseif ($index === 2)
                          <span
                            class="inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-orange-400 to-orange-500 text-lg font-bold text-white shadow-lg">
                            3
                          </span>
                        @else
                          <span
                            class="inline-flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 text-lg font-bold text-white shadow-lg">
                            {{ $index + 1 }}
                          </span>
                        @endif

                        <div class="flex items-center space-x-4">
                          @if ($candidate->photo_path)
                            <img src="{{ $candidate->photo_path }}" alt="{{ $candidate->name }}"
                              class="h-12 w-12 rounded-full object-cover shadow-md">
                          @endif
                          <div>
                            <h4 class="text-lg font-bold text-gray-900">{{ $candidate->name }}</h4>
                          </div>
                        </div>
                      </div>

                      <!-- Vote Stats -->
                      <div class="text-right">
                        <p
                          class="candidate-votes bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-3xl font-black text-transparent">
                          {{ $candidate->votes_count }}
                        </p>
                        @php
                          $portfolioVotes = $portfolio->candidates->sum('votes_count');
                          $percentage = $portfolioVotes > 0 ? ($candidate->votes_count / $portfolioVotes) * 100 : 0;
                        @endphp
                        <p class="text-sm text-gray-600">
                          <span class="candidate-percentage font-semibold">
                            {{ number_format($percentage, 1) }}
                          </span>%
                        </p>
                      </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-4 h-3 w-full overflow-hidden rounded-full bg-gray-200">
                      <div
                        class="candidate-progress h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-500"
                        style="width: {{ $percentage }}%">
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif
      @endforeach

      <!-- Live Update Indicator -->
      <div class="mt-8 flex items-center justify-center space-x-2 text-sm text-gray-600">
        <div class="h-2 w-2 animate-pulse rounded-full bg-green-500"></div>
        <span>Results update live every 5 seconds</span>
      </div>
    @else
      <div class="rounded-3xl bg-gradient-to-br from-gray-50 to-gray-100 py-20 text-center" data-aos="fade-up">
        <i class="fas fa-inbox mb-6 block text-8xl text-gray-300"></i>
        <h3 class="mb-2 text-2xl font-bold text-gray-600">No Results Available Yet</h3>
        <p class="text-lg text-gray-500">Voting is in progress. Results will appear here soon.</p>
      </div>
    @endif
  </div>

  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <!-- AOS -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

  <script>
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true
    });

    const portfolioCharts = new Map(); // Store chart references by portfolio ID

    function initializePortfolioCharts(portfolio) {
      const candidateNames = portfolio.candidates.map(c => c.name);
      const candidateVotes = portfolio.candidates.map(c => c.votes_count);
      const portfolioVotes = candidateVotes.reduce((a, b) => a + b, 0);

      // Pie Chart
      const optionsPie = {
        chart: {
          type: 'donut',
          animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 800
          }
        },
        series: candidateVotes,
        labels: candidateNames,
        colors: [
          '#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#06b6d4', '#6366f1', '#ef4444'
        ],
        plotOptions: {
          pie: {
            donut: {
              size: '75%',
              labels: {
                show: true,
                name: {
                  show: true,
                  fontSize: '16px',
                  fontWeight: 600,
                  color: '#1f2937'
                },
                value: {
                  show: true,
                  fontSize: '24px',
                  fontWeight: 700,
                  color: '#1f2937',
                  formatter: function(val) {
                    return portfolioVotes > 0 ? Math.round((val / portfolioVotes) * 100) + '%' : '0%';
                  }
                }
              }
            }
          }
        },
        legend: {
          position: 'bottom',
          fontSize: '14px',
          fontWeight: 500,
          labels: {
            colors: '#374151'
          }
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            }
          }
        }]
      };

      // Bar Chart
      const optionsBar = {
        chart: {
          type: 'bar',
          height: 400,
          animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 800
          },
          toolbar: {
            show: false
          }
        },
        series: [{
          name: 'Votes',
          data: candidateVotes,
          color: '#3b82f6'
        }],
        xaxis: {
          categories: candidateNames,
          labels: {
            style: {
              fontSize: '13px',
              fontWeight: 500,
              colors: '#6b7280'
            },
            rotate: -45,
            trim: true
          }
        },
        yaxis: {
          title: {
            text: 'Number of Votes',
            style: {
              fontSize: '14px',
              fontWeight: 600,
              color: '#1f2937'
            }
          },
          labels: {
            style: {
              fontSize: '13px',
              colors: '#6b7280'
            }
          }
        },
        colors: ['#3b82f6'],
        plotOptions: {
          bar: {
            borderRadius: 8,
            dataLabels: {
              position: 'top',
            },
            columnWidth: '60%'
          }
        },
        dataLabels: {
          enabled: true,
          offsetY: -20,
          style: {
            fontSize: '13px',
            fontWeight: 700,
            colors: ['#3b82f6']
          }
        },
        responsive: [{
          breakpoint: 768,
          options: {
            chart: {
              height: 300
            }
          }
        }]
      };

      const pieChart = new ApexCharts(document.querySelector(`#voteDistributionChart-${portfolio.id}`), optionsPie);
      const barChart = new ApexCharts(document.querySelector(`#candidateStandingsChart-${portfolio.id}`), optionsBar);

      pieChart.render();
      barChart.render();

      portfolioCharts.set(portfolio.id, {
        pie: pieChart,
        bar: barChart
      });
    }

    // Initialize charts for each portfolio
    document.addEventListener('DOMContentLoaded', () => {
      const portfolios = @json($portfolios);
      portfolios.forEach(portfolio => {
        if (portfolio.candidates.length > 0) {
          initializePortfolioCharts(portfolio);
        }
      });
    });

    // Poll for live results every 5 seconds
    function updateResults() {
      fetch('{{ route('live-results') }}')
        .then(response => response.json())
        .then(data => {
          document.getElementById('total-votes').textContent = data.totalVotes;

          data.portfolios.forEach(portfolio => {
            const charts = portfolioCharts.get(portfolio.id);
            const portfolioVotes = portfolio.candidates.reduce((sum, c) => sum + c.votes, 0);

            // Update candidate rows
            portfolio.candidates.forEach(candidate => {
              const candidateRow = document.querySelector(
                `[data-candidate-id="${candidate.id}"][data-portfolio-id="${portfolio.id}"]`);
              if (candidateRow) {
                const votesElement = candidateRow.querySelector('.candidate-votes');
                const progressBar = candidateRow.querySelector('.candidate-progress');
                const percentageElement = candidateRow.querySelector('.candidate-percentage');

                votesElement.textContent = candidate.votes;

                const percentage = portfolioVotes > 0 ? (candidate.votes / portfolioVotes) * 100 : 0;
                progressBar.style.width = `${percentage}%`;
                percentageElement.textContent = percentage.toFixed(1);
              }
            });

            // Update charts
            if (charts) {
              const votes = portfolio.candidates.map(c => c.votes);
              charts.pie.updateSeries(votes);
              charts.bar.updateSeries([{
                data: votes
              }]);
            }
          });
        })
        .catch(error => console.error('Error fetching results:', error));
    }

    // Update results every 5 seconds
    setInterval(updateResults, 5000);
  </script>
@endsection
