<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Rangkuman Deteksi Dini Tingkat Sekolah</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-smpn216-new.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    @php
        \Carbon\Carbon::setLocale('id');
    @endphp

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
            <a href="{{ route('guruBk.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-300 hover:text-white font-semibold transition">
                &larr; Kembali ke Dashboard
            </a>
            <span class="text-slate-600">|</span>
            <span class="text-xs text-slate-300">Rangkuman Laporan Sekolah</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition cursor-pointer shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Rangkuman / Simpan PDF
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
                <h2 class="text-xs sm:text-sm font-extrabold uppercase tracking-wide text-slate-900 underline underline-offset-4 decoration-1">Laporan Rangkuman Deteksi Dini Kondisi Psikososial Sekolah</h2>
                <p class="text-[10px] text-slate-600 font-semibold mt-1">Analisis Sebaran Tingkat Risiko & Ketergantungan Gawai per Kelas</p>
            </div>

            {{-- Ringkasan Statistik Tingkat Sekolah --}}
            <div class="grid grid-cols-4 gap-3 mb-6 avoid-break">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-center">
                    <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Total Siswa Terdaftar</span>
                    <span class="block text-base font-extrabold text-slate-800 mt-0.5">{{ $schoolTotalStudents }}</span>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-center">
                    <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Total Partisipan</span>
                    <span class="block text-base font-extrabold text-slate-800 mt-0.5">
                        {{ $schoolCompletedCount }} <span class="text-[10px] text-slate-400 font-normal">({{ $schoolTotalStudents > 0 ? round(($schoolCompletedCount / $schoolTotalStudents) * 100) : 0 }}%)</span>
                    </span>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-center">
                    <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Siswa Berisiko Sekolah</span>
                    <span class="block text-base font-extrabold text-rose-600 mt-0.5">
                        {{ $schoolRiskCount }} <span class="text-[10px] text-slate-400 font-normal">siswa</span>
                    </span>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-center">
                    <span class="block text-[8px] font-bold text-slate-400 uppercase tracking-wider">Siswa Tidak Berisiko</span>
                    <span class="block text-base font-extrabold text-emerald-600 mt-0.5">
                        {{ $schoolNoRiskCount }} <span class="text-[10px] text-slate-400 font-normal">siswa</span>
                    </span>
                </div>
            </div>

            {{-- Nilai Rata-rata Skor Sekolah --}}
            <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-4 mb-6 grid grid-cols-3 gap-2 text-center avoid-break">
                <div>
                    <span class="block text-[9px] font-semibold text-slate-500 uppercase">Rata-rata SDQ Sekolah</span>
                    <span class="block text-xs font-bold text-slate-800 mt-0.5">{{ $schoolAvgSdq }} <span class="text-[9px] text-slate-400 font-normal">/ 40</span></span>
                </div>
                <div class="border-x border-indigo-100">
                    <span class="block text-[9px] font-semibold text-slate-500 uppercase">Rata-rata PSC-17 Sekolah</span>
                    <span class="block text-xs font-bold text-slate-800 mt-0.5">{{ $schoolAvgPsc }} <span class="text-[9px] text-slate-400 font-normal">/ 34</span></span>
                </div>
                <div>
                    <span class="block text-[9px] font-semibold text-slate-500 uppercase">Rata-rata SAS-SV Sekolah</span>
                    <span class="block text-xs font-bold text-slate-800 mt-0.5">{{ $schoolAvgSassv }} <span class="text-[9px] text-slate-400 font-normal">/ 50</span></span>
                </div>
            </div>

            {{-- Sorotan Tingkat Risiko Kelas --}}
            @if($highestRiskClass && $highestRiskClass['risk_count'] > 0)
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 text-xs text-amber-800 flex items-start gap-3 avoid-break">
                    <svg class="w-5 h-5 flex-shrink-0 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <h4 class="font-bold text-amber-950">Sorotan Tingkat Risiko Khusus</h4>
                        <p class="mt-1 leading-relaxed">
                            Kelas dengan jumlah siswa berisiko psikososial terbanyak adalah <strong>Kelas {{ $highestRiskClass['name'] }}</strong> dengan total <strong>{{ $highestRiskClass['risk_count'] }} siswa berisiko</strong> dari {{ $highestRiskClass['completed_count'] }} partisipan ({{ $highestRiskClass['risk_percentage'] }}%). Direkomendasikan untuk memprioritaskan tindakan preventif dan bimbingan konseling kelompok pada kelas ini.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Tabel Komparasi Kelas --}}
            <div class="mb-8">
                <h3 class="text-[10px] font-bold text-slate-800 uppercase tracking-wider mb-3 border-b border-slate-200 pb-1">Tabel Analisis Sebaran Risiko per Kelas</h3>
                <table class="w-full text-left text-[9px] border-collapse border border-slate-300">
                    <thead>
                        <tr class="bg-slate-100 text-slate-800 font-bold border-b border-slate-300">
                            <th class="px-2 py-2 border-r border-slate-300 text-center w-[6%]">No</th>
                            <th class="px-2 py-2 border-r border-slate-300 w-[15%]">Nama Kelas</th>
                            <th class="px-2 py-2 border-r border-slate-300 text-center w-[12%]">Total Siswa</th>
                            <th class="px-2 py-2 border-r border-slate-300 text-center w-[15%]">Partisipasi (%)</th>
                            <th class="px-2 py-2 border-r border-slate-300 text-center w-[15%]">Siswa Berisiko</th>
                            <th class="px-2 py-2 border-r border-slate-300 text-center w-[10%]">Rerata SDQ</th>
                            <th class="px-2 py-2 border-r border-slate-300 text-center w-[10%]">Rerata PSC</th>
                            <th class="px-2 py-2 border-r border-slate-300 text-center w-[10%] whitespace-nowrap">Rerata SAS-SV</th>
                            <th class="px-2 py-2 text-center w-[12%]">Tingkat Risiko</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-700">
                        @php
                            $sortedByClass = collect($sortedByRisk)->sort(function ($a, $b) {
                                return strnatcmp($a['name'], $b['name']);
                            })->values();
                        @endphp
                        @forelse($sortedByClass as $index => $row)
                            @php
                                $percentage = $row['risk_percentage'];
                                $rawanColor = 'text-emerald-700 font-semibold';
                                $rawanText = 'Rendah';
                                if ($percentage > 25) {
                                    $rawanColor = 'text-rose-700 font-bold';
                                    $rawanText = 'Tinggi';
                                } elseif ($percentage > 0) {
                                    $rawanColor = 'text-amber-700 font-bold';
                                    $rawanText = 'Sedang';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-2 py-2 border-r border-slate-300 text-center">{{ $index + 1 }}</td>
                                <td class="px-2 py-2 border-r border-slate-300 font-bold text-slate-900">{{ $row['name'] }}</td>
                                <td class="px-2 py-2 border-r border-slate-300 text-center">{{ $row['total_students'] }}</td>
                                <td class="px-2 py-2 border-r border-slate-300 text-center">{{ $row['completed_count'] }} / {{ $row['total_students'] }} ({{ round($row['participation_rate']) }}%)</td>
                                <td class="px-2 py-2 border-r border-slate-300 text-center font-bold text-rose-600">
                                    {{ $row['risk_count'] }} <span class="text-[8px] text-slate-400 font-normal">({{ $percentage }}%)</span>
                                </td>
                                <td class="px-2 py-2 border-r border-slate-300 text-center">{{ $row['avg_sdq'] }}</td>
                                <td class="px-2 py-2 border-r border-slate-300 text-center">{{ $row['avg_psc'] }}</td>
                                <td class="px-2 py-2 border-r border-slate-300 text-center">{{ $row['avg_sassv'] }}</td>
                                <td class="px-2 py-2 text-center {{ $rawanColor }}">{{ $rawanText }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-8 text-center text-slate-500 font-medium italic">
                                    Belum ada data kelas terdaftar.
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