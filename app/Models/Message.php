<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id','receiver_id','rental_id','pesan','is_read'];

    protected $casts = ['is_read' => 'boolean'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
