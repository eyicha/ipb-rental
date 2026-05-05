<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id','nama','deskripsi','kategori','harga_per_hari','deposit',
        'stok','status','foto','rating_avg','total_sewa',
    ];

    protected $casts = [
        'foto' => 'array',
        'rating_avg' => 'float',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Resolve a stored foto path to a public URL.
     * Paths starting with "images/" live in public/ (use asset()).
     * All other paths live in storage/app/public/ (use asset('storage/')).
     */
    private function resolveFotoUrl(string $path): string
    {
        if (str_starts_with($path, 'images/')) {
            return asset($path);
        }
        return asset('storage/' . $path);
    }

    /** URL of the first photo, with sensible fallback. */
    public function getFirstFotoUrlAttribute(): string
    {
        $fotos = $this->foto;
        if (!empty($fotos) && is_array($fotos)) {
            return $this->resolveFotoUrl($fotos[0]);
        }
        return asset('images/items/proyektor.jpg');
    }

    /** Array of resolved URLs for all photos. */
    public function fotoUrls(): array
    {
        $fotos = $this->foto ?? [];
        if (!is_array($fotos)) {
            $fotos = (array)$fotos;
        }
        return array_filter(
            array_map(fn($p) => $this->resolveFotoUrl((string)$p), $fotos),
            fn($url) => !empty($url)
        );
    }

    public function getKategoriLabelAttribute(): string
    {
        return ucfirst($this->kategori);
    }

    public function getKategoriIconAttribute(): string
    {
        $icons = [
            'elektronik' => 'mdi-laptop',
            'fotografi'  => 'mdi-camera',
            'audio'      => 'mdi-headphones',
            'drone'      => 'mdi-quadcopter',
            'akademik'   => 'mdi-book-open-variant',
            'olahraga'   => 'mdi-soccer',
            'perabot'    => 'mdi-sofa',
            'kendaraan'  => 'mdi-bike',
            'lainnya'    => 'mdi-package-variant',
        ];
        return $icons[$this->kategori] ?? 'mdi-package-variant';
    }
}
