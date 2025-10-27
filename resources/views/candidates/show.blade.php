@extends('layouts.public')

@section('title', $candidate->name . ' - UN Brahma College Election')
@section('header', $candidate->name)

@section('content')
  <div class="px-4 py-12 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
      <!-- Candidate Information -->
      <div class="lg:col-span-2" data-aos="fade-right">
        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-2xl">
          <!-- Photo Section -->
          <div class="relative h-96 overflow-hidden bg-gradient-to-br from-blue-100 to-indigo-100">
            @if ($candidate->photo)
              <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}"
                class="h-full w-full object-cover transition-transform duration-500 hover:scale-110">
              <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
            @else
              <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-100">
                <i class="fas fa-user-circle text-9xl text-blue-300"></i>
              </div>
            @endif
          </div>

          <!-- Content Section -->
          <div class="space-y-8 p-8">
            <!-- Candidate Header -->
            <div>
              <h2
                class="mb-4 bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-4xl font-black text-transparent">
                {{ $candidate->name }}
              </h2>

              @if ($candidate->position)
                <div
                  class="inline-flex items-center space-x-3 rounded-full border border-yellow-200 bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-3">
                  <i class="fas fa-crown text-3xl text-yellow-500"></i>
                  <p
                    class="bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-lg font-bold text-transparent">
                    Running for {{ $candidate->position }}
                  </p>
                </div>
              @endif
            </div>

            <!-- Candidate Details -->
            <div class="flex flex-wrap gap-6 border-b border-gray-200 pb-6">
              @if ($candidate->department)
                <div class="flex items-center space-x-3">
                  <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                    <i class="fas fa-building text-xl text-blue-600"></i>
                  </div>
                  <div>
                    <p class="text-xs uppercase tracking-widest text-gray-500">Department</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $candidate->department }}</p>
                  </div>
                </div>
              @endif

              @if ($candidate->year)
                <div class="flex items-center space-x-3">
                  <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100">
                    <i class="fas fa-graduation-cap text-xl text-purple-600"></i>
                  </div>
                  <div>
                    <p class="text-xs uppercase tracking-widest text-gray-500">Year</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $candidate->year }}</p>
                  </div>
                </div>
              @endif

              <div class="flex items-center space-x-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                  <i class="fas fa-check-circle text-xl text-green-600"></i>
                </div>
                <div>
                  <p class="text-xs uppercase tracking-widest text-gray-500">Current Votes</p>
                  <p class="text-lg font-semibold text-gray-900">{{ $candidate->votes_count }}</p>
                </div>
              </div>
            </div>

            <!-- About Section -->
            @if ($candidate->bio)
              <div class="space-y-4">
                <h3 class="flex items-center space-x-3 text-2xl font-bold text-gray-900">
                  <i class="fas fa-user text-blue-600"></i>
                  <span>About</span>
                </h3>
                <p
                  class="rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50 to-indigo-50 p-6 text-lg leading-relaxed text-gray-700">
                  {{ $candidate->bio }}
                </p>
              </div>
            @endif

            <!-- Manifesto Section -->
            @if ($candidate->manifesto)
              <div class="space-y-4">
                <h3 class="flex items-center space-x-3 text-2xl font-bold text-gray-900">
                  <i class="fas fa-star text-yellow-500"></i>
                  <span>Manifesto</span>
                </h3>
                <div
                  class="whitespace-pre-line rounded-2xl border border-yellow-100 bg-gradient-to-r from-yellow-50 to-orange-50 p-6 text-lg leading-relaxed text-gray-700">
                  {{ $candidate->manifesto }}
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>

      <!-- Voting Section -->
      <div class="lg:col-span-1" data-aos="fade-left">
        <div
          class="sticky top-20 rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-2xl">
          <div class="mb-8 flex items-center space-x-3">
            <div
              class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600">
              <i class="fas fa-vote-yea text-2xl text-white"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900">Cast Your Vote</h3>
          </div>

          <!-- Mobile Input Section -->
          <div id="mobile-input-section" class="space-y-4">
            <div>
              <label for="voter-mobile" class="mb-3 flex items-center space-x-2 text-sm font-semibold text-gray-700">
                <i class="fas fa-mobile-alt text-blue-600"></i>
                <span>Mobile Number</span>
              </label>
              <input type="text" id="voter-mobile" maxlength="10" pattern="[0-9]{10}"
                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-lg font-semibold transition-all duration-300 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                placeholder="Enter 10-digit mobile">
              <p class="mt-2 flex items-center space-x-2 text-sm text-gray-500">
                <i class="fas fa-info-circle text-blue-500"></i>
                <span>You will receive an OTP to verify</span>
              </p>
            </div>

            <button type="button" onclick="initiateMSG91OTP()"
              class="group flex w-full transform items-center justify-center space-x-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 font-bold text-white transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/30">
              <i class="fas fa-paper-plane transition-transform duration-300 group-hover:scale-125"></i>
              <span>Send OTP</span>
            </button>
          </div>

          <!-- MSG91 OTP Widget Container -->
          <div id="msg91-widget-container" class="hidden space-y-4">
            <div class="flex items-center space-x-3 rounded-xl border-2 border-blue-200 bg-blue-50 p-4">
              <i class="fas fa-check-circle text-2xl text-green-500"></i>
              <div>
                <p class="text-sm font-semibold text-gray-900">OTP sent successfully!</p>
                <p class="text-xs text-gray-600">Check your phone for the verification code</p>
              </div>
            </div>

            <!-- OTP Input -->
            <div>
              <label for="otp-input" class="mb-3 flex items-center space-x-2 text-sm font-semibold text-gray-700">
                <i class="fas fa-shield-check text-green-600"></i>
                <span>Enter OTP</span>
              </label>
              <input type="text" id="otp-input" maxlength="6" pattern="[0-9]{6}"
                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-center text-lg font-semibold tracking-widest transition-all duration-300 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200"
                placeholder="000000">
            </div>

            <!-- Verify Button -->
            <button type="button" onclick="verifyMSG91OTP()"
              class="group flex w-full transform items-center justify-center space-x-2 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 font-bold text-white transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-green-500/30">
              <i class="fas fa-check-double transition-transform duration-300 group-hover:scale-125"></i>
              <span>Verify & Vote</span>
            </button>

            <!-- Retry Button -->
            <button type="button" onclick="retryMSG91OTP()"
              class="flex w-full items-center justify-center space-x-2 rounded-xl border-2 border-blue-300 bg-blue-50 px-6 py-3 font-bold text-blue-700 transition-all duration-300 hover:bg-blue-100">
              <i class="fas fa-redo"></i>
              <span>Resend OTP</span>
            </button>

            <!-- Change Number Button -->
            <button type="button" onclick="resetMSG91Form()"
              class="flex w-full items-center justify-center space-x-2 rounded-xl border-2 border-gray-300 bg-white px-6 py-3 font-bold text-gray-700 transition-all duration-300 hover:bg-gray-50">
              <i class="fas fa-arrow-left"></i>
              <span>Change Number</span>
            </button>
          </div>

          <!-- Message Alert -->
          <div id="message" class="mt-4 hidden rounded-xl p-4"></div>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentMobile = '';
    let currentCandidateId = {{ $candidate->id }};
    let msg91VerificationToken = '';
    console.log('Candidate ID:', currentCandidateId);
    // MSG91 Configuration
    var msg91Configuration = {
      widgetId: "{{ config('services.msg91.widget_id') }}",
      tokenAuth: "{{ config('services.msg91.access_token') }}",
      exposeMethods: true,
      success: (data) => {
        console.log('MSG91 Success:', data);
        msg91VerificationToken = data.token;
        document.getElementById('msg91-widget-container').classList.remove('hidden');
        showMessage('OTP verified successfully!', 'success');
      },
      failure: (error) => {
        console.error('MSG91 Failure:', error);
        showMessage('OTP verification failed. Please try again.', 'error');
      }
    };
  </script>
  <!-- MSG91 OTP Widget Script -->
  <script type="text/javascript" onload="initSendOTP(msg91Configuration)" src="https://verify.msg91.com/otp-provider.js">
  </script>

  <!-- AOS Initialization -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

  <script>
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true
    });


    function initiateMSG91OTP() {
      const mobile = document.getElementById('voter-mobile').value;
      const messageDiv = document.getElementById('message');

      if (!mobile || mobile.length !== 10) {
        showMessage('Please enter a valid 10-digit mobile number', 'error');
        return;
      }

      currentMobile = mobile;

      // Send OTP via backend to trigger MSG91 and store OTP locally
      fetch('{{ route('vote.send-otp') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            mobile: mobile,
            candidate_id: currentCandidateId
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Trigger MSG91 OTP widget with the mobile number
            const mobileWithCC = '91' + mobile;

            // Update configuration with identifier and initialize
            msg91Configuration.identifier = mobileWithCC;

            if (window.initSendOTP) {
              window.initSendOTP(msg91Configuration);
            } else {
              console.error('MSG91 widget not loaded');
              showMessage('OTP service not available. Please try again.', 'error');
            }

            document.getElementById('mobile-input-section').classList.add('hidden');
            document.getElementById('msg91-widget-container').classList.remove('hidden');
            showMessage(data.message, 'success');
          } else {
            showMessage(data.message, 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showMessage('An error occurred. Please try again.', 'error');
        });
    }

    function verifyMSG91OTP() {
      const otp = document.getElementById('otp-input').value;

      if (!otp || otp.length !== 6) {
        showMessage('Please enter a valid 6-digit OTP', 'error');
        return;
      }

      // Verify OTP with MSG91 widget
      if (window.verifyOtp) {
        window.verifyOtp(
          otp,
          (data) => {
            console.log('OTP Verified:', data);
            msg91VerificationToken = data.token || data.access_token || '';

            // Send verification token to backend
            submitVote(msg91VerificationToken);
          },
          (error) => {
            console.error('OTP Verification Error:', error);
            showMessage('Invalid OTP. Please try again.', 'error');
          }
        );
      } else {
        showMessage('MSG91 widget not available.', 'error');
      }
    }

    function submitVote(token) {
      fetch('{{ route('vote.verify-otp') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            mobile: currentMobile,
            token: token,
            candidate_id: currentCandidateId
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showMessage(data.message, 'success');
            setTimeout(() => {
              window.location.href = data.redirect;
            }, 1500);
          } else {
            showMessage(data.message, 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showMessage('An error occurred while submitting your vote.', 'error');
        });
    }

    function retryMSG91OTP() {
      if (window.retryOtp) {
        window.retryOtp(
          null, // Use default channel
          (data) => {
            console.log('OTP Resent:', data);
            showMessage('OTP resent successfully!', 'success');
          },
          (error) => {
            console.error('Retry Error:', error);
            showMessage('Failed to resend OTP. Please try again.', 'error');
          }
        );
      } else {
        showMessage('MSG91 widget not available.', 'error');
      }
    }

    function resetMSG91Form() {
      document.getElementById('mobile-input-section').classList.remove('hidden');
      document.getElementById('msg91-widget-container').classList.add('hidden');
      document.getElementById('voter-mobile').value = '';
      document.getElementById('otp-input').value = '';
      document.getElementById('message').classList.add('hidden');
      currentMobile = '';
      msg91VerificationToken = '';
    }

    function showMessage(text, type) {
      const messageDiv = document.getElementById('message');
      if (type === 'success') {
        messageDiv.className =
          'mt-4 rounded-xl p-4 bg-green-50 border-2 border-green-200 flex items-start space-x-3';
        messageDiv.innerHTML =
          '<i class="fas fa-check-circle text-green-600 text-xl flex-shrink-0 mt-1"></i><div><p class="text-sm font-semibold text-green-900">' +
          text + '</p></div>';
      } else {
        messageDiv.className = 'mt-4 rounded-xl p-4 bg-red-50 border-2 border-red-200 flex items-start space-x-3';
        messageDiv.innerHTML =
          '<i class="fas fa-exclamation-circle text-red-600 text-xl flex-shrink-0 mt-1"></i><div><p class="text-sm font-semibold text-red-900">' +
          text + '</p></div>';
      }
      messageDiv.classList.remove('hidden');
    }
  </script>
@endsection
