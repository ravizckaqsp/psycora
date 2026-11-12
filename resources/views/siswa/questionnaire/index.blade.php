<x-siswa-layout>
    <x-slot name="pageTitle">Kuesioner Psikososial</x-slot>
    <x-slot name="pageSubtitle">Tiga instrumen terintegrasi untuk analisis risiko</x-slot>

    {{-- Header info --}}
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 bg-violet-100 text-violet-700 text-xs font-semibold px-4 py-2 rounded-full mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Kuesioner Tervalidasi Secara Klinis
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Kenali Kondisi Psikososialmu</h2>
            <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed">
                Jawab 52 pertanyaan dari 3 instrumen berbeda.
            </p>
            <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed">
                Estimasi waktu: <strong class="text-slate-700">5-10 menit</strong>.
            </p>
        </div>

        {{-- Instrument cards --}}
        <div class="space-y-4 mb-8">
            @php
                $instruments = [
                    [
                        'key'   => 'SDQ',
                        'title' => 'Strengths & Difficulties Questionnaire',
                        'desc'  => 'Mengukur kekuatan dan kesulitan perilaku, emosi, dan hubungan sosial.',
                        'items' => 25,
                        'color' => 'violet',
                        'icon'  => '🧠',
                        'subscales' => ['Emosi', 'Perilaku', 'Hiperaktivitas', 'Teman Sebaya', 'Prososial'],
                    ],
                    [
                        'key'   => 'PSC-17',
                        'title' => 'Pediatric Symptom Checklist',
                        'desc'  => 'Mendeteksi gejala psikososial: masalah emosi, perhatian, dan perilaku.',
                        'items' => 17,
                        'color' => 'pink',
                        'icon'  => '💙',
                        'subscales' => ['Internalizing', 'Attention', 'Externalizing'],
                    ],
                    [
                        'key'   => 'SAS-SV',
                        'title' => 'Smartphone Addiction Scale – Short Version',
                        'desc'  => 'Mengukur tingkat ketergantungan dan adiksi penggunaan smartphone.',
                        'items' => 10,
                        'color' => 'sky',
                        'icon'  => '📱',
                        'subscales' => ['Daily Life Disturbance', 'Anticipation', 'Withdrawal'],
                    ],
                ];
                $colorMap = [
                    'violet' => ['bg'=>'bg-violet-50','border'=>'border-violet-200','badge'=>'bg-violet-400','text'=>'text-violet-700','tag'=>'bg-violet-100 text-violet-600'],
                    'pink'   => ['bg'=>'bg-pink-50',  'border'=>'border-pink-200',  'badge'=>'bg-pink-400',  'text'=>'text-pink-700',  'tag'=>'bg-pink-100 text-pink-600'],
                    'sky'    => ['bg'=>'bg-sky-50',   'border'=>'border-sky-200',   'badge'=>'bg-sky-400',   'text'=>'text-sky-700',   'tag'=>'bg-sky-100 text-sky-600'],
                ];
            @endphp

            @foreach($instruments as $i => $inst)
                @php $c = $colorMap[$inst['color']]; @endphp
                <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-2xl p-5">
                    <div class="flex items-start gap-4">
                        <div class="{{ $c['badge'] }} w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0 shadow-sm text-white">
                            {{ $inst['icon'] }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="{{ $c['badge'] }} text-white text-xs font-bold px-2 py-0.5 rounded-md">{{ $inst['key'] }}</span>
                                <h3 class="text-sm font-bold text-slate-800">{{ $inst['title'] }}</h3>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 mb-3">{{ $inst['desc'] }}</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($inst['subscales'] as $sub)
                                    <span class="{{ $c['tag'] }} text-[10px] font-medium px-2 py-0.5 rounded-full">{{ $sub }}</span>
                                @endforeach
                                <span class="bg-slate-100 text-slate-500 text-[10px] font-medium px-2 py-0.5 rounded-full">{{ $inst['items'] }} soal</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <p class="{{ $c['text'] }} text-xl font-bold">{{ $inst['items'] }}</p>
                            <p class="text-xs text-slate-400">soal</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Info notes --}}
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-blue-800 mb-1">Perhatian</p>
                    <ul class="text-xs text-blue-700 space-y-1 list-disc list-inside">
                        <li>Jawablah dengan jujur sesuai kondisi kamu sebenarnya</li>
                        <li>Jawaban tersimpan otomatis setiap soal (autosave)</li>
                        <li>Kamu bisa kembali ke soal sebelumnya</li>
                        <li>Hasil kuesioner bersifat rahasia</li>
                    </ul>
                </div>
            </div>
        </div>

        @if(!$canStart)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-4 flex items-start gap-3 shadow-sm">
                <span class="text-2xl mt-0.5">⏳</span>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-amber-800">Batas Pengisian Kuesioner</p>
                    <p class="text-xs text-amber-600 leading-relaxed">
                        Kamu hanya dapat mengisi kuesioner sekali dalam 7 hari untuk menjaga akurasi hasil skrining.
                    </p>
                    <p class="text-xs text-amber-700 font-semibold mt-1">
                        Kamu bisa mengisi kuesioner kembali pada: {{ $nextAllowedAt?->format('d F Y, H:i') }} WIB
                    </p>
                </div>
            </div>
        @endif

        @if($draft)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-4 flex items-center gap-3 shadow-sm">
                <span class="text-2xl">📝</span>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-amber-800">Draf Kuesioner Tersimpan</p>
                    <p class="text-xs text-amber-600">Kamu memiliki pengisian kuesioner yang belum diselesaikan.</p>
                </div>
                <a href="{{ route('siswa.questionnaire.sdq') }}"
                class="text-xs bg-amber-500 text-white px-3.5 py-2 rounded-lg font-semibold hover:bg-amber-600 transition flex-shrink-0 shadow-sm">
                    Lanjutkan Pengisian
                </a>
            </div>
        @endif

        @if($existing)
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-4 flex items-center gap-3 shadow-sm">
                <span class="text-2xl">✅</span>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-emerald-800">Kuesioner sebelumnya tersedia</p>
                    <p class="text-xs text-emerald-600">Terakhir: {{ $existing->completed_at?->format('d M Y, H:i') }}</p>
                </div>
                <a href="{{ route('siswa.riwayat.show', $existing->id) }}"
                class="ml-auto text-xs bg-emerald-500 text-white px-3.5 py-2 rounded-lg font-semibold hover:bg-emerald-600 transition flex-shrink-0 shadow-sm">
                    Lihat Hasil
                </a>
            </div>
        @endif

        {{-- CTA Button --}}
        @if(!$canStart)
            @if($draft)
                <button type="button" disabled
                   class="block w-full bg-slate-100 text-slate-400 text-center font-bold py-4 rounded-2xl cursor-not-allowed text-base">
                    Hapus draf & mulai baru (terkunci)
                </button>
            @else
                <button type="button" disabled
                   class="block w-full bg-slate-100 text-slate-400 text-center font-bold py-4 rounded-2xl cursor-not-allowed text-base">
                    Mulai kuesioner baru (terkunci)
                </button>
            @endif
        @else
            @if($draft)
                <a href="{{ route('siswa.questionnaire.sdq', ['new' => 'true']) }}"
                   onclick="return confirm('Apakah Anda yakin ingin menghapus draf jawaban sebelumnya dan memulai dari awal?')"
                   class="block w-full bg-slate-200 text-slate-700 text-center font-bold py-4 rounded-2xl hover:bg-slate-300 active:scale-[.98] transition-all duration-200 text-base">
                    Hapus draf & mulai baru
                </a>
            @else
                <a href="{{ route('siswa.questionnaire.sdq', ['new' => 'true']) }}"
                   class="block w-full bg-violet-400 text-white text-center font-bold py-4 rounded-2xl shadow-lg shadow-violet-100 hover:shadow-violet-200 active:scale-[.98] transition-all duration-200 text-base">
                    {{ $existing ? 'Mulai kuesioner baru' : 'Mulai kuesioner sekarang' }}
                </a>
            @endif
        @endif
        <p class="text-center text-xs text-slate-400 mt-3">Estimasi waktu:
             5-10 menit · 52 soal total</p>
    </div>
</x-siswa-layout>
