<x-admin-layout pageTitle="Edit Butir Pertanyaan Kuesioner" pageSubtitle="Perbarui pertanyaan untuk memperbarui lembar pengisian kuesioner siswa secara langsung.">

    <div class="max-w-2xl bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <form action="{{ route('admin.soal.update', $question->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Metadata info --}}
            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-slate-400">Tipe Instrumen:</span>
                    <span class="font-bold text-slate-800 uppercase block mt-0.5">{{ $question->type }}</span>
                </div>
                <div>
                    <span class="text-slate-400">Nomor Soal:</span>
                    <span class="font-bold text-slate-800 block mt-0.5">{{ $question->number }}</span>
                </div>
            </div>

            {{-- Teks Pertanyaan --}}
            <div>
                <label for="text" class="block text-xs font-semibold text-slate-500 mb-1.5">Teks Pertanyaan (Bahasa Indonesia)</label>
                <textarea name="text" id="text" rows="4" required
                          placeholder="Ketik detail pertanyaan kuesioner..."
                          class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700 leading-relaxed resize-none">{{ old('text', $question->text) }}</textarea>
                @error('text')
                    <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Subskala Klinis (SDQ & PSC-17 saja) --}}
            @if(in_array($question->type, ['sdq', 'psc17']))
                <div>
                    <label for="subscale" class="block text-xs font-semibold text-slate-500 mb-1.5">Subskala Klinis / Dimensi Diagnostik</label>
                    <input type="text" name="subscale" id="subscale" value="{{ old('subscale', $question->subscale) }}"
                           placeholder="Contoh: Emotional, Conduct, Attention, Hyperactivity"
                           class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700 font-semibold text-slate-800">
                    @error('subscale')
                        <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            @else
                <input type="hidden" name="subscale" value="">
            @endif

            {{-- Reverse Scored (SDQ saja) --}}
            @if($question->type === 'sdq')
                <div>
                    <label for="reverse_scored" class="block text-xs font-semibold text-slate-500 mb-1.5">Metode Penilaian Terbalik (Reverse Scored)?</label>
                    <select name="reverse_scored" id="reverse_scored" required
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700">
                        <option value="0" {{ old('reverse_scored', $question->reverse_scored) == 0 ? 'selected' : '' }}>
                            Tidak (Skor normal: Tidak Benar = 0, Agak Benar = 1, Benar = 2)
                        </option>
                        <option value="1" {{ old('reverse_scored', $question->reverse_scored) == 1 ? 'selected' : '' }}>
                            Ya (Terbalik: Tidak Benar = 2, Agak Benar = 1, Benar = 0)
                        </option>
                    </select>
                    @error('reverse_scored')
                        <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            @else
                <input type="hidden" name="reverse_scored" value="0">
            @endif

            {{-- Aksi Buttons --}}
            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs shadow-md shadow-teal-900/10 transition">
                    Simpan Pertanyaan
                </button>
                <a href="{{ route('admin.soal.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
