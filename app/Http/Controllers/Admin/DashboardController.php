<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use App\Models\Rental;
use App\Models\Report;
use App\Models\Verification;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::where('role', 'user')->count(),
            'total_items'       => Item::where('status', 'aktif')->count(),
            'total_rentals'     => Rental::count(),
            'pending_reports'   => Report::where('status', 'pending')->count(),
            'pending_verif'     => Verification::where('status', 'pending')->count(),
            'rentals_by_status' => Rental::all()
                ->groupBy('status')
                ->map(fn($group) => $group->count())
                ->toArray(),
        ];

        $recentRentals = Rental::with(['item', 'penyewa', 'pemilik'])
            ->latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recentRentals'));
    }
}
