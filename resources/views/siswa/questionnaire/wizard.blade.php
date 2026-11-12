<!DOCTYPE html>
<html lang="id" class="min-h-screen">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $instrument }} – Kuesioner Psycora</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-smpn216-new.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $theme = [
            'sdq' => [
                'bg'           => 'bg-violet-100 text-violet-700',
                'accent'       => '#a78bfa',
                'solid_bg'     => 'bg-violet-400',
                'gradient'     => 'bg-violet-400',
                'shadow'       => 'shadow-violet-100 hover:shadow-violet-200',
                'shadow_color' => 'rgba(167,139,250,0.15)',
            ],
            'psc17' => [
                'bg'           => 'bg-pink-100 text-pink-700',
                'accent'       => '#f472b6',
                'solid_bg'     => 'bg-pink-400',
                'gradient'     => 'bg-pink-400',
                'shadow'       => 'shadow-pink-100 hover:shadow-pink-200',
                'shadow_color' => 'rgba(244,114,182,0.15)',
            ],
            'sassv' => [
                'bg'           => 'bg-sky-100 text-sky-700',
                'accent'       => '#38bdf8',
                'solid_bg'     => 'bg-sky-400',
                'gradient'     => 'bg-sky-400',
                'shadow'       => 'shadow-sky-100 hover:shadow-sky-200',
                'shadow_color' => 'rgba(56,189,248,0.15)',
            ],
        ][$instrument_key] ?? [
            'bg'           => 'bg-violet-100 text-violet-700',
            'accent'       => '#a78bfa',
            'solid_bg'     => 'bg-violet-400',
            'gradient'     => 'bg-violet-400',
            'shadow'       => 'shadow-violet-100 hover:shadow-violet-200',
            'shadow_color' => 'rgba(167,139,250,0.15)',
        ];

        $saved = old('answers', $saved);
    @endphp
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ── Compact horizontal radio options ── */
        .radio-row {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        @media (min-width: 640px) {
            .radio-row {
                flex-direction: row;
            }
        }
        .radio-opt {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 7px;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            padding: 7px 10px;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            background: #fff;
        }
        .radio-opt:hover {
            border-color: {{ $theme['accent'] }}80;
            background: {{ $theme['accent'] }}08;
        }
        .radio-opt.is-selected {
            border-color: {{ $theme['accent'] }};
            background: {{ $theme['accent'] }}10;
        }
        .radio-circle {
            width: 15px;
            height: 15px;
            min-width: 15px;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
            flex-shrink: 0;
        }
        .radio-opt.is-selected .radio-circle {
            border-color: {{ $theme['accent'] }};
            background: {{ $theme['accent'] }};
        }
        .radio-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #fff;
        }
        .radio-opt-label {
            font-size: 11.5px;
            font-weight: 600;
            color: #64748b;
            line-height: 1.3;
        }
        .radio-opt.is-selected .radio-opt-label {
            color: {{ $theme['accent'] }};
        }

        /* ── Missed question highlight ── */
        .q-missed {
            background: #fff8f8;
            border: 1.5px solid #fecaca;
            border-radius: 14px;
        }

        /* ── Page entrance ── */
        .slide-in {
            animation: slideIn .35s cubic-bezier(.4,0,.2,1);
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50">

<div class="min-h-screen flex flex-col">

    {{-- ── Fixed top progress bar ── --}}
    <div class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-slate-100 shadow-sm">
        <div class="flex items-center px-4 sm:px-6 py-2.5">

            {{-- Back --}}
            <button type="button" onclick="submitToTab('back')"
                    class="flex items-center gap-1.5 text-slate-500 hover:text-slate-800 text-sm font-medium transition mr-4 flex-shrink-0 outline-none cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </button>

            {{-- Instrument tabs --}}
            <div class="flex items-center gap-1.5 flex-1 justify-center">
                @php
                    $instruments = [
                        ['key'=>'sdq',   'label'=>'SDQ',    'active'=>$instrument_key==='sdq',   'color'=>'bg-violet-400'],
                        ['key'=>'psc17', 'label'=>'PSC-17', 'active'=>$instrument_key==='psc17', 'color'=>'bg-pink-400'],
                        ['key'=>'sassv', 'label'=>'SAS-SV', 'active'=>$instrument_key==='sassv', 'color'=>'bg-sky-400'],
                    ];
                @endphp
                @foreach($instruments as $idx => $inst)
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="submitToTab('{{ $inst['key'] }}')"
                                class="px-3 py-1.5 rounded-full text-xs font-semibold transition cursor-pointer outline-none
                                    {{ $inst['active'] ? $inst['color'].' text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                            {{ $inst['label'] }}
                        </button>
                        @if($idx < 2)
                            <div class="w-4 h-px bg-slate-200 mx-0.5"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Counter --}}
            <span class="flex-shrink-0 ml-4 text-xs text-slate-500 font-medium whitespace-nowrap">
                Instrumen {{ $step }}/{{ $total }}
            </span>
        </div>

        {{-- Progress bar --}}
        <div class="h-1.5 bg-slate-100">
            <div class="h-full {{ $theme['solid_bg'] }} transition-all duration-500 ease-out"
                 style="width: {{ round(($step / $total) * 100) }}%"></div>
        </div>
    </div>

    {{-- ── Question area ── --}}
    <div class="flex-1 flex justify-center px-4 py-24">
        <div class="w-full max-w-3xl slide-in">

            {{-- Autosave notice --}}
            <div class="flex items-center justify-center gap-1.5 text-xs text-emerald-600 font-medium mb-5">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Pindah tab akan otomatis menyimpan draf jawaban kuesioner Anda
            </div>

            {{-- Header card --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="{{ $theme['bg'] }} text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                        {{ $instrument }}
                    </span>
                    <span class="text-slate-400 text-xs">{{ count($questions) }} Pertanyaan</span>
                </div>
                <h2 class="text-base font-bold text-slate-800 leading-snug">
                    @if($instrument_key === 'sdq')
                        Kuesioner Kekuatan dan Kesulitan Perilaku (SDQ)
                    @elseif($instrument_key === 'psc17')
                        Pemeriksaan Gejala Psikososial (PSC-17)
                    @else
                        Skala Kecanduan Smartphone (SAS-SV)
                    @endif
                </h2>
                <p class="text-slate-500 text-xs mt-1 leading-relaxed">
                    @if($instrument_key === 'sdq')
                        Untuk setiap pertanyaan, pilih opsi yang paling menggambarkan kondisi dirimu selama 6 bulan terakhir.
                    @elseif($instrument_key === 'psc17')
                        Pilih seberapa sering kamu merasakan kondisi berikut dalam kehidupan sehari-harimu.
                    @else
                        Pilih seberapa jauh kamu menyetujui pernyataan berikut mengenai perilaku penggunaan smartphone-mu.
                    @endif
                </p>
            </div>

            {{-- Validation error --}}
            @if($errors->has('answers'))
                <div class="flex items-start gap-2 bg-rose-50 border border-rose-200 text-rose-700 text-xs px-4 py-3 rounded-xl mb-5 shadow-sm">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <span class="font-bold">Pengisian Belum Lengkap:</span>
                        <p class="mt-0.5 leading-relaxed">{{ $errors->first('answers') }}</p>
                    </div>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('siswa.questionnaire.'.$instrument_key.'.store') }}"
                  id="wizardForm" class="space-y-3">
                @csrf
                <input type="hidden" name="next_tab" id="nextTabInput" value="">

                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm divide-y divide-slate-100">
                    @foreach($questions as $q)
                        @php
                            $isMissed     = $errors->has('answers') && !isset($saved[$q->number]);
                            $savedVal     = $saved[$q->number] ?? null;
                        @endphp

                        <div class="px-4 py-4 {{ $isMissed ? 'q-missed mx-2 my-1.5' : '' }}">

                            {{-- Question text + wajib badge --}}
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <p class="text-sm font-semibold text-slate-800 leading-relaxed">
                                    <span class="text-xs font-bold mr-1" style="color: {{ $theme['accent'] }}">{{ $q->number }}.</span>
                                    {{ $q->text }}
                                </p>
                                @if($isMissed)
                                    <span class="flex-shrink-0 bg-rose-100 text-rose-600 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide animate-pulse whitespace-nowrap">
                                        Wajib diisi
                                    </span>
                                @endif
                            </div>

                            {{-- Compact radio options --}}
                            {{-- SAS-SV punya 6 opsi → 2 baris x 3 kolom, lainnya 1 baris --}}
                            @if(count($options) === 6)
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2" data-q="{{ $q->number }}">
                                    @foreach($options as $value => $label)
                                        @php
                                            $isSelected = !is_null($savedVal) && $savedVal !== '' && $savedVal == $value;
                                        @endphp
                                        <div class="radio-opt {{ $isSelected ? 'is-selected' : '' }}"
                                             onclick="selectOpt(this, '{{ $q->number }}', '{{ $value }}')">
                                            <div class="radio-circle">
                                                @if($isSelected)
                                                    <div class="radio-dot"></div>
                                                @endif
                                            </div>
                                            <span class="radio-opt-label">{{ $label }}</span>
                                            <input type="radio"
                                                   name="answers[{{ $q->number }}]"
                                                   value="{{ $value }}"
                                                   class="sr-only"
                                                   {{ $isSelected ? 'checked' : '' }}>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="radio-row" data-q="{{ $q->number }}">
                                    @foreach($options as $value => $label)
                                        @php
                                            $isSelected = !is_null($savedVal) && $savedVal !== '' && $savedVal == $value;
                                        @endphp
                                        <div class="radio-opt {{ $isSelected ? 'is-selected' : '' }}"
                                             onclick="selectOpt(this, '{{ $q->number }}', '{{ $value }}')">
                                            <div class="radio-circle">
                                                @if($isSelected)
                                                    <div class="radio-dot"></div>
                                                @endif
                                            </div>
                                            <span class="radio-opt-label">{{ $label }}</span>
                                            <input type="radio"
                                                   name="answers[{{ $q->number }}]"
                                                   value="{{ $value }}"
                                                   class="sr-only"
                                                   {{ $isSelected ? 'checked' : '' }}>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Submit button --}}
                <div class="pt-2 flex justify-center">
                    <button type="submit"
                            class="{{ $theme['solid_bg'] }} text-white font-semibold px-10 py-3 rounded-xl text-sm transition-all duration-200 hover:opacity-90 active:scale-[.98] cursor-pointer">
                        {{ $step < $total ? 'Simpan jawaban' : 'Selesai' }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    // Select radio option — works for both 1-row and 2-row (grid) layouts
    function selectOpt(el, qNum, value) {
        const container = el.closest('.radio-row, .grid');

        // Reset all in this container
        container.querySelectorAll('.radio-opt').forEach(opt => {
            opt.classList.remove('is-selected');
            opt.querySelector('.radio-circle').innerHTML = '';
            opt.querySelector('input[type="radio"]').checked = false;
        });

        // Select this one
        el.classList.add('is-selected');
        el.querySelector('.radio-circle').innerHTML = '<div class="radio-dot"></div>';
        el.querySelector('input[type="radio"]').checked = true;

        // Remove missed styling if answered
        const qBlock = el.closest('.q-missed');
        if (qBlock) {
            qBlock.classList.remove('q-missed', 'mx-2', 'my-1.5');
        }
        const badge = el.closest('.px-4')?.querySelector('.animate-pulse');
        if (badge) badge.style.display = 'none';
    }

    // Submit form with tab target
    function submitToTab(tabKey) {
        const form = document.getElementById('wizardForm');
        const input = document.getElementById('nextTabInput');
        if (form && input) {
            input.value = tabKey;
            form.submit();
        } else {
            const map = { sdq: '/siswa/kuesioner/sdq', psc17: '/siswa/kuesioner/psc17', sassv: '/siswa/kuesioner/sassv' };
            window.location.href = map[tabKey] ?? '/siswa/kuesioner';
        }
    }
</script>

</body>
</html>