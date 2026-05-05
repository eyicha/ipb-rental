<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Transaction extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'transactions';

    protected $fillable = [
        'user_id',
        'item_id',
        'rental_id',      // ← tambah ini
        'order_id',
        'gross_amount',
        'snap_token',
        'status',
        'payment_type',
        'midtrans_response',
        'rental_start',
        'rental_end',
    ];
}