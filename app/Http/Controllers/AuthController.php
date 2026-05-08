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
    'nim'      => 'required|string|max:20',
    'email'    => 'required|email|unique:users',
    'whatsapp' => ['required', 'string', 'min:10', 'max:15', 'regex:/^(08|628)[0-9]{8,12}$/'],
    'password' => ['required', 'confirmed', Password::min(6)],
]);

    // Cek email terdaftar di whitelist
    $verification = EmailVerification::where('email', $validated['email'])->first();

    if (!$verification) {
        return back()->withErrors([
            'email' => 'Email tidak terdaftar di sistem IPB.'
        ])->onlyInput('name', 'nim', 'email', 'whatsapp');
    }

    // Cek NIM cocok dengan email di database
    if ($verification->nim !== $validated['nim']) {
        return back()->withErrors([
            'nim' => 'NIM tidak sesuai dengan email yang terdaftar.'
        ])->onlyInput('name', 'nim', 'email', 'whatsapp');
    }

    // Cek NIM belum dipakai user lain
    if (User::where('nim', $validated['nim'])->exists()) {
        return back()->withErrors([
            'nim' => 'NIM sudah terdaftar, akun mungkin sudah pernah dibuat.'
        ])->onlyInput('name', 'nim', 'email', 'whatsapp');
    }

    // Cek email sudah verified (sudah pernah daftar)
    // Cek email sudah verified DAN user masih ada
if ($verification->is_verified && User::where('email', $validated['email'])->exists()) {
    return back()->withErrors([
        'email' => 'Email ini sudah pernah digunakan untuk mendaftar.'
    ])->onlyInput('name', 'nim', 'email', 'whatsapp');
}
    $user = User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'nim'      => $validated['nim'],
        'whatsapp' => $validated['whatsapp'] ?? null,
        'password' => Hash::make($validated['password']),
        'role'     => 'user',
    ]);

    EmailVerification::where('email', $validated['email'])
        ->update(['is_verified' => true]);

    Auth::login($user);
    return redirect()->route('my-items.index')
        ->with('success', 'Akun berhasil dibuat! Selamat datang, ' . $user->name . '!');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}
