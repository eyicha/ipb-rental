<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'nim' => 'nullable|string|max:20|unique:users,nim',
                'password' => ['required', 'confirmed', Password::min(6)],
            ]);

            // Cek apakah email terdaftar di whitelist @apps.ipb.ac.id
            if (!EmailVerification::isEmailWhitelisted($validated['email'])) {
                return $this->error('Email tidak terdaftar', [
                    'email' => ['Email tidak terdaftar di sistem IPB. Hanya email @apps.ipb.ac.id yang terdaftar dapat mendaftar.']
                ], 422);
            }

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'nim' => $validated['nim'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'user',
            ]);

            // Update status email sebagai verified
            EmailVerification::where('email', $validated['email'])->update(['is_verified' => true]);

            $token = $user->createToken('auth-token')->plainTextToken;

            return $this->success([
                'user' => $user,
                'token' => $token,
            ], 'User registered successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', $e->errors(), 422);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($validated)) {
                return $this->error('Invalid credentials', null, 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;

            return $this->success([
                'user' => $user,
                'token' => $token,
            ], 'Login successful', 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', $e->errors(), 422);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->success(null, 'Logged out successfully', 200);
        } catch (\Exception $e) {
            return $this->error('Logout failed', null, 500);
        }
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        return $this->success($request->user(), 'User data retrieved', 200);
    }
}
