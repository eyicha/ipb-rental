<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Rental;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        // Redirect authenticated users to explore page
        if (Auth::check()) {
            return redirect()->route('explore');
        }

        $featuredItems = Item::where('status', 'aktif')
            ->orderByDesc('rating_avg')
            ->limit(6)
            ->with('owner')
            ->get();

        $stats = [
            'total_items'   => Item::where('status', 'aktif')->count(),
            'total_users'   => User::where('role', 'user')->count(),
            'total_rentals' => Rental::count(),
        ];

        return view('welcome', compact('featuredItems', 'stats'));
    }
}
