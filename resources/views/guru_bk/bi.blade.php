<x-guru-bk-layout pageTitle="Analisis Hasil & Sebaran Risiko Kuesioner" pageSubtitle="Daftar analisis distribusi status risiko per kelas serta riwayat hasil kuesioner terbaru siswa secara mendetail.">

    <div class="space-y-6">
        {{-- 1. Distribusi Status Risiko Siswa Per Kelas --}}
        <div class="bg-white rounded-2xl p-0 border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm">Distribusi Status Risiko per Kelas</h3>
                <p class="text-xs text-slate-500 font-medium mt-1">Pilih kelas di bawah ini untuk melihat detail siswa terdaftar.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 p-5">
                @foreach($classesData as $class)
                    <a href="{{ route('guruBk.siswa.index', ['class_id' => $class['id']]) }}" 
                       class="bg-slate-50 hover:bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-indigo-200 hover:scale-[1.02] transition-all duration-300 group cursor-pointer">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-extrabold text-slate-800 text-lg group-hover:text-indigo-600 transition-colors">{{ $class['name'] }}</span>
                                <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 text-[9px] font-bold border border-indigo-100 uppercase">
                                    Kelas
                                </span>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center text-xs text-slate-600">
                                    <span>Total Siswa:</span>
                                    <span class="font-semibold text-slate-800">{{ $class['total_students'] }} siswa</span>
                                </div>
                                <div class="space-y-1.5">
                                    <div class="flex justify-between items-center text-xs text-slate-600">
                                        <span>Partisipasi:</span>
                                        <span class="font-semibold text-slate-800">{{ $class['completed_count'] }}/{{ $class['total_students'] }}</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-indigo-500 h-full rounded-full group-hover:bg-indigo-600 transition-all duration-500" style="width: {{ $class['total_students'] > 0 ? ($class['completed_count'] / $class['total_students']) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-end text-[10px] font-bold text-indigo-600 group-hover:text-indigo-700">
                            <span>Lihat Siswa &rarr;</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- 2. Riwayat Assessment Terbaru --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Hasil Kuesioner Terbaru Siswa</h3>
                    <p class="text-xs text-slate-500 font-medium mt-1">Daftar 5 pengerjaan kuesioner terakhir</p>
                </div>
                <a href="{{ route('guruBk.siswa.index') }}" class="text-xs text-indigo-600 font-semibold hover:text-indigo-800 transition">
                    Lihat Semua Siswa &rarr;
                </a>
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
                        @forelse($recentQuestionnaires as $resp)
                            @php
                                $overall = $resp->riskClassification->overall_risk ?? 'Tidak Berisiko Psikososial';
                                $badgeColor = match($overall) {
                                    'Tidak Berisiko Psikososial' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    default => 'bg-rose-50 text-rose-700 border-rose-100',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-[10px]">
                                            {{ strtoupper(substr($resp->user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-800">{{ $resp->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">{{ $resp->user->studentClass->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $resp->completed_at->format('d M Y, H:i') }}</td>
                                <td class="px-5 py-4 text-center font-semibold">{{ $resp->sdq_score }}</td>
                                <td class="px-5 py-4 text-center font-semibold">{{ $resp->psc17_score }}</td>
                                <td class="px-5 py-4 text-center font-semibold">{{ $resp->sassv_score }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $badgeColor }}">
                                        {{ $overall }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('guruBk.siswa.show', $resp->user_id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-semibold transition text-[11px]">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-slate-500 font-medium italic">Belum ada kuesioner siswa yang diselesaikan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-guru-bk-layout>
