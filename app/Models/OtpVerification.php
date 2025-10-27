<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    /** @use HasFactory<\Database\Factories\OtpVerificationFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified' => 'boolean',
        ];
    }

    protected $fillable = [
        'mobile',
        'otp',
        'expires_at',
        'verified',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(string $otp): bool
    {
        return ! $this->isExpired() && $this->otp === $otp && ! $this->verified;
    }
}
