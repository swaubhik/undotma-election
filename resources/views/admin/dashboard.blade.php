@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')
@section('header', 'Dashboard')

@section('content')
  <div class="px-4 py-8 sm:px-6 lg:px-8">
    <!-- Stats Cards -->
    <div class="mb-12 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
      <!-- Candidates Card -->
      <div
        class="stat-card rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 to-indigo-50 p-8 shadow-lg transition-shadow duration-300 hover:shadow-xl">
        <div class="flex items-center justify-between">
          <div>
            <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-gray-600">Total Candidates</p>
            <p class="text-4xl font-black text-blue-600">{{ $totalCandidates }}</p>
          </div>
          <div
            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg">
            <i class="fas fa-user-tie text-2xl text-white"></i>
          </div>
        </div>
      </div>

      <!-- Votes Card -->
      <div
        class="stat-card rounded-2xl border border-green-100 bg-gradient-to-br from-green-50 to-emerald-50 p-8 shadow-lg transition-shadow duration-300 hover:shadow-xl">
        <div class="flex items-center justify-between">
          <div>
            <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-gray-600">Total Votes</p>
            <p class="text-4xl font-black text-green-600" id="total-votes-dashboard">{{ $totalVotes }}</p>
          </div>
          <div
            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 shadow-lg">
            <i class="fas fa-check-circle text-2xl text-white"></i>
          </div>
        </div>
      </div>

      <!-- Voters Card -->
      <div
        class="stat-card rounded-2xl border border-purple-100 bg-gradient-to-br from-purple-50 to-pink-50 p-8 shadow-lg transition-shadow duration-300 hover:shadow-xl">
        <div class="flex items-center justify-between">
          <div>
            <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-gray-600">Total Voters</p>
            <p class="text-4xl font-black text-purple-600">{{ $totalVoters }}</p>
          </div>
          <div
            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 shadow-lg">
            <i class="fas fa-users text-2xl text-white"></i>
          </div>
        </div>
      </div>

      <!-- Participation Card -->
      <div
        class="stat-card rounded-2xl border border-yellow-100 bg-gradient-to-br from-yellow-50 to-orange-50 p-8 shadow-lg transition-shadow duration-300 hover:shadow-xl">
        <div class="flex items-center justify-between">
          <div>
            <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-gray-600">Participation</p>
            <p class="text-4xl font-black text-yellow-600">
              {{ $totalVoters > 0 ? number_format(($totalVotes / $totalVoters) * 100, 1) : 0 }}%</p>
          </div>
          <div
            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-yellow-500 to-orange-600 shadow-lg">
            <i class="fas fa-chart-pie text-2xl text-white"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="mb-12 grid gap-8 lg:grid-cols-2">
      <!-- Vote Distribution Pie Chart -->
      <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-lg">
        <h3 class="mb-6 flex items-center space-x-3 text-xl font-bold text-gray-900">
          <i class="fas fa-chart-pie text-blue-600"></i>
          <span>Vote Distribution</span>
        </h3>
        <div id="voteDistributionChart"></div>
      </div>

      <!-- Candidate Standings Bar Chart -->
      <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-lg">
        <h3 class="mb-6 flex items-center space-x-3 text-xl font-bold text-gray-900">
          <i class="fas fa-chart-bar text-indigo-600"></i>
          <span>Candidate Standings</span>
        </h3>
        <div id="candidateStandingsChart"></div>
      </div>
    </div>

    <!-- Recent Votes Section -->
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-lg">
      <div class="border-b border-gray-200 p-8">
        <h2 class="flex items-center space-x-3 text-xl font-bold text-gray-900">
          <i class="fas fa-history text-green-600"></i>
          <span>Recent Votes</span>
        </h2>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <tr>
              <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Mobile</th>
              <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Candidate</th>
              <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Time</th>
              <th class="px-8 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            @forelse($recentVotes as $vote)
              <tr class="transition-colors duration-200 hover:bg-gray-50">
                <td class="px-8 py-4">
                  <span
                    class="inline-flex items-center space-x-2 rounded-full bg-blue-50 px-3 py-1 font-mono text-sm text-blue-700">
                    <i class="fas fa-phone text-blue-600"></i>
                    <span>{{ substr($vote->voter_mobile, 0, -4) . '****' }}</span>
                  </span>
                </td>
                <td class="px-8 py-4">
                  <div class="flex items-center space-x-3">
                    <div
                      class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600">
                      <i class="fas fa-user text-sm text-white"></i>
                    </div>
                    <div>
                      <p class="font-semibold text-gray-900">{{ $vote->candidate->name }}</p>
                      @if ($vote->candidate->position)
                        <p class="text-xs text-gray-500">{{ $vote->candidate->position }}</p>
                      @endif
                    </div>
                  </div>
                </td>
                <td class="px-8 py-4">
                  <span class="inline-flex items-center space-x-2 text-sm text-gray-600">
                    <i class="fas fa-clock text-gray-400"></i>
                    <span>{{ $vote->created_at->diffForHumans() }}</span>
                  </span>
                </td>
                <td class="px-8 py-4">
                  <span
                    class="inline-flex items-center space-x-2 rounded-full bg-green-100 px-4 py-2 text-sm font-medium text-green-700">
                    <i class="fas fa-check-circle"></i>
                    <span>Verified</span>
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-8 py-12 text-center">
                  <div>
                    <i class="fas fa-inbox mb-4 block text-4xl text-gray-300"></i>
                    <p class="font-medium text-gray-600">No votes yet</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  <script>
    let chartsPieChart, chartsBarChart;

    function initializeCharts() {
      const candidates = @json($candidatesWithVotes);
      const candidateNames = candidates.map(c => c.name);
      const candidateVotes = candidates.map(c => c.votes_count);

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
                    return val + '%';
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

      chartsPieChart = new ApexCharts(document.querySelector('#voteDistributionChart'), optionsPie);
      chartsPieChart.render();

      // Bar Chart
      const optionsBar = {
        chart: {
          type: 'bar',
          height: 350,
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
            }
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

      chartsBarChart = new ApexCharts(document.querySelector('#candidateStandingsChart'), optionsBar);
      chartsBarChart.render();
    }

    // Initialize charts on page load
    document.addEventListener('DOMContentLoaded', initializeCharts);

    // Update charts every 5 seconds
    setInterval(function() {
      fetch('{{ route('live-results') }}')
        .then(response => response.json())
        .then(data => {
          document.getElementById('total-votes-dashboard').textContent = data.totalVotes;

          if (chartsPieChart) {
            const newVotes = data.candidates.map(c => c.votes);
            chartsPieChart.updateSeries(newVotes);
          }

          if (chartsBarChart) {
            const newVotes = data.candidates.map(c => c.votes);
            chartsBarChart.updateSeries([{
              data: newVotes
            }]);
          }
        })
        .catch(error => console.error('Error fetching results:', error));
    }, 5000);
  </script>
@endsection
