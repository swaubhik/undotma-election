<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\View\View;

class CandidateController extends Controller
{
    public function index(): View
    {
        $query = Candidate::query()
            ->with('portfolio')
            ->where('is_active', true)
            ->withCount('votes');

        // Filter by portfolio
        if (request()->filled('portfolio')) {
            $query->where('portfolio_id', request('portfolio'));
        }

        // Filter by department
        if (request()->filled('department')) {
            $query->where('department', request('department'));
        }

        // Filter by year
        if (request()->filled('year')) {
            $query->where('year', request('year'));
        }

        // Sort candidates
        $sort = request('sort', 'votes');
        $direction = request('direction', 'desc');

        if ($sort === 'votes') {
            $query->orderBy('votes_count', $direction);
        } elseif ($sort === 'name') {
            $query->orderBy('name', $direction);
        }

        $candidates = $query->get();

        // Get unique values for filters
        $departments = Candidate::where('is_active', true)
            ->distinct()
            ->pluck('department')
            ->filter();

        $years = Candidate::where('is_active', true)
            ->distinct()
            ->pluck('year')
            ->filter();

        $portfolios = \App\Models\Portfolio::has('candidates')
            ->withCount(['candidates' => fn($q) => $q->where('is_active', true)])
            ->orderBy('name')
            ->get();

        return view('candidates.index', compact(
            'candidates',
            'departments',
            'years',
            'portfolios',
            'sort',
            'direction'
        ));
    }

    public function show(Candidate $candidate): View
    {
        $candidate->loadCount('votes');

        return view('candidates.show', compact('candidate'));
    }
}
