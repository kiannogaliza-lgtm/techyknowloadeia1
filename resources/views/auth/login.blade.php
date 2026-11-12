<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — MEDLINK</title>

    {{-- Tailwind CSS v4 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style type="text/tailwindcss">
        @theme {
            --font-display: 'Plus Jakarta Sans', sans-serif;
            --font-body: 'Inter', sans-serif;
        }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="text-slate-900 antialiased min-h-screen flex flex-col justify-between relative selection:bg-emerald-500 selection:text-white"
      style="
          background-image:
              linear-gradient(
                  135deg,
                  rgba(2, 20, 11, 0.90) 0%,
                  rgba(3, 30, 17, 0.82) 50%,
                  rgba(2, 20, 11, 0.94) 100%
              ),
              url('{{ asset('honrado.png') }}');
          background-size: cover;
          background-position: center;
          background-repeat: no-repeat;
          background-attachment: fixed;
      ">

    {{-- Header / Brand --}}
    <header class="w-full max-w-7xl mx-auto px-6 sm:px-8 h-20 flex items-center justify-between relative z-10">
        <a href="/" class="flex items-center gap-3 group" aria-label="MEDLINK Home">
            <img src="{{ asset('ico.jpg') }}" alt="MEDLINK Logo" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl object-cover flex-shrink-0 shadow-md border border-white/20 group-hover:scale-105 transition-transform">
            <div>
                <div class="font-display font-black text-white text-base sm:text-lg tracking-tight leading-none flex items-center gap-2">
                    MEDLINK
                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">ONLINE</span>
                </div>
                <div class="text-[11px] text-slate-300/80 mt-1 font-medium">Barangay Honrado Health Center</div>
            </div>
        </a>

        <a href="/" class="text-xs sm:text-sm font-semibold text-slate-200 hover:text-emerald-400 flex items-center gap-1.5 transition-colors px-3 py-1.5 rounded-lg hover:bg-white/5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Home
        </a>
    </header>

    {{-- Main Login Card Container --}}
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-8 sm:py-12 relative overflow-hidden">
        <div class="w-full max-w-md bg-white/95 backdrop-blur-xl border border-white/60 rounded-3xl shadow-2xl shadow-emerald-950/40 p-7 sm:p-9 relative z-10 transition-all">
            {{-- Form Header --}}
            <div class="text-center mb-7">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-bold uppercase tracking-wider mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Account Access
                </div>
                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Welcome back</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-1.5 font-medium">Sign in to your MEDLINK account to continue</p>
            </div>

            {{-- Error Notification --}}
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <p class="font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ url('/login') }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Role Selection --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Login as</label>
                    <div class="grid grid-cols-3 gap-2.5">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="role" value="resident" class="peer sr-only" checked>
                            <div class="flex flex-col items-center justify-center py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 font-semibold text-xs sm:text-sm transition-all 
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-50/80 peer-checked:text-emerald-700 peer-checked:shadow-sm hover:bg-slate-100">
                                Resident
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" name="role" value="bhw" class="peer sr-only">
                            <div class="flex flex-col items-center justify-center py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 font-semibold text-xs sm:text-sm transition-all 
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-50/80 peer-checked:text-emerald-700 peer-checked:shadow-sm hover:bg-slate-100">
                                BHW
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" name="role" value="nurse" class="peer sr-only">
                            <div class="flex flex-col items-center justify-center py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 font-semibold text-xs sm:text-sm transition-all 
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-50/80 peer-checked:text-emerald-700 peer-checked:shadow-sm hover:bg-slate-100">
                                Nurse
                            </div>
                        </label>
                    </div>
                </div>
                
                {{-- Phone Number --}}
                <div>
                    <label for="phone_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phone Number</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </span>
                        <input type="tel" name="phone_number" id="phone_number" required placeholder="09123456789"
                               minlength="11"
                               maxlength="11"
                               pattern="09[0-9]{9}"
                               inputmode="numeric"
                               oninput="validatePhoneDigits(this)"
                               title="Please enter an 11-digit Philippine phone number starting with 09 (e.g. 09123456789)"
                               class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 font-medium
                                      focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all">
                    </div>
                    <p id="phone-error-msg" class="text-xs text-rose-500 mt-1.5 hidden flex items-center gap-1 font-medium">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span id="phone-error-text">Phone number must be an 11-digit Philippine number starting with 09 (e.g. 09XXXXXXXXX).</span>
                    </p>
                </div>

                {{-- Password --}}
                <div>
                    <div class="mb-2">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" name="password" id="password" required placeholder="••••••••"
                               class="block w-full pl-10 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 font-medium
                                      focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" aria-label="Toggle Password Visibility">
                            {{-- Eye Open Icon --}}
                            <svg id="eye-open-icon" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{-- Eye Close Icon --}}
                            <svg id="eye-close-icon" class="w-4.5 h-4.5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" 
                           class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500 focus:ring-offset-0">
                    <label for="remember" class="ml-2 text-xs sm:text-sm text-slate-600 font-medium select-none">Remember this device</label>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                        class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-xl text-sm font-bold text-white
                               bg-emerald-600 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500
                               transition-all shadow-lg shadow-emerald-950/20 active:scale-[0.98]">
                    Sign In
                </button>
            </form>

            {{-- Register Option --}}
            <div class="mt-7 text-center border-t border-slate-100 pt-5">
                <p class="text-xs sm:text-sm text-slate-500 font-medium">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline ml-1">Create an account</a>
                </p>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="w-full border-t border-white/10 bg-transparent py-6 relative z-10">
        <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-white/40">
            <span>&copy; {{ date('Y') }} MEDLINK. Barangay Honrado Health Center.</span>
            <span>Web-based inventory &amp; health service request system</span>
        </div>
    </footer>

    <script>
        function validatePhoneDigits(input) {
            let val = input.value.replace(/[^0-9]/g, '');
            // Only allow starting with 0, then 9
            if (val.length >= 1 && val[0] !== '0') {
                val = '';
            } else if (val.length >= 2 && val.substring(0, 2) !== '09') {
                val = '0';
            }
            input.value = val.slice(0, 11);

            const errorMsg = document.getElementById('phone-error-msg');
            const errorText = document.getElementById('phone-error-text');

            if (input.value.length === 0) {
                if (errorMsg) errorMsg.classList.add('hidden');
                input.setCustomValidity('');
            } else if (!input.value.startsWith('09')) {
                if (errorMsg) errorMsg.classList.remove('hidden');
                if (errorText) errorText.textContent = 'Phone number must start with 09.';
                input.setCustomValidity('Phone number must start with 09');
            } else if (input.value.length < 11) {
                if (errorMsg) errorMsg.classList.remove('hidden');
                if (errorText) errorText.textContent = 'Phone number must be exactly 11 digits (e.g. 09XXXXXXXXX). Currently ' + input.value.length + '/11.';
                input.setCustomValidity('Phone number must be exactly 11 digits starting with 09');
            } else {
                if (errorMsg) errorMsg.classList.add('hidden');
                input.setCustomValidity('');
            }
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open-icon');
            const eyeClose = document.getElementById('eye-close-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClose.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClose.classList.add('hidden');
            }
        }
    </script>

</body>
</html>
