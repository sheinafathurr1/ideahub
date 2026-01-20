<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Tampilkan Form Register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses Register
    public function register(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            
            // Validasi Data Kampus
            'university_name' => 'required|string|max:255',
            'university_type' => 'required|in:PTN,PTS',
            'university_category' => 'required|string',
            'has_disability_study_program' => 'required|boolean', // <--- VALIDASI BARU
            'phone_number' => 'required|numeric',
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'has_disability_study_program.required' => 'Pertanyaan terkait Prodi PLB wajib diisi.',
        ]);

        // 2. Buat User Baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            
            // Simpan Data Kampus
            'university_name' => $validated['university_name'],
            'university_type' => $validated['university_type'],
            'university_category' => $validated['university_category'],
            'has_disability_study_program' => $validated['has_disability_study_program'], // <--- SIMPAN KE DB
            'phone_number' => $validated['phone_number'],
        ]);

        // 3. Login & Redirect
        Auth::login($user);

        return redirect()->route('dashboard.index')->with('success', 'Akun berhasil dibuat! Silakan mulai survei.');
    }

    // Tampilkan Form Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard.index'));
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}