<x-admin-layout pageTitle="Kelola Akun Guru BK" pageSubtitle="Daftarkan konselor sekolah baru untuk memantau data kesehatan psikososial siswa.">

    <div class="space-y-6">
        {{-- Tombol Pendaftaran & Pencarian --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('admin.guru-bk.create') }}" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold px-4 py-2 rounded-xl text-xs shadow-md shadow-teal-100 transition duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Daftarkan Guru BK Baru
            </a>

            <form action="{{ route('admin.guru-bk.index') }}" method="GET" class="relative w-full sm:w-80">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari Nama atau NIP Guru..."
                       class="w-full pl-10 pr-4 py-2 text-xs border border-slate-200 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition text-slate-700">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
            </form>
        </div>

        {{-- Tabel Daftar Guru --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-100">
                            <th class="px-5 py-4">Nama Lengkap</th>
                            <th class="px-5 py-4">NIP</th>
                            <th class="px-5 py-4">Email</th>
                            <th class="px-5 py-4">Gender</th>
                            <th class="px-5 py-4">Tanggal Registrasi</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($guruList as $guru)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($guru->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-slate-800">{{ $guru->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-mono text-slate-600 font-medium">{{ $guru->nip ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-600 font-medium">{{ $guru->email }}</td>
                                <td class="px-5 py-4 font-medium">
                                    @if($guru->gender === 'L' || $guru->gender === 'l')
                                        <span class="px-2 py-0.5 rounded bg-sky-50 text-sky-700 font-semibold text-[10px] border border-sky-100">Laki-laki</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-pink-50 text-pink-700 font-semibold text-[10px] border border-pink-100">Perempuan</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-slate-500 font-medium">{{ $guru->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.guru-bk.edit', $guru->id) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition text-[11px]">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.guru-bk.destroy', $guru->id) }}" method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun Guru BK ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-lg transition text-[11px]">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-500 font-medium italic">Guru BK tidak ditemukan atau belum ada data terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
