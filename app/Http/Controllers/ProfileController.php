<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_items'   => $user->items()->count(),
            'total_rentals' => $user->rentalsAsPenyewa()->count(),
        ];

        return view('profile.index', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'nim'       => 'nullable|string|max:20',
            'whatsapp'  => 'nullable|string|max:20',
            'lokasi'    => 'nullable|string|max:255',
            'avatar'    => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'password'  => 'nullable|min:8|confirmed',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        // Update field
        $user->name     = $validated['name'];
        $user->email    = $validated['email'];
        $user->whatsapp = $validated['whatsapp'] ?? $user->whatsapp;
        $user->lokasi   = $validated['lokasi'] ?? $user->lokasi;

        // NIM hanya bisa diisi kalau belum ada
        if (empty($user->nim) && !empty($validated['nim'])) {
            $user->nim = $validated['nim'];
        }

        // Password hanya update kalau diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        Auth::setUser($user->fresh());

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function uploadVerification(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:ktm,ktp',
            'file' => 'required|file|mimes:jpeg,png,jpg|max:5120',
        ]);

        $path = $request->file('file')->store('verifications', 'public');

        Auth::user()->verifications()->create([
            'tipe'   => $request->tipe,
            'file'   => $path,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Dokumen berhasil diupload, menunggu verifikasi admin.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password salah, akun tidak dihapus.');
        }

        Auth::logout();
        $user->delete();

        return redirect()->route('welcome')->with('success', 'Akun berhasil dihapus.');
    }
}