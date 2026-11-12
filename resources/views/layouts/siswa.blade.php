<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-slate-100 flex flex-col transition-transform duration-300 lg:translate-x-0">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 pt-6 pb-2">
        <div class="w-10 h-10 rounded-2xl bg-violet-600 flex items-center justify-center shadow-sm flex-shrink-0">
            <img
                src="{{ asset('images/logo-smpn216.png') }}"
                alt="Logo SMPN 216"
                class="w-14 h-14 object-contain flex-shrink-0">
            <div>
        </div>
        <div>
            <p class="font-bold text-slate-800 text-sm leading-tight">Psycora - SMPN 216 Jakarta</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Sistem Deteksi Dini Kondisi Psikososial Remaja</p>
        </div>
    </div>

    {{-- User card --}}
    <div class="px-3 pt-3 pb-2">
        <button type="button" @click="profileModalOpen = true"
                class="w-full text-left flex items-center gap-3 bg-slate-50 hover:bg-slate-100/70 rounded-2xl px-4 py-3 border border-slate-100 transition-all duration-200 cursor-pointer focus:outline-none">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-pink-400 to-rose-400 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-400 truncate mt-0.5">
                    {{ Auth::user()->studentClass->name ?? 'Siswa' }}
                    @if(Auth::user()->nis) · NIS {{ Auth::user()->nis }} @endif
                </p>
            </div>
            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-3 space-y-1 overflow-y-auto">
        @php
            $navItems = [
                ['route' => 'siswa.dashboard',           'label' => 'Dashboard',  'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route' => 'siswa.questionnaire.index', 'label' => 'Kuesioner',  'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                ['route' => 'siswa.riwayat.index',       'label' => 'Riwayat',    'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['route' => 'siswa.chatbot.index',       'label' => 'PsyBot',     'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
            ];
        @endphp

        @foreach($navItems as $item)
            @php $active = request()->routeIs($item['route']) @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200
                      {{ $active
                          ? 'bg-violet-100 text-violet-600'
                          : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                <svg class="w-5 h-5 flex-shrink-0 {{ $active ? 'text-violet-600' : 'text-slate-400' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                <span class="flex-1">{{ $item['label'] }}</span>
                @if($item['route'] === 'siswa.chatbot.index')
                    <span class="text-[9px] px-2 py-0.5 bg-violet-600 text-white rounded-full font-bold tracking-wide">AI</span>
                @endif
            </a>
        @endforeach
    </nav>

    {{-- Logout --}}
    <div class="px-3 pb-6 pt-2 border-t border-slate-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl text-sm font-semibold text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>