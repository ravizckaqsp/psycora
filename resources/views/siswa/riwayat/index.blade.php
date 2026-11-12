<x-siswa-layout>
    <x-slot name="pageTitle">Riwayat Kuesioner</x-slot>
    <x-slot name="pageSubtitle">Pantau perkembangan kondisi psikososialmu dari waktu ke waktu</x-slot>

    @php
        $riskBadge = [
            'Tidak Berisiko Psikososial' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'Berisiko Psikososial'       => 'bg-red-100 text-red-700 border-red-200',
        ];
    @endphp

    {{-- Chart Perkembangan --}}
    @if($chartData->count() > 1)
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm mb-6">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-semibold text-slate-700">Grafik Perkembangan Skor</p>
                <span class="text-xs text-slate-400">{{ $chartData->count() }} kuesioner</span>
            </div>
            <div class="h-56">
                <canvas id="historyChart"></canvas>
            </div>
        </div>
    @endif

    {{-- List riwayat --}}
    @if($responses->count() > 0)
        <div class="space-y-3">
            @foreach($responses as $i => $resp)
                @php
                    $risk = $resp->riskClassification?->overall_risk ?? '-';
                    $badge = $riskBadge[$risk] ?? 'bg-slate-100 text-slate-500 border-slate-200';
                @endphp
                <a href="{{ route('siswa.riwayat.show', $resp->id) }}"
                   class="flex items-center gap-4 bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-violet-200 transition-all duration-200 group">

                    {{-- Number --}}
                    <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center text-violet-700 font-bold text-sm flex-shrink-0">
                        {{ $responses->firstItem() + $loop->index }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-sm font-semibold text-slate-800">
                                {{ $resp->completed_at?->format('d F Y') }}
                            </p>
                            <span class="text-xs text-slate-400">{{ $resp->completed_at?->format('H:i') }} WIB</span>
                        </div>
                        <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                            <span class="text-xs text-slate-500 font-medium">SDQ: <strong class="text-slate-700">{{ $resp->sdq_score }}</strong></span>
                            <span class="text-slate-300">·</span>
                            <span class="text-xs text-slate-500 font-medium">PSC-17: <strong class="text-slate-700">{{ $resp->psc17_score }}</strong></span>
                            <span class="text-slate-300">·</span>
                            <span class="text-xs text-slate-500 font-medium">SAS-SV: <strong class="text-slate-700">{{ $resp->sassv_score }}</strong></span>
                        </div>
                    </div>

                    {{-- Risk badge --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="hidden sm:inline-flex px-3 py-1 rounded-full text-xs font-semibold border {{ $badge }}">
                            {{ $risk }}
                        </span>
                        <svg class="w-5 h-5 text-slate-300 group-hover:text-violet-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $responses->links() }}
        </div>

    @else
        {{-- Empty state --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
            <div class="text-5xl mb-4">📋</div>
            <h3 class="text-lg font-bold text-slate-700 mb-2">Belum ada riwayat</h3>
            <p class="text-slate-400 text-sm mb-6 max-w-xs mx-auto">Selesaikan kuesioner pertama untuk mulai memantau perkembangan psikososialmu.</p>
            <a href="{{ route('siswa.questionnaire.index') }}"
               class="inline-flex items-center gap-2 bg-violet-400 text-white font-bold px-6 py-3 rounded-xl shadow-md shadow-violet-100 hover:shadow-lg transition text-sm">
                Mulai kuesioner
            </a>
        </div>
    @endif

    @push('scripts')
    @if($chartData->count() > 1)
    <script>
    new Chart(document.getElementById('historyChart'), {
        type: 'line',
        data: {
            labels: @json($chartData->map(fn($d) => \Carbon\Carbon::parse($d->completed_at)->format('d M'))),
            datasets: [
                { label: 'SDQ',    data: @json($chartData->pluck('sdq_score')),    borderColor:'#a78bfa', backgroundColor:'rgba(167,139,250,0.07)', tension:.4, fill:true,  pointBackgroundColor:'#a78bfa', pointRadius:5, pointHoverRadius:7 },
                { label: 'PSC-17', data: @json($chartData->pluck('psc17_score')),  borderColor:'#f472b6', backgroundColor:'transparent', tension:.4, fill:false, pointBackgroundColor:'#f472b6', pointRadius:5, pointHoverRadius:7 },
                { label: 'SAS-SV', data: @json($chartData->pluck('sassv_score')),  borderColor:'#38bdf8', backgroundColor:'transparent', tension:.4, fill:false, pointBackgroundColor:'#38bdf8', pointRadius:5, pointHoverRadius:7 },
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { labels: { font: { size:11, family:'Inter' }, padding:16, boxWidth:10, usePointStyle:true }}},
            scales: {
                x: { grid: { display:false }, ticks: { font:{ size:11 }}},
                y: { grid: { color:'#f1f5f9' }, beginAtZero:true, ticks: { font:{ size:11 }}}
            }
        }
    });
    </script>
    @endif
    @endpush
</x-siswa-layout>
