<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Deteksi Dini Kondisi Psikososial – {{ $student->name }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-smpn216-new.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        @media screen {
            .print-area {
                background: white;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
                border: 1px solid #e2e8f0;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-area {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }
            /* Page layout resets */
            html, main, .page-enter {
                background: white !important;
                color: black !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }
            @page {
                size: A4 portrait;
                margin: 15mm 15mm 20mm 15mm;
            }
            .grid-print {
                display: flex !important;
                flex-direction: row !important;
                justify-content: space-between !important;
                align-items: stretch !important;
                width: 100% !important;
            }
            /* Table formatting */
            table {
                border-collapse: collapse !important;
                width: 100% !important;
            }
            th, td {
                border: 1px solid #cbd5e1 !important;
                padding: 5px 8px !important;
            }
            .border-none td {
                border: none !important;
                padding: 3px 5px !important;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .avoid-break {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-slate-100 font-inter antialiased min-h-screen">

    {{-- Header Menu Screen-Only --}}
    <div class="no-print bg-slate-900 text-white py-3.5 px-6 shadow-md flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <a href="{{ route('guruBk.siswa.show', $student->id) }}" class="inline-flex items-center gap-1.5 text-xs text-slate-300 hover:text-white font-semibold transition">
                &larr; Kembali ke Profil Siswa
            </a>
            <span class="text-slate-600">|</span>
            <span class="text-xs text-slate-300">Laporan Deteksi Dini: <strong>{{ $student->name }}</strong></span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition cursor-pointer shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Laporan / Simpan PDF
            </button>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="max-w-4xl mx-auto p-4 sm:p-8 my-6 print:my-0 print:p-0">

        {{-- Area Cetak --}}
        <div class="print-area bg-white rounded-3xl p-6 sm:p-10">

            {{-- Kop Surat Resmi --}}
            <div class="flex items-center justify-between border-b-[3px] border-double border-slate-800 pb-4 mb-6">
                <div class="w-20 h-20 flex-shrink-0 flex items-center justify-center">
                    <img src="{{ asset('images/logo-smpn216-new.png') }}" alt="Logo SMPN 216" class="w-20 h-20 object-contain">
                </div>
                <div class="flex-1 text-center px-4">
                    <h2 class="text-[11px] sm:text-sm font-bold tracking-wide uppercase text-slate-800 leading-tight whitespace-nowrap">Pemerintah Provinsi Daerah Khusus Ibukota Jakarta</h2>
                    <h1 class="text-lg sm:text-xl font-extrabold uppercase text-indigo-950 leading-snug mt-1">SMP Negeri 216 Jakarta</h1>
                    <p class="text-[11px] sm:text-xs text-slate-600 font-medium leading-normal mt-1">
                        Jl. Salemba Raya No. 18, Kenari, Kec. Senen, Kota Jakarta Pusat, DKI Jakarta 10430
                    </p>
                    <p class="text-[10px] sm:text-[11px] text-slate-500 font-medium leading-snug">
                        Telp: (021) 3909123 | Website: smpn216jakarta.sch.id | Email: smpn216@jakarta.go.id
                    </p>
                </div>
                {{-- Balancer --}}
                <div class="w-20 h-20 flex-shrink-0"></div>
            </div>

            @if($latestResponse)
                {{-- Judul Laporan --}}
                <div class="text-center mb-6">
                    <h2 class="text-xs sm:text-sm font-extrabold uppercase tracking-wide text-slate-900 underline underline-offset-4 decoration-1">Laporan Hasil Deteksi Dini Kondisi Psikososial</h2>
                    <p class="text-[10px] text-slate-600 font-semibold mt-1">Metode Kuesioner Diagnosis 3-in-1 (SDQ, PSC-17, SAS-SV)</p>
                </div>

                {{-- I. Identitas Siswa --}}
                <div class="border border-slate-300 rounded-xl p-4 mb-6 bg-slate-50/10">
                    <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-2 border-b border-slate-200 pb-1">I. IDENTITAS SISWA</h3>
                    <table class="w-full text-[10px] text-slate-800 leading-tight border-none">
                        <tbody>
                            <tr>
                                <td class="w-1/5 py-0.5 font-semibold text-slate-500">Nama Lengkap</td>
                                <td class="w-[3%] py-0.5 text-center text-slate-400">:</td>
                                <td class="w-1/3 py-0.5 font-bold text-slate-900">{{ $student->name }}</td>
                                <td class="w-[12%] py-0.5 pl-2 font-semibold text-slate-500">Kelas</td>
                                <td class="w-[3%] py-0.5 text-center text-slate-400">:</td>
                                <td class="py-0.5 font-bold text-slate-900">{{ $student->studentClass->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-0.5 font-semibold text-slate-500">Nomor Induk Siswa (NIS)</td>
                                <td class="py-0.5 text-center text-slate-400">:</td>
                                <td class="py-0.5 font-bold text-slate-900">{{ $student->nis ?? '-' }}</td>
                                <td class="py-0.5 pl-2 font-semibold text-slate-500 whitespace-nowrap">Tanggal Pengujian</td>
                                <td class="py-0.5 text-center text-slate-400">:</td>
                                <td class="py-0.5 font-bold text-slate-900 whitespace-nowrap">{{ $latestResponse->completed_at->translatedFormat('d F Y, H:i') }} WIB</td>
                            </tr>
                            <tr>
                                <td class="py-0.5 font-semibold text-slate-500">Jenis Kelamin</td>
                                <td class="py-0.5 text-center text-slate-400">:</td>
                                <td class="py-0.5 font-medium text-slate-800">{{ ($student->gender === 'L' || $student->gender === 'l') ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td class="py-0.5 pl-2 font-semibold text-slate-500">Status Risiko</td>
                                <td class="py-0.5 text-center text-slate-400">:</td>
                                <td class="py-0.5 font-extrabold">
                                    @php
                                        $overallRisk = $latestResponse->riskClassification->overall_risk ?? 'Tidak Berisiko';
                                        $riskColorClass = ($overallRisk === 'Tidak Berisiko Psikososial') ? 'text-emerald-700' : 'text-rose-700';
                                    @endphp
                                    <span class="{{ $riskColorClass }}">{{ $overallRisk }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- III. Detail Skor & Klasifikasi Klinis --}}
                <div class="mb-6 avoid-break">
                    <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-2 border-b border-slate-200 pb-1">II. DETAIL SKOR & KLASIFIKASI KLINIS</h3>
                    <table class="w-full text-left text-[9px] border border-collapse border-slate-300">
                        <thead>
                            <tr class="bg-slate-100 text-slate-800 font-bold border-b border-slate-300">
                                <th class="px-3 py-1.5 border-r border-slate-300">Instrumen & Dimensi Penilaian</th>
                                <th class="px-2 py-1.5 text-center border-r border-slate-300 w-1/6">Skor Siswa</th>
                                <th class="px-2 py-1.5 text-center border-r border-slate-300 w-1/6">Skor Maksimal</th>
                                <th class="px-3 py-1.5 border-r border-slate-300 w-1/4">Status Klasifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-slate-700">
                            {{-- SDQ --}}
                            <tr class="bg-indigo-50/30 font-bold text-slate-900">
                                <td colspan="4" class="px-3 py-1 border-r border-slate-300">1. Strength and Difficulties Questionnaire (SDQ) - Total Skor: {{ $latestResponse->sdq_score }}/40</td>
                            </tr>
                            @php
                                $sdqSub = $latestResponse->getSdqSubscores();
                                $sdqSubscales = [
                                    'emotional'     => 'Gejala Emosional',
                                    'conduct'       => 'Masalah Perilaku',
                                    'hyperactivity' => 'Hiperaktivitas',
                                    'peer'          => 'Masalah Teman Sebaya',
                                    'prosocial'     => 'Perilaku Prososial'
                                ];
                            @endphp
                            @foreach($sdqSubscales as $key => $label)
                                @php
                                    $score = $sdqSub[$key] ?? 0;
                                    [$classStatus, $classBadge] = \App\Models\QuestionnaireResponse::getSdqSubscaleClassification($key, $score);
                                @endphp
                                <tr>
                                    <td class="px-5 py-1 border-r border-slate-300">{{ $label }}</td>
                                    <td class="px-2 py-1 text-center border-r border-slate-300 font-bold">{{ $score }}</td>
                                    <td class="px-2 py-1 text-center border-r border-slate-300">10</td>
                                    <td class="px-3 py-1 border-r border-slate-300 font-semibold">{{ $classStatus }}</td>
                                </tr>
                            @endforeach

                            {{-- PSC-17 --}}
                            <tr class="bg-indigo-50/30 font-bold text-slate-900 border-t border-slate-300">
                                <td colspan="4" class="px-3 py-1 border-r border-slate-300">2. Pediatric Symptom Checklist-17 (PSC-17) - Total Skor: {{ $latestResponse->psc17_score }}/34</td>
                            </tr>
                            @php
                                $pscSub = $latestResponse->getPsc17Subscores();
                                $pscSubscales = [
                                    'internalizing' => ['Internalizing (Emosional)', 10],
                                    'attention'     => ['Attention (Fokus)', 10],
                                    'externalizing' => ['Externalizing (Perilaku)', 14]
                                ];
                            @endphp
                            @foreach($pscSubscales as $key => [$label, $maxScore])
                                @php
                                    $score = $pscSub[$key] ?? 0;
                                    [$classStatus, $classBadge] = \App\Models\QuestionnaireResponse::getPsc17SubscaleClassification($key, $score);
                                @endphp
                                <tr>
                                    <td class="px-5 py-1 border-r border-slate-300">{{ $label }}</td>
                                    <td class="px-2 py-1 text-center border-r border-slate-300 font-bold">{{ $score }}</td>
                                    <td class="px-2 py-1 text-center border-r border-slate-300">{{ $maxScore }}</td>
                                    <td class="px-3 py-1 border-r border-slate-300 font-semibold">{{ $classStatus }}</td>
                                </tr>
                            @endforeach

                            {{-- SAS-SV --}}
                            <tr class="bg-indigo-50/30 font-bold text-slate-900 border-t border-slate-300">
                                <td colspan="4" class="px-3 py-1 border-r border-slate-300">3. Smartphone Addiction Scale - Short Version (SAS-SV)</td>
                            </tr>
                            @php
                                $sasRisk = ($latestResponse->riskClassification->sassv_category === 'Kecanduan') ? 'Kecanduan Gawai (Berisiko)' : 'Tidak Kecanduan Gawai (Normal)';
                            @endphp
                            <tr>
                                <td class="px-5 py-1 border-r border-slate-300">Total Skor Kecanduan Gawai</td>
                                <td class="px-2 py-1 text-center border-r border-slate-300 font-bold">{{ $latestResponse->sassv_score }}</td>
                                <td class="px-2 py-1 text-center border-r border-slate-300">60</td>
                                <td class="px-3 py-1 border-r border-slate-300 font-semibold">{{ $sasRisk }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- IV. Rekomendasi & Rencana Tindakan BK --}}
                <div class="mb-8 border border-slate-300 rounded-xl p-4 bg-slate-50/5 avoid-break">
                    <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-2 border-b border-slate-200 pb-1">III. REKOMENDASI & RENCANA TINDAKAN BK</h3>
                    <p class="text-[10px] text-slate-800 leading-relaxed whitespace-pre-wrap font-serif">{{ $latestResponse->riskClassification->recommendation ?? 'Belum ada catatan rencana intervensi preventif yang disimpan.' }}</p>
                </div>

                {{-- Lembar Pengesahan Guru BK --}}
                <div class="flex items-center justify-end text-[10px] mt-10 pt-4 leading-normal avoid-break">
                    <div class="w-[45%] text-center">
                        <p class="text-slate-700">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        <p class="font-bold text-slate-800">Guru Bimbingan Konseling</p>
                        <div class="flex justify-center items-center h-16">
                            <img src="{{ asset('images/qrcode.jpeg') }}" alt="QR Code Verifikasi" class="h-14 w-14 object-contain">
                        </div>
                        <p class="font-bold text-slate-900 underline">Rezi Dwi Saputra</p>
                        <p class="text-[8px] text-slate-500 font-medium">NIP. 1028128</p>
                    </div>
                </div>
            @else
                <div class="py-12 text-center text-slate-500 font-medium italic">
                    Siswa ini belum pernah menyelesaikan pengisian kuesioner.
                </div>
            @endif
        </div>
    </div>

</body>
</html>