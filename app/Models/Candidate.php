<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    /** @use HasFactory<\Database\Factories\CandidateFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected $fillable = [
        'name',
        'bio',
        'position',
        'photo',
        'department',
        'year',
        'manifesto',
        'is_active',
    ];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function voteCount(): int
    {
        return $this->votes()->count();
    }
}
