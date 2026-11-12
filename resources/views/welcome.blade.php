<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Psycora – Sistem Monitoring Risiko Psikososial & Ketergantungan Gawai Remaja</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo-smpn216-new.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .font-outfit {
                font-family: 'Outfit', sans-serif;
            }
            .font-inter {
                font-family: 'Inter', sans-serif;
            }
            /* Smooth background glow animation */
            @keyframes pulse-glow {
                0%, 100% { transform: scale(1); opacity: 0.6; }
                50% { transform: scale(1.1); opacity: 0.8; }
            }
            .animate-pulse-glow {
                animation: pulse-glow 8s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="font-inter antialiased bg-slate-50 text-slate-800 selection:bg-violet-400 selection:text-white" x-data="{ mobileMenuOpen: false }">
        <!-- Header / Navbar -->
        <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 transition-all duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 sm:h-20">
                    <!-- Logo & Brand -->
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-smpn216.png') }}" alt="Logo SMPN 216 Jakarta" class="w-14 h-14 object-contain flex-shrink-0">
                        <div class="leading-tight">
                            <span class="block font-outfit font-bold text-base sm:text-lg text-slate-800 tracking-tight">SMP Negeri 216 Jakarta</span>
                            <span class="inline-flex items-center gap-1 text-[11px] text-violet-600 font-semibold">
                                <span class="bg-violet-50 px-1.5 py-0.5 rounded">Psycora</span>
                                - Sistem Deteksi Dini Kondisi Psikososial Remaja 
                            </span>
                        </div>
                    </div>

                    <!-- Desktop Navigation Links -->
                    <nav class="hidden md:flex items-center gap-8">
                        <a href="#fitur" class="text-sm font-semibold text-slate-600 hover:text-violet-600 transition-colors">Fitur Utama</a>
                        <a href="#instrumen" class="text-sm font-semibold text-slate-600 hover:text-violet-600 transition-colors">Instrumen Klinis</a>
                        <a href="#cara-kerja" class="text-sm font-semibold text-slate-600 hover:text-violet-600 transition-colors">Cara Kerja</a>
                        <a href="#faq" class="text-sm font-semibold text-slate-600 hover:text-violet-600 transition-colors">FAQ</a>
                    </nav>

                    <!-- Auth Actions -->
                    <div class="hidden md:flex items-center gap-3">
                        @if (Route::has('login'))
                            @auth
                                @php
                                    if(Auth::user()->hasRole('admin')) {
                                        $dashboardUrl = route('admin.dashboard');
                                    } elseif(Auth::user()->hasRole('guru_bk')) {
                                        $dashboardUrl = route('guruBk.dashboard');
                                    } else {
                                        $dashboardUrl = route('siswa.dashboard');
                                    }
                                @endphp
                                <a href="{{ $dashboardUrl }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-violet-400 text-white rounded-lg text-sm font-bold shadow-sm hover:opacity-90 hover:-translate-y-0.5 transition-all duration-200">
                                    Dashboard
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 border border-slate-200 hover:bg-red-50 hover:text-red-600 hover:border-red-100 rounded-lg text-sm font-semibold text-slate-500 transition-all duration-200">
                                        Keluar
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:border-violet-200 hover:text-violet-600 transition-colors px-4 py-2.5 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H3"></path></svg>
                                    Masuk
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 justify-center px-5 py-2.5 bg-violet-400 text-white rounded-lg text-sm font-bold hover:opacity-90 hover:-translate-y-0.5 transition-all duration-200 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"></path></svg>
                                        Daftar Akun
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>

                    <!-- Hamburger Button -->
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg border border-slate-100 bg-slate-50 text-slate-600 focus:outline-none focus:ring-2 focus:ring-violet-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="md:hidden border-b border-slate-100 bg-white" style="display: none;">
                <div class="px-4 pt-2 pb-4 space-y-1">
                    <a href="#fitur" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-slate-600 hover:bg-slate-50 hover:text-violet-600">Fitur</a>
                    <a href="#instrumen" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-slate-600 hover:bg-slate-50 hover:text-violet-600">Instrumen Klinis</a>
                    <a href="#cara-kerja" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-slate-600 hover:bg-slate-50 hover:text-violet-600">Cara Kerja</a>
                    <a href="#faq" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-slate-600 hover:bg-slate-50 hover:text-violet-600">FAQ</a>
                    <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ $dashboardUrl }}" class="flex w-full items-center justify-center px-4 py-3 bg-violet-400 text-white rounded-lg text-sm font-bold shadow-sm">
                                    Dashboard
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center justify-center px-4 py-3 border border-slate-200 text-slate-600 rounded-lg text-sm font-semibold">
                                        Keluar
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="flex w-full items-center justify-center gap-1.5 px-4 py-3 border border-slate-200 text-slate-700 rounded-lg text-sm font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H3"></path></svg>
                                    Masuk
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="flex w-full items-center justify-center gap-1.5 px-4 py-3 bg-violet-400 text-white rounded-lg text-sm font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"></path></svg>
                                        Daftar Akun
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative pt-8 pb-20 lg:pt-16 lg:pb-32 overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center opacity-100" style="background-image: url('{{ asset('images/gedung-smpn216.jpeg') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/10 to-white/5"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-white/5 via-transparent to-white"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                    <!-- Left Column: Copywriting -->
                    <div class="lg:col-span-6 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-violet-100/80 border border-violet-200 text-xs font-semibold text-violet-800 mb-6">
                            <span class="w-3.5 h-3.5"><svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4.26 10.147a60.44 60.44 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347M4.26 10.147A50.64 50.64 0 0 0 1.6 9.334 59.9 59.9 0 0 1 12 3.493a59.9 59.9 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814M4.26 10.147A50.72 50.72 0 0 1 12 13.489a50.7 50.7 0 0 1 7.74-3.342"></path></svg></span>
                            <span>SMP Negeri 216 Jakarta</span>
                        </div>
                        <h1 class="font-outfit font-extrabold text-4xl sm:text-5xl lg:text-6xl text-slate-900 tracking-tight leading-[1.1] mb-6">
                            Deteksi Dini Kondisi <span class="text-violet-500">Psikososial</span> Remaja
                        </h1>
                        <p class="text-base sm:text-lg text-slate-600 leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
                            Psycora membantu siswa memantau kondisi kesehatan psikososial dan mendeteksi tingkat ketergantungan gawai secara mandiri.
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                            @auth
                                <a href="{{ $dashboardUrl }}" class="flex items-center justify-center w-full sm:w-auto px-8 py-4 bg-violet-400 text-white rounded-lg font-bold shadow-sm hover:opacity-90 hover:-translate-y-0.5 transition-all duration-200">
                                    Mulai Deteksi Sekarang
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center justify-center w-full sm:w-auto px-8 py-4 bg-violet-400 text-white rounded-lg font-bold shadow-sm hover:opacity-90 hover:-translate-y-0.5 transition-all duration-200">
                                    Masuk ke Akun
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                                <a href="#instrumen" class="flex items-center justify-center w-full sm:w-auto px-8 py-4 bg-white border border-slate-200 text-slate-700 rounded-lg font-bold hover:bg-slate-50 transition-colors duration-200">
                                    Pelajari Instrumen
                                </a>
                            @endauth
                        </div>
                        <div class="mt-8 flex items-center justify-center lg:justify-start gap-6 text-slate-500 text-sm">
                        </div>
                    </div>

                    <!-- Right Column: Visual Dashboard Mock -->
                    <div class="lg:col-span-6 relative">
                        <div class="relative mx-auto lg:ml-auto lg:mr-0 max-w-[500px]">
                            <!-- Background Accent Glow -->
                            <div class="absolute -inset-1.5 bg-violet-200 rounded-3xl blur-2xl opacity-30"></div>
                            
                            <!-- Premium Main Dashboard Preview Card -->
                            <div class="relative bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden px-6 pt-4 pb-6 sm:px-8 sm:pt-5 sm:pb-8">
                                <div class="border-b border-slate-100 pb-4 mb-4 pt-2">
                                    <div class="flex items-center justify-center">
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 text-violet-500">
                                                <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3.75 13.5 10.5 3l-1.5 6.75h9L9.75 21l1.5-7.5h-7.5Z"></path></svg>
                                            </span>
                                            <span class="text-sm font-medium text-slate-500">3 Instrumen Terstandar</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Instrument Cards Preview inside Hero -->
                                <div class="space-y-4">
                                    <!-- SDQ Card -->
                                    <div class="bg-violet-50/50 border border-violet-100 rounded-2xl p-4 flex items-center justify-between">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600 p-2 flex-shrink-0"><svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.4 48.4 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.25 2.25 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"></path></svg></div>
                                            <div>
                                                <h4 class="text-xs font-semibold text-slate-800">SDQ (Mental & Perilaku)</h4>
                                                <p class="text-[10px] text-slate-400 mt-0.5">Strengths and Difficulties Questionnaire</p>
                                            </div>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <span class="text-xs font-semibold text-violet-700 bg-violet-100 px-2 py-0.5 rounded-full whitespace-nowrap">25 Pertanyaan</span>
                                        </div>
                                    </div>

                                    <!-- PSC-17 Card -->
                                    <div class="bg-pink-50/50 border border-pink-100 rounded-2xl p-4 flex items-center justify-between">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-10 h-10 rounded-xl bg-pink-100 flex items-center justify-center text-pink-600 p-2 flex-shrink-0"><svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.1 2.1 0 0 1-.825-.242m9.345-8.334a2.1 2.1 0 0 0-.476-.095 48.6 48.6 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.4 48.4 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"></path></svg></div>
                                            <div>
                                                <h4 class="text-xs font-semibold text-slate-800">PSC-17 (Kondisi Psikologis)</h4>
                                                <p class="text-[10px] text-slate-400 mt-0.5">Pediatric Symptom Checklist-17</p>
                                            </div>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <span class="text-xs font-semibold text-pink-700 bg-pink-100 px-2 py-0.5 rounded-full whitespace-nowrap">17 Pertanyaan</span>
                                        </div>
                                    </div>

                                    <!-- SAS-SV Card -->
                                    <div class="bg-sky-50/50 border border-sky-100 rounded-2xl p-4 flex items-center justify-between">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600 p-2 flex-shrink-0"><svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5M10.5 1.5V3h3V1.5m-3 0h3m-3 18.75h3"></path></svg></div>
                                            <div>
                                                <h4 class="text-xs font-semibold text-slate-800">SAS-SV (Ketergantungan Gawai)</h4>
                                                <p class="text-[10px] text-slate-400 mt-0.5">Smartphone Addiction Scale</p>
                                            </div>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <span class="text-xs font-semibold text-sky-700 bg-sky-100 px-2 py-0.5 rounded-full whitespace-nowrap">10 Pertanyaan</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Floating Recommendation Box -->
                                <div class="mt-6 p-3.5 rounded-2xl bg-violet-50 border border-violet-100 flex items-start gap-3">
                                    <span class="w-4 h-4 text-violet-500 flex-shrink-0"><svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456Z"></path></svg></span>
                                    <div>
                                        <p class="text-[10px] font-semibold text-violet-950">PsyBot</p>
                                        <p class="text-[10px] text-slate-600 mt-0.5 leading-relaxed">Chatbot edukatif yang akan membantumu memperoleh informasi seputar Psikososial.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Fitur Unggulan -->
        <section id="fitur" class="py-20 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="font-outfit font-extrabold text-3xl sm:text-4xl text-slate-900 tracking-tight mb-4">
                        Fitur Utama Psycora
                    </h2>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                    <!-- Fitur 1 -->
                    <div class="group bg-slate-50 hover:bg-white rounded-3xl p-6 border border-slate-100 hover:border-violet-100 hover:shadow-xl hover:shadow-violet-500/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-violet-100 group-hover:bg-violet-400 text-violet-600 group-hover:text-white flex items-center justify-center p-2.5 transition-colors duration-300 mb-6"><svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"></path></svg></div>
                        <h3 class="font-outfit font-semibold text-lg text-slate-900 mb-3">Klasifikasi Risiko Otomatis</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Hitung otomatis skor dari ketiga instrumen kuesioner ke dalam kategori Berisiko dan Tidak Berisiko Psikososial.
                        </p>
                    </div>

                    <!-- Fitur 2 -->
                    <div class="group bg-slate-50 hover:bg-white rounded-3xl p-6 border border-slate-100 hover:border-violet-100 hover:shadow-xl hover:shadow-violet-500/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-violet-100 group-hover:bg-violet-400 text-violet-600 group-hover:text-white flex items-center justify-center p-2.5 transition-colors duration-300 mb-6"><svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456Z"></path></svg></div>
                        <h3 class="font-outfit font-semibold text-lg text-slate-900 mb-3">PsyBot</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Konsultasikan manajemen waktu, regulasi emosi, dan tips mengurangi ketergantungan gawai secara langsung.
                        </p>
                    </div>

                    <!-- Fitur 3 -->
                    <div class="group bg-slate-50 hover:bg-white rounded-3xl p-6 border border-slate-100 hover:border-violet-100 hover:shadow-xl hover:shadow-violet-500/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-violet-100 group-hover:bg-violet-400 text-violet-600 group-hover:text-white flex items-center justify-center p-2.5 transition-colors duration-300 mb-6"><svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.34 9.34 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.32 12.32 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"></path></svg></div>
                        <h3 class="font-outfit font-semibold text-lg text-slate-900 mb-3">Dashboard Guru BK</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Visualisasi data analitis sekolah untuk memantau tren perkembangan psikososial siswa demi rancangan tindakan preventif.
                        </p>
                    </div>

                    <!-- Fitur 4 -->
                    <div class="group bg-slate-50 hover:bg-white rounded-3xl p-6 border border-slate-100 hover:border-violet-100 hover:shadow-xl hover:shadow-violet-500/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-violet-100 group-hover:bg-violet-400 text-violet-600 group-hover:text-white flex items-center justify-center p-2.5 transition-colors duration-300 mb-6"><svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.96 11.96 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286Z"></path></svg></div>
                        <h3 class="font-outfit font-semibold text-lg text-slate-900 mb-3">Privasi & Keamanan Data</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Setiap data hasil kuesioner disimpan dengan aman dan bersifat rahasia, hanya dapat diakses oleh Guru BK yang memiliki otoritas resmi.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Instrumen Klinis -->
        <section id="instrumen" class="py-20 bg-slate-50 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="font-outfit font-extrabold text-3xl sm:text-4xl text-slate-900 tracking-tight mb-4">
                        Tiga Instrumen Skrining Terstandar
                    </h2>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Instrumen 1: SDQ -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-violet-100/30 rounded-bl-[80px] pointer-events-none"></div>
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-violet-100 flex items-center justify-center text-violet-600 p-2.5 mb-6"><svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.4 48.4 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.25 2.25 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"></path></svg></div>
                            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-2">SDQ</h3>
                            <p class="text-xs font-semibold text-violet-600 tracking-wide mb-4">Strengths and Difficulties Questionnaire</p>
                            <p class="text-slate-500 text-xs leading-relaxed mb-6">
                                Mengukur kekuatan dan kesulitan perilaku serta emosional siswa. Instrumen ini mendeteksi 5 subskala: gejala emosional, masalah perilaku, hiperaktivitas, masalah hubungan teman sebaya, dan perilaku prososial.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-600">
                            <span>25 Butir Soal</span>
                            <span class="bg-violet-50 text-violet-700 px-2.5 py-1 rounded-full">Kesehatan Mental</span>
                        </div>
                    </div>

                    <!-- Instrumen 2: PSC-17 -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-pink-100/30 rounded-bl-[80px] pointer-events-none"></div>
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-pink-100 flex items-center justify-center text-pink-600 p-2.5 mb-6"><svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.1 2.1 0 0 1-.825-.242m9.345-8.334a2.1 2.1 0 0 0-.476-.095 48.6 48.6 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.4 48.4 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"></path></svg></div>
                            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-2">PSC-17</h3>
                            <p class="text-xs font-semibold text-pink-600 tracking-wide mb-4">Pediatric Symptom Checklist-17</p>
                            <p class="text-slate-500 text-xs leading-relaxed mb-6">
                                Skrining cepat kondisi psikososial anak dan remaja. Berguna mendeteksi gangguan dalam tiga dimensi utama: dimensi internalisasi (depresi/kecemasan), eksternalisasi (perilaku menentang), dan atensi (hiperaktif/sulit fokus).
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-600">
                            <span>17 Butir Soal</span>
                            <span class="bg-pink-50 text-pink-700 px-2.5 py-1 rounded-full">Kondisi Psikis</span>
                        </div>
                    </div>

                    <!-- Instrumen 3: SAS-SV -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-sky-100/30 rounded-bl-[80px] pointer-events-none"></div>
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-sky-100 flex items-center justify-center text-sky-600 p-2.5 mb-6"><svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5M10.5 1.5V3h3V1.5m-3 0h3m-3 18.75h3"></path></svg></div>
                            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-2">SAS-SV</h3>
                            <p class="text-xs font-semibold text-sky-600 tracking-wide mb-4">Smartphone Addiction Scale - Short Version</p>
                            <p class="text-slate-500 text-xs leading-relaxed mb-6">
                                Mengukur kecenderungan tingkat ketergantungan atau adiksi penggunaan gawai (smartphone) pada remaja. Mengidentifikasi dampak negatif gawai pada keseharian, manajemen waktu, dan relasi sosial.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-600">
                            <span>10 Butir Soal</span>
                            <span class="bg-sky-50 text-sky-700 px-2.5 py-1 rounded-full">Ketergantungan Gawai</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Cara Kerja -->
        <section id="cara-kerja" class="py-20 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="font-outfit font-extrabold text-3xl sm:text-4xl text-slate-900 tracking-tight mb-4">
                        Alur Penggunaan Psycora
                    </h2>
                </div>

                <div class="relative">
                    <!-- Connecting line for desktop -->
                    <div class="hidden lg:block absolute top-1/2 left-4 right-4 h-0.5 bg-slate-100 -translate-y-1/2 z-0"></div>

                    <div class="grid lg:grid-cols-4 gap-8 relative z-10">
                        <!-- Step 1 -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow text-center">
                            <div class="w-10 h-10 rounded-full bg-violet-100 text-violet-700 font-semibold text-sm flex items-center justify-center mx-auto mb-4">
                                01
                            </div>
                            <h4 class="font-outfit font-semibold text-base text-slate-900 mb-2">Masuk ke Sistem</h4>
                            <p class="text-slate-500 text-[11px] leading-relaxed">
                                Siswa masuk dengan mendaftarkan akun menggunakan email masing-masing.
                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow text-center">
                            <div class="w-10 h-10 rounded-full bg-violet-100 text-violet-700 font-semibold text-sm flex items-center justify-center mx-auto mb-4">
                                02
                            </div>
                            <h4 class="font-outfit font-semibold text-base text-slate-900 mb-2">Isi Kuesioner Mandiri</h4>
                            <p class="text-slate-500 text-[11px] leading-relaxed">
                                Menjawab 52 pertanyaan dari instrumen terpadu (SDQ, PSC-17, SAS-SV) secara jujur. Hanya butuh 5-10 menit.
                            </p>
                        </div>

                        <!-- Step 3 -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow text-center">
                            <div class="w-10 h-10 rounded-full bg-violet-100 text-violet-700 font-semibold text-sm flex items-center justify-center mx-auto mb-4">
                                03
                            </div>
                            <h4 class="font-outfit font-semibold text-base text-slate-900 mb-2">Hasil Skrining Risiko</h4>
                            <p class="text-slate-500 text-[11px] leading-relaxed">
                                Hasil skor dan klasifikasi risiko (Berisiko dan Tidak) langsung ditampilkan secara dinamis.
                            </p>
                        </div>

                        <!-- Step 4 -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow text-center">
                            <div class="w-10 h-10 rounded-full bg-violet-100 text-violet-700 font-semibold text-sm flex items-center justify-center mx-auto mb-4">
                                04
                            </div>
                            <h4 class="font-outfit font-semibold text-base text-slate-900 mb-2">Konsultasi PsyBot & BK</h4>
                            <p class="text-slate-500 text-[11px] leading-relaxed">
                                Konsultasikan rekomendasi pencegahan dengan Chatbot, atau dapatkan bimbingan konseling langsung dari Guru BK.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: FAQ -->
        <section id="faq" class="py-20 bg-slate-50 relative" x-data="{ activeFaq: null }">
            <div class="max-w-4xl mx-auto px-4 sm:px-6">
                <div class="text-center mb-16">
                    <h2 class="font-outfit font-extrabold text-3xl text-slate-900 tracking-tight mb-4">
                        Pertanyaan Umum (FAQ)
                    </h2>
                </div>

                <div class="space-y-4">
                    <!-- FAQ 1 -->
                    <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden transition-all duration-200">
                        <button type="button" @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full text-left px-6 py-5 flex items-center justify-between font-semibold text-slate-800 text-sm focus:outline-none">
                            <span>Apa itu Psycora?</span>
                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="activeFaq === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeFaq === 1" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="px-6 pb-5 text-xs text-slate-500 leading-relaxed border-t border-slate-50 pt-4" style="display: none;">
                            Psycora adalah platform berbasis web interaktif yang dikembangkan khusus untuk memantau kondisi kesehatan psikososial remaja serta mendeteksi tingkat ketergantungan gawai secara mandiri di SMP Negeri 216 Jakarta. Sistem ini mengintegrasikan instrumen penilaian klinis terpercaya (SDQ, PSC-17, SAS-SV).
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden transition-all duration-200">
                        <button type="button" @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full text-left px-6 py-5 flex items-center justify-between font-semibold text-slate-800 text-sm focus:outline-none">
                            <span>Berapa lama waktu yang diperlukan untuk mengisi kuesioner?</span>
                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="activeFaq === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeFaq === 2" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="px-6 pb-5 text-xs text-slate-500 leading-relaxed border-t border-slate-50 pt-4" style="display: none;">
                            Kuesioner terpadu terdiri dari 52 butir pertanyaan singkat. Rata-rata siswa memerlukan waktu sekitar 10 hingga 15 menit untuk menyelesaikan seluruh pertanyaan dengan jujur sesuai kondisi masing-masing.
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden transition-all duration-200">
                        <button type="button" @click="activeFaq = (activeFaq === 3 ? null : 3)" class="w-full text-left px-6 py-5 flex items-center justify-between font-semibold text-slate-800 text-sm focus:outline-none">
                            <span>Apakah hasil kuesioner saya bersifat rahasia?</span>
                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="activeFaq === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeFaq === 3" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="px-6 pb-5 text-xs text-slate-500 leading-relaxed border-t border-slate-50 pt-4" style="display: none;">
                            Ya, privasi adalah prioritas utama kami. Seluruh hasil skrining disimpan dalam database yang aman dan hanya dapat diakses oleh Guru Bimbingan Konseling (BK) berwenang di sekolah untuk keperluan bimbingan konseling dan pencegahan dini. Teman sekelas atau pihak eksternal tidak memiliki akses ke data Anda.
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden transition-all duration-200">
                        <button type="button" @click="activeFaq = (activeFaq === 4 ? null : 4)" class="w-full text-left px-6 py-5 flex items-center justify-between font-semibold text-slate-800 text-sm focus:outline-none">
                            <span>Bagaimana cara kerja PsyBot?</span>
                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="activeFaq === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeFaq === 4" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="px-6 pb-5 text-xs text-slate-500 leading-relaxed border-t border-slate-50 pt-4" style="display: none;">
                            PsyBot adalah chatbot edukatif yang dirancang untuk menjawab pertanyaan siswa secara ramah dan memberikan tips seputar kesehatan mental, regulasi emosi, manajemen waktu penggunaan gawai, serta kiat aktivitas fisik positif.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-400 py-12 sm:py-16 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12 sm:mb-16">
                    <!-- Brand info -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="font-outfit font-extrabold text-lg text-white tracking-tight">Psycora</span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Sistem Monitoring Risiko Psikososial & Ketergantungan Gawai Remaja Terintegrasi.
                        </p>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            SMP Negeri 216 Jakarta.
                        </p>
                    </div>

                    <!-- Links: Fitur -->
                    <div>
                        <h4 class="font-outfit font-semibold text-white text-sm mb-4">Navigasi</h4>
                        <ul class="space-y-2.5 text-xs">
                            <li><a href="#fitur" class="hover:text-white transition-colors">Fitur Utama</a></li>
                            <li><a href="#instrumen" class="hover:text-white transition-colors">Instrumen Klinis</a></li>
                            <li><a href="#cara-kerja" class="hover:text-white transition-colors">Cara Kerja Aplikasi</a></li>
                            <li><a href="#faq" class="hover:text-white transition-colors">Pertanyaan Umum (FAQ)</a></li>
                        </ul>
                    </div>

                    <!-- Links: Partner -->
                    <div>
                        <h4 class="font-outfit font-semibold text-white text-sm mb-4">Instrumen</h4>
                        <ul class="space-y-2.5 text-xs">
                            <li><a href="https://www.sdqinfo.org/" target="_blank" rel="noopener" class="hover:text-white transition-colors">SDQ Official Info</a></li>
                            <li><a href="https://www.massgeneral.org/psychiatry/treatments-and-services/pediatric-symptom-checklist" target="_blank" rel="noopener" class="hover:text-white transition-colors">PSC-17 Resources</a></li>
                            <li><a href="https://pubmed.ncbi.nlm.nih.gov/24424970/" target="_blank" rel="noopener" class="hover:text-white transition-colors">SAS-SV Publication</a></li>
                        </ul>
                    </div>

                    <!-- Links: Contact -->
                    <div>
                        <h4 class="font-outfit font-semibold text-white text-sm mb-4">Hubungi Kami</h4>
                        <p class="text-xs leading-relaxed text-slate-500 mb-2">
                            SMP Negeri 216 Jakarta
                        </p>
                        <p class="text-xs leading-relaxed text-slate-500">
                            Jl. Salemba Raya No. 18, RW.4, Kenari, Kec. Senen, Kota Jakarta Pusat, DKI Jakarta 10430
                        </p>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 text-center">
                    <p>&copy; {{ date('Y') }} Psycora</p>
                </div>
            </div>
        </footer>
    </body>
</html>