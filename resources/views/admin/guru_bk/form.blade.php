<x-admin-layout :pageTitle="$guru->exists ? 'Edit Data Guru BK' : 'Daftar Guru BK Baru'" pageSubtitle="Silakan lengkapi detail informasi profil di bawah ini.">

    <div class="max-w-2xl bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <form action="{{ $guru->exists ? route('admin.guru-bk.update', $guru->id) : route('admin.guru-bk.store') }}" method="POST" class="space-y-5">
            @csrf
            @if($guru->exists)
                @method('PUT')
            @endif

            {{-- Nama Lengkap --}}
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-500 mb-1.5">Nama Lengkap beserta Gelar</label>
                <input type="text" name="name" id="name" value="{{ old('name', $guru->name) }}"
                       placeholder="Contoh: Siti Rahayu, M.Psi" required
                       class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700">
                @error('name')
                    <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- NIP / Nomor Identitas --}}
            <div>
                <label for="nip" class="block text-xs font-semibold text-slate-500 mb-1.5">Nomor Induk Pegawai (NIP)</label>
                <input type="text" name="nip" id="nip" value="{{ old('nip', $guru->nip) }}"
                       placeholder="Contoh: BK-001" required
                       class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700 font-mono">
                @error('nip')
                    <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-500 mb-1.5">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $guru->email) }}"
                       placeholder="Contoh: guru@test.com" required
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
                    <option value="L" {{ old('gender', $guru->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender', $guru->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender')
                    <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Sandi --}}
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-500 mb-1.5">
                    Kata Sandi {{ $guru->exists ? '(Biarkan kosong jika tidak diubah)' : '' }}
                </label>
                <input type="password" name="password" id="password" {{ $guru->exists ? '' : 'required' }}
                       placeholder="Min. 8 karakter"
                       class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700">
                @error('password')
                    <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Konfirmasi Sandi --}}
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-500 mb-1.5">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation" {{ $guru->exists ? '' : 'required' }}
                       placeholder="Ulangi Kata Sandi"
                       class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700">
            </div>

            {{-- Aksi Buttons --}}
            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs shadow-md shadow-teal-900/10 transition">
                    {{ $guru->exists ? 'Simpan Perubahan' : 'Daftarkan Akun' }}
                </button>
                <a href="{{ route('admin.guru-bk.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
