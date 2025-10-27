@extends('layouts.admin')

@section('title', $candidate->name . ' - Admin Panel')
@section('header', $candidate->name)

@section('content')
  <div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
      <a href="{{ route('admin.candidates.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
        ← Back to Candidates
      </a>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow">
      <div class="p-6">
        <div class="mb-6 flex items-start justify-between">
          <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $candidate->name }}</h2>
            @if ($candidate->position)
              <p class="mt-1 text-lg text-indigo-600">{{ $candidate->position }}</p>
            @endif
          </div>
          <div class="flex gap-2">
            <a href="{{ route('admin.candidates.edit', $candidate) }}"
              class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:bg-gray-50">
              Edit
            </a>
          </div>
        </div>

        @if ($candidate->photo)
          <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}"
            class="mb-6 h-64 w-64 rounded-lg object-cover">
        @endif

        <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <h3 class="text-sm font-medium text-gray-500">Department</h3>
            <p class="mt-1 text-sm text-gray-900">{{ $candidate->department ?? '-' }}</p>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Year</h3>
            <p class="mt-1 text-sm text-gray-900">{{ $candidate->year ?? '-' }}</p>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Status</h3>
            <p class="mt-1">
              @if ($candidate->is_active)
                <span
                  class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">Active</span>
              @else
                <span
                  class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">Inactive</span>
              @endif
            </p>
          </div>
          <div>
            <h3 class="text-sm font-medium text-gray-500">Total Votes</h3>
            <p class="mt-1 text-2xl font-bold text-indigo-600">{{ $candidate->votes_count }}</p>
          </div>
        </div>

        @if ($candidate->bio)
          <div class="mb-6">
            <h3 class="mb-2 text-sm font-medium text-gray-500">Bio</h3>
            <p class="text-sm text-gray-900">{{ $candidate->bio }}</p>
          </div>
        @endif

        @if ($candidate->manifesto)
          <div>
            <h3 class="mb-2 text-sm font-medium text-gray-500">Manifesto</h3>
            <div class="whitespace-pre-line text-sm text-gray-900">{{ $candidate->manifesto }}</div>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
