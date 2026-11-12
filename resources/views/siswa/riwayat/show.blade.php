<x-siswa-layout>
    <x-slot name="pageTitle">Detail Hasil Kuesioner</x-slot>
    <x-slot name="pageSubtitle">{{ $response->completed_at?->format('d F Y, H:i') }} WIB</x-slot>

    @php
        $rc   = $response->riskClassification;
        $risk = $rc?->overall_risk ?? '-';
        $isBerisiko = $risk === 'Berisiko Psikososial';

        $jenisKelamin = $response->user->gender ?? 'L';
        $sasCutoff    = $jenisKelamin === 'P' ? 33 : 31;

        $humanLabel = [
            'Normal'     => ['badge' => 'Normal',      'sub' => 'Hasil SDQ menunjukkan kesehatan emosi dan perilakumu berada dalam batas normal.', 'ok' => true],
            'Borderline' => ['badge' => 'Borderline',  'sub' => 'Hasil SDQ berada pada batas ambang. Yuk pantau kondisi emosi dan perilakumu serta berbagi cerita.', 'ok' => false],
            'Abnormal'   => ['badge' => 'Abnormal',    'sub' => 'Hasil SDQ menunjukkan kategori abnormal. Sangat dianjurkan untuk bercerita dan berkonsultasi dengan Guru BK.', 'ok' => false],

            'Negatif'    => ['badge' => 'Negatif',     'sub' => 'Hasil PSC-17 menunjukkan kamu negatif dari indikasi masalah psikososial.', 'ok' => true],
            'Positif'    => ['badge' => 'Positif',     'sub' => 'Hasil PSC-17 menunjukkan indikasi positif psikososial. Yuk luangkan waktu untuk berbagi cerita dengan Guru BK atau orang tua.', 'ok' => false],

            'Tidak Kecanduan' => ['badge' => 'Penggunaan Wajar', 'sub' => 'Hasil SAS-SV menunjukkan kamu tidak kecanduan gawai. Terus pertahankan penggunaan smartphone yang sehat.', 'ok' => true],
            'Kecanduan'       => ['badge' => 'Perlu Dikurangi',  'sub' => 'Hasil SAS-SV menunjukkan kecanduan gawai. Disarankan untuk mulai mengurangi penggunaan HP harianmu.', 'ok' => false],
        ];

        $instruments = [
            [
                'name'  => 'SDQ',
                'score' => $response->sdq_score,
                'max'   => 40,
                'cat'   => $rc?->sdq_category,
                'hex'   => '#a78bfa',
                'icon'  => '🧠',
                'desc'  => 'Kesehatan Mental & Perilaku',
            ],
            [
                'name'  => 'PSC-17',
                'score' => $response->psc17_score,
                'max'   => 34,
                'cat'   => $rc?->psc17_category,
                'hex'   => '#f472b6',
                'icon'  => '💬',
                'desc'  => 'Gejala Psikososial',
            ],
            [
                'name'  => 'SAS-SV',
                'score' => $response->sassv_score,
                'max'   => 60,
                'cat'   => $rc?->sassv_category,
                'hex'   => '#38bdf8',
                'icon'  => '📱',
                'desc'  => 'Kebiasaan Penggunaan HP',
            ],
        ];

        // ══ REKOMENDASI PER-SUBSKALA ══
        // Semua subskala berstatus "tidak normal" akan dapat kartu rekomendasi masing-masing,
        // bukan cuma yang tertinggi.
        $sdqSubRekomendasi = [
            'emotional'     => ['title' => 'Kelola Perasaanmu', 'desc' => 'Skor masalah emosional kamu tinggi. Coba tulis jurnal harian atau cerita ke orang terpercaya saat perasaan lagi berat.'],
            'conduct'       => ['title' => 'Kenali Pemicu Perilaku', 'desc' => 'Skor masalah perilaku kamu tinggi. Yuk kenali situasi yang bikin kamu gampang emosi, dan latihan tarik napas sebelum bereaksi.'],
            'hyperactivity' => ['title' => 'Latih Fokus Bertahap', 'desc' => 'Skor hiperaktivitas & pemusatan perhatian kamu tinggi. Coba pecah tugas jadi bagian kecil dan kasih jeda istirahat tiap 20–25 menit.'],
            'peer'          => ['title' => 'Bangun Koneksi Pertemanan', 'desc' => 'Skor masalah teman sebaya kamu tinggi. Coba ikut kegiatan kelompok atau ekskul buat nambah teman dan rasa nyaman di sekolah.'],
        ];

        $pscSubRekomendasi = [
            'internalizing' => ['title' => 'Ungkapkan Perasaanmu', 'desc' => 'Gejala internal/emosi kamu perlu perhatian. Jangan pendam sendiri — cerita ke Guru BK atau orang terdekat bisa bantu meringankan.'],
            'attention'     => ['title' => 'Bangun Rutinitas Fokus', 'desc' => 'Masalah perhatian/fokus kamu perlu perhatian. Coba buat jadwal belajar dengan durasi pendek dan kurangi distraksi HP saat belajar.'],
            'externalizing' => ['title' => 'Kelola Reaksi Impulsif', 'desc' => 'Gejala eksternal/perilaku kamu perlu perhatian. Latihan berhenti sejenak sebelum bertindak bisa bantu kamu lebih tenang.'],
        ];

        $tips = [];

        // SDQ — loop semua subskala (kecuali prosocial), semua yang "tidak normal" (Borderline atau Abnormal) dapat kartu sendiri
        if ($rc?->sdq_category && $rc->sdq_category !== 'Normal') {
            $sdqSubscores  = $response->getSdqSubscores();
            $sdqAdaNotNormal = false;
            foreach ($sdqSubscores as $subName => $subVal) {
                if ($subName === 'prosocial') continue;
                [$subClass] = \App\Models\QuestionnaireResponse::getSdqSubscaleClassification($subName, $subVal);
                if (in_array($subClass, ['Borderline', 'Abnormal']) && isset($sdqSubRekomendasi[$subName])) {
                    $tips[] = $sdqSubRekomendasi[$subName];
                    $sdqAdaNotNormal = true;
                }
            }
            // Fallback: borderline tapi nggak ada subskala yang borderline/abnormal
            if (!$sdqAdaNotNormal) {
                $tips[] = ['title' => 'Kelola Emosi Bareng-Bareng', 'desc' => 'Coba mulai cerita ke orang terpercaya soal apa yang lagi kamu rasain.'];
            }
        } elseif ($rc?->sdq_category === 'Normal') {
            $tips[] = ['title' => 'Jaga Keseimbangan Diri', 'desc' => 'Terus lakukan rutinitas positif dan seimbangkan waktu belajar, istirahat, serta bermain.'];
        }

        // PSC-17 — loop semua subskala, yang statusnya "Positif (Berisiko)" dapat kartu sendiri
        if ($rc?->psc17_category === 'Positif') {
            $pscSubscores  = $response->getPsc17Subscores();
            $pscAdaPositif = false;
            foreach ($pscSubscores as $subName => $subVal) {
                [$subClass] = \App\Models\QuestionnaireResponse::getPsc17SubscaleClassification($subName, $subVal);
                if (str_contains($subClass, 'Positif') && isset($pscSubRekomendasi[$subName])) {
                    $tips[] = $pscSubRekomendasi[$subName];
                    $pscAdaPositif = true;
                }
            }
            if (!$pscAdaPositif) {
                $tips[] = ['title' => 'Cari Teman Cerita', 'desc' => 'Nggak harus sendirian mikirinnya — orang tua atau Guru BK bisa bantu cariin solusi bareng.'];
            }
        } elseif ($rc?->psc17_category === 'Negatif') {
            $tips[] = ['title' => 'Bina Hubungan Sosial Positif', 'desc' => 'Terus jalin komunikasi aktif dan luangkan waktu berkualitas bersama teman serta keluarga.'];
        }

        // SAS-SV — cuma 1 skor total, tetap per-kategori
        if ($rc?->sassv_category === 'Kecanduan') {
            $tips[] = ['title' => 'Kurangi Screen Time', 'desc' => 'Coba batasi screen time di luar belajar dan hindari HP sebelum tidur.'];
        } elseif ($rc?->sassv_category === 'Tidak Kecanduan') {
            $tips[] = ['title' => 'Pertahankan Kontrol Gawai', 'desc' => 'Kebiasaan HP kamu udah seimbang, terus aktif di aktivitas dunia nyata ya.'];
        }

        // Penutup umum
        if ($isBerisiko) {
            $tips[] = ['title' => 'Konsultasi Guru BK', 'desc' => 'Guru BK di sekolahmu terlatih untuk membantumu. Kamu tidak harus menghadapi ini sendiri!'];
        } else {
            $tips[] = ['title' => 'Tetap Aktif & Sehat', 'desc' => 'Olahraga ringan 30 menit sehari terbukti meningkatkan suasana hati dan konsentrasi belajar.'];
        }
    @endphp

    <div class="max-w-2xl mx-auto space-y-5">

        {{-- Back --}}
        <a href="{{ route('siswa.riwayat.index') }}"
           class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-violet-600 font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>

        {{-- ══ RESULT BANNER ══ --}}
        @if($isBerisiko)
            <div class="relative overflow-hidden rounded-2xl bg-amber-400 p-6 shadow-lg shadow-amber-100">
                <div class="absolute top-0 right-0 w-48 h-48 rounded-full bg-white/10 -translate-y-1/2 translate-x-1/2"></div>

                {{-- Badge di pojok kanan atas --}}
                <span class="absolute top-4 right-4 bg-amber-950/15 text-amber-950 text-[10px] font-bold px-2.5 py-1 rounded-full border border-amber-950/20">
                    {{ $risk }}
                </span>

                <div class="relative flex items-start gap-4 pr-24">
                    <div class="text-4xl leading-none">💛</div>
                    <div>
                        <p class="text-amber-900/70 text-xs font-medium mb-1">Hasil Kuesioner</p>
                        <h2 class="text-xl font-extrabold text-amber-950 leading-tight">
                            Yuk, konseling privat dengan Guru BK mu!
                        </h2>
                    </div>
                </div>
            </div>
        @else
            <div class="relative overflow-hidden rounded-2xl bg-emerald-400 p-6 shadow-lg shadow-emerald-100">
                <div class="absolute top-0 right-0 w-48 h-48 rounded-full bg-white/10 -translate-y-1/2 translate-x-1/2"></div>

                {{-- Badge di pojok kanan atas --}}
                <span class="absolute top-4 right-4 bg-emerald-950/15 text-emerald-950 text-[10px] font-bold px-2.5 py-1 rounded-full border border-emerald-950/20">
                    {{ $risk }}
                </span>

                <div class="relative flex items-start gap-4 pr-24">
                    <div class="text-4xl leading-none">🌟</div>
                    <div>
                        <p class="text-emerald-900/70 text-xs font-medium mb-1">Hasil Kuesioner</p>
                        <h2 class="text-xl font-extrabold text-emerald-950 leading-tight">
                            Kondisimu Terlihat Baik!
                        </h2>
                    </div>
                </div>
            </div>
        @endif

        {{-- ══ BAR CHART ══ --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <p class="text-sm font-semibold text-slate-700 mb-1">Posisi Skor</p>
            <p class="text-[10px] text-slate-400 mb-5">Seberapa jauh skormu dari nilai maksimum masing-masing tes</p>

            <div class="space-y-4">
                @foreach($instruments as $inst)
                    @php $pct = $inst['score'] !== null ? round(($inst['score'] / $inst['max']) * 100) : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-1.5">
                                <span class="text-sm">{{ $inst['icon'] }}</span>
                                <span class="text-xs font-semibold text-slate-700">{{ $inst['name'] }}</span>
                                <span class="text-[10px] text-slate-400">{{ $inst['desc'] }}</span>
                            </div>
                            <span class="text-xs font-bold text-slate-600">
                                {{ $inst['score'] }}<span class="font-normal text-slate-400">/{{ $inst['max'] }}</span>
                            </span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                            <div class="h-3 rounded-full transition-all duration-1000 ease-out"
                                 style="width: {{ $pct }}%; background: {{ $inst['hex'] }}"></div>
                        </div>
                        <p class="text-[9px] text-slate-400 mt-1">{{ $pct }}% dari nilai maksimum</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ══ RINGKASAN HASIL ══ --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-700 mb-1">Ringkasan Hasil</h3>
            <p class="text-[10px] text-slate-400 mb-4">Penjelasan singkat tentang hasil tiap bagian tesmu</p>

            <div class="space-y-3">
                @foreach($instruments as $inst)
                    @php
                        $hl2   = $humanLabel[$inst['cat']] ?? ['badge' => $inst['cat'], 'sub' => '-', 'ok' => true];
                        $isOk2 = $hl2['ok'];
                    @endphp
                    <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border-l-4
                        {{ $isOk2 ? 'border-emerald-400' : 'border-amber-400' }}">
                        <div class="text-xl leading-none flex-shrink-0 mt-0.5">{{ $inst['icon'] }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                                <p class="text-xs font-bold text-slate-700">{{ $inst['name'] }}</p>
                                <span class="text-[9px] font-semibold px-2 py-0.5 rounded-full
                                    {{ $isOk2 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $hl2['badge'] }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed">{{ $hl2['sub'] }}</p>

                            @if($inst['name'] === 'SDQ')
                                <div x-data="{ open: false }" class="mt-2">
                                    <button type="button" @click="open = !open"
                                            class="text-[10px] font-semibold text-violet-500 hover:text-violet-600 flex items-center gap-1">
                                        <span x-text="open ? 'Sembunyikan rincian' : 'Lihat rincian subskala'"></span>
                                        <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" x-cloak x-transition class="mt-2 pt-3 border-t border-slate-200">
                                        <p class="text-[9px] text-slate-400 mb-2">Skor tinggi = butuh perhatian, kecuali Prososial</p>
                                        <div class="space-y-1.5">
                                            @php
                                                $subscores = $response->getSdqSubscores();
                                                $subNames = [
                                                    'emotional' => 'Masalah Emosional',
                                                    'conduct' => 'Masalah Perilaku',
                                                    'hyperactivity' => 'Hiperaktivitas & Pemusatan Perhatian',
                                                    'peer' => 'Masalah Teman Sebaya',
                                                    'prosocial' => 'Perilaku Prososial'
                                                ];
                                                $maxSdqSeverity = 0;
                                                $maxSdqVal = -1;
                                                $maxSdqKey = '';
                                                foreach($subscores as $k => $v) {
                                                    if($k === 'prosocial') continue;
                                                    [$subClass] = \App\Models\QuestionnaireResponse::getSdqSubscaleClassification($k, $v);
                                                    
                                                    $severity = 0;
                                                    if ($subClass === 'Abnormal') {
                                                        $severity = 2;
                                                    } elseif ($subClass === 'Borderline') {
                                                        $severity = 1;
                                                    }
                                                    
                                                    if ($severity > $maxSdqSeverity) {
                                                        $maxSdqSeverity = $severity;
                                                        $maxSdqVal = $v;
                                                        $maxSdqKey = $k;
                                                    } elseif ($severity === $maxSdqSeverity && $severity > 0) {
                                                        if ($v > $maxSdqVal) {
                                                            $maxSdqVal = $v;
                                                            $maxSdqKey = $k;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @foreach($subscores as $subName => $subVal)
                                                @php
                                                    [$subClass, $subBadge] = \App\Models\QuestionnaireResponse::getSdqSubscaleClassification($subName, $subVal);
                                                    $isHighest = ($subName === $maxSdqKey);
                                                @endphp
                                                <div class="flex items-center justify-between p-1.5 rounded bg-white border {{ $isHighest ? 'border-amber-300' : 'border-slate-100' }}">
                                                    <div class="flex items-center gap-1.5 min-w-0">
                                                        <span class="text-[9px] font-medium text-slate-700 truncate">{{ $subNames[$subName] }}</span>
                                                        @if($isHighest)
                                                            <span class="bg-amber-100 text-amber-700 text-[7px] font-extrabold px-1.5 py-0.2 rounded">Tertinggi</span>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center gap-2 flex-shrink-0">
                                                        <span class="text-[10px] font-semibold text-slate-600">{{ $subVal }}/10</span>
                                                        <span class="text-[7px] font-bold px-1.5 py-0.2 rounded border {{ $subBadge }}">{{ $subClass }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @elseif($inst['name'] === 'PSC-17')
                                <div x-data="{ open: false }" class="mt-2">
                                    <button type="button" @click="open = !open"
                                            class="text-[10px] font-semibold text-violet-500 hover:text-violet-600 flex items-center gap-1">
                                        <span x-text="open ? 'Sembunyikan rincian' : 'Lihat rincian subskala'"></span>
                                        <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" x-cloak x-transition class="mt-2 pt-3 border-t border-slate-200">
                                        <p class="text-[9px] text-slate-400 mb-2">Skor tinggi = butuh perhatian</p>
                                        <div class="space-y-1.5">
                                            @php
                                                $subscores = $response->getPsc17Subscores();
                                                $subNames = [
                                                    'internalizing' => 'Gejala Internal / Emosi',
                                                    'attention' => 'Masalah Perhatian / Fokus',
                                                    'externalizing' => 'Gejala Eksternal / Perilaku'
                                                ];
                                                $maxPscSeverity = 0;
                                                $maxPscVal = -1;
                                                $maxPscKey = '';
                                                foreach($subscores as $k => $v) {
                                                    [$subClass] = \App\Models\QuestionnaireResponse::getPsc17SubscaleClassification($k, $v);
                                                    
                                                    $severity = str_contains($subClass, 'Positif') ? 1 : 0;
                                                    
                                                    if ($severity > $maxPscSeverity) {
                                                        $maxPscSeverity = $severity;
                                                        $maxPscVal = $v;
                                                        $maxPscKey = $k;
                                                    } elseif ($severity === $maxPscSeverity && $severity > 0) {
                                                        if ($v > $maxPscVal) {
                                                            $maxPscVal = $v;
                                                            $maxPscKey = $k;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @foreach($subscores as $subName => $subVal)
                                                @php
                                                    $maxVal = $subName === 'externalizing' ? 14 : 10;
                                                    [$subClass, $subBadge] = \App\Models\QuestionnaireResponse::getPsc17SubscaleClassification($subName, $subVal);
                                                    $isHighest = ($subName === $maxPscKey);
                                                @endphp
                                                <div class="flex items-center justify-between p-1.5 rounded bg-white border {{ $isHighest ? 'border-amber-300' : 'border-slate-100' }}">
                                                    <div class="flex items-center gap-1.5 min-w-0">
                                                        <span class="text-[9px] font-medium text-slate-700 truncate">{{ $subNames[$subName] }}</span>
                                                        @if($isHighest)
                                                            <span class="bg-amber-100 text-amber-700 text-[7px] font-extrabold px-1.5 py-0.2 rounded">Tertinggi</span>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center gap-2 flex-shrink-0">
                                                        <span class="text-[10px] font-semibold text-slate-600">{{ $subVal }}/{{ $maxVal }}</span>
                                                        <span class="text-[7px] font-bold px-1.5 py-0.2 rounded border {{ $subBadge }}">{{ $subClass }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="text-base flex-shrink-0">{{ $isOk2 ? '✅' : '💛' }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ══ REKOMENDASI DINAMIS ══ --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-700 mb-1">💡 Rekomendasi Untukmu</h3>
            <p class="text-[10px] text-slate-400 mb-4">Langkah kecil yang bisa kamu coba dari hasil kuesionermu</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($tips as $tip)
                    <div class="bg-violet-50 ring-1 ring-violet-100 rounded-xl p-4">
                        <p class="text-xs font-bold text-slate-800 mb-1">{{ $tip['title'] }}</p>
                        <p class="text-[11px] text-slate-600 leading-relaxed">{{ $tip['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ══ DISCLAIMER ══ --}}
        <div class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 flex items-start gap-2.5">
            <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-[10px] text-slate-500 leading-relaxed">
                <span class="font-semibold text-slate-600">Catatan penting:</span>
                Hasil ini bukan diagnosis medis atau psikologis. Ini adalah alat bantu skrining awal yang membantu Guru BK memahami kondisimu lebih baik. Jangan khawatir — ceritakan saja hasilnya ke Guru BK kamu!
            </p>
        </div>

        {{-- ══ ACTIONS ══ --}}
        <div class="flex flex-col sm:flex-row gap-3">
            @if($isBerisiko)
                <a href="{{ route('siswa.chatbot.index') }}"
                   class="flex-1 text-center bg-violet-400 text-white font-bold py-3.5 rounded-xl text-sm shadow-md shadow-violet-100 hover:shadow-lg hover:opacity-95 transition flex items-center justify-center gap-2">
                    Tanya PsyBot
                </a>
                <a href="{{ route('siswa.questionnaire.index') }}"
                   class="flex-1 text-center bg-white border border-slate-200 text-slate-600 font-semibold py-3.5 rounded-xl text-sm hover:bg-slate-50 transition flex items-center justify-center gap-2">
                    Kuesioner ulang
                </a>
            @else
                <a href="{{ route('siswa.questionnaire.index') }}"
                   class="flex-1 text-center bg-violet-400 text-white font-bold py-3.5 rounded-xl text-sm shadow-md shadow-violet-100 hover:shadow-lg hover:opacity-95 transition flex items-center justify-center gap-2">
                    Kuesioner ulang
                </a>
                <a href="{{ route('siswa.chatbot.index') }}"
                   class="flex-1 text-center bg-white border border-slate-200 text-slate-600 font-semibold py-3.5 rounded-xl text-sm hover:bg-slate-50 transition flex items-center justify-center gap-2">
                    Tanya PsyBot
                </a>
            @endif
        </div>

    </div>
</x-siswa-layout>