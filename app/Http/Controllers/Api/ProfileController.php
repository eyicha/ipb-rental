<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use ApiResponse;

    /**
     * Get user profile
     */
    public function show(User $user)
    {
        try {
            $user->load('items', 'rentalsAsPenyewa', 'rentalsAsPemilik');
            return $this->success($user, 'Profile retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve profile', $e->getMessage(), 500);
        }
    }

    /**
     * Get authenticated user profile
     */
    public function me(Request $request)
    {
        try {
            $user = Auth::user();
            $user->load('items', 'rentalsAsPenyewa', 'rentalsAsPemilik');
            return $this->success($user, 'Profile retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve profile', $e->getMessage(), 500);
        }
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $user->id,
                'nim' => 'nullable|string|max:20|unique:users,nim,' . $user->id,
                'whatsapp' => 'nullable|string|max:20',
                'lokasi' => 'nullable|string|max:255',
                'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $path = $request->file('avatar')->store('avatars', 'public');
                $validated['avatar'] = $path;
            }

            $user->update($validated);

            return $this->success($user, 'Profile updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->error('Failed to update profile', $e->getMessage(), 500);
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:6|confirmed',
            ]);

            if (!Hash::check($validated['current_password'], $user->password)) {
                return $this->error('Current password is incorrect', null, 400);
            }

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return $this->success(null, 'Password changed successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->error('Failed to change password', $e->getMessage(), 500);
        }
    }

    /**
     * Get user statistics
     */
    public function statistics(User $user)
    {
        try {
            $totalItems = $user->items()->count();
            $totalRentalsAsPenyewa = $user->rentalsAsPenyewa()->count();
            $totalRentalsAsPemilik = $user->rentalsAsPemilik()->count();
            $totalFinishedRentals = $user->rentalsAsPenyewa()->where('status', 'finished')->count();

            // Calculate average rating
            $avgRating = $user->rentalsAsPemilik()
                ->where('rating', '!=', null)
                ->avg('rating');

            return $this->success([
                'total_items' => $totalItems,
                'total_rentals_as_penyewa' => $totalRentalsAsPenyewa,
                'total_rentals_as_pemilik' => $totalRentalsAsPemilik,
                'total_finished_rentals' => $totalFinishedRentals,
                'average_rating' => $avgRating ? round($avgRating, 1) : 0,
            ], 'Statistics retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve statistics', $e->getMessage(), 500);
        }
    }

    /**
     * Get user's items
     */
    public function items(User $user, Request $request)
    {
        try {
            $items = $user->items()
                ->where('status', 'aktif')
                ->paginate($request->get('per_page', 12));

            return $this->paginated($items, 'Items retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve items', $e->getMessage(), 500);
        }
    }
}
