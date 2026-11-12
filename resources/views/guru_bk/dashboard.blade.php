<x-guru-bk-layout pageTitle="Dashboard Pemantauan Guru BK" pageSubtitle="Sekilas tentang kondisi psikososial dan tingkat ketergantungan gawai seluruh siswa.">

    <div class="space-y-6">
        {{-- Panel Unduh Laporan Satu Sekolah --}}
        <div class="bg-indigo-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <h3 class="text-base font-bold">Laporan Ringkasan Psikososial Sekolah</h3>
                <p class="text-xs text-indigo-200 mt-1">Dapatkan komparasi data hasil skrining psikososial dan kecanduan gawai antar tingkatan kelas secara lengkap.</p>
            </div>
            <div class="flex items-center gap-2.5 relative z-10">
                <a href="{{ route('guruBk.laporan-sekolah.print') }}" target="_blank" 
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white hover:bg-slate-100 text-indigo-950 font-bold rounded-xl text-xs transition shadow-sm cursor-pointer">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Rangkuman (PDF)
                </a>
                <a href="{{ route('guruBk.laporan-sekolah.export') }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-sm cursor-pointer border border-emerald-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Ekspor Rangkuman (Excel)
                </a>
            </div>
        </div>

        {{-- 1. Kartu Ringkasan Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            {{-- Total Siswa --}}
            <a href="{{ route('guruBk.siswa.index') }}" class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md hover:border-indigo-200 hover:scale-[1.02] transition-all duration-300 group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-100 group-hover:text-indigo-700 transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Total Siswa</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalStudents }}</p>
                </div>
            </a>

            {{-- Partisipasi --}}
            <a href="{{ route('guruBk.siswa.index', ['participation' => 'completed']) }}" class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md hover:border-emerald-200 hover:scale-[1.02] transition-all duration-300 group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-100 group-hover:text-emerald-700 transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Siswa Partisipan</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $completedStudents }} <span class="text-xs text-slate-500 font-normal">({{ $participationRate }}%)</span></p>
                </div>
            </a>

            {{-- Belum Kuesioner --}}
            <a href="{{ route('guruBk.siswa.index', ['participation' => 'none']) }}" class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md hover:border-amber-200 hover:scale-[1.02] transition-all duration-300 group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 group-hover:bg-amber-100 group-hover:text-amber-700 transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Belum Kuesioner</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $nonCompletedStudents }}</p>
                </div>
            </a>

            {{-- Siswa Risiko Tinggi --}}
            <a href="{{ route('guruBk.siswa.index', ['risk_level' => 'Berisiko Psikososial']) }}" class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md hover:border-rose-200 hover:scale-[1.02] transition-all duration-300 group cursor-pointer">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 group-hover:bg-rose-100 group-hover:text-rose-700 transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-rose-700">Berisiko Psikososial</p>
                    <p class="text-2xl font-bold text-rose-800">{{ $highRiskStudentsCount }} <span class="text-xs text-rose-500 font-normal">siswa</span></p>
                </div>
            </a>
        </div>

        {{-- 2. BI Row 1: Perbandingan Kelas --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Skor Rata-rata Instrumen per Kelas</h3>
                <p class="text-xs text-slate-400 mt-1">Perbandingan tingkat keparahan perilaku, psikososial, dan ketergantungan gawai</p>
            </div>
            <div class="my-4" style="height: 350px;">
                <canvas id="classScoresChart"></canvas>
            </div>
            <div class="text-[11px] text-slate-400">
                *Perbandingan skor antar tingkatan kelas untuk mendeteksi klaster/kelompok risiko tinggi.
            </div>
        </div>

        {{-- 2.1. Analisis Distribusi Risiko Psikososial --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Distribusi Risiko Psikososial Keseluruhan --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Distribusi Risiko Psikososial Keseluruhan</h3>
                    <p class="text-xs text-slate-400 mt-1">Proporsi siswa yang terindikasi berisiko psikososial secara keseluruhan</p>
                </div>
                <div class="my-6 flex justify-center items-center relative" style="height: 180px;">
                    <canvas id="overallRiskChart"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-2 text-[10px] border-t border-slate-50 pt-3">
                    <a href="{{ route('guruBk.siswa.index', ['risk_level' => 'Tidak Berisiko Psikososial']) }}" class="flex items-center gap-1.5 justify-center hover:text-indigo-600 transition">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                        <span>Tidak Berisiko: <strong>{{ $riskCounts['Tidak Berisiko Psikososial'] }}</strong></span>
                    </a>
                    <a href="{{ route('guruBk.siswa.index', ['risk_level' => 'Berisiko Psikososial']) }}" class="flex items-center gap-1.5 justify-center hover:text-indigo-600 transition">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-600 flex-shrink-0"></span>
                        <span>Berisiko: <strong>{{ $riskCounts['Berisiko Psikososial'] }}</strong></span>
                    </a>
                </div>
            </div>

            {{-- Distribusi Risiko Psikososial per Kelas --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Distribusi Risiko Psikososial per Kelas</h3>
                    <p class="text-xs text-slate-400 mt-1">Perbandingan jumlah siswa berisiko dan tidak berisiko di setiap kelas</p>
                </div>
                <div class="my-4 flex justify-center items-center relative" style="height: 180px; width: 100%;">
                    <canvas id="classRiskDistributionChart"></canvas>
                </div>
                <div class="text-[10px] text-slate-400 border-t border-slate-50 pt-3">
                    *Membantu mendeteksi kelas yang memerlukan intervensi klasikal segera.
                </div>
            </div>

            {{-- Peringkat Kelas Berdasarkan Jumlah Siswa Berisiko --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Peringkat Kelas Siswa Berisiko</h3>
                    <p class="text-xs text-slate-400 mt-1">Urutan kelas berdasarkan jumlah siswa dengan kategori Berisiko Psikososial</p>
                </div>
                <div class="my-4 flex justify-center items-center relative" style="height: 180px; width: 100%;">
                    <canvas id="classRiskRankingChart"></canvas>
                </div>
                <div class="text-[10px] text-slate-400 border-t border-slate-50 pt-3">
                    *Prioritas bimbingan kelompok/konseling didasarkan pada tingkat kerentanan kelas.
                </div>
            </div>
        </div>

        {{-- 3. Detail Analisis per Instrumen --}}
        <div class="space-y-8 border-t border-slate-100 pt-6">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Analisis Rinci per Instrumen Kuesioner</h3>
                <p class="text-xs text-slate-500 mt-1">Perbandingan sebaran kategori risiko dan rata-rata skor detail subskala antar instrumen.</p>
            </div>

            {{-- Row 1: 3 Grafik Distribusi Kategori (Doughnut) Bersampingan --}}
            <div class="space-y-3">
                <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                    <span class="w-1.5 h-3 bg-indigo-600 rounded-full"></span>
                    Distribusi Kategori Penilaian Utama
                </h4>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Distribusi SDQ --}}
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <h5 class="font-bold text-slate-700 text-xs">Distribusi Klasifikasi Kesehatan Mental (SDQ)</h5>
                            <p class="text-[10px] text-slate-400 mt-0.5">Proporsi tingkat kesulitan emosional & perilaku siswa</p>
                        </div>
                        <div class="my-4 flex justify-center items-center relative" style="height: 160px;">
                            <canvas id="sdqCategoryChart"></canvas>
                        </div>
                        <div class="grid grid-cols-3 gap-1 text-[10px] border-t border-slate-50 pt-3">
                            <a href="{{ route('guruBk.siswa.index', ['sdq_category' => 'Normal']) }}" class="flex items-center gap-1 hover:text-indigo-600 transition">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                <span class="truncate">Normal: <strong>{{ $sdqCounts['Normal'] }}</strong></span>
                            </a>
                            <a href="{{ route('guruBk.siswa.index', ['sdq_category' => 'Borderline']) }}" class="flex items-center gap-1 hover:text-indigo-600 transition">
                                <span class="w-2 h-2 rounded-full bg-amber-500 flex-shrink-0"></span>
                                <span class="truncate">Borderline: <strong>{{ $sdqCounts['Borderline'] }}</strong></span>
                            </a>
                            <a href="{{ route('guruBk.siswa.index', ['sdq_category' => 'Abnormal']) }}" class="flex items-center gap-1 hover:text-indigo-600 transition">
                                <span class="w-2 h-2 rounded-full bg-rose-600 flex-shrink-0"></span>
                                <span class="truncate">Abnormal: <strong>{{ $sdqCounts['Abnormal'] }}</strong></span>
                            </a>
                        </div>
                    </div>

                    {{-- Distribusi PSC-17 --}}
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <h5 class="font-bold text-slate-700 text-xs">Distribusi Klasifikasi Kondisi Psikososial (PSC-17)</h5>
                            <p class="text-[10px] text-slate-400 mt-0.5">Proporsi indikasi gangguan psikososial secara umum</p>
                        </div>
                        <div class="my-4 flex justify-center items-center relative" style="height: 160px;">
                            <canvas id="pscCategoryChart"></canvas>
                        </div>
                        <div class="grid grid-cols-2 gap-1 text-[10px] border-t border-slate-50 pt-3">
                            <a href="{{ route('guruBk.siswa.index', ['psc17_category' => 'Negatif']) }}" class="flex items-center gap-1.5 justify-center hover:text-indigo-600 transition">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                <span>Negatif: <strong>{{ $pscCounts['Negatif'] }}</strong></span>
                            </a>
                            <a href="{{ route('guruBk.siswa.index', ['psc17_category' => 'Positif']) }}" class="flex items-center gap-1.5 justify-center hover:text-indigo-600 transition">
                                <span class="w-2 h-2 rounded-full bg-rose-600 flex-shrink-0"></span>
                                <span>Positif: <strong>{{ $pscCounts['Positif'] }}</strong></span>
                            </a>
                        </div>
                    </div>

                    {{-- Distribusi SAS-SV --}}
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <h5 class="font-bold text-slate-700 text-xs">Distribusi Ketergantungan Gawai (SAS-SV)</h5>
                            <p class="text-[10px] text-slate-400 mt-0.5">Proporsi tingkat kecanduan gawai pada siswa</p>
                        </div>
                        <div class="my-4 flex justify-center items-center relative" style="height: 160px;">
                            <canvas id="sassvCategoryChart"></canvas>
                        </div>
                        <div class="grid grid-cols-2 gap-1 text-[10px] border-t border-slate-50 pt-3">
                            <a href="{{ route('guruBk.siswa.index', ['sassv_category' => 'Tidak Kecanduan']) }}" class="flex items-center gap-1.5 justify-center hover:text-indigo-600 transition">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                <span>Tidak Kecanduan: <strong>{{ $sassvCounts['Tidak Kecanduan'] }}</strong></span>
                            </a>
                            <a href="{{ route('guruBk.siswa.index', ['sassv_category' => 'Kecanduan']) }}" class="flex items-center gap-1.5 justify-center hover:text-indigo-600 transition">
                                <span class="w-2 h-2 rounded-full bg-rose-600 flex-shrink-0"></span>
                                <span>Kecanduan: <strong>{{ $sassvCounts['Kecanduan'] }}</strong></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Row 2: 3 Grafik Subskala / Sebaran (Bar) Bersampingan --}}
            <div class="space-y-3">
                <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                    <span class="w-1.5 h-3 bg-indigo-600 rounded-full"></span>
                    Analisis Detail Skor & Subskala
                </h4>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Rinci SDQ --}}
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <h5 class="font-bold text-slate-700 text-xs">Rata-rata Skor Subskala Kesehatan Mental</h5>
                            <p class="text-[10px] text-slate-400 mt-0.5">Rata-rata 5 subskala emosi & perilaku (Skor 0-10)</p>
                        </div>
                        <div class="my-3" style="height: 170px;">
                            <canvas id="sdqSubscaleChart"></canvas>
                        </div>
                        <div class="text-[10px] text-slate-400">
                            *Skor tinggi = kesulitan lebih besar (kecuali subskala Prososial).
                        </div>
                    </div>

                    {{-- Rinci PSC-17 --}}
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <h5 class="font-bold text-slate-700 text-xs">Rata-rata Skor Subskala Kondisi Psikososial</h5>
                            <p class="text-[10px] text-slate-400 mt-0.5">Rata-rata 3 dimensi gangguan mental/perilaku anak</p>
                        </div>
                        <div class="my-3" style="height: 170px;">
                            <canvas id="pscSubscaleChart"></canvas>
                        </div>
                        <div class="text-[10px] text-slate-400">
                            *Subskala: Internalizing (emosi), Attention (fokus), Externalizing (perilaku).
                        </div>
                    </div>

                    {{-- Rinci SAS-SV --}}
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <h5 class="font-bold text-slate-700 text-xs">Sebaran Frekuensi Skor Kecanduan Gawai</h5>
                            <p class="text-[10px] text-slate-400 mt-0.5">Jumlah pengerjaan berdasarkan pengelompokan total skor (Skor 10-50)</p>
                        </div>
                        <div class="my-3" style="height: 170px;">
                            <canvas id="sassvDistributionChart"></canvas>
                        </div>
                        <div class="text-[10px] text-slate-400">
                            *Skor lebih tinggi menandakan tingkat ketergantungan gawai yang semakin berat.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. BAR CHART - RATA-RATA SKOR PER KELAS
            const ctxClass = document.getElementById('classScoresChart').getContext('2d');
            new Chart(ctxClass, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($classLabels) !!},
                    datasets: [
                        {
                            label: 'Rata SDQ (Kesehatan Mental)',
                            data: {!! json_encode($classSdq) !!},
                            backgroundColor: 'rgba(167, 139, 250, 0.75)',
                            borderColor: '#a78bfa',
                            borderWidth: 1.5
                        },
                        {
                            label: 'Rata PSC-17 (Psikososial)',
                            data: {!! json_encode($classPsc) !!},
                            backgroundColor: 'rgba(244, 114, 182, 0.75)',
                            borderColor: '#f472b6',
                            borderWidth: 1.5
                        },
                        {
                            label: 'Rata SAS-SV (Gawai)',
                            data: {!! json_encode($classSassv) !!},
                            backgroundColor: 'rgba(56, 189, 248, 0.75)',
                            borderColor: '#38bdf8',
                            borderWidth: 1.5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (event, elements, chart) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const classIds = {!! json_encode($classIds) !!};
                            const classId = classIds[index];
                            window.location.href = `{{ route('guruBk.siswa.index') }}?class_id=${classId}`;
                        }
                    },
                    onHover: (event, chartElement) => {
                        event.native.target.style.cursor = chartElement.length ? 'pointer' : 'default';
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            grid: { color: '#f1f5f9' },
                            max: 50
                        }
                    },
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 9 } } }
                    }
                }
            });

            // 1.1. OVERALL RISK DOUGHNUT
            const ctxOverallRisk = document.getElementById('overallRiskChart').getContext('2d');
            new Chart(ctxOverallRisk, {
                type: 'doughnut',
                data: {
                    labels: ['Tidak Berisiko', 'Berisiko'],
                    datasets: [{
                        data: [
                            {{ $riskCounts['Tidak Berisiko Psikososial'] }},
                            {{ $riskCounts['Berisiko Psikososial'] }}
                        ],
                        backgroundColor: ['#10b981', '#e11d48'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (event, elements, chart) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const label = chart.data.labels[index];
                            const risk = label === 'Berisiko' ? 'Berisiko Psikososial' : 'Tidak Berisiko Psikososial';
                            window.location.href = `{{ route('guruBk.siswa.index') }}?risk_level=${risk}`;
                        }
                    },
                    onHover: (event, chartElement) => {
                        event.native.target.style.cursor = chartElement.length ? 'pointer' : 'default';
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.label}: ${context.raw} siswa`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            // 1.2. CLASS RISK DISTRIBUTION STACKED BAR
            const ctxClassRisk = document.getElementById('classRiskDistributionChart').getContext('2d');
            new Chart(ctxClassRisk, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($classLabels) !!},
                    datasets: [
                        {
                            label: 'Tidak Berisiko',
                            data: {!! json_encode($classRiskLow) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.75)',
                            borderColor: '#10b981',
                            borderWidth: 1.5
                        },
                        {
                            label: 'Berisiko',
                            data: {!! json_encode($classRiskHigh) !!},
                            backgroundColor: 'rgba(225, 29, 72, 0.75)',
                            borderColor: '#e11d48',
                            borderWidth: 1.5
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (event, elements, chart) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const datasetIndex = elements[0].datasetIndex;
                            const classIds = {!! json_encode($classIds) !!};
                            const classId = classIds[index];
                            const risk = datasetIndex === 1 ? 'Berisiko Psikososial' : 'Tidak Berisiko Psikososial';
                            window.location.href = `{{ route('guruBk.siswa.index') }}?class_id=${classId}&risk_level=${risk}`;
                        }
                    },
                    onHover: (event, chartElement) => {
                        event.native.target.style.cursor = chartElement.length ? 'pointer' : 'default';
                    },
                    scales: {
                        x: { 
                            stacked: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { stepSize: 1 }
                        },
                        y: { 
                            stacked: true,
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: true, position: 'bottom', labels: { boxWidth: 10, font: { size: 9 } } }
                    }
                }
            });

            // 1.3. CLASS RISK RANKING HORIZONTAL BAR
            const ctxClassRank = document.getElementById('classRiskRankingChart').getContext('2d');
            new Chart(ctxClassRank, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($rankingClassLabels) !!},
                    datasets: [{
                        label: 'Siswa Berisiko',
                        data: {!! json_encode($rankingClassData) !!},
                        backgroundColor: 'rgba(244, 63, 94, 0.75)',
                        borderColor: '#f43f5e',
                        borderWidth: 1.5
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (event, elements, chart) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const rankingClassIds = {!! json_encode($rankingClassIds) !!};
                            const classId = rankingClassIds[index];
                            window.location.href = `{{ route('guruBk.siswa.index') }}?class_id=${classId}&risk_level=Berisiko Psikososial`;
                        }
                    },
                    onHover: (event, chartElement) => {
                        event.native.target.style.cursor = chartElement.length ? 'pointer' : 'default';
                    },
                    scales: {
                        x: { 
                            grid: { color: '#f1f5f9' },
                            ticks: { stepSize: 1 } 
                        },
                        y: { 
                            grid: { display: false } 
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // 2. SDQ CATEGORY DOUGHNUT
            const ctxSdqCat = document.getElementById('sdqCategoryChart').getContext('2d');
            new Chart(ctxSdqCat, {
                type: 'doughnut',
                data: {
                    labels: ['Normal', 'Borderline', 'Abnormal'],
                    datasets: [{
                        data: [
                            {{ $sdqCounts['Normal'] }},
                            {{ $sdqCounts['Borderline'] }},
                            {{ $sdqCounts['Abnormal'] }}
                        ],
                        backgroundColor: ['#10b981', '#f59e0b', '#e11d48'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (event, elements, chart) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const label = chart.data.labels[index];
                            window.location.href = `{{ route('guruBk.siswa.index') }}?sdq_category=${label}`;
                        }
                    },
                    onHover: (event, chartElement) => {
                        event.native.target.style.cursor = chartElement.length ? 'pointer' : 'default';
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.label}: ${context.raw} siswa`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            // 3. SDQ SUBSCALES BAR CHART
            const ctxSdqSub = document.getElementById('sdqSubscaleChart').getContext('2d');
            new Chart(ctxSdqSub, {
                type: 'bar',
                data: {
                    labels: ['Emosional', 'Perilaku', 'Hiperaktivitas', ['Teman', 'Sebaya'], 'Prososial'],
                    datasets: [{
                        label: 'Skor Rata-rata Subskala',
                        data: [
                            {{ $sdqSubscaleAverages['emotional'] }},
                            {{ $sdqSubscaleAverages['conduct'] }},
                            {{ $sdqSubscaleAverages['hyperactivity'] }},
                            {{ $sdqSubscaleAverages['peer'] }},
                            {{ $sdqSubscaleAverages['prosocial'] }}
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
                                minRotation: 0
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // 4. PSC-17 CATEGORY DOUGHNUT
            const ctxPscCat = document.getElementById('pscCategoryChart').getContext('2d');
            new Chart(ctxPscCat, {
                type: 'doughnut',
                data: {
                    labels: ['Normal (Negatif)', 'Berisiko (Positif)'],
                    datasets: [{
                        data: [
                            {{ $pscCounts['Negatif'] }},
                            {{ $pscCounts['Positif'] }}
                        ],
                        backgroundColor: ['#10b981', '#e11d48'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (event, elements, chart) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const label = chart.data.labels[index];
                            const category = label.includes('Positif') ? 'Positif' : 'Negatif';
                            window.location.href = `{{ route('guruBk.siswa.index') }}?psc17_category=${category}`;
                        }
                    },
                    onHover: (event, chartElement) => {
                        event.native.target.style.cursor = chartElement.length ? 'pointer' : 'default';
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.label}: ${context.raw} siswa`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            // 5. PSC-17 SUBSCALES BAR CHART
            const ctxPscSub = document.getElementById('pscSubscaleChart').getContext('2d');
            new Chart(ctxPscSub, {
                type: 'bar',
                data: {
                    labels: [['Internalizing', '(Emosi)'], ['Attention', '(Perhatian)'], ['Externalizing', '(Perilaku)']],
                    datasets: [{
                        label: 'Skor Rata-rata Subskala',
                        data: [
                            {{ $pscSubscaleAverages['internalizing'] }},
                            {{ $pscSubscaleAverages['attention'] }},
                            {{ $pscSubscaleAverages['externalizing'] }}
                        ],
                        backgroundColor: 'rgba(236, 72, 153, 0.75)',
                        borderColor: '#ec4899',
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
                                minRotation: 0
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // 6. SAS-SV CATEGORY DOUGHNUT
            const ctxSassvCat = document.getElementById('sassvCategoryChart').getContext('2d');
            new Chart(ctxSassvCat, {
                type: 'doughnut',
                data: {
                    labels: ['Tidak Kecanduan', 'Kecanduan'],
                    datasets: [{
                        data: [
                            {{ $sassvCounts['Tidak Kecanduan'] }},
                            {{ $sassvCounts['Kecanduan'] }}
                        ],
                        backgroundColor: ['#10b981', '#e11d48'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (event, elements, chart) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const label = chart.data.labels[index];
                            window.location.href = `{{ route('guruBk.siswa.index') }}?sassv_category=${label}`;
                        }
                    },
                    onHover: (event, chartElement) => {
                        event.native.target.style.cursor = chartElement.length ? 'pointer' : 'default';
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.label}: ${context.raw} siswa`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            // 7. SAS-SV DISTRIBUTION BAR CHART
            const ctxSassvDist = document.getElementById('sassvDistributionChart').getContext('2d');
            new Chart(ctxSassvDist, {
                type: 'bar',
                data: {
                    labels: ['Skor 10-20', 'Skor 21-30', 'Skor 31-40', 'Skor 41-60'],
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: [
                            {{ $sassvDistribution['10-20'] }},
                            {{ $sassvDistribution['21-30'] }},
                            {{ $sassvDistribution['31-40'] }},
                            {{ $sassvDistribution['41-60'] }}
                        ],
                        backgroundColor: 'rgba(56, 189, 248, 0.75)',
                        borderColor: '#38bdf8',
                        borderWidth: 1.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 0,
                            grid: { color: '#f1f5f9' },
                            ticks: { stepSize: 1 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                autoSkip: false,
                                maxRotation: 0,
                                minRotation: 0
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
    @endpush
</x-guru-bk-layout>