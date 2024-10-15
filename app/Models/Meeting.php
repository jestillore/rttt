<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'context',
        'language',
    ];

    public function audiences(): HasMany
    {
        return $this->hasMany(Audience::class);
    }

    public function transcripts(): HasMany
    {
        return $this->hasMany(Transcript::class);
    }

    public function generateTranscriptsToString(): string
    {
        return $this->transcripts()
            ->pluck('content')
            ->join("\n");
    }
}
