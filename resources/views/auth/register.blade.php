<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account — MEDLINK</title>

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

    {{-- Main Register Card Container --}}
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-8 sm:py-12 relative overflow-hidden">
        <div class="w-full max-w-lg bg-white/95 backdrop-blur-xl border border-white/60 rounded-3xl shadow-2xl shadow-emerald-950/40 p-7 sm:p-9 relative z-10">
            {{-- Form Header --}}
            <div class="text-center mb-7">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-bold uppercase tracking-wider mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Resident &amp; Staff Registration
                </div>
                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Create your account</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-1.5 font-medium">Join MEDLINK to access Barangay Honrado health services online</p>
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
            <form action="{{ url('/register') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
                @csrf
                
                {{-- Role Selection --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Register as</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="role" value="resident" class="peer sr-only" {{ old('role', 'resident') === 'resident' ? 'checked' : '' }} onchange="onRoleChange('resident')">
                            <div class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 font-semibold text-xs sm:text-sm transition-all 
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-50/80 peer-checked:text-emerald-700 peer-checked:shadow-sm hover:bg-slate-100">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Resident</span>
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" name="role" value="nurse" class="peer sr-only" {{ old('role') === 'nurse' ? 'checked' : '' }} onchange="onRoleChange('nurse')">
                            <div class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 font-semibold text-xs sm:text-sm transition-all 
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-50/80 peer-checked:text-emerald-700 peer-checked:shadow-sm hover:bg-slate-100">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span>Nurse</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- First and Last Name --}}
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">First Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input type="text" name="first_name" id="first_name" required placeholder="Kian"
                                   class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 font-medium
                                          focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all">
                        </div>
                    </div>
                    <div>
                        <label for="last_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Last Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input type="text" name="last_name" id="last_name" required placeholder="Nogaliza"
                                   class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 font-medium
                                          focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all">
                        </div>
                    </div>
                </div>

                {{-- Phone Number & Age --}}
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="phone_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phone Number</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </span>
                            <input type="tel" name="phone_number" id="phone_number" required placeholder="09123456789"
                                   value="{{ old('phone_number') }}"
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
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="age" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Age</label>
                            <span id="age-category-badge" class="hidden text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 transition-all"></span>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input type="number" name="age" id="age" required min="0" max="130" placeholder="e.g. 20"
                                   value="{{ old('age') }}"
                                   oninput="updateAgeCategory(this.value)"
                                   class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 font-medium
                                          focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all">
                        </div>
                    </div>
                </div>

                {{-- Password --}}
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input type="password" name="password" id="password" required placeholder="••••••••"
                                   class="block w-full pl-10 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 font-medium
                                          focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all">
                            <button type="button" onclick="togglePassword('password', 'eye-open-1', 'eye-close-1')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" aria-label="Toggle Password">
                                <svg id="eye-open-1" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-close-1" class="w-4.5 h-4.5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••"
                                   class="block w-full pl-10 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 font-medium
                                          focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all">
                            <button type="button" onclick="togglePassword('password_confirmation', 'eye-open-2', 'eye-close-2')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" aria-label="Toggle Password">
                                <svg id="eye-open-2" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-close-2" class="w-4.5 h-4.5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ID Photo Upload (Residents only) --}}
                <div id="id-photo-section">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Government ID Photo <span class="text-rose-500">*</span></label>
                    <div id="id-drop-zone"
                         class="relative border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50 hover:border-emerald-400 hover:bg-emerald-50/30 transition-all cursor-pointer group"
                         onclick="document.getElementById('id_photo').click()">
                        {{-- Preview image (hidden initially) --}}
                        <img id="id-preview" src="#" alt="ID Preview"
                             class="hidden w-full max-h-44 object-contain rounded-xl p-2">
                        {{-- Upload prompt --}}
                        <div id="id-upload-prompt" class="flex flex-col items-center justify-center py-7 px-4 text-center">
                            <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center mb-2.5 text-slate-400 group-hover:text-emerald-600 group-hover:border-emerald-300 transition-colors shadow-xs">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 3h18M3 3v18"/>
                                </svg>
                            </div>
                            <p class="text-xs sm:text-sm font-bold text-slate-700">Click or drag to upload your ID</p>
                            <p class="text-[11px] text-slate-400 mt-1">National ID, Driver's License, Passport, etc. &bull; JPG, PNG &bull; max 5MB</p>
                        </div>
                        <input type="file" id="id_photo" name="id_photo" accept="image/*" required
                               class="sr-only"
                               onchange="previewIdPhoto(this)">
                    </div>
                    @error('id_photo')
                        <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Terms Checkbox --}}
                <div class="flex items-start">
                    <input type="checkbox" id="terms" name="terms" required
                           class="mt-1 w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500 focus:ring-offset-0">
                    <label for="terms" id="terms-label" class="ml-2 text-xs sm:text-sm text-slate-600 font-medium select-none">
                        I agree to register with correct information as a resident of Barangay Honrado.
                    </label>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                        class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-xl text-sm font-bold text-white
                               bg-emerald-600 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500
                               transition-all shadow-lg shadow-emerald-950/20 active:scale-[0.98]">
                    Register Account
                </button>
            </form>

            {{-- Login Option --}}
            <div class="mt-7 text-center border-t border-slate-100 pt-5">
                <p class="text-xs sm:text-sm text-slate-500 font-medium">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline ml-1">Sign in instead</a>
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
        function togglePassword(inputId, openIconId, closeIconId) {
            const input = document.getElementById(inputId);
            const eyeOpen = document.getElementById(openIconId);
            const eyeClose = document.getElementById(closeIconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClose.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClose.classList.add('hidden');
            }
        }

        function previewIdPhoto(input) {
            const preview = document.getElementById('id-preview');
            const prompt  = document.getElementById('id-upload-prompt');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    prompt.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

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

        function getAgeCategory(ageVal) {
            if (ageVal === '' || isNaN(ageVal)) return null;
            const age = parseInt(ageVal, 10);
            if (age < 0) return null;
            if (age >= 60) return { label: 'Senior citizen', color: 'bg-amber-100 text-amber-800 border-amber-300' };
            if (age >= 30) return { label: 'Adult', color: 'bg-emerald-100 text-emerald-800 border-emerald-300' };
            if (age >= 18) return { label: 'Young adult', color: 'bg-blue-100 text-blue-800 border-blue-300' };
            if (age >= 13) return { label: 'Teenager / Adolescent', color: 'bg-purple-100 text-purple-800 border-purple-300' };
            if (age >= 3)  return { label: 'Child', color: 'bg-cyan-100 text-cyan-800 border-cyan-300' };
            if (age >= 1)  return { label: 'Toddler', color: 'bg-yellow-100 text-yellow-800 border-yellow-300' };
            return { label: 'Infant', color: 'bg-pink-100 text-pink-800 border-pink-300' };
        }

        function updateAgeCategory(val) {
            const badge = document.getElementById('age-category-badge');
            const result = getAgeCategory(val);
            if (!result) {
                badge.className = 'hidden';
                badge.textContent = '';
                return;
            }
            badge.className = `text-[11px] font-bold px-2 py-0.5 rounded-full border transition-all ${result.color}`;
            badge.textContent = result.label;
        }

        function onRoleChange(role) {
            const idSection = document.getElementById('id-photo-section');
            const idInput   = document.getElementById('id_photo');
            const termsLbl  = document.getElementById('terms-label');

            if (role === 'nurse') {
                if (idSection) idSection.classList.add('hidden');
                if (idInput)   idInput.removeAttribute('required');
                if (termsLbl)  termsLbl.textContent = 'I agree to register with correct information as a Nurse of Barangay Honrado Health Center.';
            } else {
                if (idSection) idSection.classList.remove('hidden');
                if (idInput)   idInput.setAttribute('required', 'required');
                if (termsLbl)  termsLbl.textContent = 'I agree to register with correct information as a resident of Barangay Honrado.';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const ageInput = document.getElementById('age');
            if (ageInput && ageInput.value) {
                updateAgeCategory(ageInput.value);
            }

            const selectedRole = document.querySelector('input[name="role"]:checked');
            if (selectedRole) {
                onRoleChange(selectedRole.value);
            }
        });

        // Drag-and-drop support
        const dropZone = document.getElementById('id-drop-zone');
        dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-green-500','bg-green-50'); });
        dropZone.addEventListener('dragleave', () => { dropZone.classList.remove('border-green-500','bg-green-50'); });
        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('border-green-500','bg-green-50');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const dt = new DataTransfer();
                dt.items.add(file);
                const input = document.getElementById('id_photo');
                input.files = dt.files;
                previewIdPhoto(input);
            }
        });
    </script>

</body>
</html>
