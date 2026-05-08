<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reporter_id','terlapor_id','item_id','rental_id',
        'kategori','deskripsi','bukti','status','balasan_admin',
    ];

    protected $casts = ['bukti' => 'array'];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function terlapor()
    {
        return $this->belongsTo(User::class, 'terlapor_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending'  => 'bg-warning text-dark',
            'diproses' => 'bg-info text-dark',
            'selesai'  => 'bg-success',
            'ditolak'  => 'bg-danger',
        ];
        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getKategoriLabelAttribute(): string
    {
        $labels = [
            'penipuan'      => 'Penipuan',
            'barang_rusak'  => 'Kerusakan Barang',
            'tidak_sesuai'  => 'Tidak Sesuai Deskripsi',
            'keterlambatan' => 'Keterlambatan',
            'klarifikasi' => 'Klarifikasi Pemblokiran',
            'lainnya'       => 'Lainnya',
        ];
        return $labels[$this->kategori] ?? $this->kategori;
    }
}
