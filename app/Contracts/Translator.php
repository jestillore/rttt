<?php

namespace App\Contracts;

use App\Models\Audience;
use App\Models\Meeting;

interface Translator
{
    public function translate(Meeting $meeting, Audience $audience, string $sentence): string;

    public function summarize(Meeting $meeting, Audience $audience, string $transcript): string;
}
