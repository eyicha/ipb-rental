<?php

namespace App\Services;

use App\Data\IPBLokasi;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Collection;

class RecommendationService
{
    // Mapping dari nama kampus di IPBLokasi ke key proximity
    private array $kampusKey = [
        'IPB Dramaga'            => 'dramaga',
        'IPB Baranangsiang'      => 'baranangsiang',
        'IPB Gunung Gede'        => 'taman_kencana', // Gunung Gede deket Taman Kencana
        'IPB Cilibende (Vokasi)' => 'cilibende',
        'IPB Taman Kencana'      => 'taman_kencana',
        'IPB Sukabumi'           => 'sukabumi',
    ];

    private array $proximityMatrix = [
        'dramaga'       => ['dramaga' => 0, 'baranangsiang' => 2, 'taman_kencana' => 1, 'cilibende' => 1, 'sukabumi' => 3],
        'baranangsiang' => ['dramaga' => 2, 'baranangsiang' => 0, 'taman_kencana' => 1, 'cilibende' => 2, 'sukabumi' => 3],
        'taman_kencana' => ['dramaga' => 1, 'baranangsiang' => 1, 'taman_kencana' => 0, 'cilibende' => 1, 'sukabumi' => 3],
        'cilibende'     => ['dramaga' => 1, 'baranangsiang' => 2, 'taman_kencana' => 1, 'cilibende' => 0, 'sukabumi' => 2],
        'sukabumi'      => ['dramaga' => 3, 'baranangsiang' => 3, 'taman_kencana' => 3, 'cilibende' => 2, 'sukabumi' => 0],
    ];

    private array $proximityScore = [1.0, 0.75, 0.45, 0.1];

    private float $weightRating    = 0.50;
    private float $weightProximity = 0.35;
    private float $weightCategory  = 0.15;

    public function recommend(User $user, int $limit = 9): Collection
    {
        $userKey             = $this->getKampusKey($user->lokasi);
        $preferredCategories = $user->preferred_categories ?? [];

        $items = Item::with('user')
            ->where('status', 'available')
            ->where('stok', '>', 0)
            ->get();

        return $items->map(function (Item $item) use ($userKey, $preferredCategories) {
                $itemKey = $this->getKampusKey($item->user?->lokasi);

                $ratingScore    = $this->getRatingScore($item->user?->rating_avg ?? 0);
                $proximityScore = $this->getProximityScore($userKey, $itemKey);
                $categoryScore  = $this->getCategoryScore($item->kategori, $preferredCategories);

                $item->recommendation_score = ($ratingScore    * $this->weightRating)
                                            + ($proximityScore * $this->weightProximity)
                                            + ($categoryScore  * $this->weightCategory);

                $item->proximity_level = $this->getProximityLevel($userKey, $itemKey);
                $item->proximity_label = $this->getProximityLabel($item->proximity_level);

                return $item;
            })
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();
    }

    // Konversi string lokasi detail → kampus key
    private function getKampusKey(?string $lokasi): string
    {
        if (!$lokasi) return 'dramaga'; // default

        $kampus = IPBLokasi::findKampus($lokasi);

        return $this->kampusKey[$kampus] ?? 'dramaga';
    }

    private function getRatingScore(float $rating): float
    {
        if ($rating <= 0) return 0.5;
        return min(max(($rating - 4.0) / 1.0, 0.0), 1.0);
    }

    private function getProximityScore(string $userKey, string $itemKey): float
    {
        $level = $this->getProximityLevel($userKey, $itemKey);
        return $this->proximityScore[$level];
    }

    private function getProximityLevel(string $userKey, string $itemKey): int
    {
        return $this->proximityMatrix[$userKey][$itemKey] ?? 2;
    }

    private function getCategoryScore(string $itemKategori, array $preferredCategories): float
    {
        if (empty($preferredCategories)) return 0.5;
        return in_array(strtolower($itemKategori), array_map('strtolower', $preferredCategories))
            ? 1.0
            : 0.0;
    }

    public function getProximityLabel(int $level): string
    {
        return ['Sangat dekat', 'Dekat', 'Sedang', 'Jauh'][$level] ?? 'Sedang';
    }
}