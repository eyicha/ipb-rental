<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function __construct(private RecommendationService $service) {}

    // Dipanggil via AJAX dari halaman explore
    public function index(): \Illuminate\Http\JsonResponse
    {
        $recommendations = $this->service->recommend(auth()->user(), 9);

        return response()->json([
            'success' => true,
            'data'    => $recommendations->map(fn($item) => [
                'id'              => $item->id,
                'nama'            => $item->nama,
                'kategori'        => $item->kategori,
                'harga_per_hari'  => $item->harga_per_hari,
                'foto'            => $item->foto,
                'proximity_label' => $item->proximity_label,
                'proximity_level' => $item->proximity_level,
                'score'           => round($item->recommendation_score * 100),
                'url'             => route('items.show', $item->id),
            ]),
        ]);
    }

    // Update preferensi kategori user
    public function updatePreferences(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'preferred_categories'   => ['required', 'array'],
            'preferred_categories.*' => ['string'],
        ]);

        auth()->user()->update([
            'preferred_categories' => $request->preferred_categories,
        ]);

        return response()->json(['success' => true, 'message' => 'Preferensi disimpan!']);
    }
}