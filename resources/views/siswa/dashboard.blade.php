<x-siswa-layout>
    <x-slot name="pageTitle">Dashboard</x-slot>
    <x-slot name="pageSubtitle">Selamat datang kembali, {{ Auth::user()->name }}</x-slot>

    @if($totalCompleted === 0)
    {{-- ═══════════════════════════════════════════
         EMPTY STATE — siswa belum pernah assessment
    ═══════════════════════════════════════════ --}}

        {{-- Onboarding card --}}
        <div class="bg-white border-2 border-dashed border-violet-200 rounded-2xl p-8 mb-6 text-center">
            <div class="w-14 h-14 rounded-2xl bg-violet-100 flex items-center justify-center mx-auto mb-4 text-2xl">📋</div>
            <h3 class="text-base font-extrabold text-slate-800 mb-1">Mulai kuesioner pertamamu! 🚀</h3>
            <p class="text-sm text-slate-500 max-w-sm mx-auto mb-6 leading-relaxed">
                Jawab <span class="font-semibold text-violet-600">52 pertanyaan</span> singkat untuk mengetahui kondisi psikososialmu.
                Hanya butuh sekitar <span class="font-semibold text-violet-600">5-10 menit</span>.
            </p>

            {{-- Step pills --}}
            <div class="flex items-center justify-center gap-3 mb-6 flex-wrap">
                @foreach([
                    ['🧠','SDQ','25 soal','Kesehatan Mental'],
                    ['💬','PSC-17','17 soal','Kondisi Psikis'],
                    ['📱','SAS-SV','10 soal','Penggunaan HP'],
                ] as $step)
                    <div class="flex items-center gap-2 bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 text-left">
                        <span class="text-xl">{{ $step[0] }}</span>
                        <div>
                            <p class="text-xs font-bold text-slate-700">{{ $step[1] }} <span class="font-normal text-slate-400">· {{ $step[2] }}</span></p>
                            <p class="text-[10px] text-slate-400">{{ $step[3] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <a href="{{ route('siswa.questionnaire.index') }}"
               class="inline-flex items-center gap-2 bg-violet-400 text-white text-sm font-bold px-8 py-3 rounded-xl hover:bg-violet-700 transition">
                Mulai kuesioner sekarang
            </a>
        </div>

        {{-- Penjelasan 3 instrumen --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach([
                ['🧠','SDQ','Strengths & Difficulties Questionnaire','Mengukur kekuatan dan kesulitan dalam kesehatan mental & perilaku sehari-hari.','bg-violet-50','border-violet-100','text-violet-700','bg-violet-100'],
                ['💬','PSC-17','Pediatric Symptom Checklist','Mendeteksi gejala psikososial seperti kecemasan, perhatian, dan hubungan sosial.','bg-pink-50','border-pink-100','text-pink-700','bg-pink-100'],
                ['📱','SAS-SV','Smartphone Addiction Scale','Menilai seberapa tinggi ketergantungan kamu terhadap penggunaan smartphone.','bg-sky-50','border-sky-100','text-sky-700','bg-sky-100'],
            ] as [$em,$name,$full,$desc,$bg,$border,$text,$tagbg])
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-2xl mb-3">{{ $em }}</div>
                    <p class="text-sm font-bold text-slate-800 mb-0.5">{{ $name }}</p>
                    <p class="text-[10px] text-slate-400 mb-2">{{ $full }}</p>
                    <p class="text-xs text-slate-500 leading-relaxed">{{ $desc }}</p>
                </div>
            @endforeach
        </div>

    @else
    {{-- ═══════════════════════════════════════════
         HAS DATA STATE — sudah ada assessment
    ═══════════════════════════════════════════ --}}

        {{-- ── SCORE CARDS ── --}}
        @php
            $scores = [
                [
                    'label'      => 'SDQ',
                    'desc'       => 'Kesehatan Mental',
                    'score'      => $lastResponse?->sdq_score,
                    'max'        => 40,
                    'hex'        => '#a78bfa',
                    'icon_bg'    => 'bg-violet-100',
                    'icon_color' => 'text-violet-500',
                    'bar_bg'     => 'bg-violet-100',
                    'bar_fill'   => 'bg-violet-400',
                    'icon_svg'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
                ],
                [
                    'label'      => 'PSC-17',
                    'desc'       => 'Gejala Psikososial',
                    'score'      => $lastResponse?->psc17_score,
                    'max'        => 34,
                    'hex'        => '#f472b6',
                    'icon_bg'    => 'bg-pink-100',
                    'icon_color' => 'text-pink-500',
                    'bar_bg'     => 'bg-pink-100',
                    'bar_fill'   => 'bg-pink-400',
                    'icon_svg'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
                ],
                [
                    'label'      => 'SAS-SV',
                    'desc'       => 'Adiksi Smartphone',
                    'score'      => $lastResponse?->sassv_score,
                    'max'        => 60,
                    'hex'        => '#38bdf8',
                    'icon_bg'    => 'bg-sky-100',
                    'icon_color' => 'text-sky-500',
                    'bar_bg'     => 'bg-sky-100',
                    'bar_fill'   => 'bg-sky-400',
                    'icon_svg'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

            {{-- Total Kuesioner --}}
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col justify-between">
                <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-slate-800">{{ $totalCompleted }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Total Kuesioner</p>
                </div>
            </div>

            {{-- SDQ / PSC-17 / SAS-SV --}}
            @foreach($scores as $s)
                @php $pct = $s['score'] !== null ? round(($s['score'] / $s['max']) * 100) : 0; @endphp
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl {{ $s['icon_bg'] }} flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $s['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $s['icon_svg'] !!}
                            </svg>
                        </div>
                        <div class="relative w-12 h-12">
                            <svg class="w-12 h-12 -rotate-90" viewBox="0 0 48 48">
                                <circle cx="24" cy="24" r="19" fill="none" stroke="#f1f5f9" stroke-width="5"/>
                                <circle cx="24" cy="24" r="19" fill="none"
                                        stroke="{{ $s['hex'] }}"
                                        stroke-width="5"
                                        stroke-linecap="round"
                                        stroke-dasharray="{{ round(2 * 3.14159 * 19) }}"
                                        stroke-dashoffset="{{ round(2 * 3.14159 * 19 * (1 - $pct / 100)) }}"
                                        style="transition: stroke-dashoffset 1s ease"/>
                            </svg>
                            <span class="absolute inset-0 flex items-center justify-center text-[9px] font-bold" style="color: {{ $s['hex'] }}">{{ $pct }}%</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 mb-1">
                        <span class="text-2xl font-bold text-slate-800">{{ $s['score'] ?? '–' }}</span>
                        <span class="text-sm text-slate-400 font-medium">/ {{ $s['max'] }}</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-600">{{ $s['label'] }}</p>
                    <p class="text-[10px] text-slate-400">{{ $s['desc'] }}</p>
                    <div class="mt-3 {{ $s['bar_bg'] }} rounded-full h-1.5 overflow-hidden">
                        <div class="{{ $s['bar_fill'] }} h-1.5 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── MAIN GRID ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT COL --}}
            <div class="lg:col-span-1 space-y-4">

                {{-- Risk Status --}}
                @if($lastResponse?->riskClassification)
                    @php
                        $risk    = $lastResponse->riskClassification->overall_risk;
                        $isAman2 = $risk === 'Tidak Berisiko Psikososial';
                        $rCfg    = $isAman2
                            ? ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-700', 'icon' => '✅']
                            : ['bg' => 'bg-amber-50',   'border' => 'border-amber-200',   'text' => 'text-amber-700',   'icon' => '⚠️'];
                    @endphp
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest mb-3">Status Risiko Terakhir</p>
                        <div class="{{ $rCfg['bg'] }} {{ $rCfg['border'] }} border rounded-xl p-4">
                            <div class="flex items-start gap-2.5 mb-2">
                                <span class="text-lg leading-none mt-0.5">{{ $rCfg['icon'] }}</span>
                                <span class="{{ $rCfg['text'] }} font-bold text-sm leading-snug">{{ $risk }}</span>
                            </div>
                            <p class="{{ $rCfg['text'] }} text-xs leading-relaxed opacity-80">
                                {{ $lastResponse->riskClassification->recommendation }}
                            </p>
                        </div>
                    </div>
                @endif

                {{-- CTA --}}
                <div class="bg-violet-100 border border-violet-200/50 rounded-2xl p-5 text-violet-800">
                    <p class="font-bold text-sm mb-1">Kuesioner Baru</p>
                    <p class="text-violet-600/90 text-xs mb-4 leading-relaxed">
                        Lakukan pengisian kuesioner ulang untuk memantau perkembanganmu.
                    </p>
                    <a href="{{ route('siswa.questionnaire.index') }}"
                       class="block bg-white text-violet-600 text-center text-sm font-bold py-2.5 rounded-xl hover:bg-violet-50 transition-colors duration-200 shadow-sm">
                        Isi kuesioner lagi →
                    </a>
                </div>

                {{-- Quick Links --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest mb-3">Menu Cepat</p>
                    <div class="space-y-2">
                        <a href="{{ route('siswa.riwayat.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition text-sm text-slate-700 font-medium group">
                            <span class="w-7 h-7 rounded-lg bg-violet-100 flex items-center justify-center group-hover:bg-violet-200 transition">📋</span>
                            Lihat Riwayat
                        </a>
                        <a href="{{ route('siswa.chatbot.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition text-sm text-slate-700 font-medium group">
                            <span class="w-7 h-7 rounded-lg bg-violet-100 flex items-center justify-center group-hover:bg-emerald-200 transition">🤖</span>
                            Tanya PsyBot
                        </a>
                    </div>
                </div>
            </div>

            {{-- RIGHT COL --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Chart --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-1">
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Perkembangan Skor</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Tren skor dari setiap kuesioner yang kamu selesaikan</p>
                        </div>
                        <a href="{{ route('siswa.riwayat.index') }}" class="text-xs text-violet-600 hover:underline font-medium">Lihat semua</a>
                    </div>
                    <div class="h-48 mt-3">
                        <canvas id="progressChart"></canvas>
                    </div>
                    <div class="flex flex-wrap gap-3 mt-3 pt-3 border-t border-slate-100">
                        <div class="flex items-center gap-1.5 text-[10px] text-slate-500">
                            <span class="w-3 h-1 rounded-full bg-violet-400 inline-block"></span> SDQ – Maks 40
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] text-slate-500">
                            <span class="w-3 h-1 rounded-full bg-pink-400 inline-block"></span> PSC-17 – Maks 34
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] text-slate-500">
                            <span class="w-3 h-1 rounded-full bg-sky-400 inline-block"></span> SAS-SV – Maks 60
                        </div>
                        <span class="text-[10px] text-slate-400 ml-auto italic">Skor lebih tinggi = risiko lebih besar</span>
                    </div>
                </div>

                {{-- Riwayat --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-semibold text-slate-700">Riwayat Terakhir</p>
                        <a href="{{ route('siswa.riwayat.index') }}" class="text-xs text-violet-600 hover:underline font-medium">Lihat semua</a>
                    </div>
                    <div class="space-y-2">
                        @foreach($history->reverse() as $item)
                            @php
                                $r    = $item->riskClassification?->overall_risk ?? '-';
                                $isOk = $r === 'Tidak Berisiko Psikososial';
                                $badge = $isOk ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700';
                            @endphp
                            <a href="{{ route('siswa.riwayat.show', $item->id) }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition group">
                                <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-sm font-bold text-violet-600 flex-shrink-0">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-slate-700 truncate">
                                        {{ $item->completed_at?->format('d M Y') }}
                                    </p>
                                    <p class="text-[10px] text-slate-400">
                                        SDQ: {{ $item->sdq_score }}/40 · PSC: {{ $item->psc17_score }}/34 · SAS: {{ $item->sassv_score }}/60
                                    </p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $badge }} flex-shrink-0 whitespace-nowrap">{{ $r }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    @endif {{-- end @if($totalCompleted === 0) --}}

    @push('scripts')
    @if($totalCompleted > 0 && $history->count() > 0)
    <script>
    const labels = @json($history->map(fn($h) => $h->completed_at?->format('d M')));
    const sdq    = @json($history->map(fn($h) => $h->sdq_score));
    const psc    = @json($history->map(fn($h) => $h->psc17_score));
    const sas    = @json($history->map(fn($h) => $h->sassv_score));

    new Chart(document.getElementById('progressChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'SDQ (maks 40)',
                    data: sdq,
                    borderColor: '#a78bfa',
                    backgroundColor: 'rgba(167,139,250,0.07)',
                    tension: 0.4, fill: true,
                    pointBackgroundColor: '#a78bfa',
                    pointRadius: 4, pointHoverRadius: 6,
                },
                {
                    label: 'PSC-17 (maks 34)',
                    data: psc,
                    borderColor: '#f472b6',
                    backgroundColor: 'transparent',
                    tension: 0.4, fill: false,
                    pointBackgroundColor: '#f472b6',
                    pointRadius: 4, pointHoverRadius: 6,
                },
                {
                    label: 'SAS-SV (maks 60)',
                    data: sas,
                    borderColor: '#38bdf8',
                    backgroundColor: 'transparent',
                    tension: 0.4, fill: false,
                    pointBackgroundColor: '#38bdf8',
                    pointRadius: 4, pointHoverRadius: 6,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}`
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10, family: 'Plus Jakarta Sans' }}},
                y: {
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 10, family: 'Plus Jakarta Sans' }},
                    beginAtZero: true,
                    max: 65,
                }
            }
        }
    });
    </script>
    @endif
    @endpush

</x-siswa-layout>