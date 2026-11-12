<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Approval — MEDLINK</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <style type="text/tailwindcss">
        body { font-family: 'Inter', sans-serif; }
    </style>
    <style>
        @keyframes pulse-ring {
            0%   { transform: scale(0.9); opacity: 0.7; }
            50%  { transform: scale(1.05); opacity: 0.3; }
            100% { transform: scale(0.9); opacity: 0.7; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }
        .pulse-ring { animation: pulse-ring 2.5s ease-in-out infinite; }
        .float-icon { animation: float 3s ease-in-out infinite; }

        @keyframes dot-bounce {
            0%, 80%, 100% { transform: translateY(0); opacity: 0.4; }
            40%            { transform: translateY(-6px); opacity: 1; }
        }
        .dot { animation: dot-bounce 1.4s ease-in-out infinite; }
        .dot:nth-child(2) { animation-delay: 0.2s; }
        .dot:nth-child(3) { animation-delay: 0.4s; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col relative bg-center bg-cover bg-no-repeat bg-fixed"
      style="background-image: url('{{ asset('honrado.png') }}');">

    {{-- Dark Overlay --}}
    <div class="absolute inset-0 bg-[#0c2d48]/90 z-0 pointer-events-none"></div>

    {{-- Header --}}
    <header class="w-full px-6 h-16 flex items-center justify-between relative z-10">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center shadow-lg">
                <span class="material-icons-outlined text-white" style="font-size:18px;">local_hospital</span>
            </div>
            <div>
                <span class="text-white font-bold text-sm tracking-wide">MEDLINK</span>
                <span class="text-blue-300 text-[9px] font-medium uppercase tracking-widest block -mt-0.5">Barangay Honrado</span>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-white/50 hover:text-white text-sm font-medium flex items-center gap-1.5 transition-colors relative z-10">
                <span class="material-icons-outlined" style="font-size:16px;">logout</span>
                Sign out
            </button>
        </form>
    </header>

    {{-- Main content --}}
    <main class="flex-1 flex items-center justify-center px-6 py-12 relative z-10">
        <div class="text-center max-w-md w-full">

            {{-- Animated icon --}}
            <div class="relative inline-flex items-center justify-center mb-10">
                {{-- Outer pulse ring --}}
                <div class="pulse-ring absolute w-40 h-40 rounded-full" style="background: rgba(251,191,36,0.1);"></div>
                {{-- Middle ring --}}
                <div class="absolute w-28 h-28 rounded-full" style="background: rgba(251,191,36,0.15);"></div>
                {{-- Icon circle --}}
                <div class="float-icon relative w-20 h-20 rounded-full flex items-center justify-center shadow-2xl"
                     style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <span class="material-icons-outlined text-white" style="font-size:38px;">pending_actions</span>
                </div>
            </div>

            {{-- Message card --}}
            <div class="bg-white/[0.06] backdrop-blur-sm rounded-2xl border border-white/10 px-8 py-8">
                <h1 class="text-2xl font-bold text-white mb-3">Account Pending Approval</h1>
                <p class="text-blue-200/80 text-sm leading-relaxed mb-6">
                    Hi, <span class="text-white font-semibold">{{ auth()->user()->first_name }}</span>! Your registration was submitted successfully.
                    A nurse will review your ID and verify your account shortly.
                </p>

                {{-- Status steps --}}
                <div class="space-y-3 mb-8 text-left">
                    {{-- Step 1 done --}}
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-white" style="font-size:15px;">check</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">Registration submitted</p>
                            <p class="text-xs text-blue-300/70">Your details and ID photo were uploaded</p>
                        </div>
                    </div>
                    {{-- Step 2 in progress --}}
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-amber-400 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-white" style="font-size:15px;">hourglass_top</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-amber-300">Nurse review in progress</p>
                            <p class="text-xs text-blue-300/70">Your ID photo is being reviewed</p>
                        </div>
                    </div>
                    {{-- Step 3 waiting --}}
                    <div class="flex items-center gap-3 opacity-40">
                        <div class="w-7 h-7 rounded-full border-2 border-white/30 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-white/60" style="font-size:15px;">lock_open</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">Access granted</p>
                            <p class="text-xs text-blue-300/70">You'll be able to use all features</p>
                        </div>
                    </div>
                </div>

                {{-- Loading dots --}}
                <div class="flex items-center justify-center gap-2 mb-6">
                    <span class="text-blue-300/60 text-xs mr-1">Waiting for approval</span>
                    <span class="dot w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>
                    <span class="dot w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>
                    <span class="dot w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>
                </div>

                {{-- Refresh hint --}}
                <p class="text-blue-300/50 text-xs">
                    Once approved, 
                    <a href="{{ route('login') }}" class="text-blue-300 hover:text-white underline font-medium transition-colors">sign in again</a>
                    to access your dashboard.
                </p>
            </div>

            {{-- Contact --}}
            <p class="text-blue-300/40 text-xs mt-6">
                Questions? Contact Barangay Honrado Health Center.
            </p>
        </div>
    </main>

</body>
</html>
