@extends('layouts.admin')

@section('title', 'Edit Candidate - Admin Panel')
@section('header', 'Edit Candidate: ' . $candidate->name)

@section('content')
  <div class="px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
      <div class="rounded-lg bg-white p-6 shadow">
        <form action="{{ route('admin.candidates.update', $candidate) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="space-y-6">
            <!-- Portfolio Selection -->
            <div>
              <label for="portfolio_id" class="block text-sm font-medium text-gray-700">Portfolio *</label>
              <select name="portfolio_id" id="portfolio_id" required
                class="@error('portfolio_id') border-red-300 @else border-gray-300 @enderror mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">Select Portfolio</option>
                @foreach ($portfolios as $portfolio)
                  <option value="{{ $portfolio->id }}"
                    {{ old('portfolio_id', $candidate->portfolio_id) == $portfolio->id ? 'selected' : '' }}>
                    {{ $portfolio->name }}
                  </option>
                @endforeach
              </select>
              @error('portfolio_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
              <input type="text" name="name" id="name" value="{{ old('name', $candidate->name) }}" required
                class="@error('name') border-red-300 @else border-gray-300 @enderror mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
              <input type="text" name="position" id="position" value="{{ old('position', $candidate->position) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                <input type="text" name="department" id="department"
                  value="{{ old('department', $candidate->department) }}"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              </div>

              <div>
                <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                <select name="year" id="year"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                  <option value="">Select Year</option>
                  <option value="1st Year" {{ old('year', $candidate->year) == '1st Year' ? 'selected' : '' }}>1st Year
                  </option>
                  <option value="2nd Year" {{ old('year', $candidate->year) == '2nd Year' ? 'selected' : '' }}>2nd Year
                  </option>
                  <option value="3rd Year" {{ old('year', $candidate->year) == '3rd Year' ? 'selected' : '' }}>3rd Year
                  </option>
                  <option value="4th Year" {{ old('year', $candidate->year) == '4th Year' ? 'selected' : '' }}>4th Year
                  </option>
                </select>
              </div>
            </div>

            <div>
              <label for="photo" class="block text-sm font-medium text-gray-700">Photo</label>
              @if ($candidate->photo)
                <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}"
                  class="mt-2 h-32 w-32 rounded-lg object-cover">
                <p class="mt-2 text-sm text-gray-500">Current photo. Upload a new one to replace it.</p>
              @endif
              <input type="file" name="photo" id="photo" accept="image/*"
                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
              <p class="mt-1 text-xs text-gray-500">Maximum file size: 2MB</p>
            </div>

            <div>
              <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
              <textarea name="bio" id="bio" rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('bio', $candidate->bio) }}</textarea>
            </div>

            <div>
              <label for="manifesto" class="block text-sm font-medium text-gray-700">Manifesto</label>
              <textarea name="manifesto" id="manifesto" rows="6"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('manifesto', $candidate->manifesto) }}</textarea>
            </div>

            <div class="flex items-center">
              <input type="checkbox" name="is_active" id="is_active" value="1"
                {{ old('is_active', $candidate->is_active) ? 'checked' : '' }}
                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
              <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
            </div>
          </div>

          <div class="mt-6 flex items-center justify-end gap-x-3">
            <a href="{{ route('admin.candidates.index') }}"
              class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              Cancel
            </a>
            <button type="submit"
              class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Update Candidate
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
