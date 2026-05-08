<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RecommendationService;
use Illuminate\Http\JsonResponse;

class RecommendationController extends Controller
{
    public function index(RecommendationService $service): JsonResponse
    {
        $user = auth()->user();
        $recommendations = $service->recommend($user, 9);

        return response()->json([
            'success' => true,
            'data'    => $recommendations->map(fn($item) => [
                'id'                   => $item->id,
                'nama'                 => $item->nama,
                'kategori'             => $item->kategori,
                'harga_per_hari'       => $item->harga_per_hari,
                'deposit'              => $item->deposit,
                'foto'                 => $item->foto,
                'stok'                 => $item->stok,
                'deskripsi'            => $item->deskripsi,
                'pemilik' => [
                    'id'         => $item->user?->id,
                    'name'       => $item->user?->name,
                    'rating_avg' => $item->user?->rating_avg,
                    'lokasi'     => $item->user?->lokasi,
                ],
                'proximity_level'      => $item->proximity_level,
                'proximity_label'      => $item->proximity_label,
                'recommendation_score' => round($item->recommendation_score * 100),
            ]),
        ]);
    }
}