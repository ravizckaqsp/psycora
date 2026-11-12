<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminGuruBkController extends Controller
{
    public function index(Request $request)
    {
        $query = User::role('guru_bk');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $guruList = $query->orderBy('name')->get();

        return view('admin.guru_bk.index', compact('guruList'));
    }

    public function create()
    {
        $guru = new User();
        return view('admin.guru_bk.form', compact('guru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nip' => 'required|string|max:50|unique:users,nip', // NIP Guru
            'gender' => 'required|in:L,P',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah digunakan.',
        ]);

        $guru = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
        ]);

        $guru->assignRole('guru_bk');

        return redirect()->route('admin.guru-bk.index')->with('success', 'Akun Guru BK berhasil didaftarkan.');
    }

    public function edit($id)
    {
        $guru = User::role('guru_bk')->findOrFail($id);
        return view('admin.guru_bk.form', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $guru = User::role('guru_bk')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($guru->id)],
            'nip' => ['required', 'string', 'max:50', Rule::unique('users', 'nip')->ignore($guru->id)],
            'gender' => 'required|in:L,P',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah digunakan.',
        ]);

        $guru->name = $request->name;
        $guru->email = $request->email;
        $guru->nip = $request->nip;
        $guru->gender = $request->gender;

        if ($request->filled('password')) {
            $guru->password = Hash::make($request->password);
        }

        $guru->save();

        return redirect()->route('admin.guru-bk.index')->with('success', 'Data Guru BK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guru = User::role('guru_bk')->findOrFail($id);
        $guru->delete();

        return redirect()->route('admin.guru-bk.index')->with('success', 'Akun Guru BK berhasil dihapus.');
    }
}
