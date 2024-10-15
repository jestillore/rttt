<?php

namespace App\Translators;

use App\Contracts\Translator;
use App\Models\Audience;
use App\Models\Meeting;

class Nothing implements Translator
{

    public function translate(Meeting $meeting, Audience $audience, string $sentence): string
    {
        return $sentence;
    }

    public function summarize(Meeting $meeting, Audience $audience, string $transcript): string
    {
        return $transcript;
    }
}
