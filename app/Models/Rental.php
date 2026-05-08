<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rental extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_id','penyewa_id','pemilik_id','tanggal_mulai','tanggal_selesai',
        'durasi_hari','total_harga','deposit','status','catatan','bukti_dp',
        'rating','ulasan',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function penyewa()
    {
        return $this->belongsTo(User::class, 'penyewa_id');
    }

    public function pemilik()
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending'           => 'bg-warning text-dark',
            'pending_payment'   => 'bg-info text-dark',
            'dp_paid'           => 'bg-info text-dark',
            'active'            => 'bg-success',
            'finished'          => 'bg-secondary',
            'cancelled'         => 'bg-danger',
            'declined'          => 'bg-danger',
            // di getStatusBadgeAttribute()
'overdue' => 'bg-danger',
        ];
        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending'           => 'Menunggu Konfirmasi',
            'pending_payment'   => 'Menunggu Pembayaran',
            'dp_paid'           => 'DP Sudah Dibayar',
            'active'            => 'Sedang Disewa',
            'finished'          => 'Selesai',
            'cancelled'         => 'Dibatalkan',
            'declined'          => 'Ditolak',
            'overdue' => 'Terlambat Dikembalikan',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getStepperProgressAttribute(): int
    {
        $steps = [
            'pending'           => 0,
            'pending_payment'   => 25,
            'dp_paid'           => 33,
            'delivering'        => 50,
            'active'            => 67,
            'returning'         => 83,
            'finished'          => 100,
            'cancelled'         => 0,
            'declined'          => 0,
            'overdue' => 100
        ];
        return $steps[$this->status] ?? 0;
    }
}
