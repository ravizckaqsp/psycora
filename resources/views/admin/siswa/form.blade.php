<x-admin-layout :pageTitle="$siswa->exists ? 'Edit Data Siswa' : 'Daftar Siswa Baru'" pageSubtitle="Silakan lengkapi detail informasi profil di bawah ini.">

    <div class="max-w-2xl bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <form action="{{ $siswa->exists ? route('admin.siswa.update', $siswa->id) : route('admin.siswa.store') }}" method="POST" class="space-y-5">
            @csrf
            @if($siswa->exists)
                @method('PUT')
            @endif

            {{-- Nama Lengkap --}}
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-500 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $siswa->name) }}"
                       placeholder="Contoh: Andi Pratama" required
                       class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700">
                @error('name')
                    <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- NIS --}}
            <div>
                <label for="nis" class="block text-xs font-semibold text-slate-500 mb-1.5">Nomor Induk Siswa (NIS)</label>
                <input type="text" name="nis" id="nis" value="{{ old('nis', $siswa->nis) }}"
                       placeholder="Contoh: 2024001" required
                       class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700 font-mono">
                @error('nis')
                    <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Kelas --}}
            <div>
                <label for="class_id" class="block text-xs font-semibold text-slate-500 mb-1.5">Pilih Kelas</label>
                <select name="class_id" id="class_id" required
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700">
                    <option value="">Pilih Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $siswa->class_id) == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                @error('class_id')
                    <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-500 mb-1.5">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $siswa->email) }}"
                       placeholder="Contoh: siswa@test.com" required
                       class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700">
                @error('email')
                    <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Gender --}}
            <div>
                <label for="gender" class="block text-xs font-semibold text-slate-500 mb-1.5">Jenis Kelamin</label>
                <select name="gender" id="gender" required
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="L" {{ old('gender', $siswa->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender', $siswa->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender')
                    <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tanggal Lahir --}}
            <div>
                <label for="tanggal_lahir" class="block text-xs font-semibold text-slate-500 mb-1.5">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" 
                       value="{{ old('tanggal_lahir', $siswa->siswa && $siswa->siswa->tanggal_lahir ? $siswa->siswa->tanggal_lahir->format('Y-m-d') : '') }}"
                       class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700">
                @error('tanggal_lahir')
                    <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Tahun Ajaran --}}
            <div>
                <label for="tahun_ajaran" class="block text-xs font-semibold text-slate-500 mb-1.5">Tahun Ajaran</label>
                <select name="tahun_ajaran" id="tahun_ajaran"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach(['2023/2024', '2024/2025', '2025/2026'] as $ta)
                        <option value="{{ $ta }}" {{ old('tahun_ajaran', $siswa->siswa?->tahun_ajaran) === $ta ? 'selected' : '' }}>
                            {{ $ta }}
                        </option>
                    @endforeach
                </select>
                @error('tahun_ajaran')
                    <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Sandi --}}
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-500 mb-1.5">
                    Kata Sandi {{ $siswa->exists ? '(Biarkan kosong jika tidak diubah)' : '' }}
                </label>
                <input type="password" name="password" id="password" {{ $siswa->exists ? '' : 'required' }}
                       placeholder="Min. 8 karakter"
                       class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700">
                @error('password')
                    <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Konfirmasi Sandi --}}
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-500 mb-1.5">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation" {{ $siswa->exists ? '' : 'required' }}
                       placeholder="Ulangi Kata Sandi"
                       class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700">
            </div>

            {{-- Aksi Buttons --}}
            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs shadow-md shadow-teal-900/10 transition">
                    {{ $siswa->exists ? 'Simpan Perubahan' : 'Daftarkan Akun' }}
                </button>
                <a href="{{ route('admin.siswa.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
