<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\View\View;

class CandidateController extends Controller
{
    public function index(): View
    {
        $candidates = Candidate::query()
            ->where('is_active', true)
            ->withCount('votes')
            ->get();

        return view('candidates.index', compact('candidates'));
    }

    public function show(Candidate $candidate): View
    {
        $candidate->loadCount('votes');

        return view('candidates.show', compact('candidate'));
    }
}
