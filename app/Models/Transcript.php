<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transcript extends Model
{
    protected $fillable = [
        'meeting_id',
        'source_language',
        'destination_language',
        'content',
    ];
}
