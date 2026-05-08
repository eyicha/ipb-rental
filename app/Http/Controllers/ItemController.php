<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function explore(Request $request)
    {
        $query = Item::where('status', 'aktif')->with('owner');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->q . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        switch ($request->get('sort', 'popular')) {
            case 'harga_asc':
                $query->orderBy('harga_per_hari');
                break;
            case 'harga_desc':
                $query->orderByDesc('harga_per_hari');
                break;
            case 'rating':
                $query->orderByDesc('rating_avg');
                break;
            case 'terbaru':
                $query->latest();
                break;
            default:
                $query->orderByDesc('total_sewa');
        }

        $items = $query->paginate(12)->withQueryString();

        $categories = ['elektronik','fotografi','audio','drone','akademik','olahraga','perabot','kendaraan','lainnya'];

        // Get personalized recommendations for authenticated users
        $recommendations = $this->getRecommendations();

        return view('explore', compact('items', 'categories', 'recommendations'));
    }

   private function getRecommendations(): \Illuminate\Support\Collection
{
    if (!Auth::check()) {
        return collect();
    }

    $service = app(\App\Services\RecommendationService::class);
    return $service->recommend(Auth::user(), 9);
}

    public function show(Item $item)
    {
        if ($item->status === 'nonaktif') {
            abort(404);
        }
        $item->load('owner');
        return view('items.show', compact('item'));
    }
}
