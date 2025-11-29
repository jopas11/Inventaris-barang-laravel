<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login dengan redirect sesuai role
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->status === 'aktif' && Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            return match ($user->role) {
                'admin' => redirect()->route('dashboard')->with('login_success', 'Selamat datang, Admin!'),
                'pengelola' => redirect()->route('pengelola')->with('login_success', 'Selamat datang, Pengelola!'),
                'user' => redirect()->route('user')->with('login_success', 'Selamat datang!'),
                default => redirect()->route('login')->withErrors(['email' => 'Role tidak valid.']),
            };
        }

        return back()->withErrors(['email' => 'Email atau password salah, atau akun belum aktif.']);
    }

    // Menampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^[A-Z][A-Za-z0-9]{7,}$/', // Awali dengan huruf kapital, kombinasi huruf dan angka, min 8 karakter
                'regex:/[0-9]/', // Harus mengandung angka
            ],
            'role' => ['required', Rule::in(['pengelola', 'user'])],
        ], [
            'password.regex' => 'Password harus diawali huruf kapital, minimal 8 karakter dan mengandung angka.',
        ]);

        $user = User::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $request->role,
            'status' => 'pending',
        ]);

        Role::create([
            'id_user' => $user->id,
            'role' => $request->role,
        ]);

        return redirect()->route('register')->with('success', 'Akun Anda berhasil dibuat! Tunggu persetujuan admin sebelum login.');
    }


    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
