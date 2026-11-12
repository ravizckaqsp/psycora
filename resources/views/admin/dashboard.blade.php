<x-admin-layout pageTitle="Dashboard Administrasi Sistem" pageSubtitle="Ringkasan teknis operasional dan statistik master data platform Psycora.">

    <div class="space-y-6">
        {{-- 1. Kartu Ringkasan Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            {{-- Total Guru BK --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Total Guru BK</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalGuruBk }}</p>
                </div>
            </div>

            {{-- Total Siswa --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Total Siswa</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalSiswa }}</p>
                </div>
            </div>

            {{-- Total Kelas --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Total Kelas</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalKelas }}</p>
                </div>
            </div>

            {{-- Total Soal --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Total Butir Soal</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalSoal }}</p>
                </div>
            </div>
        </div>

        {{-- 2. Tautan Pintas CRUD --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <a href="{{ route('admin.guru-bk.index') }}" class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:border-teal-500/30 hover:shadow-md transition group">
                <h4 class="font-bold text-slate-800 text-sm group-hover:text-teal-600 transition">Kelola Akun Guru BK &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Tambah, edit, dan hapus akun bimbingan konseling.</p>
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:border-teal-500/30 hover:shadow-md transition group">
                <h4 class="font-bold text-slate-800 text-sm group-hover:text-teal-600 transition">Kelola Akun Siswa &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Registrasi akun siswa baru dan penempatan kelas.</p>
            </a>
            <a href="{{ route('admin.soal.index') }}" class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:border-teal-500/30 hover:shadow-md transition group">
                <h4 class="font-bold text-slate-800 text-sm group-hover:text-teal-600 transition">Konfigurasi Pertanyaan &rarr;</h4>
                <p class="text-xs text-slate-500 mt-1">Ubah butir pertanyaan kuesioner SDQ, PSC-17, dan SAS-SV.</p>
            </a>
        </div>

        {{-- 3. Aktivitas Asesmen Siswa Terbaru --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm">Aktivitas Asesmen Terbaru Siswa</h3>
                <p class="text-xs text-slate-500 mt-1">Daftar pengerjaan kuesioner siswa terbaru yang tercatat di sistem</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-100">
                            <th class="px-5 py-3.5">Nama Siswa</th>
                            <th class="px-5 py-3.5">Kelas</th>
                            <th class="px-5 py-3.5">Tanggal Selesai</th>
                            <th class="px-5 py-3.5 text-center">Skor SDQ</th>
                            <th class="px-5 py-3.5 text-center">Skor PSC-17</th>
                            <th class="px-5 py-3.5 text-center">Skor SAS-SV</th>
                            <th class="px-5 py-3.5">Risiko Keseluruhan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($recentActivities as $resp)
                            @php
                                $overall = $resp->riskClassification->overall_risk ?? 'Tidak Berisiko Psikososial';
                                $badgeColor = match($overall) {
                                    'Tidak Berisiko Psikososial' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    default => 'bg-rose-50 text-rose-700 border-rose-100',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-4 font-bold text-slate-800">{{ $resp->user->name }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-700">{{ $resp->user->studentClass->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-500 font-medium">{{ $resp->completed_at->format('d M Y, H:i') }}</td>
                                <td class="px-5 py-4 text-center font-semibold text-violet-700">{{ $resp->sdq_score }}</td>
                                <td class="px-5 py-4 text-center font-semibold text-pink-700">{{ $resp->psc17_score }}</td>
                                <td class="px-5 py-4 text-center font-semibold text-sky-700">{{ $resp->sassv_score }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $badgeColor }}">
                                        {{ $overall }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-500 italic font-medium">Belum ada aktivitas pengisian kuesioner dari siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>