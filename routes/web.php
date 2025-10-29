<?php

use App\Http\Controllers\Admin\CandidateController as AdminCandidateController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/vote', [PortfolioController::class, 'index'])->name('vote.index');
Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
Route::get('/candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');

Route::post('/vote/send-otp', [VoteController::class, 'sendOtp'])->name('vote.send-otp');
Route::post('/vote/verify-otp', [VoteController::class, 'verifyOtp'])->name('vote.verify-otp');

Route::get('/results', [VoteController::class, 'results'])->name('results');
Route::get('/api/live-results', [VoteController::class, 'liveResults'])->name('live-results');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/voters', [AdminDashboardController::class, 'voters'])->name('voters');
    Route::resource('candidates', AdminCandidateController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
