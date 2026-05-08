<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name','nim','email','whatsapp','lokasi','avatar','role','password','rating_avg','is_blocked','blocked_reason'
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
    'email_verified_at' => 'datetime',
    'password'          => 'hashed',
    'is_blocked'        => 'boolean', // ✅ Tambahkan ini
];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function rentalsAsPenyewa()
    {
        return $this->hasMany(Rental::class, 'penyewa_id');
    }

    public function rentalsAsPemilik()
    {
        return $this->hasMany(Rental::class, 'pemilik_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function verifications()
    {
        return $this->hasMany(Verification::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2e4156&color=fff&size=64';
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper($words[0][0] . $words[1][0]);
        }
        return strtoupper(substr($this->name, 0, 2));
    }
}
