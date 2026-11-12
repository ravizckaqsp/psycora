<x-guru-bk-layout pageTitle="Profil & Rencana Preventif Siswa" pageSubtitle="Visualisasi rekam jejak psikososial individu dan pengelolaan intervensi dini.">

    <div class="space-y-6" x-data="{ activeTab: 'trend', openRows: {} }">
        {{-- Screen-only Layout --}}
        <div class="no-print space-y-6">
            {{-- Tombol Kembali & Download --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <a href="{{ route('guruBk.siswa.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 font-semibold transition">
                    &larr; Kembali
                </a>
                <div class="flex items-center gap-3">
                    @if($latestResponse)
                        <a href="{{ route('guruBk.laporan-siswa.print', $student->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition cursor-pointer shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Unduh Laporan (PDF)
                        </a>
                    @endif
                    <span class="text-xs text-slate-500 font-semibold">NIS: <strong>{{ $student->nis ?? '-' }}</strong></span>
                </div>
            </div>

            {{-- 1. Informasi Ringkas Siswa --}}
            <div class="bg-indigo-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -top-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 text-white flex items-center justify-center font-bold text-xl border border-white/10 shadow-inner flex-shrink-0">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">{{ $student->name }}</h2>
                            <p class="text-xs text-indigo-200 mt-1">
                                Kelas {{ $student->studentClass->name ?? 'Belum Diatur' }} &middot; 
                                {{ ($student->gender === 'L' || $student->gender === 'l') ? 'Laki-laki' : 'Perempuan' }} &middot; 
                                {{ $student->email }}
                            </p>
                        </div>
                    </div>

                    {{-- Status Klasifikasi Terakhir --}}
                    <div class="flex items-center gap-3">
                        <div class="text-left md:text-right">
                            <p class="text-[10px] text-indigo-300 font-semibold tracking-wider uppercase">Tingkat Risiko Terakhir</p>
                            @if($latestResponse && $latestResponse->riskClassification)
                                <h3 class="text-lg font-extrabold mt-0.5">{{ $latestResponse->riskClassification->overall_risk }}</h3>
                            @else
                                <h3 class="text-lg font-extrabold text-indigo-300 mt-0.5">Belum Dinilai</h3>
                            @endif
                        </div>
                        @if($latestResponse && $latestResponse->riskClassification)
                            @php
                                $riskBg = match($latestResponse->riskClassification->overall_risk) {
                                    'Tidak Berisiko Psikososial' => 'bg-emerald-500 text-white border-emerald-400',
                                    default => 'bg-rose-50 text-white border-rose-400',
                                };
                            @endphp
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg border {{ $riskBg }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center text-indigo-300">
                                N/A
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Row Utama: Grafik & Detail Intervensi BK --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Bagian Kiri: Grafik Detail Subskala Terakhir Siswa (Col-span 2) --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm lg:col-span-2 flex flex-col justify-between">
                    <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm">Grafik Analisis Detail Subskala Terakhir</h3>
                            <p class="text-xs text-slate-500 font-medium mt-1">Rincian nilai subskala SDQ, PSC-17, dan total skor SAS-SV dari pengisian terbaru</p>
                        </div>
                    </div>

                    <div class="my-6">
                        @if($latestResponse)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4" style="min-height: 240px;">
                                {{-- Grafik SDQ --}}
                                <div class="flex flex-col justify-between h-full">
                                    <h4 class="text-center font-bold text-slate-700 text-xs mb-2">Subskala SDQ (Terakhir)</h4>
                                    <div class="relative" style="height: 180px;">
                                        <canvas id="sdqSubscaleChart"></canvas>
                                    </div>
                                </div>
                                {{-- Grafik PSC-17 --}}
                                <div class="flex flex-col justify-between h-full">
                                    <h4 class="text-center font-bold text-slate-700 text-xs mb-2">Subskala PSC-17 (Terakhir)</h4>
                                    <div class="relative" style="height: 180px;">
                                        <canvas id="pscSubscaleChart"></canvas>
                                    </div>
                                </div>
                                {{-- Grafik SAS-SV --}}
                                <div class="flex flex-col justify-between h-full">
                                    <h4 class="text-center font-bold text-slate-700 text-xs mb-2">Total Skor SAS-SV (Terakhir)</h4>
                                    <div class="relative" style="height: 180px;">
                                        <canvas id="sassvScoreChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="h-[240px] flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-12 h-12 text-slate-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                </svg>
                                <p class="text-xs">Siswa ini belum pernah menyelesaikan pengisian kuesioner</p>
                            </div>
                        @endif
                    </div>

                    <div class="text-[10px] text-slate-600 font-medium flex items-center gap-1.5 bg-slate-50 rounded-xl p-3 border border-slate-100/50">
                        <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <span><strong>Interpretasi Subskala:</strong> Grafik ini merepresentasikan sebaran skor sub-dimensi pada pengisian kuesioner terakhir siswa untuk menganalisis area intervensi prioritas.</span>
                    </div>
                </div>

                {{-- Bagian Kanan: Rekomendasi/Catatan Konseling Preventif BK --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm">Rekomendasi Tindakan Konseling</h3>
                            <p class="text-xs text-slate-500 font-medium mt-1">Intervensi dini terarah sebelum gejala berkembang menjadi gangguan psikologis berat</p>
                        </div>

                        @if($latestResponse)
                            <form action="{{ route('guruBk.siswa.recommendation', $student->id) }}" method="POST" class="space-y-3.5">
                                @csrf
                                <div>
                                    <label for="recommendation" class="block text-xs font-semibold text-slate-500 mb-1.5">Catatan Intervensi Guru BK</label>
                                    <textarea name="recommendation" id="recommendation" rows="9"
                                              placeholder="Ketik rencana intervensi preventif untuk siswa ini (misal: Konseling tatap muka berkala, pembatasan gawai di sekolah, edukasi manajemen waktu berkelompok)..."
                                              class="w-full px-3.5 py-3 text-xs border border-slate-200 rounded-2xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition text-slate-700 leading-relaxed resize-none">{{ $latestResponse->riskClassification->recommendation ?? '' }}</textarea>
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-xs shadow-md shadow-indigo-100 transition-all duration-150">
                                    Simpan Catatan Intervensi BK
                                </button>
                            </form>
                        @else
                            <div class="p-5 border border-dashed border-slate-200 rounded-2xl text-center text-slate-500 space-y-2 py-12">
                                <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="text-xs font-medium leading-normal text-slate-500">Catatan intervensi hanya dapat diberikan setelah siswa menyelesaikan minimal satu kali kuesioner.</p>
                            </div>
                        @endif
                    </div>
                    
                    @if($latestResponse && isset($latestResponse->riskClassification->updated_at))
                        <div class="text-[10px] text-slate-500 font-semibold text-center mt-4">
                            Pembaruan Terakhir: {{ $latestResponse->riskClassification->updated_at->format('d M Y, H:i') }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- 3. Riwayat Lengkap Pengisian Kuesioner Siswa --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Riwayat Pengisian Kuesioner</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Daftar lengkap hasil pengerjaan instrumen diagnostik</p>
                    </div>
                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">{{ count($tableHistory) }} Kuesioner</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-100">
                                <th class="px-5 py-4">Tanggal Pengujian</th>
                                <th class="px-5 py-4 text-center">Skor SDQ (Max 40)</th>
                                <th class="px-5 py-4 text-center">Skor PSC-17 (Max 34)</th>
                                <th class="px-5 py-4 text-center">Skor SAS-SV (Max 60)</th>
                                <th class="px-5 py-4">Status Klasifikasi Risiko</th>
                                <th class="px-5 py-4">Detail Skor Sub-Dimensi Klinis</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($tableHistory as $response)
                                @php
                                    $classification = $response->riskClassification;
                                    $overall = $classification->overall_risk ?? 'Rendah';
                                    $badgeColor = match($overall) {
                                        'Tidak Berisiko Psikososial' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        default => 'bg-rose-50 text-rose-700 border-rose-100',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-5 py-4 font-semibold text-slate-800">
                                        {{ $response->completed_at->format('d M Y, H:i') }} WIB
                                    </td>
                                    <td class="px-5 py-4 text-center font-bold text-violet-500">
                                        {{ $response->sdq_score }}
                                        <span class="text-[10px] text-slate-400 font-normal">/40</span>
                                    </td>
                                    <td class="px-5 py-4 text-center font-bold text-pink-500">
                                        {{ $response->psc17_score }}
                                        <span class="text-[10px] text-slate-400 font-normal">/34</span>
                                    </td>
                                    <td class="px-5 py-4 text-center font-bold text-sky-500">
                                        {{ $response->sassv_score }}
                                        <span class="text-[10px] text-slate-400 font-normal">/60</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $badgeColor }}">
                                            {{ $overall }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <button @click="openRows[{{ $response->id }}] = !openRows[{{ $response->id }}]"
                                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition text-[11px] outline-none">
                                            <span x-text="openRows[{{ $response->id }}] ? 'Tutup Detail' : 'Buka Skor Klinis'"></span>
                                            <svg class="w-3.5 h-3.5 transition duration-200" :class="openRows[{{ $response->id }}] ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                {{-- Baris Detail Subdomain Klinis --}}
                                <tr x-show="openRows[{{ $response->id }}]" x-transition class="bg-slate-50/50">
                                    <td colspan="6" class="px-5 py-4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs text-slate-600 leading-relaxed">
                                            {{-- SDQ Subscores --}}
                                            <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm space-y-3">
                                                <div class="border-b border-slate-100 pb-1.5 flex justify-between items-center">
                                                    <h4 class="font-bold text-violet-500">Skor Sub-Dimensi SDQ</h4>
                                                    <span class="text-[10px] text-slate-400 font-semibold">Total: <strong class="text-violet-600">{{ $response->sdq_score }}</strong>/40</span>
                                                </div>
                                                @php
                                                    $sdqSub = $response->getSdqSubscores();
                                                    $sdqSubscales = [
                                                        'emotional'     => 'Gejala Emosional',
                                                        'conduct'       => 'Masalah Perilaku',
                                                        'hyperactivity' => 'Hiperaktivitas',
                                                        'peer'          => 'Masalah Teman Sebaya',
                                                        'prosocial'     => 'Perilaku Prososial'
                                                    ];
                                                @endphp
                                                <div class="space-y-2">
                                                    @foreach($sdqSubscales as $key => $label)
                                                        @php
                                                            $score = $sdqSub[$key] ?? 0;
                                                            [$classStatus, $classBadge] = \App\Models\QuestionnaireResponse::getSdqSubscaleClassification($key, $score);
                                                        @endphp
                                                        <div class="flex items-center justify-between py-1 border-b border-slate-50 last:border-0">
                                                            <div>
                                                                <span class="font-semibold text-slate-700">{{ $label }}</span>
                                                                <span class="text-[9px] text-slate-400 block mt-0.5">Skor: <strong class="text-slate-700 font-bold">{{ $score }}</strong>/10</span>
                                                            </div>
                                                            <span class="px-2 py-0.5 rounded text-[9px] font-bold border {{ $classBadge }}">
                                                                {{ $classStatus }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            {{-- PSC-17 Subscores --}}
                                            <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm space-y-3">
                                                <div class="border-b border-slate-100 pb-1.5 flex justify-between items-center">
                                                    <h4 class="font-bold text-pink-500">Skor Sub-Dimensi PSC-17</h4>
                                                    <span class="text-[10px] text-slate-400 font-semibold">Total: <strong class="text-pink-600">{{ $response->psc17_score }}</strong>/34</span>
                                                </div>
                                                @php
                                                    $pscSub = $response->getPsc17Subscores();
                                                    $pscSubscales = [
                                                        'internalizing' => ['Internalizing (Emosional)', 10],
                                                        'attention'     => ['Attention (Pusat Perhatian)', 10],
                                                        'externalizing' => ['Externalizing (Perilaku)', 14]
                                                    ];
                                                @endphp
                                                <div class="space-y-2">
                                                    @foreach($pscSubscales as $key => [$label, $maxScore])
                                                        @php
                                                            $score = $pscSub[$key] ?? 0;
                                                            [$classStatus, $classBadge] = \App\Models\QuestionnaireResponse::getPsc17SubscaleClassification($key, $score);
                                                        @endphp
                                                        <div class="flex items-center justify-between py-1.5 border-b border-slate-50 last:border-0">
                                                            <div>
                                                                <span class="font-semibold text-slate-700">{{ $label }}</span>
                                                                <span class="text-[9px] text-slate-400 block mt-0.5">Skor: <strong class="text-slate-700 font-bold">{{ $score }}</strong>/{{ $maxScore }}</span>
                                                            </div>
                                                            <span class="px-2 py-0.5 rounded text-[9px] font-bold border {{ $classBadge }}">
                                                                {{ $classStatus }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            {{-- SAS-SV Subscores --}}
                                            <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm space-y-3">
                                                <div class="border-b border-slate-100 pb-1.5 flex justify-between items-center">
                                                    <h4 class="font-bold text-sky-500">Detail Jawaban Butir SAS-SV</h4>
                                                    <span class="text-[10px] text-slate-400 font-semibold">Total: <strong class="text-sky-600">{{ $response->sassv_score }}</strong>/60</span>
                                                </div>
                                                @php
                                                    $sasAns = $response->sassvAnswers->pluck('answer_value', 'question_number')->toArray();
                                                @endphp
                                                <div class="space-y-1.5 max-h-[220px] overflow-y-auto pr-1">
                                                    @for($i = 1; $i <= 10; $i++)
                                                        @php
                                                            $qText = $sasQuestions[$i]->text ?? "Pertanyaan {$i}";
                                                            $score = $sasAns[$i] ?? 1;
                                                            // Highlight high scores: 5 and 6 in rose, 4 in amber, others in slate
                                                            $color = match($score) {
                                                                5, 6 => 'bg-rose-50 text-rose-700 border-rose-100 font-extrabold',
                                                                4 => 'bg-amber-50 text-amber-700 border-amber-100 font-semibold',
                                                                default => 'bg-slate-50 text-slate-500 border-slate-100',
                                                            };
                                                        @endphp
                                                        <div class="p-2 bg-slate-50/50 rounded-lg border border-slate-100 flex items-start justify-between gap-3 text-[10px]">
                                                            <div class="flex-1">
                                                                <span class="font-bold text-slate-400">{{ $i }}.</span>
                                                                <span class="text-slate-600 leading-tight">{{ $qText }}</span>
                                                            </div>
                                                            <span class="px-1.5 py-0.5 rounded border text-[9px] flex-shrink-0 {{ $color }}">
                                                                Skor: {{ $score }}
                                                            </span>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-slate-500 font-medium italic">Siswa ini belum pernah mengisi kuesioner.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>



    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if($latestResponse)
            @php
                $latestSdq = $latestResponse->getSdqSubscores();
                $latestPsc = $latestResponse->getPsc17Subscores();
            @endphp

            // 1. SDQ SUBSCALES CHART (LATEST)
            const ctxSdq = document.getElementById('sdqSubscaleChart').getContext('2d');
            new Chart(ctxSdq, {
                type: 'bar',
                data: {
                    labels: [['Gejala', 'Emosi'], ['Masalah', 'Perilaku'], ['Hiper-', 'aktivitas'], ['Teman', 'Sebaya'], ['Pro-', 'sosial']],
                    datasets: [{
                        data: [
                            {{ $latestSdq['emotional'] ?? 0 }},
                            {{ $latestSdq['conduct'] ?? 0 }},
                            {{ $latestSdq['hyperactivity'] ?? 0 }},
                            {{ $latestSdq['peer'] ?? 0 }},
                            {{ $latestSdq['prosocial'] ?? 0 }}
                        ],
                        backgroundColor: 'rgba(99, 102, 241, 0.75)',
                        borderColor: '#6366f1',
                        borderWidth: 1.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 0,
                            max: 10,
                            grid: { color: '#f1f5f9' },
                            ticks: { stepSize: 2 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                autoSkip: false,
                                maxRotation: 0,
                                minRotation: 0,
                                font: { size: 9 }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // 2. PSC-17 SUBSCALES CHART (LATEST)
            const ctxPsc = document.getElementById('pscSubscaleChart').getContext('2d');
            new Chart(ctxPsc, {
                type: 'bar',
                data: {
                    labels: [['Internal', '(Emosi)'], ['Atensi', '(Fokus)'], ['Eksternal', '(Perilaku)']],
                    datasets: [{
                        data: [
                            {{ $latestPsc['internalizing'] ?? 0 }},
                            {{ $latestPsc['attention'] ?? 0 }},
                            {{ $latestPsc['externalizing'] ?? 0 }}
                        ],
                        backgroundColor: 'rgba(244, 63, 94, 0.75)',
                        borderColor: '#f43f5e',
                        borderWidth: 1.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 0,
                            max: 14,
                            grid: { color: '#f1f5f9' },
                            ticks: { stepSize: 2 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                autoSkip: false,
                                maxRotation: 0,
                                minRotation: 0,
                                font: { size: 9 }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // 3. SAS-SV SCORE CHART (LATEST)
            const ctxSassv = document.getElementById('sassvScoreChart').getContext('2d');
            new Chart(ctxSassv, {
                type: 'bar',
                data: {
                    labels: [['Kecanduan', 'Gawai']],
                    datasets: [{
                        data: [
                            {{ $latestResponse->sassv_score ?? 0 }}
                        ],
                        backgroundColor: 'rgba(14, 165, 233, 0.75)',
                        borderColor: '#0ea5e9',
                        borderWidth: 1.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 0,
                            max: 60,
                            grid: { color: '#f1f5f9' },
                            ticks: { stepSize: 10 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                autoSkip: false,
                                maxRotation: 0,
                                minRotation: 0,
                                font: { size: 9 }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });


            @endif
        });
    </script>
    @endpush
</x-guru-bk-layout>
