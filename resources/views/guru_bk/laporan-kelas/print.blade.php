<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Deteksi Dini Kelas {{ $class->name }}</title>
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
                margin: 15mm 10mm 15mm 10mm;
            }
            /* Avoid page break inside summary card & signature */
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
            <a href="{{ route('guruBk.siswa.index', ['class_id' => $class->id]) }}" class="inline-flex items-center gap-1.5 text-xs text-slate-300 hover:text-white font-semibold transition">
                &larr; Kembali ke Daftar Siswa
            </a>
            <span class="text-slate-600">|</span>
            <span class="text-xs text-slate-300">Laporan Kelas: <strong>{{ $class->name }}</strong></span>
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
                    <h2 class="text-sm sm:text-base font-bold tracking-wide uppercase text-slate-800 leading-tight">Pemerintah Provinsi Daerah Khusus Ibukota Jakarta</h2>
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

            {{-- Judul Laporan --}}
            <div class="text-center mb-6">
                <h2 class="text-xs sm:text-sm font-extrabold uppercase tracking-wide text-slate-900 underline underline-offset-4 decoration-1">Laporan Hasil Deteksi Dini Kondisi Psikososial Kelas</h2>
                <p class="text-[10px] text-slate-600 font-semibold mt-1">Kelas: {{ $class->name }} &middot; Tahun Ajaran: {{ $class->academic_year ?? '-' }}</p>
            </div>

            {{-- Ringkasan Statistik Kelas --}}
            <div class="grid grid-cols-4 gap-3 mb-6 avoid-break">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-center">
                    <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Total Siswa</span>
                    <span class="block text-base font-extrabold text-slate-800 mt-0.5">{{ $totalStudents }}</span>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-center">
                    <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Telah Kuesioner</span>
                    <span class="block text-base font-extrabold text-slate-800 mt-0.5">
                        {{ $completedCount }} <span class="text-[10px] text-slate-400 font-normal">({{ $totalStudents > 0 ? round(($completedCount / $totalStudents) * 100) : 0 }}%)</span>
                    </span>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-center">
                    <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Berisiko Psikososial</span>
                    <span class="block text-base font-extrabold text-rose-600 mt-0.5">
                        {{ $risks['Berisiko Psikososial'] ?? 0 }} <span class="text-[10px] text-slate-400 font-normal">siswa</span>
                    </span>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-center">
                    <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Tidak Berisiko</span>
                    <span class="block text-base font-extrabold text-emerald-600 mt-0.5">
                        {{ $risks['Tidak Berisiko Psikososial'] ?? 0 }} <span class="text-[10px] text-slate-400 font-normal">siswa</span>
                    </span>
                </div>
            </div>

            {{-- Nilai Rata-rata Skor --}}
            <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-4 mb-6 grid grid-cols-3 gap-2 text-center avoid-break">
                <div>
                    <span class="block text-[9px] font-semibold text-slate-500 uppercase">Rata-rata SDQ</span>
                    <span class="block text-xs font-bold text-slate-800 mt-0.5">{{ $avgSdq }} <span class="text-[9px] text-slate-400 font-normal">/ 40</span></span>
                </div>
                <div class="border-x border-indigo-100">
                    <span class="block text-[9px] font-semibold text-slate-500 uppercase">Rata-rata PSC-17</span>
                    <span class="block text-xs font-bold text-slate-800 mt-0.5">{{ $avgPsc }} <span class="text-[9px] text-slate-400 font-normal">/ 34</span></span>
                </div>
                <div>
                    <span class="block text-[9px] font-semibold text-slate-500 uppercase">Rata-rata SAS-SV</span>
                    <span class="block text-xs font-bold text-slate-800 mt-0.5">{{ $avgSassv }} <span class="text-[9px] text-slate-400 font-normal">/ 50</span></span>
                </div>
            </div>

            {{-- Tabel Siswa --}}
            <div class="mb-8">
                <table class="w-full text-left text-[9px] border-collapse border border-slate-300">
                    <thead>
                        <tr class="bg-slate-100 text-slate-800 font-bold border-b border-slate-300">
                            <th class="px-2.5 py-2 border-r border-slate-300 text-center w-[4%]">No</th>
                            <th class="px-2.5 py-2 border-r border-slate-300 w-[12%]">NIS</th>
                            <th class="px-2.5 py-2 border-r border-slate-300 w-[23%]">Nama Siswa</th>
                            <th class="px-2.5 py-2 border-r border-slate-300 text-center w-[5%]">L/P</th>
                            <th class="px-2.5 py-2 border-r border-slate-300 text-center w-[9%]">SDQ</th>
                            <th class="px-2.5 py-2 border-r border-slate-300 text-center w-[9%]">PSC-17</th>
                            <th class="px-2.5 py-2 border-r border-slate-300 text-center w-[9%]">SAS-SV</th>
                            <th class="px-2.5 py-2 border-r border-slate-300 w-[16%]">Tingkat Risiko</th>
                            <th class="px-2.5 py-2 w-[13%]">Catatan BK / Intervensi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-700">
                        @forelse($class->students as $index => $student)
                            @php
                                $latestResponse = $student->questionnaireResponses->first();
                                $overallRisk = $latestResponse->riskClassification->overall_risk ?? null;

                                $riskColor = match($overallRisk) {
                                    'Tidak Berisiko Psikososial' => 'text-emerald-700 font-semibold',
                                    'Berisiko Psikososial'       => 'text-rose-700 font-bold',
                                    default => 'text-slate-400 italic',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-2.5 py-1.5 border-r border-slate-300 text-center">{{ $index + 1 }}</td>
                                <td class="px-2.5 py-1.5 border-r border-slate-300 font-mono text-slate-600">{{ $student->nis ?? '-' }}</td>
                                <td class="px-2.5 py-1.5 border-r border-slate-300 font-semibold text-slate-900">{{ $student->name }}</td>
                                <td class="px-2.5 py-1.5 border-r border-slate-300 text-center">{{ strtoupper($student->gender) }}</td>
                                <td class="px-2.5 py-1.5 border-r border-slate-300 text-center font-semibold">
                                    {{ $latestResponse ? $latestResponse->sdq_score : '-' }}
                                </td>
                                <td class="px-2.5 py-1.5 border-r border-slate-300 text-center font-semibold">
                                    {{ $latestResponse ? $latestResponse->psc17_score : '-' }}
                                </td>
                                <td class="px-2.5 py-1.5 border-r border-slate-300 text-center font-semibold">
                                    {{ $latestResponse ? $latestResponse->sassv_score : '-' }}
                                </td>
                                <td class="px-2.5 py-1.5 border-r border-slate-300 {{ $riskColor }}">
                                    {{ $overallRisk ?? 'Belum Partisipasi' }}
                                </td>
                                <td class="px-2.5 py-1.5 text-slate-600 italic">
                                    {{ $latestResponse && $latestResponse->riskClassification && $latestResponse->riskClassification->recommendation ? Str::limit($latestResponse->riskClassification->recommendation, 40) : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-8 text-center text-slate-500 font-medium italic">
                                    Tidak ada data siswa terdaftar di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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

        </div>
    </div>

</body>
</html>