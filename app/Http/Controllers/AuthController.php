<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, ' . $user->name . '!');
            }
            return redirect()->route('my-items.index')->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'nim'      => 'nullable|string|max:20|unique:users',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        // Cek apakah email terdaftar di whitelist @apps.ipb.ac.id
        if (!EmailVerification::isEmailWhitelisted($validated['email'])) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar di sistem IPB. Hanya email @apps.ipb.ac.id yang terdaftar dapat mendaftar.'
            ])->onlyInput('email', 'name', 'nim');
        }

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'nim'      => $validated['nim'] ?? null,
            'password' => Hash::make($validated['password']),
            'role'     => 'user',
        ]);

        // Update status email sebagai verified
        EmailVerification::where('email', $validated['email'])->update(['is_verified' => true]);

        Auth::login($user);
        return redirect()->route('my-items.index')->with('success', 'Akun berhasil dibuat! Selamat datang, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}
