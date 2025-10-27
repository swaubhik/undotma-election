@extends('layouts.admin')

@section('title', 'Voters List - Admin Panel')
@section('header', 'Voters List')

@section('content')
  <div class="px-4 sm:px-6 lg:px-8">
    <div class="overflow-hidden rounded-lg bg-white shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Mobile Number</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Voted For</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Voted At</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          @forelse($voters as $voter)
            <tr>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                {{ substr($voter->voter_mobile, 0, -4) . '****' }}
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                {{ $voter->candidate->name ?? 'N/A' }}
              </td>
              <td class="whitespace-nowrap px-6 py-4">
                @if ($voter->verified)
                  <span
                    class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">Verified</span>
                @else
                  <span
                    class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">Pending</span>
                @endif
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                {{ $voter->created_at->format('M d, Y H:i') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No voters found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $voters->links() }}
    </div>
  </div>
@endsection
