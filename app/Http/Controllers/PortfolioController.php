<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $portfolios = Portfolio::with(['candidates' => function ($query) {
            $query->withCount('votes');
        }])
            ->withCount('votes as total_votes')
            ->get()
            ->map(function ($portfolio) {
                $portfolio->voted = $portfolio->votes()
                    ->where('mobile', session('voter_mobile'))
                    ->exists();
                return $portfolio;
            });

        $election_end_time = config('app.election_end_time', '5:00 PM, October 31, 2025');

        return view('portfolios.index', compact('portfolios', 'election_end_time'));
    }
}
