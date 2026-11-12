<!DOCTYPE html>
<html lang="id" class="min-h-screen">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun – Psycora</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-smpn216-new.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .panel-bg {
            background: #a78bfa;
        }
        .deco-circle {
            position: absolute;
            border-radius: 9999px;
            background: rgba(255,255,255,.05);
        }
        .float-card { animation: floatCard 7s ease-in-out infinite; }
        @keyframes floatCard {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-10px); }
        }

        /* Soft badge on left panel */
        .info-badge {
            background: rgba(255,255,255,.25);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,.4);
        }

        /* Step indicator */
        .step-line {
            height: 2px;
            background: #e2e8f0;
            flex: 1;
            transition: background .3s;
        }
        .step-line.active { background: #8b5cf6; }

        .step-circle {
            width: 32px; height: 32px; border-radius: 9999px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700;
            transition: all .3s;
            flex-shrink: 0;
        }
        .step-circle.done    { background: #8b5cf6; color: #fff; }
        .step-circle.active  { background: #ede9fe; color: #7c3aed; border: 2px solid #8b5cf6; }
        .step-circle.pending { background: #f1f5f9; color: #94a3b8; border: 2px solid #e2e8f0; }

        /* Input */
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

        /* Password strength bar */
        .strength-bar { height: 4px; border-radius: 9999px; transition: width .3s, background .3s; }

        /* CTA */
        .btn-primary {
            background: #a78bfa;
            transition: opacity .18s, transform .12s, box-shadow .18s;
        }
        .btn-primary:hover  { opacity: .92; box-shadow: 0 8px 20px rgba(139,92,246,.3); }
        .btn-primary:active { transform: scale(.98); }
        .btn-primary:disabled { opacity: .6; cursor: not-allowed; }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            transition: background .18s;
        }
        .btn-secondary:hover { background: #e2e8f0; }

        /* Toggle password */
        .pwd-toggle {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #94a3b8; transition: color .18s;
        }
        .pwd-toggle:hover { color: #7c3aed; }

        /* Checkbox */
        .checkbox-wrap {
            display: flex; align-items: flex-start; gap: 10px;
            cursor: pointer; padding: 4px 0;
        }
        .checkbox-wrap input[type="checkbox"] {
            width: 17px; height: 17px; min-width: 17px; margin-top: 1px;
            border-radius: 4px; accent-color: #8b5cf6; cursor: pointer;
        }

        /* Spinner */
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner { animation: spin .7s linear infinite; }

        /* Fade-up */
        .fade-up { animation: fadeUp .35s ease-out both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .du-1 { animation-delay: .05s; }
        .du-2 { animation-delay: .10s; }
        .du-3 { animation-delay: .15s; }
        .du-4 { animation-delay: .20s; }

        /* Step panels */
        .step-panel { display: none; }
        .step-panel.active { display: block; }

        /* Select field */
        select.field-input { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 36px; }
    </style>
</head>
<body class="min-h-screen bg-slate-50">

<div class="min-h-screen flex">

    {{-- ══════════════════════════════
         LEFT PANEL – 40% (same as login)
    ══════════════════════════════ --}}
    <div class="hidden lg:flex lg:w-[40%] panel-bg relative overflow-hidden flex-col items-center justify-center px-10 py-10">

        <div class="deco-circle absolute top-[-80px] left-[-80px] w-80 h-80"></div>
        <div class="deco-circle absolute bottom-[-60px] right-[-60px] w-64 h-64"></div>
        <div class="absolute top-1/2 left-0 w-2 h-32 bg-white/10 rounded-r-full"></div>

        {{-- Floating card --}}
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

            {{-- Steps guide on left panel --}}
            <div class="space-y-3">
                @foreach([
                    ['num' => '1', 'title' => 'Data Diri', 'desc' => 'Nama, NIS & jenis kelamin'],
                    ['num' => '2', 'title' => 'Info Sekolah', 'desc' => 'Kelas & tahun ajaran'],
                    ['num' => '3', 'title' => 'Akun', 'desc' => 'Email & password'],
                ] as $s)
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ $s['num'] }}
                        </div>
                        <div>
                            <p class="text-white text-xs font-semibold leading-tight">{{ $s['title'] }}</p>
                            <p class="text-white/55 text-xs">{{ $s['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <h1 class="text-white font-extrabold text-3xl text-center leading-tight mb-3">
            Mulai Perjalananmu<br>Bersama Psycora
        </h1>
        <p class="text-white/70 text-center text-sm max-w-xs leading-relaxed">
            Daftarkan dirimu dan mulai pantau kesehatan psikososialmu hari ini.
        </p>
    </div>

    {{-- ══════════════════════════════
         RIGHT PANEL – 60% Form
    ══════════════════════════════ --}}
    <div class="flex-1 flex items-center justify-center px-6 py-10 bg-white">
        <div class="w-full max-w-lg">

            {{-- Mobile logo (only visible on small screens) --}}
            <div class="lg:hidden flex items-center gap-3 mb-8 fade-up">
                <img src="{{ asset('images/logo-smpn216.png') }}" alt="Logo SMPN 216" class="w-14 h-14 object-contain flex-shrink-0">
                <div>
                    <p class="font-bold text-slate-800 text-sm">SMPN 216 Jakarta</p>
                    <p class="text-slate-500 text-xs mt-0.5 leading-snug">Psycora - Sistem Deteksi Dini Kondisi Psikososial Remaja</p>
                </div>
            </div>

            {{-- Heading --}}
            <div class="fade-up du-1 mb-6">
                <h2 class="text-2xl font-bold text-slate-800">Buat Akun Baru</h2>
                <p class="text-slate-500 text-sm mt-1">Isi data di bawah ini untuk mendaftar sebagai siswa</p>
            </div>

            {{-- Step Indicator --}}
            <div class="flex items-center gap-2 mb-7 fade-up du-2" id="stepIndicator">
                {{-- Step 1 --}}
                <div class="step-circle active" id="circle-1">1</div>
                <div class="step-line" id="line-1"></div>
                {{-- Step 2 --}}
                <div class="step-circle pending" id="circle-2">2</div>
                <div class="step-line" id="line-2"></div>
                {{-- Step 3 --}}
                <div class="step-circle pending" id="circle-3">3</div>
            </div>

            {{-- Step labels --}}
            <div class="flex justify-between text-xs text-slate-400 mb-6 fade-up du-2 px-1">
                <span class="font-medium text-violet-600" id="label-1">Data Diri</span>
                <span id="label-2">Info Sekolah</span>
                <span id="label-3">Akun</span>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                {{-- ═══ STEP 1: Data Diri ═══ --}}
                <div class="step-panel active fade-up du-3" id="panel-1">
                    <div class="space-y-4">

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="name">
                                Nama Lengkap
                            </label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                   required placeholder="Sesuai kartu pelajar"
                                   class="field-input w-full px-4 py-3 bg-slate-50 border {{ $errors->has('name') ? 'error border-red-300' : 'border-slate-200' }} rounded-xl text-slate-800 text-sm placeholder-slate-400">
                            @error('name')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="nis">
                                NIS <span class="text-slate-400 font-normal">(Nomor Induk Siswa)</span>
                            </label>
                            <input id="nis" type="text" name="nis" value="{{ old('nis') }}"
                                   required placeholder="Sesuai kartu pelajar"
                                   maxlength="20"
                                   class="field-input w-full px-4 py-3 bg-slate-50 border {{ $errors->has('nis') ? 'error border-red-300' : 'border-slate-200' }} rounded-xl text-slate-800 text-sm placeholder-slate-400">
                            @error('nis')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="tanggal_lahir">
                                Tanggal Lahir
                            </label>
                            <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                   required
                                   class="field-input w-full px-4 py-3 bg-slate-50 border {{ $errors->has('tanggal_lahir') ? 'error border-red-300' : 'border-slate-200' }} rounded-xl text-slate-800 text-sm">
                            @error('tanggal_lahir')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="jenis_kelamin">
                                Jenis Kelamin
                            </label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required
                                    class="field-input select-input w-full px-4 py-3 bg-slate-50 border {{ $errors->has('jenis_kelamin') ? 'error border-red-300' : 'border-slate-200' }} rounded-xl text-slate-800 text-sm">
                                <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih jenis kelamin</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    <button type="button" onclick="nextStep(1)"
                            class="btn-primary mt-6 w-full text-white font-semibold py-3.5 px-4 rounded-xl text-sm flex items-center justify-center gap-2">
                        Lanjut
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                {{-- ═══ STEP 2: Info Sekolah ═══ --}}
                <div class="step-panel" id="panel-2">
                    <div class="space-y-4">

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="kelas">
                                Kelas
                            </label>
                            <select id="kelas" name="kelas" required
                                    class="field-input select-input w-full px-4 py-3 bg-slate-50 border {{ $errors->has('kelas') ? 'error border-red-300' : 'border-slate-200' }} rounded-xl text-slate-800 text-sm">
                                <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>Pilih kelas kamu</option>
                                @foreach(['7-1','7-2','7-3','7-4','7-5','7-6','7-7','7-8','7-9','8-1','8-2','8-3','8-4','8-5','8-6','8-7','8-8','8-9','9-1','9-2','9-3','9-4','9-5','9-6','9-7','9-8','9-9'] as $k)
                                    <option value="{{ $k }}" {{ old('kelas') == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                                @endforeach
                            </select>
                            @error('kelas')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="tahun_ajaran">
                                Tahun Ajaran
                            </label>
                            <select id="tahun_ajaran" name="tahun_ajaran" required
                                    class="field-input select-input w-full px-4 py-3 bg-slate-50 border {{ $errors->has('tahun_ajaran') ? 'error border-red-300' : 'border-slate-200' }} rounded-xl text-slate-800 text-sm">
                                <option value="" disabled {{ old('tahun_ajaran') ? '' : 'selected' }}>Pilih tahun ajaran</option>
                                @foreach(['2026/2027'] as $ta)
                                    <option value="{{ $ta }}" {{ old('tahun_ajaran') == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                                @endforeach
                            </select>
                            @error('tahun_ajaran')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="prevStep(2)"
                                class="btn-secondary flex-1 font-semibold py-3.5 px-4 rounded-xl text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Kembali
                        </button>
                        <button type="button" onclick="nextStep(2)"
                                class="btn-primary flex-1 text-white font-semibold py-3.5 px-4 rounded-xl text-sm flex items-center justify-center gap-2">
                            Lanjut
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- ═══ STEP 3: Akun ═══ --}}
                <div class="step-panel" id="panel-3">
                    <div class="space-y-4">

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="email">
                                Email
                            </label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   required placeholder="siswa@gmail.com"
                                   autocomplete="email"
                                   class="field-input w-full px-4 py-3 bg-slate-50 border {{ $errors->has('email') ? 'error border-red-300' : 'border-slate-200' }} rounded-xl text-slate-800 text-sm placeholder-slate-400">
                            @error('email')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="password">
                                Password
                            </label>
                            <p class="text-xs text-slate-400 mb-1.5">
                                Minimal 8 karakter, kombinasi huruf besar, angka, dan simbol (contoh: !@#$%)
                            </p>
                            <div class="relative">
                                <input id="password" type="password" name="password"
                                       required placeholder="Minimal 8 karakter"
                                       autocomplete="new-password"
                                       oninput="checkStrength(this.value)"
                                       class="field-input w-full px-4 py-3 pr-12 bg-slate-50 border {{ $errors->has('password') ? 'error border-red-300' : 'border-slate-200' }} rounded-xl text-slate-800 text-sm placeholder-slate-400">
                                <button type="button" class="pwd-toggle" id="togglePwd1"
                                        aria-label="Tampilkan atau sembunyikan password">
                                    <svg id="eye1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg id="eyeOff1" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                            {{-- Strength bar --}}
                            <div class="mt-2 flex gap-1.5" id="strengthBars">
                                <div class="strength-bar flex-1 bg-slate-200" id="bar1"></div>
                                <div class="strength-bar flex-1 bg-slate-200" id="bar2"></div>
                                <div class="strength-bar flex-1 bg-slate-200" id="bar3"></div>
                                <div class="strength-bar flex-1 bg-slate-200" id="bar4"></div>
                            </div>
                            <p class="text-xs text-slate-400 mt-1" id="strengthLabel">Masukkan password untuk melihat kekuatannya</p>
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="password_confirmation">
                                Konfirmasi Password
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                       required placeholder="Ulangi password kamu"
                                       autocomplete="new-password"
                                       class="field-input w-full px-4 py-3 pr-12 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm placeholder-slate-400">
                                <button type="button" class="pwd-toggle" id="togglePwd2"
                                        aria-label="Tampilkan atau sembunyikan konfirmasi password">
                                    <svg id="eye2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg id="eyeOff2" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Terms --}}
                        <label class="checkbox-wrap">
                            <input type="checkbox" name="terms" id="terms" required
                                   class="rounded border-slate-300 text-violet-600 focus:ring-violet-400 focus:ring-offset-0">
                            <span class="text-sm text-slate-600 leading-snug">
                                Saya menyetujui
                                <a href="javascript:void(0)" onclick="openTermsModal('terms')" class="text-violet-600 font-semibold underline underline-offset-2 hover:text-violet-800">Syarat & Ketentuan</a>
                                serta
                                <a href="javascript:void(0)" onclick="openTermsModal('privacy')" class="text-violet-600 font-semibold underline underline-offset-2 hover:text-violet-800">Kebijakan Privasi</a>
                                Psycora
                            </span>
                        </label>

                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="prevStep(3)"
                                class="btn-secondary flex-1 font-semibold py-3.5 px-4 rounded-xl text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Kembali
                        </button>
                        <button type="submit" id="submitBtn"
                                class="btn-primary flex-1 text-white font-semibold py-3.5 px-4 rounded-xl text-sm flex items-center justify-center gap-2">
                            <span id="btnContent" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Buat Akun
                            </span>
                            <span id="btnLoading" class="hidden flex items-center gap-2">
                                <svg class="spinner w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Membuat akun...
                            </span>
                        </button>
                    </div>
                </div>

                {{-- Sudah punya akun --}}
                <p class="text-center text-sm text-slate-500 mt-5">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-violet-600 font-semibold hover:text-violet-800 underline underline-offset-2 transition-colors">
                        Masuk di sini
                    </a>
                </p>

            </form>
        </div>
    </div>

</div>

{{-- Modal Syarat & Ketentuan / Kebijakan Privasi --}}
<div id="termsModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden" role="dialog" aria-modal="true">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-100 flex flex-col max-h-[85vh] overflow-hidden">
        {{-- Header Modal --}}
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 id="modalTitle" class="text-base font-bold text-slate-800">Syarat & Ketentuan</h3>
            <button type="button" onclick="closeTermsModal()" class="text-slate-400 hover:text-slate-600 transition focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        {{-- Konten Modal --}}
        <div class="px-6 py-6 overflow-y-auto space-y-4 text-xs sm:text-sm text-slate-600 leading-relaxed" id="modalContent">
            {{-- Konten Dinamis --}}
        </div>
        {{-- Footer Modal --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="closeTermsModal()" 
                    class="px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold rounded-xl transition cursor-pointer outline-none shadow-md shadow-violet-100">
                Saya Mengerti
            </button>
        </div>
    </div>
</div>

<script>
    /* ══ Terms & Privacy Modal ══ */
    const termsTitle = "Syarat & Ketentuan Penggunaan Psycora";
    const termsBody = `
        <div class="space-y-3">
            <p>Selamat datang di <strong>Psycora</strong>, platform deteksi dini kondisi psikososial dan kecanduan gawai siswa SMP Negeri 216 Jakarta.</p>
            <p>Dengan mengakses dan mendaftar di platform ini, Anda setuju untuk mematuhi syarat dan ketentuan berikut:</p>
            <ol class="list-decimal pl-5 space-y-1.5 font-medium text-slate-700">
                <li><strong>Keabsahan Akun:</strong> Akun ini dibuat khusus untuk siswa terdaftar SMP Negeri 216 Jakarta. Pendaftaran menggunakan data palsu tidak diperbolehkan.</li>
                <li><strong>Kejujuran Pengisian:</strong> Anda setuju untuk mengisi kuesioner secara jujur, objektif, dan apa adanya tanpa paksaan untuk memastikan keakuratan hasil deteksi dini psikososial Anda.</li>
                <li><strong>Penggunaan Informasi:</strong> Platform ini adalah alat skrining awal dan bukan pengganti diagnosis psikologis klinis resmi. Hasil kuesioner akan digunakan oleh Guru Bimbingan Konseling (BK) sekolah untuk merancang rencana preventif.</li>
                <li><strong>Keamanan Akun:</strong> Anda bertanggung jawab untuk menjaga kerahasiaan kata sandi Anda dan aktivitas yang terjadi di bawah akun Anda.</li>
            </ol>
        </div>
    `;

    const privacyTitle = "Kebijakan Privasi Psycora";
    const privacyBody = `
        <div class="space-y-3">
            <p>Di <strong>Psycora</strong>, kami sangat memprioritaskan keamanan dan kerahasiaan data pribadi Anda selaku siswa SMP Negeri 216 Jakarta.</p>
            <p>Kebijakan Privasi ini menjelaskan bagaimana kami mengelola data Anda:</p>
            <ol class="list-decimal pl-5 space-y-1.5 font-medium text-slate-700">
                <li><strong>Data yang Dikumpulkan:</strong> Kami mengumpulkan informasi pendaftaran (Nama, NIS, Email, Kelas, Tanggal Lahir, Jenis Kelamin) serta respon jawaban kuesioner SDQ, PSC-17, dan SAS-SV yang Anda isi.</li>
                <li><strong>Kerahasiaan Hasil Skrining:</strong> Seluruh hasil skrining disimpan dalam database yang aman dan <strong>hanya dapat diakses oleh Guru Bimbingan Konseling (BK) berwenang</strong>. Teman sekelas atau pihak eksternal tidak memiliki akses ke data Anda.</li>
                <li><strong>Penggunaan Data:</strong> Data hasil kuesioner digunakan murni untuk keperluan pembinaan diri, konseling preventif, serta deteksi risiko psikososial siswa oleh konselor sekolah.</li>
                <li><strong>Retensi Data:</strong> Informasi pribadi Anda akan disimpan selama Anda terdaftar aktif sebagai siswa di sekolah ini, dan akan dihapus atau dianonimkan setelah Anda lulus.</li>
            </ol>
        </div>
    `;

    function openTermsModal(type) {
        const modal = document.getElementById('termsModal');
        const titleEl = document.getElementById('modalTitle');
        const contentEl = document.getElementById('modalContent');

        if (type === 'terms') {
            titleEl.textContent = termsTitle;
            contentEl.innerHTML = termsBody;
        } else if (type === 'privacy') {
            titleEl.textContent = privacyTitle;
            contentEl.innerHTML = privacyBody;
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeTermsModal() {
        const modal = document.getElementById('termsModal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Close modal on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeTermsModal();
        }
    });

    /* ══ Multi-step navigation ══ */
    let current = 1;
    const total = 3;

    function goToStep(step) {
        // Hide all panels
        for (let i = 1; i <= total; i++) {
            document.getElementById('panel-' + i).classList.remove('active');
            document.getElementById('panel-' + i).classList.add('fade-up');

            const circle = document.getElementById('circle-' + i);
            const label  = document.getElementById('label-' + i);

            if (i < step) {
                circle.className = 'step-circle done';
                circle.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>`;
                label.className = 'text-violet-500 font-medium';
            } else if (i === step) {
                circle.className = 'step-circle active';
                circle.textContent = i;
                label.className = 'font-medium text-violet-600';
            } else {
                circle.className = 'step-circle pending';
                circle.textContent = i;
                label.className = 'text-slate-400';
            }

            if (i < total) {
                document.getElementById('line-' + i).className = i < step ? 'step-line active' : 'step-line';
            }
        }

        document.getElementById('panel-' + step).classList.add('active');
        current = step;
    }

    function nextStep(from) {
        if (!validateStep(from)) return;
        if (from < total) goToStep(from + 1);
    }

    function prevStep(from) {
        if (from > 1) goToStep(from - 1);
    }

    function validateStep(step) {
        const panel = document.getElementById('panel-' + step);
        const inputs = panel.querySelectorAll('input[required], select[required]');
        let valid = true;
        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('error');
                input.focus();
                valid = false;
            } else {
                input.classList.remove('error');
            }
        });
        return valid;
    }

    /* ══ Password strength ══ */
    function checkStrength(val) {
        let score = 0;
        if (val.length >= 8)              score++;
        if (/[A-Z]/.test(val))            score++;
        if (/[0-9]/.test(val))            score++;
        if (/[^A-Za-z0-9]/.test(val))     score++;

        const colors = ['', '#ef4444', '#f97316', '#eab308', '#22c55e'];
        const labels = ['', 'Lemah – tambahkan huruf besar & angka', 'Cukup – bisa lebih kuat lagi!', 'Bagus – tinggal sedikit lagi!', 'Kuat – password kamu aman!'];

        for (let i = 1; i <= 4; i++) {
            const bar = document.getElementById('bar' + i);
            bar.style.background = i <= score ? colors[score] : '#e2e8f0';
        }
        document.getElementById('strengthLabel').textContent = val.length ? labels[score] : 'Masukkan password untuk melihat kekuatannya';
        document.getElementById('strengthLabel').style.color = val.length ? colors[score] : '#94a3b8';
    }

    /* ══ Toggle password visibility ══ */
    function setupToggle(btnId, inputId, eyeId, eyeOffId) {
        document.getElementById(btnId).addEventListener('click', () => {
            const input  = document.getElementById(inputId);
            const eye    = document.getElementById(eyeId);
            const eyeOff = document.getElementById(eyeOffId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            eye.classList.toggle('hidden', isHidden);
            eyeOff.classList.toggle('hidden', !isHidden);
        });
    }
    setupToggle('togglePwd1', 'password',             'eye1', 'eyeOff1');
    setupToggle('togglePwd2', 'password_confirmation', 'eye2', 'eyeOff2');

    /* ══ Loading state on submit ══ */
    document.getElementById('registerForm').addEventListener('submit', () => {
        const btn     = document.getElementById('submitBtn');
        const content = document.getElementById('btnContent');
        const loading = document.getElementById('btnLoading');
        btn.disabled = true;
        content.classList.add('hidden');
        loading.classList.remove('hidden');
    });

    /* ══ If page reloaded with errors, jump to step 3 (account errors most common) ══ */
    @if($errors->hasAny(['email', 'password']))
        goToStep(3);
    @elseif($errors->hasAny(['kelas', 'tahun_ajaran']))
        goToStep(2);
    @elseif($errors->hasAny(['name', 'nis', 'tanggal_lahir', 'jenis_kelamin']))
        goToStep(1);
    @endif
</script>

</body>
</html>