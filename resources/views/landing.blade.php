@extends('layouts.public')

@section('title', 'Welcome to UN Brahma College Student Elections')

@section('content')
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section
      class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 py-24 text-white">
      <div class="absolute inset-0 opacity-20">
        <div class="floating absolute left-10 top-10 h-72 w-72 rounded-full bg-white blur-3xl"></div>
        <div class="floating absolute bottom-10 right-10 h-96 w-96 rounded-full bg-yellow-300 blur-3xl"
          style="animation-delay: 2s;"></div>
      </div>

      <div class="container relative z-10 mx-auto px-6">
        <div class="text-center" data-aos="fade-up">
          <div class="mb-6">
            <i class="fas fa-vote-yea pulse-glow text-8xl"></i>
          </div>
          <h1 class="gradient-text mb-6 text-6xl font-black md:text-7xl">
            UN Brahma College
          </h1>
          <p class="mb-8 text-3xl font-bold md:text-4xl">
            Student Election Portal
          </p>
          <p class="mx-auto mb-12 max-w-3xl text-xl font-light md:text-2xl">
            Empowering students to choose their leaders through a secure, transparent, and modern voting platform
          </p>
          <div class="flex flex-wrap justify-center gap-6">
            <a href="{{ route('candidates.index') }}"
              class="group flex transform items-center space-x-3 rounded-2xl bg-white px-10 py-5 text-lg font-bold text-blue-600 shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-white/20">
              <i class="fas fa-users transition-transform duration-300 group-hover:scale-125"></i>
              <span>View Candidates</span>
            </a>
            <a href="{{ route('results') }}"
              class="group flex transform items-center space-x-3 rounded-2xl border-4 border-white bg-transparent px-10 py-5 text-lg font-bold text-white shadow-2xl transition-all duration-300 hover:bg-white hover:text-blue-600">
              <i class="fas fa-chart-bar transition-transform duration-300 group-hover:scale-125"></i>
              <span>Live Results</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Animated Wave -->
      <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
          <path
            d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,58.7C960,64,1056,64,1152,58.7C1248,53,1344,43,1392,37.3L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"
            fill="white" fill-opacity="1">
            <animate attributeName="d" dur="10s" repeatCount="indefinite"
              values="
                        M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,58.7C960,64,1056,64,1152,58.7C1248,53,1344,43,1392,37.3L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z;
                        M0,32L48,42.7C96,53,192,75,288,80C384,85,480,75,576,64C672,53,768,43,864,48C960,53,1056,75,1152,80C1248,85,1344,75,1392,69.3L1440,64L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z;
                        M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,58.7C960,64,1056,64,1152,58.7C1248,53,1344,43,1392,37.3L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z" />
          </path>
        </svg>
      </div>
    </section>

    <!-- Features Section -->
    <section class="bg-white py-24">
      <div class="container mx-auto px-6">
        <div class="mb-20 text-center" data-aos="fade-up">
          <h2 class="mb-6 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-5xl font-black text-transparent">
            Why Choose Our Platform?
          </h2>
          <p class="mx-auto max-w-3xl text-xl text-gray-600">
            A modern, secure, and transparent voting system designed specifically for students
          </p>
        </div>

        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-3">
          <!-- Feature 1 -->
          <div class="card-hover rounded-3xl bg-gradient-to-br from-blue-50 to-indigo-50 p-10 shadow-xl"
            data-aos="fade-up" data-aos-delay="100">
            <div
              class="mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg">
              <i class="fas fa-shield-check text-4xl text-white"></i>
            </div>
            <h3 class="mb-4 text-2xl font-bold text-gray-800">Secure Voting</h3>
            <p class="leading-relaxed text-gray-600">
              OTP-based authentication ensures each vote is secure and verified. Your privacy and vote integrity are our
              top priorities.
            </p>
          </div>

          <!-- Feature 2 -->
          <div class="card-hover rounded-3xl bg-gradient-to-br from-purple-50 to-pink-50 p-10 shadow-xl"
            data-aos="fade-up" data-aos-delay="200">
            <div
              class="mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 shadow-lg">
              <i class="fas fa-chart-line text-4xl text-white"></i>
            </div>
            <h3 class="mb-4 text-2xl font-bold text-gray-800">Live Results</h3>
            <p class="leading-relaxed text-gray-600">
              Watch real-time vote counts with beautiful charts and graphs. Transparency at every step of the election
              process.
            </p>
          </div>

          <!-- Feature 3 -->
          <div class="card-hover rounded-3xl bg-gradient-to-br from-green-50 to-emerald-50 p-10 shadow-xl"
            data-aos="fade-up" data-aos-delay="300">
            <div
              class="mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 shadow-lg">
              <i class="fas fa-mobile-alt text-4xl text-white"></i>
            </div>
            <h3 class="mb-4 text-2xl font-bold text-gray-800">Mobile Friendly</h3>
            <p class="leading-relaxed text-gray-600">
              Vote from anywhere, anytime using your smartphone or computer. Responsive design for all devices.
            </p>
          </div>

          <!-- Feature 4 -->
          <div class="card-hover rounded-3xl bg-gradient-to-br from-yellow-50 to-orange-50 p-10 shadow-xl"
            data-aos="fade-up" data-aos-delay="400">
            <div
              class="mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-yellow-500 to-orange-600 shadow-lg">
              <i class="fas fa-user-graduate text-4xl text-white"></i>
            </div>
            <h3 class="mb-4 text-2xl font-bold text-gray-800">Student Verified</h3>
            <p class="leading-relaxed text-gray-600">
              Only registered students can vote. Each student gets one vote to ensure fair and democratic elections.
            </p>
          </div>

          <!-- Feature 5 -->
          <div class="card-hover rounded-3xl bg-gradient-to-br from-red-50 to-rose-50 p-10 shadow-xl" data-aos="fade-up"
            data-aos-delay="500">
            <div
              class="mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 shadow-lg">
              <i class="fas fa-clock text-4xl text-white"></i>
            </div>
            <h3 class="mb-4 text-2xl font-bold text-gray-800">Quick & Easy</h3>
            <p class="leading-relaxed text-gray-600">
              Simple three-step process: Get OTP, verify, and vote. Complete your vote in less than 2 minutes.
            </p>
          </div>

          <!-- Feature 6 -->
          <div class="card-hover rounded-3xl bg-gradient-to-br from-cyan-50 to-blue-50 p-10 shadow-xl" data-aos="fade-up"
            data-aos-delay="600">
            <div
              class="mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 shadow-lg">
              <i class="fas fa-check-circle text-4xl text-white"></i>
            </div>
            <h3 class="mb-4 text-2xl font-bold text-gray-800">Transparent</h3>
            <p class="leading-relaxed text-gray-600">
              Complete transparency with audit trails and instant vote confirmation. Trust in the democratic process.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <section class="bg-gradient-to-br from-gray-50 to-gray-100 py-24">
      <div class="container mx-auto px-6">
        <div class="mb-20 text-center" data-aos="fade-up">
          <h2 class="mb-6 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-5xl font-black text-transparent">
            How It Works
          </h2>
          <p class="mx-auto max-w-3xl text-xl text-gray-600">
            Voting made simple in three easy steps
          </p>
        </div>

        <div class="mx-auto max-w-5xl">
          <!-- Step 1 -->
          <div class="mb-16 flex flex-col items-center gap-12 md:flex-row" data-aos="fade-right">
            <div class="order-2 flex-1 md:order-1">
              <div class="mb-4 text-8xl font-black text-blue-100">01</div>
              <h3 class="mb-4 text-3xl font-bold text-gray-800">
                <i class="fas fa-user-check mr-3 text-blue-600"></i>
                Verify Your Identity
              </h3>
              <p class="text-lg leading-relaxed text-gray-600">
                Enter your email address to receive a secure OTP code. This ensures only authorized students can
                participate in the voting process.
              </p>
            </div>
            <div class="order-1 flex-1 md:order-2">
              <div class="glass-effect rounded-3xl p-10 shadow-2xl">
                <i class="fas fa-envelope-open-text text-8xl text-blue-600"></i>
              </div>
            </div>
          </div>

          <!-- Step 2 -->
          <div class="mb-16 flex flex-col items-center gap-12 md:flex-row-reverse" data-aos="fade-left">
            <div class="order-2 flex-1">
              <div class="mb-4 text-8xl font-black text-purple-100">02</div>
              <h3 class="mb-4 text-3xl font-bold text-gray-800">
                <i class="fas fa-users mr-3 text-purple-600"></i>
                Choose Your Candidate
              </h3>
              <p class="text-lg leading-relaxed text-gray-600">
                Browse through candidate profiles, view their departments and years, and make an informed decision on who
                best represents you.
              </p>
            </div>
            <div class="order-1 flex-1">
              <div class="glass-effect rounded-3xl p-10 shadow-2xl">
                <i class="fas fa-user-tie text-8xl text-purple-600"></i>
              </div>
            </div>
          </div>

          <!-- Step 3 -->
          <div class="flex flex-col items-center gap-12 md:flex-row" data-aos="fade-right">
            <div class="order-2 flex-1 md:order-1">
              <div class="mb-4 text-8xl font-black text-green-100">03</div>
              <h3 class="mb-4 text-3xl font-bold text-gray-800">
                <i class="fas fa-check-double mr-3 text-green-600"></i>
                Cast Your Vote
              </h3>
              <p class="text-lg leading-relaxed text-gray-600">
                Submit your vote with confidence. Your vote is recorded securely and you can immediately view the live
                results after voting.
              </p>
            </div>
            <div class="order-1 flex-1 md:order-2">
              <div class="glass-effect rounded-3xl p-10 shadow-2xl">
                <i class="fas fa-vote-yea text-8xl text-green-600"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 py-24 text-white">
      <div class="absolute inset-0 opacity-10">
        <div class="floating absolute right-0 top-0 h-96 w-96 rounded-full bg-white blur-3xl"></div>
        <div class="floating absolute bottom-0 left-0 h-72 w-72 rounded-full bg-yellow-300 blur-3xl"
          style="animation-delay: 1.5s;"></div>
      </div>

      <div class="container relative z-10 mx-auto px-6 text-center" data-aos="zoom-in">
        <div class="pulse-glow mb-8 inline-block">
          <i class="fas fa-bullhorn text-7xl"></i>
        </div>
        <h2 class="mb-8 text-5xl font-black md:text-6xl">
          Ready to Make Your Voice Heard?
        </h2>
        <p class="mx-auto mb-12 max-w-3xl text-2xl font-light">
          Join thousands of students in shaping the future of UN Brahma College
        </p>
        <div class="flex flex-wrap justify-center gap-6">
          @auth
            <a href="{{ route('candidates.index') }}"
              class="flex transform items-center space-x-3 rounded-2xl bg-white px-12 py-6 text-xl font-bold text-blue-600 shadow-2xl transition-all duration-300 hover:scale-110 hover:shadow-white/30">
              <i class="fas fa-vote-yea"></i>
              <span>Vote Now</span>
            </a>
          @else
            <a href="{{ route('login') }}"
              class="flex transform items-center space-x-3 rounded-2xl bg-white px-12 py-6 text-xl font-bold text-blue-600 shadow-2xl transition-all duration-300 hover:scale-110 hover:shadow-white/30">
              <i class="fas fa-sign-in-alt"></i>
              <span>Get Started</span>
            </a>
          @endauth
          <a href="{{ route('results') }}"
            class="flex transform items-center space-x-3 rounded-2xl border-4 border-white bg-transparent px-12 py-6 text-xl font-bold text-white shadow-2xl transition-all duration-300 hover:bg-white hover:text-purple-600">
            <i class="fas fa-chart-pie"></i>
            <span>View Results</span>
          </a>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-white py-24">
      <div class="container mx-auto px-6">
        <div class="grid gap-10 text-center md:grid-cols-4">
          <div class="card-hover rounded-3xl bg-gradient-to-br from-blue-50 to-blue-100 p-8 shadow-lg"
            data-aos="fade-up" data-aos-delay="100">
            <div class="gradient-text mb-4 text-6xl font-black">{{ \App\Models\User::where('role', 'voter')->count() }}+
            </div>
            <p class="text-xl font-semibold text-gray-700">
              <i class="fas fa-users mr-2 text-blue-600"></i>
              Registered Voters
            </p>
          </div>
          <div class="card-hover rounded-3xl bg-gradient-to-br from-purple-50 to-purple-100 p-8 shadow-lg"
            data-aos="fade-up" data-aos-delay="200">
            <div class="gradient-text mb-4 text-6xl font-black">{{ \App\Models\Candidate::count() }}</div>
            <p class="text-xl font-semibold text-gray-700">
              <i class="fas fa-user-tie mr-2 text-purple-600"></i>
              Candidates
            </p>
          </div>
          <div class="card-hover rounded-3xl bg-gradient-to-br from-green-50 to-green-100 p-8 shadow-lg"
            data-aos="fade-up" data-aos-delay="300">
            <div class="gradient-text mb-4 text-6xl font-black">{{ \App\Models\Vote::count() }}</div>
            <p class="text-xl font-semibold text-gray-700">
              <i class="fas fa-check-circle mr-2 text-green-600"></i>
              Votes Cast
            </p>
          </div>
          <div class="card-hover rounded-3xl bg-gradient-to-br from-yellow-50 to-yellow-100 p-8 shadow-lg"
            data-aos="fade-up" data-aos-delay="400">
            <div class="gradient-text mb-4 text-6xl font-black">100%</div>
            <p class="text-xl font-semibold text-gray-700">
              <i class="fas fa-shield-check mr-2 text-yellow-600"></i>
              Secure
            </p>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
