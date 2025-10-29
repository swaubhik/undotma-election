<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCandidateRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Models\Candidate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CandidateController extends Controller
{
    public function index(): View|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $query = Candidate::query()
            ->with('portfolio')
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

        // Filter by status
        if (request()->filled('status')) {
            $query->where('is_active', request('status') === 'active');
        }

        // Handle export request
        if (request('export') === 'xlsx') {
            return (new \App\Exports\CandidatesExport($query))
                ->download('candidates-' . now()->format('Y-m-d') . '.xlsx');
        }

        $candidates = $query->latest()->paginate(10);

        // Get filter options
        $portfolios = \App\Models\Portfolio::orderBy('name')->get();
        $departments = Candidate::distinct()->pluck('department')->filter();
        $years = Candidate::distinct()->pluck('year')->filter();

        return view('admin.candidates.index', compact(
            'candidates',
            'portfolios',
            'departments',
            'years'
        ));
    }

    public function create(): View
    {
        // Load all portfolios for the dropdown selection
        $portfolios = \App\Models\Portfolio::orderBy('name')->get();
        return view('admin.candidates.create', compact('portfolios'));
    }

    public function store(StoreCandidateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle photo upload if present in the validated data
        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
            $data['photo'] = $data['photo']->store('candidates', 'public');
        }

        Candidate::create($data);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate created successfully.');
    }

    public function show(Candidate $candidate): View
    {
        $candidate->loadCount('votes');

        return view('admin.candidates.show', compact('candidate'));
    }

    public function edit(Candidate $candidate): View
    {
        // Load all portfolios for the dropdown selection
        $portfolios = \App\Models\Portfolio::orderBy('name')->get();
        return view('admin.candidates.edit', compact('candidate', 'portfolios'));
    }

    public function update(UpdateCandidateRequest $request, Candidate $candidate): RedirectResponse
    {
        $data = $request->validated();

        // Handle photo upload if present in the validated data
        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
            if ($candidate->photo) {
                Storage::disk('public')->delete($candidate->photo);
            }
            $data['photo'] = $data['photo']->store('candidates', 'public');
        }

        $candidate->update($data);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate updated successfully.');
    }

    public function destroy(Candidate $candidate): RedirectResponse
    {
        if ($candidate->photo) {
            Storage::disk('public')->delete($candidate->photo);
        }

        $candidate->delete();

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate deleted successfully.');
    }
}
