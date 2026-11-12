<!DOCTYPE html>
<html lang="id" class="min-h-screen">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk – Psycora</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-smpn216-new.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ── Left panel original solid background ── */
        .panel-bg {
            background: #a78bfa;
        }

        /* Decorative circles (original style) */
        .deco-circle {
            position: absolute;
            border-radius: 9999px;
            background: rgba(255,255,255,.05);
        }

        /* Float animation on card */
        .float-card { animation: floatCard 7s ease-in-out infinite; }
        @keyframes floatCard {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-10px); }
        }

        /* Input focus ring */
        .field-input {
            transition: border-color .18s, box-shadow .18s;
        }
        .field-input:focus {
            outline: none;
            border-color: #a78bfa;
            box-shadow: 0 0 0 3px rgba(167,139,250,.18);
        }
        .field-input.error {
            border-color: #fca5a5;
            box-shadow: 0 0 0 3px rgba(252,165,165,.18);
        }

        /* CTA button */
        .btn-primary {
            background: #a78bfa;
            transition: opacity .18s, transform .12s, box-shadow .18s;
        }
        .btn-primary:hover  { opacity: .92; box-shadow: 0 8px 20px rgba(139,92,246,.3); }
        .btn-primary:active { transform: scale(.98); }
        .btn-primary:disabled { opacity: .65; cursor: not-allowed; }

        /* Checkbox bigger hit area */
        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 6px 0;
        }
        .checkbox-wrap input[type="checkbox"] {
            width: 18px; height: 18px; min-width: 18px;
            border-radius: 5px;
            accent-color: #8b5cf6;
            cursor: pointer;
        }

        /* Soft badge on left panel */
        .info-badge {
            background: rgba(255,255,255,.25);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,.4);
        }

        /* Spinner */
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner { animation: spin .7s linear infinite; }

        /* Page entrance */
        .fade-up {
            animation: fadeUp .4s ease-out both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up-1 { animation-delay: .05s; }
        .fade-up-2 { animation-delay: .12s; }
        .fade-up-3 { animation-delay: .19s; }
        .fade-up-4 { animation-delay: .26s; }
        .fade-up-5 { animation-delay: .33s; }
    </style>
</head>
<body class="min-h-screen bg-slate-50">

<div class="min-h-screen flex">

    {{-- ════════════════════════════════════════
         LEFT PANEL – Branding (original dark)
    ════════════════════════════════════════ --}}
    <div class="hidden lg:flex lg:w-[40%] panel-bg relative overflow-hidden flex-col items-center justify-center px-10 py-10">

        {{-- Decorative circles --}}
        <div class="deco-circle absolute top-[-80px] left-[-80px] w-80 h-80"></div>
        <div class="deco-circle absolute bottom-[-60px] right-[-60px] w-64 h-64"></div>
        <div class="absolute top-1/2 left-0 w-2 h-32 bg-white/10 rounded-r-full"></div>

        {{-- Floating info card --}}
        <div class="float-card info-badge rounded-2xl p-6 mb-8 w-full max-w-sm shadow-2xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-14 h-14 rounded-2xl bg-violet-350 flex items-center justify-center shadow-sm flex-shrink-0">
                    <img src="{{ asset('images/logo-smpn216.png') }}" alt="Logo SMPN 216" class="w-14 h-14 object-contain flex-shrink-0">
                </div>
                <div>
                    <p class="text-white font-bold text-sm">SMPN 216 Jakarta</p>
                    <p class="text-white/60 text-xs mt-1">Psycora - Sistem Deteksi Dini Kondisi Psikososial Remaja</p>
                </div>
            </div>
            <div class="space-y-3">
                @foreach(['SDQ – Kesehatan Mental & Perilaku', 'PSC-17 – Gejala Psikososial', 'SAS-SV – Adiksi Smartphone'] as $item)
                    <div class="flex items-center gap-2 text-white/80 text-sm">
                        <svg class="w-4 h-4 text-emerald-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ $item }}
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Headline --}}
        <h1 class="text-white font-extrabold text-3xl text-center leading-tight mb-3">
            Kenali Kesehatan<br>Psikososialmu
        </h1>
        <p class="text-white/70 text-center text-sm max-w-xs leading-relaxed">
            Sistem untuk memantau dan menganalisis risiko psikososial remaja dan pola penggunaan gawai.
        </p>

        {{-- Stats --}}
    </div>

    {{-- ════════════════════════════════════════
         RIGHT PANEL – Login Form
    ════════════════════════════════════════ --}}
    <div class="flex-1 flex items-center justify-center px-6 py-12 bg-white">
        <div class="w-full max-w-md">

            {{-- Mobile logo (only visible on small screens) --}}
            <div class="lg:hidden flex items-center gap-3 mb-8 fade-up">
                <img src="{{ asset('images/logo-smpn216.png') }}" alt="Logo SMPN 216" class="w-14 h-14 object-contain flex-shrink-0">
                <div>
                    <p class="font-bold text-slate-800 text-sm">SMPN 216 Jakarta</p>
                    <p class="text-slate-500 text-xs mt-0.5 leading-snug">Psycora - Sistem Deteksi Dini Kondisi Psikososial Remaja</p>
                </div>
            </div>

            {{-- Heading --}}
            <div class="fade-up fade-up-1">
                <h2 class="text-2xl font-bold text-slate-800">Selamat datang!</h2>
                <p class="text-slate-500 text-sm mt-1 mb-7">Masuk ke akun kamu untuk melanjutkan</p>
            </div>

            {{-- Session status --}}
            @if(session('status'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2 fade-up fade-up-1">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            {{-- General auth error (wrong credentials) --}}
            @if($errors->any() && !$errors->has('email') && !$errors->has('password'))
                <div class="mb-5 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm flex items-center gap-2 fade-up fade-up-1">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    Email atau password salah. Coba lagi ya!
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5" id="loginForm">
                @csrf

                {{-- Email --}}
                <div class="fade-up fade-up-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="email">Email</label>
                    <input
                        id="email" type="email" name="email"
                        value="{{ old('email') }}"
                        required autofocus autocomplete="email"
                        placeholder="siswa@gmail.com"
                        class="field-input w-full px-4 py-3 bg-slate-50 border {{ $errors->has('email') ? 'error border-red-300' : 'border-slate-200' }} rounded-xl text-slate-800 text-sm placeholder-slate-400"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="fade-up fade-up-3">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-sm font-semibold text-slate-700" for="password">Password</label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-xs text-violet-500 hover:text-violet-700 underline underline-offset-2 font-medium transition-colors">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    {{-- Password field + toggle --}}
                    <div class="relative">
                        <input
                            id="password" type="password" name="password"
                            required autocomplete="current-password"
                            placeholder="Masukkan password kamu"
                            class="field-input w-full px-4 py-3 pr-12 bg-slate-50 border {{ $errors->has('password') ? 'error border-red-300' : 'border-slate-200' }} rounded-xl text-slate-800 text-sm placeholder-slate-400"
                        >
                        {{-- Show / hide password toggle --}}
                        <button type="button" id="togglePassword"
                                aria-label="Tampilkan atau sembunyikan password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-violet-500 transition-colors p-1">
                            {{-- Eye icon (password hidden state) --}}
                            <svg id="iconEye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{-- Eye-off icon (password visible state) --}}
                            <svg id="iconEyeOff" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember me – large tap area --}}
                <div class="fade-up fade-up-4">
                    <label class="checkbox-wrap select-none">
                        <input id="remember" type="checkbox" name="remember"
                               class="rounded border-slate-300 text-violet-600 focus:ring-violet-400 focus:ring-offset-0">
                        <span class="text-sm text-slate-600">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit button --}}
                <div class="fade-up fade-up-5">
                    <button type="submit" id="submitBtn"
                            class="btn-primary w-full text-white font-semibold py-3.5 px-4 rounded-xl text-sm flex items-center justify-center gap-2">
                        {{-- Default state --}}
                        <span id="btnContent" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Masuk
                        </span>
                        {{-- Loading state --}}
                        <span id="btnLoading" class="hidden flex items-center gap-2">
                            <svg class="spinner w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Sedang masuk...
                        </span>
                    </button>
                </div>

                {{-- Register link --}}
                <p class="text-center text-sm text-slate-500 fade-up fade-up-5">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-violet-600 font-semibold hover:text-violet-800 underline underline-offset-2 transition-colors">
                        Daftar di sini
                    </a>
                </p>

            </form>
        </div>
    </div>

</div>

<script>
    /* ── Toggle show/hide password ── */
    const toggleBtn  = document.getElementById('togglePassword');
    const pwdInput   = document.getElementById('password');
    const iconEye    = document.getElementById('iconEye');
    const iconEyeOff = document.getElementById('iconEyeOff');

    toggleBtn.addEventListener('click', () => {
        const isHidden = pwdInput.type === 'password';
        pwdInput.type  = isHidden ? 'text' : 'password';
        iconEye.classList.toggle('hidden', isHidden);
        iconEyeOff.classList.toggle('hidden', !isHidden);
    });

    /* ── Loading state on submit ── */
    document.getElementById('loginForm').addEventListener('submit', () => {
        const btn     = document.getElementById('submitBtn');
        const content = document.getElementById('btnContent');
        const loading = document.getElementById('btnLoading');
        btn.disabled = true;
        content.classList.add('hidden');
        loading.classList.remove('hidden');
    });
</script>

</body>
</html>