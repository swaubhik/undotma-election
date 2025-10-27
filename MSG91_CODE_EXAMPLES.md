# MSG91 OTP Widget - Code Examples & Snippets

## Server-Side Examples

### 1. Basic OTP Sending (Backend)

```php
<?php

// In VoteController.php
public function sendOtp(Request $request): JsonResponse
{
    $request->validate([
        'mobile' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
        'candidate_id' => ['required', 'exists:candidates,id'],
    ]);

    // Check if already voted
    if (Vote::where('voter_mobile', $request->mobile)->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'You have already voted.',
        ], 400);
    }

    // Generate OTP
    $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // Store OTP with 10-minute expiry
    OtpVerification::updateOrCreate(
        ['mobile' => $request->mobile],
        [
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
            'verified' => false,
        ]
    );

    // Get widget config
    $widgetConfig = $this->sendOTPViaMSG91($request->mobile, $otp);

    return response()->json([
        'success' => true,
        'message' => 'OTP sent successfully!',
        'widgetConfig' => $widgetConfig,
    ]);
}
```

### 2. Token Verification (Backend)

```php
<?php

// In VoteController.php
protected function verifyMSG91Token(string $token): array
{
    $authKey = config('services.msg91.auth_key');
    $accessToken = config('services.msg91.access_token');

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://control.msg91.com/api/v5/widget/verifyAccessToken',
        CURLOPT_RETURNTRANSFER => true,
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

    if ($httpCode === 200 && !empty($result)) {
        return ['success' => true, 'data' => $result];
    }

    return [
        'success' => false,
        'message' => 'Token verification failed with MSG91.',
    ];
}
```

### 3. Vote Recording with Transaction

```php
<?php

// In VoteController.php
public function verifyOtp(Request $request): JsonResponse
{
    $request->validate([
        'mobile' => ['required', 'string'],
        'token' => ['required', 'string'],
        'candidate_id' => ['required', 'exists:candidates,id'],
    ]);

    // Verify token with MSG91
    $verificationResult = $this->verifyMSG91Token($request->token);

    if (!$verificationResult['success']) {
        return response()->json([
            'success' => false,
            'message' => 'Token verification failed.',
        ], 400);
    }

    // Record vote in transaction
    DB::transaction(function () use ($request, $verificationResult) {
        Vote::create([
            'candidate_id' => $request->candidate_id,
            'voter_mobile' => $request->mobile,
            'verified' => true,
        ]);

        // Mark OTP as verified
        OtpVerification::where('mobile', $request->mobile)
            ->update(['verified' => true]);
    });

    return response()->json([
        'success' => true,
        'message' => 'Your vote has been recorded successfully!',
        'redirect' => route('results'),
    ]);
}
```

---

## Client-Side Examples

### 1. Initialize OTP Widget

```javascript
// Configuration object
const msg91Configuration = {
    widgetId: "356a41657456333234313434", // Your widget ID
    tokenAuth: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...", // Token
    identifier: "919876543210", // Mobile with country code
    exposeMethods: true, // Enable exposed methods
    success: (data) => {
        console.log("OTP Widget Success:", data);
        // Handle successful verification
    },
    failure: (error) => {
        console.error("OTP Widget Error:", error);
        // Handle verification failure
    },
};

// Initialize widget when script loads
if (window.initSendOTP) {
    window.initSendOTP(msg91Configuration);
}
```

### 2. Send OTP via Widget

```javascript
// Trigger OTP sending
function initiateMSG91OTP() {
    const mobile = document.getElementById("voter-mobile").value;

    if (!mobile || mobile.length !== 10) {
        showMessage("Please enter valid 10-digit mobile", "error");
        return;
    }

    // Call backend to send OTP
    fetch("/vote/send-otp", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": getCsrfToken(),
        },
        body: JSON.stringify({
            mobile: mobile,
            candidate_id: currentCandidateId,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Initialize MSG91 widget
                const config = {
                    ...data.widgetConfig,
                    success: handleOtpSuccess,
                    failure: handleOtpFailure,
                };
                window.initSendOTP(config);

                // Show OTP input
                document
                    .getElementById("msg91-widget-container")
                    .classList.remove("hidden");
                showMessage("OTP sent successfully!", "success");
            } else {
                showMessage(data.message, "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showMessage("Failed to send OTP", "error");
        });
}
```

### 3. Verify OTP and Vote

```javascript
function verifyMSG91OTP() {
    const otp = document.getElementById("otp-input").value;

    if (!otp || otp.length !== 6) {
        showMessage("Please enter valid 6-digit OTP", "error");
        return;
    }

    // Call MSG91 widget's verify function
    if (!window.verifyOtp) {
        showMessage("OTP widget not available", "error");
        return;
    }

    window.verifyOtp(
        otp,
        (data) => {
            console.log("OTP verified:", data);
            const token = data.token || data.access_token;
            submitVote(token);
        },
        (error) => {
            console.error("Verification failed:", error);
            showMessage("Invalid OTP. Please try again.", "error");
        }
    );
}

function submitVote(token) {
    fetch("/vote/verify-otp", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": getCsrfToken(),
        },
        body: JSON.stringify({
            mobile: currentMobile,
            token: token,
            candidate_id: currentCandidateId,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showMessage(data.message, "success");
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                showMessage(data.message, "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showMessage("Failed to record vote", "error");
        });
}
```

### 4. Retry OTP

```javascript
function retryMSG91OTP() {
    if (!window.retryOtp) {
        showMessage("OTP widget not available", "error");
        return;
    }

    // Retry with default channel (null)
    // Or specify channel: '11' for SMS, '4' for Voice, '3' for Email
    window.retryOtp(
        null,
        (data) => {
            console.log("OTP Resent:", data);
            showMessage("OTP resent successfully!", "success");
        },
        (error) => {
            console.error("Retry Error:", error);
            showMessage("Failed to resend OTP", "error");
        }
    );
}
```

### 5. Reset Form

```javascript
function resetMSG91Form() {
    // Hide OTP container
    document.getElementById("msg91-widget-container").classList.add("hidden");

    // Clear inputs
    document.getElementById("voter-mobile").value = "";
    document.getElementById("otp-input").value = "";

    // Reset variables
    currentMobile = "";
    msg91VerificationToken = "";

    // Hide messages
    document.getElementById("message").classList.add("hidden");

    // Show mobile input section
    document.getElementById("mobile-input-section").classList.remove("hidden");
}
```

### 6. Helper Functions

```javascript
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').content;
}

function showMessage(text, type) {
    const messageDiv = document.getElementById("message");

    if (type === "success") {
        messageDiv.className =
            "mt-4 rounded-xl p-4 bg-green-50 border-2 border-green-200 flex items-start space-x-3";
        messageDiv.innerHTML = `
            <i class="fas fa-check-circle text-green-600 text-xl flex-shrink-0 mt-1"></i>
            <div>
                <p class="text-sm font-semibold text-green-900">${text}</p>
            </div>
        `;
    } else {
        messageDiv.className =
            "mt-4 rounded-xl p-4 bg-red-50 border-2 border-red-200 flex items-start space-x-3";
        messageDiv.innerHTML = `
            <i class="fas fa-exclamation-circle text-red-600 text-xl flex-shrink-0 mt-1"></i>
            <div>
                <p class="text-sm font-semibold text-red-900">${text}</p>
            </div>
        `;
    }

    messageDiv.classList.remove("hidden");
}

function getWidgetData() {
    if (window.getWidgetData) {
        return window.getWidgetData();
    }
    return null;
}
```

---

## HTML Structure

### Complete Voting Form

```html
<div id="mobile-input-section" class="space-y-4">
    <div>
        <label
            for="voter-mobile"
            class="mb-3 flex items-center space-x-2 text-sm font-semibold text-gray-700"
        >
            <i class="fas fa-mobile-alt text-blue-600"></i>
            <span>Mobile Number</span>
        </label>
        <input
            type="text"
            id="voter-mobile"
            maxlength="10"
            pattern="[0-9]{10}"
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-lg font-semibold"
            placeholder="Enter 10-digit mobile"
        />
    </div>

    <button
        type="button"
        onclick="initiateMSG91OTP()"
        class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 font-bold text-white"
    >
        <i class="fas fa-paper-plane"></i>
        Send OTP
    </button>
</div>

<!-- MSG91 Widget Container -->
<div id="msg91-widget-container" class="hidden space-y-4">
    <div
        class="flex items-center space-x-3 rounded-xl border-2 border-blue-200 bg-blue-50 p-4"
    >
        <i class="fas fa-check-circle text-2xl text-green-500"></i>
        <div>
            <p class="text-sm font-semibold text-gray-900">
                OTP sent successfully!
            </p>
            <p class="text-xs text-gray-600">
                Check your phone for the verification code
            </p>
        </div>
    </div>

    <div>
        <label
            for="otp-input"
            class="mb-3 flex items-center space-x-2 text-sm font-semibold text-gray-700"
        >
            <i class="fas fa-shield-check text-green-600"></i>
            <span>Enter OTP</span>
        </label>
        <input
            type="text"
            id="otp-input"
            maxlength="6"
            pattern="[0-9]{6}"
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-center text-lg font-semibold tracking-widest"
            placeholder="000000"
        />
    </div>

    <button
        type="button"
        onclick="verifyMSG91OTP()"
        class="w-full rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 font-bold text-white"
    >
        <i class="fas fa-check-double"></i>
        Verify & Vote
    </button>

    <button
        type="button"
        onclick="retryMSG91OTP()"
        class="w-full rounded-xl border-2 border-blue-300 bg-blue-50 px-6 py-3 font-bold text-blue-700"
    >
        <i class="fas fa-redo"></i>
        Resend OTP
    </button>

    <button
        type="button"
        onclick="resetMSG91Form()"
        class="w-full rounded-xl border-2 border-gray-300 bg-white px-6 py-3 font-bold text-gray-700"
    >
        <i class="fas fa-arrow-left"></i>
        Change Number
    </button>
</div>

<!-- Message Display -->
<div id="message" class="mt-4 hidden rounded-xl p-4"></div>
```

---

## API Payloads

### Request to Send OTP

```json
{
    "mobile": "9876543210",
    "candidate_id": 1
}
```

### Response from Send OTP

```json
{
    "success": true,
    "message": "OTP sent successfully to your mobile number.",
    "widgetConfig": {
        "widgetId": "356a41657456333234313434",
        "identifier": "919876543210",
        "exposeMethods": true
    }
}
```

### Request to Verify OTP

```json
{
    "mobile": "919876543210",
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIn0...",
    "candidate_id": 1
}
```

### Response from Verify OTP

```json
{
    "success": true,
    "message": "Your vote has been recorded successfully!",
    "redirect": "http://localhost:8000/results"
}
```

---

## cURL Examples

### Send OTP via Backend

```bash
curl -X POST http://localhost:8000/vote/send-otp \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your_csrf_token" \
  -d '{
    "mobile": "9876543210",
    "candidate_id": 1
  }'
```

### Verify OTP

```bash
curl -X POST http://localhost:8000/vote/verify-otp \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your_csrf_token" \
  -d '{
    "mobile": "919876543210",
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "candidate_id": 1
  }'
```

### MSG91 Token Verification

```bash
curl -X POST https://control.msg91.com/api/v5/widget/verifyAccessToken \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "authkey": "345269AqHLkx1yux68ff0247P1",
    "access-token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
  }'
```

---

## Debugging & Logging

### Browser Console Commands

```javascript
// Check if widget is loaded
console.log(typeof window.sendOtp);

// Get widget configuration
console.log(window.getWidgetData());

// Check current mobile
console.log("Current Mobile:", currentMobile);

// Check verification token
console.log("Verification Token:", msg91VerificationToken);

// Test OTP sending
window.sendOtp(
    "919876543210",
    (data) => console.log("Success:", data),
    (error) => console.log("Error:", error)
);
```

### Server-Side Debugging

```php
// Log OTP generation
Log::info('OTP Generated', [
    'mobile' => $request->mobile,
    'otp' => $otp,
    'candidate_id' => $request->candidate_id
]);

// Log verification attempt
Log::info('Verification Attempted', [
    'mobile' => $request->mobile,
    'token' => substr($request->token, 0, 20) . '...'
]);

// Log vote creation
Log::info('Vote Created', [
    'voter_mobile' => $request->mobile,
    'candidate_id' => $request->candidate_id
]);
```

---

## Common Issues & Solutions

### Issue: Widget Not Loading

```javascript
// Check if script loaded
if (!window.initSendOTP) {
    console.error("MSG91 widget script not loaded");
    // Reload script
    const script = document.createElement("script");
    script.src = "https://verify.msg91.com/otp-provider.js";
    document.body.appendChild(script);
}
```

### Issue: OTP Not Received

```javascript
// Check mobile format
const mobile = "919876543210";
if (!/^91\d{10}$/.test(mobile)) {
    console.error("Invalid mobile format");
}
```

### Issue: Token Verification Failed

```php
// Log API response
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
Log::error('MSG91 Verification Error', [
    'httpCode' => $httpCode,
    'response' => $response
]);
```

---

**Last Updated**: October 27, 2025  
**Version**: 1.0  
**Ready for Implementation**: ✅
