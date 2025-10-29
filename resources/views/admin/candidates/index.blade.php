@extends('layouts.admin')

@section('title', 'Manage Candidates - Admin Panel')
@section('header', 'Manage Candidates')

@section('content')
  <div class="px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="mb-6 sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <p class="mt-2 text-sm text-gray-700">A list of all candidates in the election.</p>
      </div>
      <div class="mt-4 space-x-3 sm:ml-16 sm:mt-0 sm:flex-none">
        <form method="GET" action="{{ route('admin.candidates.index') }}" class="inline">
          <input type="hidden" name="export" value="xlsx">
          <button type="submit"
            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
            <i class="fas fa-file-excel mr-2 text-green-600"></i>
            Export Excel
          </button>
        </form>
        <a href="{{ route('admin.candidates.create') }}"
          class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
          <i class="fas fa-plus mr-2"></i>
          Add Candidate
        </a>
      </div>
    </div>

    <!-- Filters Section -->
    <div class="mb-6 overflow-hidden rounded-lg bg-white shadow">
      <form method="GET" class="grid gap-4 p-4 sm:p-6 md:grid-cols-2 lg:grid-cols-4">
        <!-- Portfolio Filter -->
        <div>
          <label for="portfolio" class="block text-sm font-medium text-gray-700">Portfolio</label>
          <select name="portfolio" id="portfolio"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <option value="">All Portfolios</option>
            @foreach ($portfolios as $portfolio)
              <option value="{{ $portfolio->id }}" {{ request('portfolio') == $portfolio->id ? 'selected' : '' }}>
                {{ $portfolio->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Department Filter -->
        @if ($departments->isNotEmpty())
          <div>
            <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
            <select name="department" id="department"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              <option value="">All Departments</option>
              @foreach ($departments as $department)
                <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>
                  {{ $department }}
                </option>
              @endforeach
            </select>
          </div>
        @endif

        <!-- Year Filter -->
        @if ($years->isNotEmpty())
          <div>
            <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
            <select name="year" id="year"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              <option value="">All Years</option>
              @foreach ($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                  {{ $year }}
                </option>
              @endforeach
            </select>
          </div>
        @endif

        <!-- Status Filter -->
        <div>
          <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
          <select name="status" id="status"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>

        <!-- Filter Actions -->
        <div class="flex items-center justify-end space-x-3 lg:col-span-4">
          <a href="{{ route('admin.candidates.index') }}"
            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="fas fa-undo mr-2"></i>
            Reset
          </a>
          <button type="submit"
            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="fas fa-filter mr-2"></i>
            Apply Filters
          </button>
        </div>
      </form>
    </div>

    <!-- Results Info -->
    <div class="mb-4 flex items-center justify-between text-sm text-gray-700">
      <div>
        Showing <span class="font-medium">{{ $candidates->count() }}</span> of <span
          class="font-medium">{{ $candidates->total() }}</span> candidates
        @if (request()->hasAny(['portfolio', 'department', 'year', 'status']))
          with current filters
        @endif
      </div>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Candidate</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Position</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Department</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Votes</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          @forelse($candidates as $candidate)
            <tr>
              <td class="whitespace-nowrap px-6 py-4">
                <div class="flex items-center">
                  @if ($candidate->photo)
                    <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}"
                      class="h-10 w-10 rounded-full object-cover">
                  @else
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200">
                      <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path
                          d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                      </svg>
                    </div>
                  @endif
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ $candidate->name }}</div>
                    <div class="text-sm text-gray-500">{{ $candidate->year }}</div>
                  </div>
                </div>
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $candidate->position ?? '-' }}</td>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $candidate->department ?? '-' }}</td>
              <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-indigo-600">{{ $candidate->votes_count }}
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                @if ($candidate->is_active)
                  <span
                    class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">Active</span>
                @else
                  <span
                    class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">Inactive</span>
                @endif
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                <a href="{{ route('admin.candidates.edit', $candidate) }}"
                  class="mr-3 text-indigo-600 hover:text-indigo-900">Edit</a>
                <form action="{{ route('admin.candidates.destroy', $candidate) }}" method="POST" class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this candidate?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No candidates found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $candidates->links() }}
    </div>
  </div>
@endsection
