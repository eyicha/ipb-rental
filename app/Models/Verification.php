<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','tipe','file','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending'  => 'bg-warning text-dark',
            'verified' => 'bg-success',
            'rejected' => 'bg-danger',
        ];
        return $badges[$this->status] ?? 'bg-secondary';
    }
}
