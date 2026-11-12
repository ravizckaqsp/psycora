@props(['pageTitle' => 'Monitoring Bimbingan Konseling', 'pageSubtitle' => null])

<!DOCTYPE html>
<html lang="id" class="min-h-screen">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} – Panel Guru Bimbingan Konseling</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-smpn216-new.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-50 font-inter antialiased" x-data="{ sidebarOpen: false, profileModalOpen: false }">

{{-- Mobile overlay --}}
<div x-show="sidebarOpen" x-transition.opacity
     @click="sidebarOpen = false"
     class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm lg:hidden"
     style="display:none"></div>

{{-- Sidebar --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-xl flex flex-col transition-transform duration-300 lg:translate-x-0">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100">
        <img src="{{ asset('images/logo-smpn216-new.png') }}" alt="Logo SMPN 216" class="w-12 h-12 object-contain flex-shrink-0">
        <div class="min-w-0 flex-1">
            <p class="font-bold text-slate-800 text-sm leading-tight truncate">SMPN 216 Jakarta</p>
            <p class="text-xs text-indigo-500 font-semibold mt-0.5 truncate">Guru BK Portal</p>
        </div>
    </div>

    {{-- User info --}}
    <div class="px-4 py-4 border-b border-slate-100">
        <button type="button" @click="profileModalOpen = true" class="w-full text-left flex items-center gap-3 bg-indigo-50/50 hover:bg-indigo-100/80 rounded-xl p-3 transition-colors cursor-pointer focus:outline-none">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-sky-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-indigo-600 font-medium truncate">Konselor Sekolah</p>
            </div>
            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @php
            $navItems = [
                ['route' => 'guruBk.dashboard', 'label' => 'Dashboard BK', 'match' => 'guruBk.dashboard',
                 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z'],
                ['route' => 'guruBk.bi',        'label' => 'Kelas',  'match' => 'guruBk.bi',
                 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['route' => 'guruBk.siswa.index', 'label' => 'Data Siswa', 'match' => 'guruBk.siswa.*',
                 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
            ];
        @endphp

        @foreach($navItems as $item)
            @php $active = request()->routeIs($item['match']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ $active ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    {{-- Logout --}}
    <div class="px-3 py-4 border-t border-slate-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-medium text-slate-500 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- Main content --}}
<div class="lg:pl-64 flex flex-col min-h-screen">

    {{-- Top bar --}}
    <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100 px-4 sm:px-6 py-3.5 flex items-center gap-4">
        {{-- Hamburger --}}
        <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Breadcrumb / Title --}}
        <div class="flex-1 min-w-0">
            <h1 class="text-base font-bold text-slate-800 truncate">{{ $pageTitle }}</h1>
            @if($pageSubtitle)
                <p class="text-xs text-slate-500 font-medium truncate">{{ $pageSubtitle }}</p>
            @endif
        </div>

        {{-- Role badge --}}
        <div class="flex items-center gap-2">
            <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-semibold rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                Sesi Guru BK Aktif
            </span>
        </div>
    </header>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="mx-4 sm:mx-6 mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mx-4 sm:mx-6 mt-4 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Page content --}}
    <main class="flex-1 p-4 sm:p-6 page-enter">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="py-4 px-6 text-center text-xs text-slate-400 border-t border-slate-100">
        Psycora &copy; {{ date('Y') }} · Panel Pemantauan Gangguan Perilaku & Kecanduan Gawai Remaja
    </footer>
</div>

    {{-- Profile Info Modal --}}
    <div x-show="profileModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="profileModalOpen = false">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="bg-indigo-600 px-6 py-6 text-white relative">
                    <button type="button" @click="profileModalOpen = false" class="absolute top-4 right-4 text-white/80 hover:text-white transition focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md border border-white/20 flex items-center justify-center text-white font-bold text-2xl shadow-inner">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold leading-tight">{{ Auth::user()->name }}</h3>
                            <span class="inline-flex items-center bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full mt-1.5 uppercase tracking-wide">
                                Konselor Sekolah
                            </span>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-4 bg-slate-50/50">
                    <div class="bg-white rounded-2xl border border-slate-100 p-4 space-y-3 shadow-sm">
                        <div class="flex justify-between items-center py-1 border-b border-slate-50">
                            <span class="text-xs font-semibold text-slate-400">NIP</span>
                            <span class="text-xs font-bold text-slate-700">{{ Auth::user()->nip ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-slate-50">
                            <span class="text-xs font-semibold text-slate-400">Email</span>
                            <span class="text-xs font-bold text-slate-700">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-xs font-semibold text-slate-400">Jenis Kelamin</span>
                            <span class="text-xs font-bold text-slate-700">
                                @if(Auth::user()->gender === 'L')
                                    Laki-laki
                                @elseif(Auth::user()->gender === 'P')
                                    Perempuan
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                    <button type="button" @click="profileModalOpen = false" 
                            class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition cursor-pointer outline-none">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

@stack('scripts')
</body>
</html>