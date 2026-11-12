<x-admin-layout pageTitle="Konfigurasi Soal Kuesioner" pageSubtitle="Kelola teks pertanyaan instrumen SDQ, PSC-17, dan SAS-SV di database.">

    <div class="space-y-6" x-data="{ activeTab: 'sdq' }">
        {{-- Navigation Tabs --}}
        <div class="border-b border-slate-200">
            <nav class="-mb-px flex gap-6" aria-label="Tabs">
                <button @click="activeTab = 'sdq'"
                        :class="activeTab === 'sdq' ? 'border-teal-500 text-teal-600 font-bold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 border-b-2'"
                        class="py-3.5 px-1 text-xs font-semibold outline-none transition duration-150">
                    SDQ (Aspek Perilaku & Emosional)
                    <span class="ml-1.5 px-2 py-0.5 bg-slate-100 text-slate-600 rounded-full font-bold text-[10px]">{{ count($sdqQuestions) }}</span>
                </button>
                <button @click="activeTab = 'psc17'"
                        :class="activeTab === 'psc17' ? 'border-teal-500 text-teal-600 font-bold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 border-b-2'"
                        class="py-3.5 px-1 text-xs font-semibold outline-none transition duration-150">
                    PSC-17 (Skrining Psikososial)
                    <span class="ml-1.5 px-2 py-0.5 bg-slate-100 text-slate-600 rounded-full font-bold text-[10px]">{{ count($pscQuestions) }}</span>
                </button>
                <button @click="activeTab = 'sassv'"
                        :class="activeTab === 'sassv' ? 'border-teal-500 text-teal-600 font-bold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 border-b-2'"
                        class="py-3.5 px-1 text-xs font-semibold outline-none transition duration-150">
                    SAS-SV (Kecanduan Gawai)
                    <span class="ml-1.5 px-2 py-0.5 bg-slate-100 text-slate-600 rounded-full font-bold text-[10px]">{{ count($sassvQuestions) }}</span>
                </button>
            </nav>
        </div>

        {{-- Tab Contents --}}
        
        {{-- SDQ Tab --}}
        <div x-show="activeTab === 'sdq'" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-100">
                            <th class="px-5 py-4 w-20">No. Soal</th>
                            <th class="px-5 py-4">Teks Pertanyaan</th>
                            <th class="px-5 py-4">Subskala Klinis</th>
                            <th class="px-5 py-4">Reverse Scored?</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($sdqQuestions as $q)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-4 font-bold text-slate-800 text-center font-mono">{{ $q->number }}</td>
                                <td class="px-5 py-4 leading-relaxed font-semibold text-slate-800">{{ $q->text }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-2 py-0.5 bg-violet-50 text-violet-700 rounded text-[10px] font-bold border border-violet-100">
                                        {{ $q->subscale ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @if($q->reverse_scored)
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded text-[10px] font-bold border border-amber-100">Ya (Terbalik)</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-slate-50 text-slate-500 rounded text-[10px] font-medium border border-slate-100">Tidak</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.soal.edit', $q->id) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition text-[11px]">
                                        Edit Soal
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PSC-17 Tab --}}
        <div x-show="activeTab === 'psc17'" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden" style="display:none">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-100">
                            <th class="px-5 py-4 w-20">No. Soal</th>
                            <th class="px-5 py-4">Teks Pertanyaan</th>
                            <th class="px-5 py-4">Subskala Klinis</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($pscQuestions as $q)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-4 font-bold text-slate-800 text-center font-mono">{{ $q->number }}</td>
                                <td class="px-5 py-4 leading-relaxed font-semibold text-slate-800">{{ $q->text }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-2 py-0.5 bg-pink-50 text-pink-700 rounded text-[10px] font-bold border border-pink-100">
                                        {{ $q->subscale ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.soal.edit', $q->id) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition text-[11px]">
                                        Edit Soal
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SAS-SV Tab --}}
        <div x-show="activeTab === 'sassv'" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden" style="display:none">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-100">
                            <th class="px-5 py-4 w-20">No. Soal</th>
                            <th class="px-5 py-4">Teks Pertanyaan</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($sassvQuestions as $q)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-4 font-bold text-slate-800 text-center font-mono">{{ $q->number }}</td>
                                <td class="px-5 py-4 leading-relaxed font-semibold text-slate-800">{{ $q->text }}</td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.soal.edit', $q->id) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition text-[11px]">
                                        Edit Soal
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
