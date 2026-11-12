<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="MEDLINK — Barangay Honrado Health Center. Check medicine availability, request home health service, and stay updated on health schedules — all online.">
    <title>MEDLINK — Barangay Honrado Health Center</title>

    {{-- Tailwind CSS v4 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    {{-- Google Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <style type="text/tailwindcss">
        @theme {
            --font-display: 'Plus Jakarta Sans', sans-serif;
            --font-body: 'Inter', sans-serif;
        }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @vite(['resources/js/medlink.js'])
</head>
<body class="h-full bg-slate-950 text-slate-100 antialiased selection:bg-emerald-500 selection:text-white flex flex-col justify-between">

{{-- ══════════════════════════════════════════════════════════════
     TOP HEADER (Plain text labels, NO icons in menu bar)
══════════════════════════════════════════════════════════════ --}}
<header class="absolute top-0 left-0 w-full z-50 px-4 sm:px-8 lg:px-12 py-5">
    <div class="w-full flex items-center justify-between">
        
        {{-- 1. Brand Logo (Strictly Left) --}}
        <a href="/" class="flex items-center gap-3 group shrink-0">
            <img src="{{ asset('ico.jpg') }}" alt="MEDLINK Logo" 
                 class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl object-cover shadow-lg border border-white/20 group-hover:scale-105 transition-transform">
            <div>
                <div class="font-display font-black text-white text-lg sm:text-xl tracking-tight leading-none flex items-center gap-2">
                    MEDLINK
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">ONLINE</span>
                </div>
                <div class="text-xs text-slate-300/80 mt-1 font-medium">Barangay Honrado Health Center</div>
            </div>
        </a>

        {{-- 2. Center Menu: Pure Text (NO ICONS) --}}
        <nav class="hidden md:flex items-center gap-9 mx-auto">
            <a href="/" class="text-sm font-bold text-emerald-400 hover:text-emerald-300 transition-colors drop-shadow">
                Home
            </a>
            <a href="#about-us" class="text-sm font-semibold text-slate-200 hover:text-emerald-400 transition-colors drop-shadow">
                About Us
            </a>
            <a href="#gallery" class="text-sm font-semibold text-slate-200 hover:text-emerald-400 transition-colors drop-shadow">
                Health Center Photos
            </a>
            <a href="#services" class="text-sm font-semibold text-slate-200 hover:text-emerald-400 transition-colors drop-shadow">
                Services
            </a>
            <a href="#stats" class="text-sm font-semibold text-slate-200 hover:text-emerald-400 transition-colors drop-shadow">
                Community Data
            </a>
        </nav>

        {{-- 3. Right Space Spacer (Keeps center menu balanced) --}}
        <div class="shrink-0 w-10 md:w-48 flex justify-end">
            {{-- Mobile hamburger for small screens --}}
            <button type="button" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" 
                    class="md:hidden p-2 rounded-xl bg-white/10 text-white hover:bg-white/20 transition-colors">
                <span class="material-icons-outlined">menu</span>
            </button>
        </div>

    </div>

    {{-- Mobile Dropdown Menu --}}
    <div id="mobileMenu" class="hidden md:hidden mt-3 p-4 rounded-2xl bg-slate-900/95 backdrop-blur-md border border-white/15 space-y-3">
        <a href="/" class="block text-sm font-bold text-emerald-400">Home</a>
        <a href="#about-us" class="block text-sm font-semibold text-slate-200 hover:text-emerald-400">About Us</a>
        <a href="#gallery" class="block text-sm font-semibold text-slate-200 hover:text-emerald-400">Health Center Photos</a>
        <a href="#services" class="block text-sm font-semibold text-slate-200 hover:text-emerald-400">Services</a>
        <a href="#stats" class="block text-sm font-semibold text-slate-200 hover:text-emerald-400">Community Data</a>
    </div>
</header>

{{-- ══════════════════════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════════════════════ --}}
<section class="relative w-full min-h-[92vh] flex items-center justify-start overflow-hidden px-4 sm:px-8 lg:px-16 pt-28 pb-14"
    style="
        background-image:
            linear-gradient(
                90deg,
                rgba(2, 20, 11, 0.92) 0%,
                rgba(3, 30, 17, 0.85) 40%,
                rgba(3, 30, 17, 0.65) 70%,
                rgba(3, 30, 17, 0.35) 100%
            ),
            url('{{ asset('honrado.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    "
>
    <div class="w-full max-w-4xl py-6">

        {{-- Location Pill --}}
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-950/70 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-6 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Brgy. Honrado &bull; San Francisco, Surigao del Norte</span>
        </div>

        {{-- Headline --}}
        <h1 class="font-display text-4xl sm:text-6xl lg:text-7xl font-extrabold text-white tracking-tight leading-[1.08] mb-6">
            Health services,<br>
            <span class="text-emerald-400">right from home.</span>
        </h1>

        {{-- Description --}}
        <p class="text-slate-200/90 text-base sm:text-lg lg:text-xl font-normal leading-relaxed max-w-2xl mb-9">
            MEDLINK lets residents of Barangay Honrado check real-time medicine availability, request home health services, and follow clinic schedules online — all without the repeat trip to the health center.
        </p>

        {{-- In-Hero Sign In & Create Account Actions --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-8">
            @if (Route::has('login'))
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-xl text-base font-bold text-slate-950 bg-emerald-400 hover:bg-emerald-300 transition-all shadow-xl shadow-emerald-950/50 active:scale-95">
                    <span>Sign In to MEDLINK</span>
                    <span class="material-icons-outlined" style="font-size: 20px;">login</span>
                </a>
            @endif
            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center gap-2 px-7 py-4 rounded-xl text-base font-bold text-white bg-white/10 hover:bg-white/15 border border-white/20 backdrop-blur-sm transition-all active:scale-95">
                    <span class="material-icons-outlined" style="font-size: 20px;">person_add</span>
                    <span>Create Account</span>
                </a>
            @endif
        </div>

        {{-- Trust note --}}
        <div class="flex items-center gap-2 text-xs text-slate-300/70 font-medium">
            <span class="material-icons-outlined text-emerald-400" style="font-size: 16px;">verified</span>
            <span>Medicine requests need nurse approval before release. Managed by Honrado Health Center.</span>
        </div>

    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     COMMUNITY STATS STRIP
══════════════════════════════════════════════════════════════ --}}
<section id="stats" class="w-full bg-white text-slate-900 border-t border-slate-200 py-12 px-4 sm:px-8">
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-4 sm:divide-x sm:divide-slate-200 text-center">
        
        <div class="px-6">
            <div class="font-display text-4xl sm:text-5xl font-black text-slate-900">73</div>
            <div class="mt-1 text-sm text-slate-500 font-medium">Residents surveyed in<br><strong class="text-slate-800">Barangay Honrado</strong></div>
        </div>

        <div class="px-6">
            <div class="font-display text-4xl sm:text-5xl font-black text-emerald-600">72.6%</div>
            <div class="mt-1 text-sm text-slate-500 font-medium">Said checking medicines online<br><strong class="text-slate-800">would save them a trip</strong></div>
        </div>

        <div class="px-6">
            <div class="font-display text-4xl sm:text-5xl font-black text-blue-600">45.2%</div>
            <div class="mt-1 text-sm text-slate-500 font-medium">Believe MEDLINK will improve<br><strong class="text-slate-800">healthcare access in the community</strong></div>
        </div>

    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     ABOUT US & HOW MEDLINK HELPS
══════════════════════════════════════════════════════════════ --}}
<section id="about-us" class="w-full bg-slate-50 py-16 sm:py-20 px-4 sm:px-8 border-t border-slate-200">
    <div class="max-w-7xl mx-auto">
        <div class="max-w-2xl mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">About Us</span>
            <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3 tracking-tight">Barangay Honrado Health Center</h2>
            <p class="text-slate-500 text-sm sm:text-base mt-2">
                We are dedicated to providing accessible, transparent, and responsive healthcare services to all families in San Francisco, Surigao del Norte.
            </p>
        </div>

        <div id="services" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Before --}}
            <div class="bg-white p-7 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-rose-600">Traditional System</h3>
                </div>
                <ul class="space-y-3.5 text-sm text-slate-600">
                    <li class="flex items-start gap-2.5">
                        <span class="material-icons-outlined text-rose-500 shrink-0" style="font-size:18px;">close</span>
                        <span>Walk-in trips under heat or rain just to ask if paracetamol or prescription medicine is available.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="material-icons-outlined text-rose-500 shrink-0" style="font-size:18px;">close</span>
                        <span>Manual paper logbooks prone to wear, tear, or misplacement over time.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="material-icons-outlined text-rose-500 shrink-0" style="font-size:18px;">close</span>
                        <span>No live updates on vaccination, prenatal checkup, or ultrasound schedules.</span>
                    </li>
                </ul>
            </div>

            {{-- With Medlink --}}
            <div class="bg-white p-7 sm:p-8 rounded-2xl border-2 border-emerald-500 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-600">With MEDLINK Online System</h3>
                </div>
                <ul class="space-y-3.5 text-sm text-slate-700 font-medium">
                    <li class="flex items-start gap-2.5">
                        <span class="material-icons-outlined text-emerald-600 shrink-0" style="font-size:18px;">check</span>
                        <span>Check available medicine stock and request home delivery or center pickup in seconds.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="material-icons-outlined text-emerald-600 shrink-0" style="font-size:18px;">check</span>
                        <span>Digital patient records, disease surveillance, and child vaccine dose tracking.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="material-icons-outlined text-emerald-600 shrink-0" style="font-size:18px;">check</span>
                        <span>Live barangay health announcements and official notices delivered straight to your phone.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     COMMUNITY GALLERY (pic1, pic2, pic3, pic4, pic5)
══════════════════════════════════════════════════════════════ --}}
<section id="gallery" class="w-full bg-white py-16 sm:py-24 px-4 sm:px-8 border-t border-slate-200">
    <div class="max-w-7xl mx-auto">
        <div class="max-w-2xl mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">Community Activities</span>
            <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3 tracking-tight">Barangay Health Center in Action</h2>
            <p class="text-slate-500 text-sm sm:text-base mt-2">
                Actual moments and documentation of healthcare services, medical missions, and community support in Barangay Honrado.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            {{-- PIC 1 --}}
            <div class="group bg-slate-50 rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <div class="relative h-56 overflow-hidden bg-slate-200">
                    <img src="{{ asset('images/pic1.jpg') }}" alt="pic1 - Health Center Consultation" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-3 left-3 bg-slate-950/70 backdrop-blur-xs text-white text-[11px] font-bold px-2.5 py-1 rounded-md">
                        pic1
                    </span>
                </div>
                <div class="p-5">
                    <h3 class="font-display font-bold text-base text-slate-900">Health Center Facility & Clinic</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        The Barangay Honrado Health Center building, serving as the central hub for local consultations, walk-in patient triage, and daily medical inquiries.
                    </p>
                </div>
            </div>

            {{-- PIC 2 --}}
            <div class="group bg-slate-50 rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <div class="relative h-56 overflow-hidden bg-slate-200">
                    <img src="{{ asset('images/pic2.jpg') }}" alt="pic2 - Medicine Dispensing" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-3 left-3 bg-slate-950/70 backdrop-blur-xs text-white text-[11px] font-bold px-2.5 py-1 rounded-md">
                        pic2
                    </span>
                </div>
                <div class="p-5">
                    <h3 class="font-display font-bold text-base text-slate-900">Medicine Dispensing & Inventory</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Accredited barangay medicine stockroom where paracetamol, maintenance drugs, antibiotics, and vitamins are stored and verified by our nurse.
                    </p>
                </div>
            </div>

            {{-- PIC 3 --}}
            <div class="group bg-slate-50 rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <div class="relative h-56 overflow-hidden bg-slate-200">
                    <img src="{{ asset('images/pic3.jpg') }}" alt="pic3 - Child Immunization" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-3 left-3 bg-slate-950/70 backdrop-blur-xs text-white text-[11px] font-bold px-2.5 py-1 rounded-md">
                        pic3
                    </span>
                </div>
                <div class="p-5">
                    <h3 class="font-display font-bold text-base text-slate-900">Child & Infant Immunization Day</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        National Immunization Program sessions administering 1st dose, 2nd dose, and booster vaccines for toddlers, babies, and school-age children.
                    </p>
                </div>
            </div>

            {{-- PIC 4 --}}
            <div class="group bg-slate-50 rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <div class="relative h-56 overflow-hidden bg-slate-200">
                    <img src="{{ asset('images/pic4.jpg') }}" alt="pic4 - Home Service & Delivery" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-3 left-3 bg-slate-950/70 backdrop-blur-xs text-white text-[11px] font-bold px-2.5 py-1 rounded-md">
                        pic4
                    </span>
                </div>
                <div class="p-5">
                    <h3 class="font-display font-bold text-base text-slate-900">BHW Home Visits & Delivery</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Dedicated Barangay Health Workers bringing essential maintenance medicines directly to the doorsteps of seniors, persons with disabilities, and bedridden residents.
                    </p>
                </div>
            </div>

            {{-- PIC 5 --}}
            <div class="group bg-slate-50 rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-all sm:col-span-2 lg:col-span-1">
                <div class="relative h-56 overflow-hidden bg-slate-200">
                    <img src="{{ asset('images/pic5.jpg') }}" alt="pic5 - Community Health Surveillance" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-3 left-3 bg-slate-950/70 backdrop-blur-xs text-white text-[11px] font-bold px-2.5 py-1 rounded-md">
                        pic5
                    </span>
                </div>
                <div class="p-5">
                    <h3 class="font-display font-bold text-base text-slate-900">Health Surveillance & Community Care</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Barangay health workers actively monitoring community wellness, coordinating Dengue awareness drives, and checking vital signs for our residents.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     FOOTER
══════════════════════════════════════════════════════════════ --}}
<footer class="w-full bg-slate-900 text-slate-400 py-8 px-4 sm:px-8 border-t border-slate-800 text-xs">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <span class="font-bold text-white text-sm">MEDLINK</span> &bull; Barangay Honrado Health Center
            <p class="text-[11px] text-slate-500 mt-0.5">San Francisco, Surigao del Norte</p>
        </div>
        <p class="text-[11px]">&copy; {{ date('Y') }} Barangay Honrado Health Center. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
