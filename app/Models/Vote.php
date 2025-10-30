<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /** @use HasFactory<\Database\Factories\VoteFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'verified' => 'boolean',
        ];
    }

    protected $fillable = [
        'candidate_id',
        'portfolio_id',
        'voter_mobile',
        'verified',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class, 'portfolio_id');
    }
}
