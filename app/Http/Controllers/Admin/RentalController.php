<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function index(Request $request)
    {
        $query = Rental::with(['item','penyewa','pemilik']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $query->whereHas('item', fn($q) => $q->where('nama', 'like', '%' . $request->q . '%'))
                  ->orWhereHas('penyewa', fn($q) => $q->where('name', 'like', '%' . $request->q . '%'));
        }
        $rentals = $query->latest()->paginate(20)->withQueryString();
        return view('admin.rentals.index', compact('rentals'));
    }

    public function show(Rental $rental)
    {
        $rental->load(['item','penyewa','pemilik']);
        return view('admin.rentals.show', compact('rental'));
    }
}
