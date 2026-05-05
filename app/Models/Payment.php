<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'amount',
        'type',
        'status',
        'transaction_id',
        'payment_method',
        'paid_at',
        'proof_url',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    // Cek apakah payment sudah sukses
    public function isSuccess(): bool
    {
        return $this->status === 'success' && $this->paid_at !== null;
    }
}
