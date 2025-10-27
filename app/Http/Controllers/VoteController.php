<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\OtpVerification;
use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VoteController extends Controller
{
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'mobile' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'candidate_id' => ['required', 'exists:candidates,id'],
        ]);

        if (Vote::where('voter_mobile', $request->mobile)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already voted.',
            ], 400);
        }

        // Generate OTP for local tracking (optional backup)
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(10);

        OtpVerification::updateOrCreate(
            ['mobile' => $request->mobile],
            [
                'otp' => $otp,
                'expires_at' => $expiresAt,
                'verified' => false,
            ]
        );

        // Send OTP via MSG91 - returns widget configuration
        $widgetConfig = $this->sendOTPViaMSG91($request->mobile, $otp);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully to your mobile number.',
            'widgetConfig' => $widgetConfig,
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'mobile' => ['required', 'string'],
            'token' => ['required', 'string'],
            'candidate_id' => ['required', 'exists:candidates,id'],
        ]);

        if (Vote::where('voter_mobile', $request->mobile)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already voted.',
            ], 400);
        }

        // Verify token with MSG91 server
        $verificationResult = $this->verifyMSG91Token($request->token);

        if (! $verificationResult['success']) {
            return response()->json([
                'success' => false,
                'message' => $verificationResult['message'] ?? 'Token verification failed.',
            ], 400);
        }

        DB::transaction(function () use ($request, $verificationResult) {
            Vote::create([
                'candidate_id' => $request->candidate_id,
                'voter_mobile' => $request->mobile,
                'verified' => true,
            ]);

            // Mark any existing OTP records as verified
            OtpVerification::where('mobile', $request->mobile)
                ->update(['verified' => true]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Your vote has been recorded successfully!',
            'redirect' => route('results'),
        ]);
    }

    public function results(): View
    {
        $candidates = Candidate::query()
            ->where('is_active', true)
            ->withCount(['votes' => fn($query) => $query->where('verified', true)])
            ->orderBy('votes_count', 'desc')
            ->get();

        $totalVotes = Vote::where('verified', true)->count();

        return view('results', compact('candidates', 'totalVotes'));
    }

    public function liveResults(): JsonResponse
    {
        $candidates = Candidate::query()
            ->where('is_active', true)
            ->withCount(['votes' => fn($query) => $query->where('verified', true)])
            ->orderBy('votes_count', 'desc')
            ->get(['id', 'name', 'photo'])
            ->map(fn($candidate) => [
                'id' => $candidate->id,
                'name' => $candidate->name,
                'photo' => $candidate->photo,
                'votes' => $candidate->votes_count,
            ]);

        $totalVotes = Vote::where('verified', true)->count();

        return response()->json([
            'candidates' => $candidates,
            'totalVotes' => $totalVotes,
        ]);
    }

    protected function sendOTPViaMSG91(string $mobile, string $otp): array
    {
        // Format mobile with country code for MSG91
        $mobileWithCC = '91' . $mobile;

        // Initialize widget data - in production, fetch from MSG91 API
        return [
            'widgetId' => config('services.msg91.widget_id'),
            'identifier' => $mobileWithCC,
            'exposeMethods' => true,
        ];
    }

    protected function verifyMSG91Token(string $token): array
    {
        $authKey = config('services.msg91.auth_key');
        $accessToken = config('services.msg91.access_token');

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://control.msg91.com/api/v5/widget/verifyAccessToken',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'authkey' => $authKey,
                'access-token' => $token,
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $result = json_decode($response, true);

        if ($httpCode === 200 && ! empty($result)) {
            return [
                'success' => true,
                'data' => $result,
            ];
        }

        return [
            'success' => false,
            'message' => 'Token verification failed with MSG91.',
        ];
    }
}
