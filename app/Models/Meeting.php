<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    protected $fillable = [
        'code',
        'context',
        'language',
    ];

    public function audiences(): HasMany
    {
        return $this->hasMany(Audience::class);
    }
}
