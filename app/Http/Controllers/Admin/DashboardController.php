<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalCandidates = Candidate::count();
        $totalVotes = Vote::where('verified', true)->count();
        $totalVoters = Vote::distinct('voter_mobile')->count('voter_mobile');

        $candidatesWithVotes = Candidate::query()
            ->withCount(['votes' => fn($query) => $query->where('verified', true)])
            ->orderBy('votes_count', 'desc')
            ->get();

        $recentVotes = Vote::query()
            ->with('candidate')
            ->where('verified', true)
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalCandidates',
            'totalVotes',
            'totalVoters',
            'candidatesWithVotes',
            'recentVotes'
        ));
    }

    public function voters(): View
    {
        $voters = Vote::query()
            ->with('candidate')
            ->select('voter_mobile', 'verified', 'created_at')
            ->latest()
            ->paginate(20);
        return view('admin.voters', compact('voters'));
    }
}
