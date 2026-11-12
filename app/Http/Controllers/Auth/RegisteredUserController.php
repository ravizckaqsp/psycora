<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nis' => ['required', 'string', 'max:20', 'unique:siswa,nis'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas' => ['required', 'string', 'max:10'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ], [
            'nis.unique' => 'NIS sudah digunakan.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->jenis_kelamin,
        ]);

        $user->assignRole('siswa');

        // Cari atau buat kelas yang sesuai
        $class = \App\Models\Classes::firstOrCreate(
            ['name' => $request->kelas],
            [
                'grade' => (int) substr($request->kelas, 0, 1),
                'academic_year' => $request->tahun_ajaran,
            ]
        );

        // Buat profil siswa
        $user->siswa()->create([
            'nis' => $request->nis,
            'tanggal_lahir' => $request->tanggal_lahir,
            'class_id' => $class->id,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
