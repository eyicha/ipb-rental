<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'nim',
        'name',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    // Cek apakah email valid (terdaftar di database)
    public static function isEmailWhitelisted(string $email): bool
    {
        return self::where('email', $email)->exists();
    }

    // Cek apakah sudah verified
    public static function isEmailVerified(string $email): bool
    {
        return self::where('email', $email)->where('is_verified', true)->exists();
    }
}
